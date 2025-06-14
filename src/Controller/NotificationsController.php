<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use App\Service\NotificationService;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\NotificationsTable $Notifications
 */
class NotificationsController extends BaseController
{
    protected $notificationService;

    public function initialize(): void
    {
        parent::initialize();
        $this->notificationService = new NotificationService();
    }

    public function fetchNotifications()
    {
        $userId = $this->Authentication->getIdentity()->get('id');
        $newNotifications = $this->notificationService->getUserNewNotifications($userId);
        $oldNotifications = $this->notificationService->getUserOldNotifications($userId);

        $this->set(compact('newNotifications', 'oldNotifications'));
        $this->viewBuilder()->setLayout('ajax');
    }

    public function countNotifications()
    {
        $this->autoRender = false;
        $userId = $this->Authentication->getIdentity()->get('id');
        $count = $this->notificationService->getUnreadCount($userId);

        return AjaxResponseHelper::createResponse(
            true,
            'Count Notification',
            ['count' => $count]
        );
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function index()
    {
        $this->set('title', 'Notifications Settings');

        // Fetch Notification Settings
        $notificationSettingTable = TableRegistry::getTableLocator()->get('NotificationSettings');
        $query = $notificationSettingTable->find();
        $notifications = $this->paginate($query);

        // Fetch Users and create an array of user IDs to first names
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $users = $usersTable->find()->toArray();
        $userNamesById = [];

        // Create a mapping of user ID to first name
        foreach ($users as $user) {
            $userNamesById[$user->id] = $user->first_name;
        }

        // Process each notification to convert user IDs to names
        foreach ($notifications as $notification) {
            if ($notification->notify_to_team_members) {
                $notifyToIds = explode(',', $notification->notify_to_team_members); // Split IDs
                $notifyToNames = []; // Array to store the user names

                // Map user IDs to first names
                foreach ($notifyToIds as $userId) {
                    if (isset($userNamesById[$userId])) {
                        $notifyToNames[] = $userNamesById[$userId];
                    }
                }

                // Add the user names to the notification object
                $notification->notify_to_team_members_names = $notifyToNames;
            }
        }

        $this->set(compact('notifications', 'users'));
    }

    public function saveNotificationSettings()
    {
        // Ensure the request is an AJAX request
        if ($this->request->is('ajax')) {
            $this->autoRender = false; // No need to render a view for AJAX

            $notificationSettingId = $this->request->getData('id');
            $notificationSettingTable = TableRegistry::getTableLocator()->get('NotificationSettings');
            $notificationSetting = $notificationSettingTable->get($notificationSettingId);

            if (!$notificationSetting) {
                throw new NotFoundException('Notification not found');
            }

            // Prepare the data
            $data = [
                'enable_system' => $this->request->getData('enable_system'),
                'enable_email' => $this->request->getData('enable_email'),
            ];

            $teamMembers = $this->request->getData('notify_to_team_members');

            if (is_array($teamMembers)) {
                // Convert the array to a comma-separated string
                $data['notify_to_team_members'] = implode(',', $teamMembers);
            } else {
                $data['notify_to_team_members'] = ''; // Handle if no team members are selected
            }

            // Patch the entity and save
            $notification = $notificationSettingTable->patchEntity($notificationSetting, $data);

            if ($notificationSettingTable->save($notification)) {
                return AjaxResponseHelper::createResponse(true, 'Settings updated successfully');
            } else {
                return AjaxResponseHelper::createResponse(false, 'Failed to update settings');
            }
        }
    }
}
