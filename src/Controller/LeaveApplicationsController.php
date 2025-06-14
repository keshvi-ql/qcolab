<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Chronos\Chronos;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use App\Utility\FileHelper;
use Cake\ORM\TableRegistry;
use App\Utility\SettingHelper;
use App\Utility\ControllerHelper;
use App\Utility\AjaxResponseHelper;
use App\Service\NotificationService;
use Cake\Http\Exception\NotFoundException;

/**
 * LeaveApplications Controller
 *
 * @property \App\Model\Table\LeaveApplicationsTable $LeaveApplications
 */
class LeaveApplicationsController extends BaseController
{

    protected $notificationService;

    public function initialize(): void
    {
        parent::initialize();
        $this->notificationService = new NotificationService();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    // public function index()
    // {
    //     $this->set('title', 'Leave');

    //     $query = $this->LeaveApplications->find()
    //         ->where(['LeaveApplications.deleted' => 0])
    //         ->contain(['LeaveTypes', 'Applicant', 'Checker']);
    //     $leaveApplications = $this->paginate($query);

    //     $LeaveTypes = $this->LeaveApplications->LeaveTypes->find('list', [
    //         'key' => 'id',
    //         'value' => 'title'
    //     ])->toArray();

    //     $this->set(compact('leaveApplications', 'LeaveTypes'));
    // }
     public function index()
    {
        $this->set('title', 'Leave');
 
        $loginUser = $this->Authentication->getIdentity();
        $loginUserId = $loginUser->get('id');
        $roleId = $loginUser->get('role_id');
        $adminRoleId = ControllerHelper::adminRoleId();
 
        $query = $this->LeaveApplications->find()
            ->where(['LeaveApplications.deleted' => 0])
            ->contain(['LeaveTypes', 'Applicant', 'Checker']);
 
        // If not admin, show only their own leave applications
        if ($roleId != $adminRoleId) {
            $query->where(['LeaveApplications.applicant_id' => $loginUserId]);
        }
 
        $leaveApplications = $this->paginate($query);
 
        $LeaveTypes = $this->LeaveApplications->LeaveTypes->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();
 
        $this->set(compact('leaveApplications', 'LeaveTypes'));
    }

    public function add($leaveId = null)
    {
        $leave = '';
        $loginUserId = $this->Authentication->getIdentity()->get('id');
        $LeaveTypes = $this->LeaveApplications->LeaveTypes->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        if ($leaveId) {
            $leaveApplication = $this->LeaveApplications->get($leaveId, [
                'contain' => ['LeaveTypes', 'Applicant', 'Checker']
            ]);
        }

        $adminRoleId = ControllerHelper::adminRoleId();
        $users = $this->fetchTable('Users')->find('list', [
            'key' => 'id',
            'value' => 'first_name'
        ])
            ->where(['role_id !=' => $adminRoleId])
            ->toArray();

        $leave = $leaveId ? $leaveApplication : $this->LeaveApplications->newEmptyEntity();

        $leaveDetails = [
            'leave_application' => $leave,
            'leave_type' => $leave->leave_type->title ?? null,
            'leave_type_color' => $leave->leave_type->color ?? null,
            'applicant_name' => $leave->applicant ? $leave->applicant->first_name . ' ' . $leave->applicant->last_name : $this->Authentication->getIdentity()->get('first_name'),
            'applicant_avatar' => $leave->applicant->profile_image ?? null,
            'applicant_job_title' => $leave->applicant->job_title ?? null,
            'checker_name' => $leave->checker ? $leave->checker->first_name . ' ' . $leave->checker->last_name : '',
            'checker_avatar' => $leave->checker->profile_image ?? null,
        ];

        $notificationId = $this->request->getQuery('notification_id');
        if ($notificationId) {
            $this->notificationService->markAsRead($notificationId, $loginUserId);
        }

        if ($this->request->is(['post', 'put'])) {

            // Process uploaded files
            $newFiles = $this->processUploadedFiles($leaveId);

            // Calculate leave duration and hours
            list($duration, $hours, $days, $half_day_type, $start_date, $end_date) = $this->calculateLeaveDuration($leave);

            // Prepare leave data for saving
            $leave_data = $this->prepareLeaveData($leave, $newFiles, $duration, $hours, $days, $half_day_type, $start_date, $end_date);

            $duration = $this->request->getData('duration');
            $hours_per_day = 8;

            if ($duration === "multiple_days") {
                $days = $this->leave_days_count($leave_data);
                $leave_data['total_days'] = $days;
                $leave_data['total_hours'] = $days * $hours_per_day;
            }

            if ($leaveId) {
                $leave_data['status'] = $leave->status;
            }

            // Patch the leave entity with the new data
            $leave = $this->LeaveApplications->patchEntity($leave, $leave_data);

            // Save leave application
            if ($this->LeaveApplications->save($leave)) {
                if (!$leaveId) {
                    $this->sendNotifications($leave->id, $leave);
                }
                return $this->redirect(['action' => 'index']);
                // return AjaxResponseHelper::createResponse(true, 'Leave has been saved.');
            } else {
                // return AjaxResponseHelper::createResponse(false, 'Unable to save leave.', ['errors' => $leave->getErrors()]);
            }
        }

        $this->set(compact('leave', 'LeaveTypes', 'leaveDetails', 'users'));
        $this->viewBuilder()->setLayout('ajax');
    }

    private function processUploadedFiles($leaveId)
    {
        $existingFiles = '';
        if ($leaveId) {
            $leave = $this->LeaveApplications->get($leaveId);
            $existingFiles = $leave->files;
        }

        $newFiles = $this->request->getData('uploadedFiles') ?? [];
        if (is_array($newFiles)) {
            $newFiles = implode(',', $newFiles);
        }

        if (!empty($newFiles)) {
            FileHelper::moveFilesFromTempDirToPermanentDir('temp_uploads', 'uploads', $newFiles);
        }

        return !empty($existingFiles) ? $existingFiles . ',' . $newFiles : $newFiles;
    }

    private function calculateLeaveDuration($leave)
    {
        $start_date = $end_date = '';
        $duration = $this->request->getData('duration');
        $hours_per_day = 8;
        $hours = 0;
        $days = 0;
        $half_day_type = NULL;
        if ($duration == 'single_day') {
            $hours = $hours_per_day;
            $days = 1;
            $start_date = $this->request->getData('single_date') ?? $leave->start_date;
            $start_date = $start_date instanceof \Cake\I18n\Date ? $start_date->i18nFormat('Y-m-d') : $start_date;
            $end_date = $start_date;
        } elseif ($duration == 'multiple_days') {
            $start_date = $this->request->getData('start_date') ?? $leave->start_date;
            $end_date = $this->request->getData('end_date') ?? $leave->end_date;

            $start_date = $start_date instanceof \Cake\I18n\Date ? $start_date->i18nFormat('Y-m-d') : $start_date;
            $end_date = $end_date instanceof \Cake\I18n\Date ? $end_date->i18nFormat('Y-m-d') : $end_date;

            // Calculate total days
            $d_start = new \DateTime($start_date);
            $d_end = new \DateTime($end_date);

            $days = $d_start->diff($d_end)->days + 1;
            $hours = $days * $hours_per_day;
        } elseif ($duration == 'half_day') {
            $start_date = $this->request->getData('hour_date') ?? $leave->start_date;
            $start_date = $start_date instanceof \Cake\I18n\Date ? $start_date->i18nFormat('Y-m-d') : $start_date;
            $half_day_type = $this->request->getData('half_day_type');
            $end_date = $start_date;
            $hours = $hours_per_day / 2;
            $days = $hours / $hours_per_day;
        }

        if (!empty($start_date)) {
            $start_date = FrozenDate::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
        }
        if (!empty($end_date)) {
            $end_date = FrozenDate::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
        }

        return [$duration, $hours, $days, $half_day_type, $start_date, $end_date];
    }

    private function prepareLeaveData($leave, $newFiles, $duration, $hours, $days, $half_day_type, $start_date, $end_date)
    {
        $now = date('Y-m-d H:i:s');

        $aplicantId = $this->request->getData('applicant_id');

        return [
            "leave_type_id" => $this->request->getData('leave_type_id'),
            "start_date" => $start_date,
            "end_date" => $end_date,
            "total_hours" => $hours,
            "total_days" => $days,
            "half_day_type" => $half_day_type,
            "applicant_id" => $aplicantId ?? $this->Authentication->getIdentity()->get('id'),
            "reason" => $this->request->getData('reason'),
            "status" => $aplicantId ? "approved" : "pending",
            "created_at" => $now,
            "created_by" => 0,
            "checked_at" => $aplicantId ? $now : null,
            "checked_by" => $aplicantId ? $this->Authentication->getIdentity()->get('id') : 0,
            "files" => $newFiles,
        ];
    }

    private function sendNotifications($leaveId, $leave)
    {
        if ($leave->status == 'approved') {
            $type = 'leave_assigned';
            $message = 'Leave Assigned';
        }
        if ($leave->status == 'pending') {
            $type = 'leave_application_submitted';
            $message = 'Leave applied Successfully';
        }
        $getData = SettingHelper::getNotificationSettingValue($type);

        if ($getData->enable_system == 1) {
            $recipients = !empty($getData->notify_to_team_members) ? explode(',', $getData->notify_to_team_members) : [$leave->applicant_id];
            $module = $getData->module;
            $createdBy = $leave->applicant_id;
            $this->notificationService->createNotification($type, $message, $createdBy, $recipients, $leaveId, $module);
        }

        // if ($getData->enable_email == 1) {
        //     $recipients = !empty($getData->notify_to_team_members) ? explode(',', $getData->notify_to_team_members) : [];
        //     if (count($recipients) > 0) {
        //         foreach ($recipients as $recipient) {
        //             $this->sendNotificationEmail($recipient, $leave);
        //         }
        //     }
        // }
    }

    private function sendNotificationEmail($userId, $leave)
    {
        $get_email_template = $this->getEmailTemplate('general_notification');

        if (!$get_email_template) {
            return false;
        }

        $userTable = TableRegistry::getTableLocator()->get('Users');
        $user = $userTable->find()
            ->where(['id' => $userId])
            ->first();

        $placeholders = [
            '{APP_TITLE}' => 'Q-Collab',
            '{EVENT_TITLE}' => 'Leave Applied',
            '{EVENT_DETAILS}' => $leave,
        ];

        $template_body = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->message);
        $template_subject = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->subject);

        $this->sendEmail($user->email, $template_subject, $template_body);
    }

    private function leave_days_count($leaveData)
    {
        $days = 0;
        $applicantId = $leaveData['applicant_id'];

        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $userData = $usersTable->get($applicantId, [
            'conditions' => ['Users.status' => 1]
        ]);

        $dStart = FrozenTime::createFromFormat('Y-m-d', $leaveData['start_date']);
        $dEnd = FrozenTime::createFromFormat('Y-m-d', $leaveData['end_date']);
        $dEnd = $dEnd->modify('+1 days'); // Include the end date

        $dateRange = new \DatePeriod($dStart, new \DateInterval('P1D'), $dEnd);

        $leaveDates = [];
        foreach ($dateRange as $date) {
            $leaveDates[] = $date->format('Y-m-d');
        }

        $lastSaturday = (new FrozenTime("last Saturday of " . $leaveData['start_date']))->format('Y-m-d');

        foreach ($leaveDates as $date) {
            if ($this->holidayCheck($date)) {
                continue; // Skip holidays
            }

            $dayOfWeek = Chronos::parse($date)->format('l'); // Get day of the week

            if ($dayOfWeek == 'Sunday') {
                continue; // Skip Sundays
            } elseif ($dayOfWeek == 'Saturday') {
                // Apply Saturday logic
                if ($userData->is_trainee) {
                    $days += 0.5;
                } elseif ($date == $lastSaturday) {
                    $days += 0.5;
                }
            } else {
                $days++;
            }
        }

        return $days;
    }

    private function holidayCheck($date)
    {
        return false;

        // $holidaysTable = TableRegistry::getTableLocator()->get('Holidays');
        // $holiday = $holidaysTable->find()
        // ->where(['Holidays.deleted' => 0, 'Holidays.h_date' => $date])
        // ->first();

        // return !empty($holiday);
    }

    public function deleteFiles()
    {
        $this->request->allowMethod(['post', 'delete']);

        $id = $this->request->getData('id'); // ID of the partial leave
        $filename = $this->request->getData('file'); // Filename to delete

        $Leave = $this->LeaveApplications->get($id);

        if ($Leave && $filename) {
            $fileArray = explode(',', $Leave->files);
            if (($key = array_search($filename, $fileArray)) !== false) {
                // Remove the file from the array
                unset($fileArray[$key]);

                $Leave->files = implode(',', $fileArray);

                if ($this->LeaveApplications->save($Leave)) {
                    try {
                        // Delete the file from the server
                        FileHelper::deleteFile($filename, 'uploads');
                        return AjaxResponseHelper::createResponse(true, 'File deleted successfully');
                    } catch (NotFoundException $e) {
                        return AjaxResponseHelper::createResponse(false, 'File not found.');
                    }
                }
                return AjaxResponseHelper::createResponse(false, 'Failed to update record.');
            } else {
                return AjaxResponseHelper::createResponse(false, 'File not found in database record.');
            }
        }
        return AjaxResponseHelper::createResponse(false, 'File not found.');
    }

    public function updateStatus()
    {
        $this->autoRender = false;
        if ($this->request->is(['post', 'put'])) {

            $leaveId = $this->request->getData('id');
            $status = $this->request->getData('status');
            $notificationId = $this->request->getData('notification_id');

            $leave = $this->LeaveApplications->get($leaveId);

            $now = date('Y-m-d H:i:s');
            $leave_data = array(
                "checked_by" => $this->Authentication->getIdentity()->get('id'),
                "checked_at" => $now,
                "status" => $status,
            );

            $leave = $this->LeaveApplications->patchEntity($leave, $leave_data);

            if ($this->LeaveApplications->save($leave)) {

                $userId = $this->Authentication->getIdentity()->get('id');
                if ($notificationId) {
                    $this->notificationService->markAsRead($notificationId, $userId);
                }

                if ($status == 'approved') {
                    $type = 'leave_approved';
                    $message = 'Leave Approved';
                }
                if ($status == 'rejected') {
                    $type = 'leave_rejected';
                    $message = 'Leave Rejected';
                }
                $getData = SettingHelper::getNotificationSettingValue($type);
                $recipients = !empty($getData->notify_to_team_members) ? explode(',', $getData->notify_to_team_members) : [$leave->applicant_id];
                $module = $getData->module;
                $createdBy = $this->Authentication->getIdentity()->get('id');
                $this->notificationService->createNotification($type, $message, $createdBy, $recipients, $leaveId, $module);

                return AjaxResponseHelper::createResponse(
                    true,
                    'Status Updated'
                );
            } else {
                $errors = $leave->getErrors();
                return AjaxResponseHelper::createResponse(false, 'Status not updated', ['errors' => $errors]);
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Leave Application id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if ($this->LeaveApplications->softDelete($id)) {
            $this->Flash->success(__('The leave application has been marked as deleted.'));
        } else {
            $this->Flash->error(__('The leave application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
