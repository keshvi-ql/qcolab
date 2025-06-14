<?php

declare(strict_types=1);

namespace App\Utility;

use ReflectionClass;
use Cake\Http\Response;
// use Authentication\IdentityInterface;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Http\ServerRequest;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Authentication\AuthenticationServiceInterface;

class ControllerHelper
{
    public static function getControllersAndActions()
    {
        $controllerDir = ROOT . DS . 'src' . DS . 'Controller';
        $files = self::getControllerFiles($controllerDir);

        $controllers = [];

        foreach ($files as $file) {
            $className = self::getClassNameFromFile($file);

            // Skip specific controllers like AppController and ErrorController
            if (in_array($className, ['App\Controller\AppController', 'App\Controller\ErrorController', 'App\Controller\BaseController'])) {
                continue;
            }

            if ($className) {
                $methods = self::listMethods($className);

                // Extract only the controller name, e.g., "Roles" instead of "App\Controller\RolesController"
                $controllerName = str_replace('Controller', '', basename($className));

                $controllers[$controllerName] = $methods;
            }
        }

        return $controllers;
    }

    private static function getControllerFiles($dir)
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getRealPath();
            }
        }

        return $files;
    }

    private static function getClassNameFromFile($filePath)
    {
        // Remove the ROOT directory and 'src/' prefix from the path
        $relativePath = str_replace([ROOT . DS . 'src' . DS, '.php'], '', $filePath);

        // Replace directory separators with namespace separators
        $className = str_replace(DS, '\\', $relativePath);

        // Prepend the App namespace
        return "App\\" . $className;
    }

    private static function listMethods($className)
    {
        // Get methods of the base Controller class to filter out inherited methods
        $baseControllerMethods = get_class_methods('Cake\Controller\Controller');

        // Define methods that should be excluded (public access methods)
        $publicAccessMethods = ['login', 'forgotPassword', 'resetPassword', 'logout'];

        $classFile = ROOT . DS . str_replace('\\', DS, str_replace('App\\', 'src/', $className)) . '.php';

        if (file_exists($classFile)) {
            require_once $classFile;
        }

        $methodsList = [];
        if (class_exists($className)) {
            $reflection = new ReflectionClass($className);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->class === $className && !in_array($method->name, $baseControllerMethods) && !in_array($method->name, $publicAccessMethods)) {
                    $methodsList[] = $method->name;
                }
            }
        }
        return $methodsList;
    }

    public static function setLoginUserPermissionsInSession($userId, $session)
    {
        // Fetch the user's role and permissions
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($userId, [
            'contain' => ['Roles.RolePermissions']
        ]);

        $permissionsTable = TableRegistry::getTableLocator()->get('RolePermissions');
        $permissions = $permissionsTable->find()
            ->where(['role_id' => $user->role->id])
            ->toArray();

        // Convert to multidimensional array format
        $permissionArray = [];
        foreach ($permissions as $permission) {
            $permissionArray[$permission->controller][] = $permission->action;
        }

        // Update session
        $session->write('User.permissions', $permissionArray);
    }

    public static function flashMessage($controller, $type, $messageKey)
    {
        $message = Configure::read($messageKey);

        if (!$message) {
            $message = $messageKey;
        }

        $controller->Flash->{$type}($message);
    }

    public static function getRoleName($roleId)
    {
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $role = $rolesTable->find()
            ->select(['name']) // Select only the 'name' field
            ->where(['id' => $roleId])
            ->first();

        return $role ? $role->name : null;
    }

    public static function adminRoleId()
    {
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $adminRoleId = $rolesTable->find()
        ->select(['id'])
        ->where(['name' => 'Admin'])
        ->first()
        ->id;

        return $adminRoleId ? $adminRoleId : 0;
    }

    public static function updateAllIdentityValues(ServerRequest $request, $newIdentityData)
    {
        $identity = $request->getAttribute('identity');

        if ($identity) {
            // Replace the identity with the new data
            $request = $request->withAttribute('identity', $newIdentityData);

            // Save the updated identity to the session or auth object
            $request->getSession()->write('Auth', $newIdentityData);

            return true;
        }

        return false;
    }
}
