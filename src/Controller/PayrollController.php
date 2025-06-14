<?php

declare(strict_types=1);

namespace App\Controller;

use TCPDF;
use Cake\Routing\Router;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use App\Controller\AppController;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Cake\Http\Exception\NotFoundException;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Cake\Http\Exception\ForbiddenException;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Payroll Controller
 *
 * @property \App\Model\Table\PayrollTable $Payroll
 */
class PayrollController extends BaseController
{
    private $usersTable;
    private $PayrollDeductionsTable;
    private $payrollEarningsTable;
    private $balanceLeavesTable;
    private $settingsTable;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->usersTable = $this->getTableLocator()->get('Users');
        $this->PayrollDeductionsTable = $this->getTableLocator()->get('PayrollDeductions');
        $this->payrollEarningsTable = $this->getTableLocator()->get('PayrollEarnings');
        $this->balanceLeavesTable = $this->getTableLocator()->get('BalanceLeaves');
        $this->settingsTable = $this->getTableLocator()->get('Settings');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Payroll');

        $session = $this->request->getSession();

        $currentDate = FrozenTime::now();
        $previousMonth = $currentDate->modify('-1 month')->format('F-Y');

        $selectedUser = $session->read('user_id');
        $selectedMonth = $session->read('selected_month');

        if (!$selectedMonth) {
            $selectedMonth = $previousMonth;
        }

        $selectedMonthFormatted = \DateTime::createFromFormat('F-Y', $selectedMonth);

        if (!$selectedMonthFormatted) {
            $selectedMonthFormatted = \DateTime::createFromFormat('F-Y', $previousMonth);
        }

        $selectedMonthFormatted = $selectedMonthFormatted->format('F-Y');

        $query = $this->Payroll->find()
            ->contain(['Users']);

        if ($selectedUser) {
            $query->where(['user_id' => $selectedUser]);
        }

        $query->where(['month LIKE' => $selectedMonthFormatted . '%']);

        $payrolls = $query->all();

        $users = $this->usersTable->find()->where(['deleted' => 0, 'is_admin' => 0])->order(['first_name' => 'ASC'])->toArray();

        $this->set(compact('payrolls', 'users', 'selectedMonth'));
    }

    public function setSession()
    {
        $this->request->allowMethod(['post']);

        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $session = $this->request->getSession();

            if (isset($data['selected_month'])) {
                $session->write('selected_month', $data['selected_month']);
            }

            if (isset($data['user_id'])) {
                $session->write('user_id', $data['user_id']);
            } else {
                $session->delete('user_id');
            }

            return $this->response->withStatus(204);
        }

        throw new ForbiddenException('Invalid request');
    }

    public function gerenatePayroll()
    {
        $this->request->allowMethod(['ajax']);

        $result = [];

        $currentDate = FrozenTime::now();
        $previousMonth = $currentDate->modify('-1 month')->format('F-Y');

        $existingPayroll = $this->Payroll->find()
            ->where(['month' => $previousMonth])
            ->contain(['Users'])
            ->toArray();

        if (!empty($existingPayroll)) {
            return $this->response->withType('application/json')->withStringBody(json_encode([
                'success' => true,
                'message' => 'Payroll already generated for the previous month.',
                'data' => $existingPayroll
            ]));
        } else {
            $usersData = $this->Payroll->payrollCalculate();

            foreach ($usersData as $data) {
                if ($data->security_deposit_amount) {
                    $data->netpayable -= $data->security_deposit_amount;
                }

                if ($data->earning_amount) {
                    $data->netpayable += $data->earning_amount;
                }

                $month = date('F-Y', strtotime($data->start_date));

                $payrollData = [
                    [
                        "user_id" => $data->id,
                        "month" => $month,
                        "total_working_days" => $data->total_working_days,
                        "days_present" => $data->days_present,
                        "paid_leaves" => $data->paid_leaves,
                        "unpaid_leaves" => $data->unpaid_leaves,
                        "deduction_of_leaves" => $data->deduction_of_leaves,
                        "basic_salary" => $data->basic_salary,
                        "total_balance_leaves" => $data->balance_leaves,
                        "net_payable" => $data->netpayable,
                        "employee_code" => $data->employee_code,
                        "pan_number" => $data->pan_no,
                        "bank_name" => $data->bank_name,
                        "bank_account_number" => $data->bank_account_no
                    ]
                ];

                if (isset($data->salary)) {
                    $payrollEntities = $this->Payroll->newEntities($payrollData);

                    foreach ($payrollEntities as $payroll) {
                        if ($this->Payroll->save($payroll)) {
                            $payroll_id = $payroll->id;

                            if (isset($data->id)) {
                                $deductionData = [
                                    [
                                        "payroll_id" => $payroll_id,
                                        "title" => 'Security Deposit Amount',
                                        "amount" => $data->security_deposit_amount
                                    ]
                                ];

                                $deductionEntities =  $this->payrollEarningsTable->newEntities($deductionData);

                                foreach ($deductionEntities as $deduction) {
                                    $this->PayrollDeductionsTable->save($deduction);
                                }
                            }

                            if ($data->earning_amount) {
                                $earningData = [
                                    [
                                        "payroll_id" => $payroll_id,
                                        "title" => "Balance Leaves Encashment(" . $data->n_leaves . " leaves)",
                                        "amount" => $data->earning_amount
                                    ]
                                ];

                                $earningEntities =  $this->payrollEarningsTable->newEntities($earningData);

                                foreach ($earningEntities as $earning) {
                                    $this->payrollEarningsTable->save($earning);
                                }
                            }

                            $this->balanceLeavesTable->updateBalanceLeaves($data->id, $data->balance_leaves);

                            $data->payroll_id = $payroll_id;
                            $result[] = $data;
                        } else {
                            return $this->response->withType('application/json')->withStringBody(json_encode([
                                'success' => false,
                                'message' => 'Error occurred while generating payroll.'
                            ]));
                        }
                    }
                }
            }

            return $this->response->withType('application/json')->withStringBody(json_encode([
                'success' => true,
                'data' => $result
            ]));
        }
    }

    public function showPayroll($payrollId)
    {
        $payroll = $this->Payroll->get($payrollId, [
            'contain' => ['Users']
        ]);

        $payrollEarnings = $this->payrollEarningsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $payrollDeductions = $this->PayrollDeductionsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $this->set(compact('payroll', 'payrollEarnings', 'payrollDeductions'));

        $this->viewBuilder()->setLayout('ajax');
    }

    public function payrollPdf($payrollId, $mode = "view")
    {
        $this->viewBuilder()->disableAutoLayout();

        $payroll = $this->Payroll->get($payrollId, [
            'contain' => ['Users']
        ]);

        $payrollEarnings = $this->payrollEarningsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $payrollDeductions = $this->PayrollDeductionsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $settings = $this->settingsTable->find()->toArray();

        $this->set(compact('payroll', 'payrollEarnings', 'payrollDeductions', 'settings'));

        $html = $this->renderView('Payroll/payroll_pdf');

        $pdf = new TCPDF();

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->SetFontSize(10);

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf_name = $payroll->users->first_name . '_' . $payroll->users->last_name . '_' . date('M_Y', strtotime($payroll->month)) . '.pdf';

        if ($mode === "view") {
            $pdf->SetTitle($pdf_name);
            $pdf->Output($pdf_name, 'I');
        } else if ($mode === "send_email") {
            $temp_download_path = WWW_ROOT . "temp_uploads" . DS . $pdf_name;
            $pdf->Output($temp_download_path, "F");
            return $temp_download_path;
        }
    }

    private function renderView($template)
    {
        $view = $this->viewBuilder()
            ->setTemplate($template)
            ->build();

        return $view->render();
    }

    public function addEarning()
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'ajax']);

        $data = $this->request->getData();

        if (!empty($data['id'])) {
            $earning = $this->payrollEarningsTable->get($data['id']);
        } else {
            $earning = $this->payrollEarningsTable->newEmptyEntity();
        }

        $earning = $this->payrollEarningsTable->patchEntity($earning, $data);

        if ($this->payrollEarningsTable->save($earning)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Data has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save data.');
        }
    }

    public function addDeduction()
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'ajax']);

        $data = $this->request->getData();

        if (!empty($data['id'])) {
            $deduction = $this->PayrollDeductionsTable->get($data['id']);
        } else {
            $deduction = $this->PayrollDeductionsTable->newEmptyEntity();
        }

        $deduction = $this->PayrollDeductionsTable->patchEntity($deduction, $data);

        if ($this->PayrollDeductionsTable->save($deduction)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Data has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save data.');
        }
    }

    public function sendMail()
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'ajax']);

        $userId = $this->request->getData('userId');
        $month = $this->request->getData('month');

        $payroll = $this->Payroll->find()
            ->contain(['Users'])
            ->where(['user_id' => $userId, 'month' => $month])->first();

        if ($payroll->users->email) {
            $getEmailTemplate = $this->getEmailTemplate('salary-slip');

            if (!$getEmailTemplate) {
                return false;
            }

            $placeholders = [
                '{firstname}' => $payroll->users->first_name,
                '{month}' => $payroll->month,
            ];

            $templateBody = str_replace(array_keys($placeholders), array_values($placeholders), $getEmailTemplate->message);
            $templateSubject = str_replace(array_keys($placeholders), array_values($placeholders), $getEmailTemplate->subject);

            $attachmentPath  = $this->payrollPdf($payroll->id, 'send_email');

            if (!$attachmentPath || !file_exists($attachmentPath)) {
                return AjaxResponseHelper::createResponse(false, 'Salary slip could not be generated.');
            }

            if ($this->sendSalaryMail($payroll, $templateSubject, $templateBody, $attachmentPath)) {
                if (file_exists($attachmentPath)) {
                    unlink($attachmentPath);
                }
                return AjaxResponseHelper::createResponse(true, 'The Email has been sent!');
            } else {
                return AjaxResponseHelper::createResponse(false, 'Failed to send email.');
            }
        } else {
            return AjaxResponseHelper::createResponse(false, 'Email not found.');
        }
    }

    public function export($payrollId)
    {
        $this->autoRender = false;

        $payroll = $this->Payroll->get($payrollId, [
            'contain' => ['Users']
        ]);

        $payrollEarnings = $this->payrollEarningsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $payrollDeductions = $this->PayrollDeductionsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $settings = $this->settingsTable->find()->toArray();

        $fileName = $payroll->users->first_name . '_' . $payroll->users->last_name . '_' . date('M_Y', strtotime($payroll->month)) . '.xlsx';

        $companyName = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '808080'
                ]
            ]
        ];

        $subHeading = [
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'A9A9A9'
                ]
            ]
        ];

        $subsubHeading = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'BEBEBE'
                ]
            ]
        ];

        $subsubHeading2 = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'DCDCDC'
                ]
            ]
        ];

        $Labels = [
            'font' => [
                'bold' => true,
            ],
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle(date('F - Y', strtotime($payroll->month)));

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        for ($i = 0; $i < 24; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(16);
        }

        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(23);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);

        $sheet->mergeCells('B1:F1');
        $sheet->setCellValue('B1', 'QUEUELOOP SOLUTIONS LLP');
        $style = $sheet->getStyle('B1:F1');
        $style->applyFromArray($companyName);
        $sheet->getRowDimension(1)->setRowHeight(18);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('B2:F2');
        $sheet->setCellValue('B2', 'Surat, Gujrat');
        $sheet->getRowDimension(2)->setRowHeight(40);
        $style = $sheet->getStyle('B2');
        $style->getAlignment()->setWrapText(true);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('B3:F3');
        $sheet->setCellValue('B3', "Salary Slip for " . date('F - Y', strtotime($payroll->month)));
        $style = $sheet->getStyle('B3:F3');
        $style->applyFromArray($subHeading);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B5', 'Employee Name');
        $sheet->setCellValue('B6', 'Employee Code');
        $sheet->setCellValue('B7', 'Designation');
        $sheet->setCellValue('B8', 'PAN');
        $sheet->setCellValue('B9', 'Bank Name');
        $sheet->setCellValue('B10', 'Bank Account Number');
        $style = $sheet->getStyle('B5:B10');
        $style->applyFromArray($Labels);

        $sheet->setCellValue('C5', $payroll->users->first_name . $payroll->users->last_name);
        $sheet->setCellValue('C6', $payroll->employee_code);
        $sheet->setCellValue('C7', $payroll->users->job_title);
        $sheet->setCellValue('C8', $payroll->pan_number ? $payroll->pan_number : "-");
        $sheet->setCellValue('C9', $payroll->bank_name);
        $sheet->setCellValueExplicit('C10', $payroll->bank_account_number, DataType::TYPE_STRING);
        $style = $sheet->getStyle('C5:C10');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('D5', 'Date of Joining');
        $sheet->setCellValue('D6', 'Total Working Days');
        $sheet->setCellValue('D7', 'Days Present');
        $sheet->mergeCells('D8:D9');
        $sheet->setCellValue('D8', 'Leaves Taken');
        $style = $sheet->getStyle('D8');
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D10', 'Balance Leaves');
        $style = $sheet->getStyle('D5:D10');
        $style->applyFromArray($Labels);

        $sheet->mergeCells('E4:F4');

        $sheet->mergeCells('E5:F5');
        $sheet->setCellValue('E5', $payroll->users->date_of_joining ? $this->Date->format($payroll->users->date_of_joining, false) : "-");
        $sheet->mergeCells('E6:F6');
        $sheet->setCellValue('E6', $payroll->total_working_days);
        $sheet->mergeCells('E7:F7');
        $sheet->setCellValue('E7', $payroll->days_present);
        $sheet->setCellValue('E8', 'Paid');
        $sheet->setCellValue('F8', 'Unpaid');
        $style = $sheet->getStyle('E8:F8');
        $style->getFont()->setBold(true);
        $sheet->setCellValue('E9', $payroll->paid_leaves);
        $sheet->setCellValue('F9', $payroll->unpaid_leaves);
        $sheet->mergeCells('E10:F10');
        $sheet->setCellValue('E10', $payroll->total_balance_leaves);
        $style = $sheet->getStyle('E5:F10');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('B11:C11');
        $sheet->mergeCells('D11:F11');

        $sheet->mergeCells('B12:C12');
        $sheet->setCellValue('B12', 'Earnings');
        $sheet->mergeCells('D12:F12');
        $sheet->setCellValue('D12', 'Deductions');
        $style = $sheet->getStyle('B12:F12');
        $style->applyFromArray($subsubHeading);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B13', 'Particulars');
        $sheet->setCellValue('C13', 'Amount');
        $sheet->setCellValue('D13', 'Particulars');
        $sheet->mergeCells('E13:F13');
        $sheet->setCellValue('E13', 'Amount');
        $style = $sheet->getStyle('B13:F13');
        $style->applyFromArray($subsubHeading2);
        $style->getFont()->setBold(true);

        $sheet->setCellValue('B14', 'Basic Salary');

        $earningscellValue = 15;
        $totalEarning = '0';
        foreach ($payrollEarnings as $earnings) {
            $sheet->setCellValue('B' . $earningscellValue, $earnings->title);
            $amount = (float)$earnings->amount;
            $sheet->setCellValue('C' . $earningscellValue, "₹ " . number_format($amount, 2));
            $earningscellValue++;
            $totalEarning += $amount;
        }

        $sheet->setCellValue('B18', 'Total Earnings');
        $style = $sheet->getStyle('B14:B18');
        $style->applyFromArray($Labels);

        $sheet->setCellValue('C14', "₹ " . number_format((float)$payroll->basic_salary, 2));
        $sheet->setCellValue('C18', "₹ " . number_format($totalEarning + $payroll->basic_salary, 2));
        $style = $sheet->getStyle('C14:C18');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('D14', 'For Leaves Taken');

        $totalDeduction = '0';
        $deductionscellValue = 15;
        foreach ($payrollDeductions as $deductions) {
            $sheet->setCellValue('D' . $deductionscellValue, $deductions->title);
            $sheet->mergeCells('E' . $deductionscellValue . ':F' . $deductionscellValue);
            $amount = (float)$deductions->amount;
            $sheet->setCellValue('E' . $deductionscellValue, "₹ " . number_format($amount, 2));
            $deductionscellValue++;
            $totalDeduction += $amount;
        }

        $sheet->mergeCells('E' . $deductionscellValue . ':F' . $deductionscellValue);
        $sheet->setCellValue('D18', 'Total Deductions');
        $style = $sheet->getStyle('D14:D18');
        $style->applyFromArray($Labels);

        $sheet->mergeCells('E14:F14');
        $sheet->setCellValue('E14', "₹ " . number_format((float)$payroll->deduction_of_leaves, 2));
        $sheet->mergeCells('E16:F16');
        $sheet->mergeCells('E17:F17');
        $sheet->mergeCells('E18:F18');
        $sheet->setCellValue('E18', "₹ " . number_format($payroll->deduction_of_leaves + $totalDeduction, 2));
        $style = $sheet->getStyle('E14:E20');
        $style->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->mergeCells('E19:F19');

        $sheet->mergeCells('B20:C20');
        $sheet->setCellValue('B20', '(Net Payable = Total Earnings - Total Deductions)');
        $style = $sheet->getStyle('B20');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('D20', 'Net Payable');
        $style = $sheet->getStyle('D20');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $style->getFont()->setBold(true);
        $sheet->mergeCells('E20:F20');
        $sheet->setCellValue('E20', "₹ " . number_format((float)$payroll->net_payable, 2));
        $style = $sheet->getStyle('B20:F20');
        $style->applyFromArray($subsubHeading2);

        $sheet->mergeCells('B21:C24');
        $sheet->mergeCells('D21:F23');
        $sheet->mergeCells('D24:F24');
        $sheet->setCellValue('D24', 'Authorised Signatory');
        $style = $sheet->getStyle('D21:F24');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $drawing = new Drawing();
        $drawing->setName('Signature');
        $drawing->setPath(Router::url('/assets/images/signature.png', true));
        $drawing->setCoordinates('D21');
        $drawing->setOffsetX(60);
        $drawing->setOffsetY(5);
        // $drawing->setWidth(150);
        $drawing->setHeight(50);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $tableRange = 'B1:F' . 24;
        $style = $sheet->getStyle($tableRange);
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        $writer->save('php://output');

        exit;
    }

    /**
     * View method
     *
     * @param string|null $id Payroll id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $payroll = $this->Payroll->get($id, contain: ['Users', 'PayrollDeductions', 'PayrollEarnings']);
        $this->set(compact('payroll'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $payroll = $this->Payroll->newEmptyEntity();
        if ($this->request->is('post')) {
            $payroll = $this->Payroll->patchEntity($payroll, $this->request->getData());
            if ($this->Payroll->save($payroll)) {
                $this->Flash->success(__('The payroll has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payroll could not be saved. Please, try again.'));
        }
        $users = $this->Payroll->Users->find('list', limit: 200)->all();
        $this->set(compact('payroll', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Payroll id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Salary Slip');

        $payroll = $this->Payroll->get($id, [
            'contain' => ['Users']
        ]);

        $payrollEarnings = $this->payrollEarningsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        $payrollDeductions = $this->PayrollDeductionsTable->find()
            ->where(['payroll_id' => $payroll->id])
            ->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (isset($data['net_payable'])) {
                $data['net_payable'] = (float)str_replace(',', '', $data['net_payable']);
            }

            $payroll = $this->Payroll->patchEntity($payroll, $data);

            if ($this->Payroll->save($payroll)) {
                ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');

                return $this->redirect(['action' => 'index']);
            } else {
                ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
            }
        }

        $this->set(compact('payroll', 'payrollEarnings', 'payrollDeductions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Payroll id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payroll = $this->Payroll->get($id);
        if ($this->Payroll->delete($payroll)) {
            $this->Flash->success(__('The payroll has been deleted.'));
        } else {
            $this->Flash->error(__('The payroll could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
