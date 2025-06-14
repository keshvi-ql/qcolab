<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Projects Controller
 *
 */
class ProjectsController extends BaseController
{
    private $usersTable;
    private $projectMembersTable;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->usersTable = $this->getTableLocator()->get('Users');
        $this->projectMembersTable = $this->getTableLocator()->get('ProjectMembers');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Projects');

        $query = $this->Projects->find()
            ->where(['Projects.deleted' => 0])
            ->contain(['Clients', 'ProjectStatuses'])
            ->order(['Projects.created' => 'DESC']);

        $projects = $query->all();

        $clients = $this->Projects->Clients->find()
            ->where(['type' => 'client', 'deleted' => 0])
            ->order(['first_name' => 'ASC'])
            ->all()
            ->combine(
                'id',
                function ($client) {
                    return $client->first_name . ' ' . $client->last_name;
                }
            )
            ->toArray();

        $statuses = $this->Projects->ProjectStatuses->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        $this->set(compact('projects', 'clients', 'statuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $project = $this->Projects->get($id, contain: ['Clients', 'ProjectStatuses']);

        $projectMembers = $this->projectMembersTable->find()
            ->where(['project_id' => $project->id, 'ProjectMembers.deleted' => '0'])
            ->contain(['Users', 'Projects'])
            ->toArray();

        $users = $this->usersTable->find()
            ->where(['deleted' => 0])
            ->order(['first_name' => 'ASC'])
            ->all()
            ->combine(
                'id',
                function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
                }
            )
            ->toArray();

        $activeTab = $this->request->getQuery('tab') ?: 'tab1';

        $this->set('title', $project->title);
        $this->set(compact('project', 'projectMembers', 'users', 'activeTab'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'ajax']);

        $data = $this->request->getData();

        $loginUserId = $this->Authentication->getIdentity()->get('id');

        $data['created_by'] = $loginUserId;

        if (!empty($data['client_id'])) {
            $client = $this->Projects->Clients->find()
                ->select(['lead_no'])
                ->where(['id' => $data['client_id']])
                ->first();

            if ($client) {
                $data['project_no'] = $client->lead_no;
            } else {
                return AjaxResponseHelper::createResponse(false, 'Client not found.');
            }
        }

        if (!empty($data['id'])) {
            $project = $this->Projects->get($data['id']);
        } else {
            $project = $this->Projects->newEmptyEntity();
        }

        try {
            if (!empty($data['start_date'])) {
                $data['start_date'] = (new \DateTime($data['start_date']))->format('Y-m-d');
            }

            if (empty($data['deadline'])) {
                $data['deadline'] = null;
            } else {
                $data['deadline'] = (new \DateTime($data['deadline']))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return AjaxResponseHelper::createResponse(false, 'Invalid date format.');
        }

        $project = $this->Projects->patchEntity($project, $data);

        if ($this->Projects->save($project)) {
            return AjaxResponseHelper::createResponse(true, 'Project has been saved.');
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to Save Project.');
        }
    }

    public function addMember()
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'ajax']);

        $data = $this->request->getData();

        $loginUserId = $this->Authentication->getIdentity()->get('id');

        $projectId = $data['project_id'];
        $userIds = $data['user_id'];

        foreach ($userIds as $userId) {
            // Check if the user is already assigned to the project
            $exists = $this->projectMembersTable->exists([
                'project_id' => $projectId,
                'user_id' => $userId,
            ]);

            if (!$exists) {
                // Save only if not already assigned
                $memberData = [
                    'project_id' => $projectId,
                    'user_id' => $userId,
                ];

                $member = $this->projectMembersTable->newEmptyEntity();
                $member = $this->projectMembersTable->patchEntity($member, $memberData);

                if (!$this->projectMembersTable->save($member)) {
                    return AjaxResponseHelper::createResponse(false, 'Unable to save member.');
                }
            }
        }

        return AjaxResponseHelper::createResponse(true, 'Members have been saved.');
    }

    /**
     * Edit method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $project = $this->Projects->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->getData());
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The project could not be saved. Please, try again.'));
        }
        $this->set(compact('project'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Project id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $projectId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$projectId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($projectId) {
            try {
                $project = $this->Projects->get($projectId);
                if (!$this->Projects->delete($project)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $project = $this->Projects->get($id);
                    if (!$this->Projects->delete($project)) {
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

        if ($this->request->is('ajax')) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($response));
        }

        ControllerHelper::flashMessage($this, 'success', $response['message']);
        return $this->redirect(['action' => 'index']);
    }

    public function deleteMember($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $memberId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$memberId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($memberId) {
            try {
                $member = $this->projectMembersTable->get($memberId);
                if (!$this->projectMembersTable->delete($member)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        }

        if ($allDeleted) {
            $response = ['status' => 'success', 'message' => 'The record(s) have been deleted.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Some record(s) could not be deleted. Please, try again.'];
        }

        if ($this->request->is('ajax')) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($response));
        }

        ControllerHelper::flashMessage($this, 'success', $response['message']);
        return $this->redirect(['action' => 'index']);
    }
}
