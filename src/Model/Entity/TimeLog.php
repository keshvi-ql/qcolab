<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TimeLog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property \Cake\I18n\Date $date
 * @property \Cake\I18n\DateTime $clock_in_time
 * @property \Cake\I18n\DateTime|null $clock_out_time
 * @property \Cake\I18n\Time|null $total_work_duration
 * @property string|null $status
 * @property string|null $note
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Pause[] $pauses
 */
class TimeLog extends Entity
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
        'user_id' => true,
        'date' => true,
        'clock_in_time' => true,
        'clock_out_time' => true,
        'total_work_duration' => true,
        'status' => true,
        'note' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'pauses' => true,
    ];
}
