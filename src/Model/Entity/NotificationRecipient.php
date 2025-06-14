<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotificationRecipient Entity
 *
 * @property int $id
 * @property int $notification_id
 * @property int $user_id
 * @property bool $is_read
 * @property \Cake\I18n\DateTime|null $read_at
 * @property bool $deleted
 *
 * @property \App\Model\Entity\Notification $notification
 * @property \App\Model\Entity\User $user
 */
class NotificationRecipient extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'notification_id' => true,
        'user_id' => true,
        'is_read' => true,
        'read_at' => true,
        'deleted' => true,
        'notification' => true,
        'user' => true,
    ];
}
