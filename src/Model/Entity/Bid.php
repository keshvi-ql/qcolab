<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bid Entity
 *
 * @property int $id
 * @property string $url
 * @property int $source
 * @property int $profile
 * @property string $type
 * @property string $rate
 * @property int $created_by
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int $deleted
 */
class Bid extends Entity
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
        'url' => true,
        'source' => true,
        'profile' => true,
        'type' => true,
        'rate' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
