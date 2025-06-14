<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Model\Entity\RolePermission;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Roles Controller
 *
 * @property \App\Model\Table\RolesTable $Roles
 */
class RolesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Roles');

        $query = $this->Roles->find();
        $roles = $this->paginate($query);

        $this->set(compact('roles'));
    }

    /**
     * View method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title', 'Add Role');

        $role = $this->Roles->newEmptyEntity();
        if ($this->request->is('post')) {

            $role = $this->Roles->patchEntity($role, $this->request->getData());

            // Get the selected permissions from the request data
            $selectedPermissions = array_filter($this->request->getData('permissions', []), function ($value) {
                return $value !== '0' && strpos($value, '-') !== false;
            });

            // Check if at least one permission is selected
            if (empty($selectedPermissions)) {
                $role->setError('permissions', ['Please select at least one Action of any Controller.']);
            }

            if ($role->hasErrors()) {
                $errorMessages = [];
                foreach ($role->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error(__($errorMessage), ['escape' => false]);
            } else {
                if ($this->Roles->save($role)) {
                    // Handle role permissions after saving the role
                    $selectedPermissions = array_filter($this->request->getData('permissions', []), function ($value) {
                        return $value !== '0' && strpos($value, '-') !== false;
                    });

                    $rolePermissionsTable = $this->fetchTable('RolePermissions');

                    foreach ($selectedPermissions as $permissionValue) {
                        list($controller, $action) = explode('-', $permissionValue);

                        // Create a new RolePermission entry
                        $rolePermission = $rolePermissionsTable->newEntity([
                            'role_id' => $role->id,
                            'controller' => $controller,
                            'action' => $action
                        ]);
                        $rolePermissionsTable->save($rolePermission);
                    }
                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_created');
                    return $this->redirect(['controller' => 'Roles', 'action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_created_error');
                    return $this->redirect(['controller' => 'Roles', 'action' => 'add']);
                }
            }
        }

        $controllers = ControllerHelper::getControllersAndActions();
        $this->set(compact('role', 'controllers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Role');

        $role = $this->Roles->get($id, ['contain' => ['RolePermissions']]);

        if ($this->request->is(['post', 'put'])) {
            $role = $this->Roles->patchEntity($role, $this->request->getData());

            // Get the selected permissions from the request data
            $selectedPermissions = array_filter($this->request->getData('permissions', []), function ($value) {
                return $value !== '0' && strpos($value, '-') !== false;
            });

            // Check if at least one permission is selected
            if (empty($selectedPermissions)) {
                $role->setError('permissions', ['Please select at least one Action of any Controller.']);
            }

            // If there are validation errors, show error messages
            if ($role->hasErrors()) {
                $errorMessages = [];
                foreach ($role->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error(__($errorMessage), ['escape' => false]);
            } else {
                if ($this->Roles->save($role)) {
                    // Delete existing role permissions and add new ones
                    $rolePermissionsTable = $this->fetchTable('RolePermissions');
                    $rolePermissionsTable->deleteAll(['role_id' => $role->id]);

                    $selectedPermissions = array_filter($this->request->getData('permissions', []), function ($value) {
                        return $value !== '0' && strpos($value, '-') !== false;
                    });

                    foreach ($selectedPermissions as $permissionValue) {
                        list($controller, $action) = explode('-', $permissionValue);

                        // Create new RolePermission entries
                        $rolePermission = $rolePermissionsTable->newEntity([
                            'role_id' => $role->id,
                            'controller' => $controller,
                            'action' => $action
                        ]);
                        $rolePermissionsTable->save($rolePermission);
                    }

                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');

                    // Update session for the currently logged-in user
                    $session = $this->request->getSession();
                    $currentUser = $this->Authentication->getIdentity();
                    if ($currentUser) {
                        ControllerHelper::setLoginUserPermissionsInSession($currentUser->getIdentifier(), $session);
                    }

                    return $this->redirect(['action' => 'index']);
                }
                ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
            }
        }

        $controllers = ControllerHelper::getControllersAndActions();

        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find('all', ['conditions' => ['Users.role_id' => $id]])->toArray();

        $this->set(compact('role', 'controllers', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'delete']);

        // Get IDs from the request data
        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        // If no IDs are provided, get the single ID from the request data
        $roleId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$roleId && empty($idsArray)) {
            // No ID or IDs provided, return an error response
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($roleId) {
            // Single deletion
            try {
                $role = $this->Roles->get($roleId);
                if (!$this->Roles->delete($role)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            // Multiple deletions
            foreach ($idsArray as $id) {
                try {
                    $role = $this->Roles->get($id);
                    if (!$this->Roles->delete($role)) {
                        $allDeleted = false;
                    }
                } catch (RecordNotFoundException $e) {
                    $allDeleted = false;
                }
            }
        }

        if ($allDeleted) {
            $response = ['status' => 'success', 'message' => 'The record(s) have been deleted.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Some record(s) could not be deleted. Please, try again.'];
        }

        // Return the JSON response if the request is AJAX
        if ($this->request->is('ajax')) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($response));
        }

        ControllerHelper::flashMessage($this, 'success', $response['message']);
        return $this->redirect(['action' => 'index']);
    }
}
