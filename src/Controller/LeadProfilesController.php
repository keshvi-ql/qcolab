<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * LeadProfiles Controller
 *
 * @property \App\Model\Table\LeadProfilesTable $LeadProfiles
 */
class LeadProfilesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Lead Profiles');

        $query = $this->LeadProfiles->find();
        $leadProfiles = $this->paginate($query);

        $this->set(compact('leadProfiles'));
    }

    /**
     * View method
     *
     * @param string|null $id Lead Profile id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $leadProfile = $this->LeadProfiles->get($id, contain: []);
        $this->set(compact('leadProfile'));
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
            $leadProfile = $this->LeadProfiles->get($data['id']);
        } else {
            // Add mode
            $leadProfile = $this->LeadProfiles->newEmptyEntity();
        }

        $leadProfile = $this->LeadProfiles->patchEntity($leadProfile, $data);

        if ($this->LeadProfiles->save($leadProfile)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Lead profile has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save lead profile.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Lead Profile id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $leadProfile = $this->LeadProfiles->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $leadProfile = $this->LeadProfiles->patchEntity($leadProfile, $this->request->getData());
            if ($this->LeadProfiles->save($leadProfile)) {
                $this->Flash->success(__('The lead profile has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lead profile could not be saved. Please, try again.'));
        }
        $this->set(compact('leadProfile'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lead Profile id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $profileId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$profileId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($profileId) {
            try {
                $leadProfile = $this->LeadProfiles->get($profileId);
                if (!$this->LeadProfiles->delete($leadProfile)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $leadProfile = $this->LeadProfiles->get($id);
                    if (!$this->LeadProfiles->delete($leadProfile)) {
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
