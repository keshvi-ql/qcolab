<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotificationSetting Entity
 *
 * @property int $id
 * @property string $type
 * @property string $module
 * @property bool $enable_email
 * @property bool $enable_system
 * @property string|null $notify_to_team_members
 * @property bool $deleted
 */
class NotificationSetting extends Entity
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
        'enable_email' => true,
        'enable_system' => true,
        'notify_to_team_members' => true,
        'deleted' => true,
    ];
}
