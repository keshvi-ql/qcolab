<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $alt_email
 * @property string|null $phone_no
 * @property string|null $skype
 * @property int|null $country
 * @property int|null $source
 * @property bool|null $favorite
 * @property string|null $note
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 */
class Client extends Entity
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
        'bid_id' => true,
        'lead_no' => true,
        'first_name' => true,
        'last_name' => true,
        'email' => true,
        'alt_email' => true,
        'phone_no' => true,
        'skype' => true,
        'country' => true,
        'source' => true,
        'favorite' => true,
        'note' => true,
        'type' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'client_converted_at' => true,
    ];
}
