<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * LeadSources Controller
 *
 */
class LeadSourcesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Lead Sources');

        $query = $this->LeadSources->find();
        $leadSources = $this->paginate($query);

        $this->set(compact('leadSources'));
    }

    /**
     * View method
     *
     * @param string|null $id Lead Source id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $leadSource = $this->LeadSources->get($id, contain: []);
        $this->set(compact('leadSource'));
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
            $leadSource = $this->LeadSources->get($data['id']);
        } else {
            // Add mode
            $leadSource = $this->LeadSources->newEmptyEntity();
        }

        $leadSource = $this->LeadSources->patchEntity($leadSource, $data);

        if ($this->LeadSources->save($leadSource)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Lead source has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save lead source.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Lead Source id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $leadSource = $this->LeadSources->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $leadSource = $this->LeadSources->patchEntity($leadSource, $this->request->getData());
            if ($this->LeadSources->save($leadSource)) {
                $this->Flash->success(__('The lead source has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lead source could not be saved. Please, try again.'));
        }
        $this->set(compact('leadSource'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lead Source id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $sourceId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$sourceId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($sourceId) {
            try {
                $leadSource = $this->LeadSources->get($sourceId);
                if (!$this->LeadSources->delete($leadSource)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $leadSource = $this->LeadSources->get($id);
                    if (!$this->LeadSources->delete($leadSource)) {
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
