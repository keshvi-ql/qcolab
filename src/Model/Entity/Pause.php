<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pause Entity
 *
 * @property int $id
 * @property int $time_log_id
 * @property \Cake\I18n\DateTime $pause_time
 * @property \Cake\I18n\DateTime|null $resume_time
 * @property \Cake\I18n\Time|null $pause_duration
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\TimeLog $time_log
 */
class Pause extends Entity
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
        'time_log_id' => true,
        'pause_time' => true,
        'resume_time' => true,
        'pause_duration' => true,
        'created' => true,
        'modified' => true,
        'time_log' => true,
    ];
}
