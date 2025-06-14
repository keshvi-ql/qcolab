<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Notification Entity
 *
 * @property int $id
 * @property string $type
 * @property string $module
 * @property int|null $entity_id
 * @property int $created_by
 * @property string $message
 * @property \Cake\I18n\DateTime $created_at
 * @property int $deleted
 *
 * @property \App\Model\Entity\NotificationRecipient[] $notification_recipients
 * @property \App\Model\Entity\User $user
 */
class Notification extends Entity
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
        'type' => true,
        'module' => true,
        'entity_id' => true,
        'created_by' => true,
        'message' => true,
        'created_at' => true,
        'deleted' => true,
        'notification_recipients' => true,
        'user' => true,
    ];
}
