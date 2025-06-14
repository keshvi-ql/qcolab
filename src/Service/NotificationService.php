<?php

namespace App\Service;

use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

class NotificationService
{
    protected $Notifications;
    protected $NotificationRecipients;

    public function __construct()
    {
        $this->Notifications = TableRegistry::getTableLocator()->get('Notifications');
        $this->NotificationRecipients = TableRegistry::getTableLocator()->get('NotificationRecipients');
    }

    // Method to create a notification
    public function createNotification($type, $message, $createdBy, $recipients, $entityId, $module = null)
    {
        // Create a notification record
        $notification = $this->Notifications->newEntity([
            'type' => $type,
            'message' => $message,
            'created_by' => $createdBy,
            'created_at' => date('Y-m-d H:i:s'),
            'module' => $module,
            'entity_id' => $entityId
        ]);

        if ($this->Notifications->save($notification)) {
            // Associate recipients with the notification
            foreach ($recipients as $recipientId) {
                $recipient = $this->NotificationRecipients->newEntity([
                    'notification_id' => $notification->id,
                    'user_id' => $recipientId,
                    'is_read' => false
                ]);
                $this->NotificationRecipients->save($recipient);
            }
            return true;
        }

        return false;
    }

    // Fetch recent notifications for a user
    public function getUserNewNotifications($userId, $limit = 10)
    {
        return $this->NotificationRecipients->find()
            ->contain([
                'Notifications' => [
                    'Users' => function ($q) {
                        return $q->select(['Users.id', 'Users.first_name', 'Users.last_name', 'Users.profile_image']);
                    }
                ]
            ])
            ->where(['NotificationRecipients.user_id' => $userId])
            ->where(['NotificationRecipients.is_read' => 0])
            ->where(['NotificationRecipients.deleted' => 0])
            ->order(['Notifications.created_at' => 'DESC'])
            ->limit($limit)
            ->all();
    }

    public function getUserOldNotifications($userId, $limit = 10)
    {
        return $this->NotificationRecipients->find()
            ->contain([
                'Notifications' => [
                    'Users' => function ($q) {
                        return $q->select(['Users.id', 'Users.first_name', 'Users.last_name', 'Users.profile_image']);
                    }
                ]
            ])
            ->where(['NotificationRecipients.user_id' => $userId])
            ->where(['NotificationRecipients.is_read' => 1])
            ->where(['NotificationRecipients.deleted' => 0])
            ->order(['Notifications.created_at' => 'DESC'])
            ->limit($limit)
            ->all();
    }

    // Mark notification as read
    public function markAsRead($notificationId, $userId)
    {
        $notificationRecipient = $this->NotificationRecipients->find()
            ->where([
                'notification_id' => $notificationId,
                'user_id' => $userId
            ])
            ->first();

        if ($notificationRecipient) {
            $notificationRecipient->is_read = true;
            $notificationRecipient->read_at = date('Y-m-d H:i:s');
            return $this->NotificationRecipients->save($notificationRecipient);
        }

        throw new RecordNotFoundException('Notification not found.');
    }

    // Get unread notification count
    public function getUnreadCount($userId)
    {
        return $this->NotificationRecipients->find()
            ->where(['user_id' => $userId, 'is_read' => false, 'deleted' => 0])
            ->count();
    }
}
