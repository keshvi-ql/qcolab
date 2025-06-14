<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GeneralFile Entity
 *
 * @property int $id
 * @property string $file_name
 * @property string|null $file_id
 * @property string|null $description
 * @property string $file_size
 * @property int $user_id
 * @property int $uploaded_by
 * @property bool $deleted
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 */
class GeneralFile extends Entity
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
        'file_name' => true,
        'file_id' => true,
        'description' => true,
        'file_size' => true,
        'user_id' => true,
        'uploaded_by' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
