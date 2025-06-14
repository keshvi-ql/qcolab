<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PartialLeave Entity
 *
 * @property int $id
 * @property \Cake\I18n\Date $start_date
 * @property \Cake\I18n\Date $end_date
 * @property string $total_hours
 * @property int $applicant_id
 * @property string $reason
 * @property string $status
 * @property \Cake\I18n\DateTime $created_at
 * @property int $created_by
 * @property \Cake\I18n\DateTime|null $checked_at
 * @property int $checked_by
 * @property string|null $files
 * @property int $deleted
 */
class PartialLeave extends Entity
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
        'start_date' => true,
        'end_date' => true,
        'total_hours' => true,
        'applicant_id' => true,
        'reason' => true,
        'status' => true,
        'created_at' => true,
        'created_by' => true,
        'checked_at' => true,
        'checked_by' => true,
        'files' => true,
        'deleted' => true,
    ];
}
