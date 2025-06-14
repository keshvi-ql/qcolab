<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 */
class ClientsController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Clients');

        $query = $this->Clients->find('all')
            ->where(['Clients.type' => 'client', 'Clients.deleted' => 0])
            ->contain('Countries')
            ->contain('LeadSources')
            ->order(['Clients.created' => 'DESC']);

        $clients = $this->paginate($query);

        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, contain: []);
        $this->set(compact('client'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('title', 'Add Client');

        $countries = $this->Clients->Countries->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $sources = $this->Clients->LeadSources->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        $client = $this->Clients->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['type'] = 'lead';

            $client = $this->Clients->patchEntity($client, $data);

            if ($client->hasErrors()) {
                $errorMessages = [];
                foreach ($client->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->Clients->save($client)) {
                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_created');

                    return $this->redirect(['controller' => 'Clients', 'action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_created_error');
                }
            }
        }

        $this->set(compact('client', 'countries', 'sources'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Client');

        $client = $this->Clients->get($id, contain: ['Countries']);

        $countries = $this->Clients->Countries->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $sources = $this->Clients->LeadSources->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $client = $this->Clients->patchEntity($client, $data);

            if ($client->hasErrors()) {
                $errorMessages = [];
                foreach ($client->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->Clients->save($client)) {
                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');

                    return $this->redirect(['action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
                }
            }
        }

        $this->set(compact('client', 'countries', 'sources'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
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
        $clientId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$clientId && empty($idsArray)) {
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

        if ($clientId) {
            // Single deletion
            try {
                $client = $this->Clients->get($clientId);
                if (!$this->Clients->delete($client)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            // Multiple deletions
            foreach ($idsArray as $id) {
                try {
                    $client = $this->Clients->get($id);
                    if (!$this->Clients->delete($client)) {
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
