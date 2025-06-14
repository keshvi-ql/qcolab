<?php

declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use \Authentication\IdentityInterface;
use Cake\Core\Configure;

/**
 * User helper
 */
class UserHelper extends Helper
{
    public array $helpers = ['Html'];

    public function hasPermission($controller, $action)
    {
        $session = $this->_View->getRequest()->getSession();
        $permissions = $session->read('User.permissions');

        if (isset($permissions[$controller]) && in_array($action, $permissions[$controller])) {
            return true;
        }

        return false;
    }

    public function getLoginUserAttribute(string $attribute, $default = null)
    {
        $identity = $this->getView()->getRequest()->getAttribute('identity');
        $adminRoleId = $this->getAdminRoleId();

        if ($identity instanceof IdentityInterface && $identity->get($attribute) !== null) {

            // Check if the attribute is 'is_admin' based on role_id
            if ($attribute === 'is_admin' && $adminRoleId !== null) {
                return $identity->get('role_id') === $adminRoleId;
            }

            if ($attribute === 'profile_image') {
                return \App\Model\Entity\User::getProfileImagePath() . $identity->get('profile_image');
            }

            return $identity->get($attribute);
        }

        return $default;
    }

    public function getAdminRoleId()
    {
        // Fetch the Admin role ID from the roles table
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $adminRole = $rolesTable->find()
            ->where(['name' => 'Admin'])
            ->first();

        return $adminRole ? $adminRole->id : null;
    }

    public function isAdmin(): bool
    {
        $identity = $this->getView()->getRequest()->getAttribute('identity');

        // Get the role_id for the Admin role
        $adminRoleId = $this->getAdminRoleId();

        if ($identity instanceof IdentityInterface && $identity->get('role_id') !== null && $adminRoleId !== null) {
            // Check if the user's role_id matches the admin role_id
            return $identity->get('role_id') === $adminRoleId;
        }

        return false;
    }

    public function profileImage(?string $imageName = null, array $options = [], ?string $firstName = null, ?string $lastName = null): string
    {
        $initials = '';
        if ($firstName) {
            $initials .= strtoupper($firstName[0]);
        }
        if ($lastName) {
            $initials .= strtoupper($lastName[0]);
        }

        if ($imageName) {
            $imageUrl = Router::url('/profile_img_uploads/' . $imageName, true);
            return $this->Html->image($imageUrl, $options);
        } else {
            return '<span class="letter-icon d-inline-flex align-items-center justify-content-center bg-warning text-white lh-1 rounded-pill w-40px h-40px">' . htmlspecialchars($initials) . '</span>';
        }
    }
}
