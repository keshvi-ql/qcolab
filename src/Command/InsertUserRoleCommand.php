<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use App\Utility\ControllerHelper;

/**
 * InsertUserRole command.
 */
class InsertUserRoleCommand extends Command
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // Load the Users and Roles tables
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $rolePermissionsTable = TableRegistry::getTableLocator()->get('RolePermissions');

        // Create a new role entity
        $role = $rolesTable->newEntity([
            'name' => 'Admin', // Example role name
        ]);

        // Save the role
        if ($rolesTable->save($role)) {
            $io->out('Role saved successfully with ID: ' . $role->id);

            // Create a new user entity
            $user = $usersTable->newEntity([
                'first_name' => 'Admin',
                'middle_name' => '',
                'last_name' => '',
                'is_admin' => 1,
                'phone_no' => '1234567890',
                'alt_phone_no' => '0987654321',
                'dob' => '1990-01-01', // Use Y-m-d format for dates
                'profile_image' => '',
                'email' => 'admin@gmail.com',
                'password' => 'admin', // Ensure this is hashed in your User entity
                'job_title' => 'Software Developer',
                'is_trainee' => 0,
                'status' => 1,
                'role_id' => $role->id, // Link user to the role
                'created' => FrozenTime::now(), // Current timestamp
                'modified' => FrozenTime::now(),
            ]);

            // Save the user
            if ($usersTable->save($user)) {
                $io->out('User saved successfully with ID: ' . $user->id);

                // Retrieve all controllers and actions using the ControllerHelper class
                $controllerHelper = new ControllerHelper();
                $controllersAndActions = $controllerHelper->getControllersAndActions();

                foreach ($controllersAndActions as $controller => $actions) {
                    foreach ($actions as $action) {
                        // Insert directly into role_permissions table
                        $rolePermission = $rolePermissionsTable->newEntity([
                            'role_id' => $role->id,
                            'controller' => $controller,
                            'action' => $action,
                        ]);

                        if ($rolePermissionsTable->save($rolePermission)) {
                            $io->out('Permission saved: ' . $controller . ' -> ' . $action);
                        } else {
                            $io->err('Failed to save permission for: ' . $controller . ' -> ' . $action);
                        }
                    }
                }
            } else {
                $io->err('Failed to save user.');
            }
        } else {
            $io->err('Failed to save role.');
        }
    }
}
