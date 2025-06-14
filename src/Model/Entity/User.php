<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property bool $is_admin
 * @property string|null $phone_no
 * @property string|null $alt_phone_no
 * @property \Cake\I18n\Date|null $dob
 * @property string|null $profile_image
 * @property string $email
 * @property string $password
 * @property string|null $job_title
 * @property bool $is_trainee
 * @property string|null $remember_me_token
 * @property string|null $token
 * @property \Cake\I18n\DateTime|null $email_verified_at
 * @property \Cake\I18n\DateTime|null $token_requested_at
 * @property \Cake\I18n\DateTime|null $password_updated_at
 * @property \Cake\I18n\DateTime|null $last_login_at
 * @property bool $status
 * @property int $role_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Role $role
 */
class User extends Entity
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
        'first_name' => true,
        'middle_name' => true,
        'last_name' => true,
        'is_admin' => true,
        'phone_no' => true,
        'alt_phone_no' => true,
        'dob' => true,
        'profile_image' => false,
        'email' => true,
        'password' => true,
        'confirm_password' => false,
        'job_title' => true,
        'is_trainee' => true,
        'remember_me_token' => true,
        'token' => true,
        'email_verified_at' => true,
        'token_requested_at' => true,
        'password_updated_at' => true,
        'last_login_at' => true,
        'status' => true,
        'role_id' => true,
        'created' => true,
        'modified' => true,
        'gender' => true,
        'alt_email' => true,
        'address' => true,
        'alt_address' => true,
        'skype' => true,
        'employee_code' => true,
        'pan_no' => true,
        'bank_name' => true,
        'bank_account_no' => true,
        'security_deposit_amount' => true,
        'salary' => true,
        'date_of_joining' => true,
        'increment_month' => true,
        'is_bde' => true,
        'sticky_note' => true,
        'deleted' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }

    protected function _setFirstName($firstName)
    {
        return ucfirst(trim($firstName));
    }

    protected function _setMiddleName($middleName)
    {
        return ucfirst(trim($middleName));
    }

    protected function _setLastName($lastName)
    {
        return ucfirst(trim($lastName));
    }

    protected function _setEmail($email)
    {
        return trim($email);
    }

    protected static $profileImagePath = '/profile_img_uploads/';

    public function getProfileImage()
    {
        if (!empty($this->profile_image)) {
            // Return the full path to the user's profile image
            return self::$profileImagePath . $this->profile_image;
        }

        return '/assets/images/avatar.jpg';
    }

    // Function to retrieve profile image path
    public static function getProfileImagePath()
    {
        return self::$profileImagePath;
    }

    // protected function _getFirstName($firstName)
    // {
    //     return stripslashes($firstName);
    // }
}
