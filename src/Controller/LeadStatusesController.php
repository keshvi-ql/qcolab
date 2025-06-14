<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * LeadStatuses Controller
 *
 */
class LeadStatusesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Lead Statuses');

        $query = $this->LeadStatuses->find();
        $leadStatuses = $this->paginate($query);

        $this->set(compact('leadStatuses'));
    }

    /**
     * View method
     *
     * @param string|null $id Lead Status id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $leadStatus = $this->LeadStatuses->get($id, contain: []);
        $this->set(compact('leadStatus'));
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
            $leadStatus = $this->LeadStatuses->get($data['id']);
        } else {
            // Add mode
            $leadStatus = $this->LeadStatuses->newEmptyEntity();
        }

        $leadStatus = $this->LeadStatuses->patchEntity($leadStatus, $data);

        if ($this->LeadStatuses->save($leadStatus)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Lead status has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save lead status.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Lead Status id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $leadStatus = $this->LeadStatuses->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $leadStatus = $this->LeadStatuses->patchEntity($leadStatus, $this->request->getData());
            if ($this->LeadStatuses->save($leadStatus)) {
                $this->Flash->success(__('The lead status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lead status could not be saved. Please, try again.'));
        }
        $this->set(compact('leadStatus'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lead Status id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
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
                $leadStatus = $this->LeadStatuses->get($statusId);
                if (!$this->LeadStatuses->delete($leadStatus)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $leadStatus = $this->LeadStatuses->get($id);
                    if (!$this->LeadStatuses->delete($leadStatus)) {
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
