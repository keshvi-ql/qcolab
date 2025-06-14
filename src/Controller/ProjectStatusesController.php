<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * ProjectStatuses Controller
 *
 */
class ProjectStatusesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Project Statuses');

        $query = $this->ProjectStatuses->find()->where(['deleted' => 0]);
        $projectStatuses = $this->paginate($query);

        $this->set(compact('projectStatuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Project Status id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $projectStatus = $this->ProjectStatuses->get($id, contain: []);
        $this->set(compact('projectStatus'));
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

        if (!empty($data['id'])) {
            // Edit mode
            $projectStatus = $this->ProjectStatuses->get($data['id']);
        } else {
            // Add mode
            $projectStatus = $this->ProjectStatuses->newEmptyEntity();
        }

        $projectStatus = $this->ProjectStatuses->patchEntity($projectStatus, $data);

        if ($this->ProjectStatuses->save($projectStatus)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Project status has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save project status.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Project Status id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $projectStatus = $this->ProjectStatuses->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projectStatus = $this->ProjectStatuses->patchEntity($projectStatus, $this->request->getData());
            if ($this->ProjectStatuses->save($projectStatus)) {
                $this->Flash->success(__('The project status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The project status could not be saved. Please, try again.'));
        }
        $this->set(compact('projectStatus'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Project Status id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $statusId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$statusId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($statusId) {
            try {
                $projectStatus = $this->ProjectStatuses->get($statusId);
                if (!$this->ProjectStatuses->delete($projectStatus)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $projectStatus = $this->ProjectStatuses->get($id);
                    if (!$this->ProjectStatuses->delete($projectStatus)) {
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
}
