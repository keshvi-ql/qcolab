<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\FileHelper;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use App\Utility\SettingHelper;
use App\Utility\ControllerHelper;
use App\Utility\AjaxResponseHelper;
use App\Service\NotificationService;
use Cake\Http\Exception\NotFoundException;

/**
 * PartialLeaves Controller
 *
 * @property \App\Model\Table\PartialLeavesTable $PartialLeaves
 */
class PartialLeavesController extends BaseController
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
    public function index()
    {
        $this->set('title', 'Partial Leaves');

        $query = $this->PartialLeaves->find()
            ->where(['PartialLeaves.deleted' => 0])
            ->contain(['Applicant']);

        $partialLeaves = $this->paginate($query);

        $this->set(compact('partialLeaves'));
    }

    /**
     * View method
     *
     * @param string|null $id Partial Leave id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $partialLeave = $this->PartialLeaves->get($id, contain: []);
        $this->set(compact('partialLeave'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($leaveId = null)
    {
        if ($leaveId) {
            $partialLeaveData = $this->PartialLeaves->get($leaveId, [
                'contain' => ['Applicant', 'Checker']
            ]);
        }

        $partialLeave = $leaveId ? $partialLeaveData : $this->PartialLeaves->newEmptyEntity();

        $loginUserId = $this->Authentication->getIdentity()->get('id');

        $maxLeaveHours = 4;
        $totalHoursRecords = $this->PartialLeaves->find()
            ->select([
                'id',
                'applicant_id',
                'total_hours' => $this->PartialLeaves->find()->func()->sum('total_hours')
            ])
            ->where([
                'applicant_id' => $loginUserId,
                'deleted' => 0,
                'status' => 'approved',
                'start_date >=' => date('Y-m-01'),
                'start_date <=' => date('Y-m-t')
            ])
            ->group(['applicant_id'])
            ->all();

        $totalHoursCount = $totalHoursRecords->sumOf('total_hours') ?? 0;
        $remainingHours = max($maxLeaveHours - $totalHoursCount, 0);

        $leaveDetails = [
            'leave_application' => $partialLeave,
            'applicant_name' => $partialLeave->applicant ? $partialLeave->applicant->first_name . ' ' . $partialLeave->applicant->last_name : $this->Authentication->getIdentity()->get('first_name'),
            'applicant_avatar' => $partialLeave->applicant->profile_image ?? null,
            'applicant_job_title' => $partialLeave->applicant->job_title ?? null,
            'checker_name' => $partialLeave->checker ? $partialLeave->checker->first_name . ' ' . $partialLeave->checker->last_name : '',
            'checker_avatar' => $partialLeave->checker->profile_image ?? null,
        ];

        $adminRoleId = ControllerHelper::adminRoleId();

        $users = $this->fetchTable('Users')->find('list', [
            'key' => 'id',
            'value' => 'first_name'
        ])
            ->where(['role_id !=' => $adminRoleId])
            ->toArray();

        $notificationId = $this->request->getQuery('notification_id');
        if ($notificationId) {
            $this->notificationService->markAsRead($notificationId, $loginUserId);
        }

        if ($this->request->is(['post', 'put'])) {
            $existingFiles = '';
            if (!empty($leaveId)) {
                $existingFiles = $partialLeave->files;
            }

            $newFiles = $this->request->getData('uploadedFiles') ?? [];

            if (is_array($newFiles)) {
                $newFiles = implode(',', $newFiles); // Convert array to string
            }

            if (!empty($newFiles)) {
                // Move files from temp to permanent directory if new files are uploaded
                FileHelper::moveFilesFromTempDirToPermanentDir('temp_uploads', 'uploads', $newFiles);
            }

            $allFiles = !empty($existingFiles) ? $existingFiles . ',' . $newFiles : $newFiles;

            $start_date = $this->request->getData('start_date');
            $end_date = $start_date;
            $hours = $this->request->getData('hours');

            if (!empty($start_date)) {
                $start_date = FrozenDate::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
            }
            if (!empty($end_date)) {
                $end_date = FrozenDate::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
            }

            $now = date('Y-m-d H:i:s');
            $aplicantId = $this->request->getData('applicant_id');
            $data = array(
                "start_date" => $start_date,
                "end_date" => $end_date,
                "total_hours" => $hours,
                "applicant_id" => $aplicantId ?? $loginUserId,
                "reason" => $this->request->getData('reason'),
                "status" => $aplicantId ? "approved" : "pending",
                "created_at" => $now,
                "created_by" => 0,
                "checked_at" => null,
                "checked_by" => 0,
                "files" => $allFiles
            );

            if ($leaveId) {
                $data['status'] = $partialLeave->status;
            }

            if ($data['status'] == 'approved') {
                $data['checked_at'] = $now;
                $data['checked_by'] = $loginUserId;
            }

            $partialLeave = $this->PartialLeaves->patchEntity($partialLeave, $data);

            if ($this->PartialLeaves->save($partialLeave)) {

                if (!$leaveId) {
                    $this->sendNotifications($partialLeave->id, $partialLeave);
                }

                return $this->redirect(['action' => 'index']);
                // return AjaxResponseHelper::createResponse(
                //     true,
                //     'Partial leave has been saved successfully.'
                // );
            } else {
                // $message = 'The partial leave could not be saved. Please, try again.';
                // $status = false;
                // return $this->redirect(['action' => 'index']);
            }
        }

        $this->set(compact('partialLeave', 'totalHoursCount', 'remainingHours', 'leaveDetails', 'users'));
        $this->viewBuilder()->setLayout('ajax');
    }

    private function sendNotifications($leaveId, $leave)
    {
        if ($leave->status == 'approved') {
            $type = 'partial_leave_assigned';
            $message = 'Partial Leave Assigned';
        }
        if ($leave->status == 'pending') {
            $type = 'partial_leave_application_submitted';
            $message = 'Partial Leave applied Successfully';
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
        //             $this->sendNotificationEmail($recipient, $partialLeave);
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
            '{EVENT_TITLE}' => 'Partial Leave Applied',
            '{EVENT_DETAILS}' => $leave,
        ];

        $template_body = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->message);
        $template_subject = str_replace(array_keys($placeholders), array_values($placeholders), $get_email_template->subject);

        $this->sendEmail($user->email, $template_subject, $template_body);
    }

    public function deleteFiles()
    {
        $this->request->allowMethod(['post', 'delete']);

        $id = $this->request->getData('id'); // ID of the partial leave
        $filename = $this->request->getData('file'); // Filename to delete

        $partialLeave = $this->PartialLeaves->get($id);

        if ($partialLeave && $filename) {
            $fileArray = explode(',', $partialLeave->files);
            if (($key = array_search($filename, $fileArray)) !== false) {
                // Remove the file from the array
                unset($fileArray[$key]);

                // Update the files field with remaining files
                $partialLeave->files = implode(',', $fileArray);

                // Save the updated record
                if ($this->PartialLeaves->save($partialLeave)) {
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

            $leave = $this->PartialLeaves->get($leaveId);

            $now = date('Y-m-d H:i:s');
            $leave_data = array(
                "checked_by" => $this->Authentication->getIdentity()->get('id'),
                "checked_at" => $now,
                "status" => $status,
            );

            $leave = $this->PartialLeaves->patchEntity($leave, $leave_data);

            if ($this->PartialLeaves->save($leave)) {

                $userId = $this->Authentication->getIdentity()->get('id');
                if ($notificationId) {
                    $this->notificationService->markAsRead($notificationId, $userId);
                }

                if ($status == 'approved') {
                    $type = 'partial_leave_approved';
                    $message = 'Partial Leave Approved';
                }
                if ($status == 'rejected') {
                    $type = 'partial_leave_rejected';
                    $message = 'Partial Leave Rejected';
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
     * @param string|null $id Partial Leave id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        if ($this->PartialLeaves->softDelete($id)) {
            $this->Flash->success(__('The leave application has been marked as deleted.'));
        } else {
            $this->Flash->error(__('The leave application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
