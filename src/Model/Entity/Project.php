<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Project Entity
 *
 * @property int $id
 * @property string|null $project_no
 * @property int $project_status_id
 * @property int $client_id
 * @property string $title
 * @property string|null $description
 * @property string|null $url
 * @property \Cake\I18n\Date $start_date
 * @property \Cake\I18n\Date|null $deadline
 * @property string $type
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int $deleted
 *
 * @property \App\Model\Entity\ProjectStatus $project_status
 * @property \App\Model\Entity\Client $client
 */
class Project extends Entity
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
        'project_no' => true,
        'status_id' => true,
        'client_id' => true,
        'title' => true,
        'description' => true,
        'url' => true,
        'start_date' => true,
        'deadline' => true,
        'type' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'project_status' => true,
        'client' => true,
    ];
}
