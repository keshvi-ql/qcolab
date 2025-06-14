<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Leads Controller
 *
 */
class LeadsController extends BaseController
{
    private $clientsTable;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->clientsTable = $this->getTableLocator()->get('Clients');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Leads');

        $query = $this->clientsTable->find('all')
            ->where(['Clients.type' => 'lead', 'Clients.deleted' => 0])
            ->contain(['Countries', 'LeadSources', 'Bids'])
            ->order(['Clients.created' => 'DESC']);

        $leads = $this->paginate($query);

        $this->set(compact('leads'));
    }

    /**
     * View method
     *
     * @param string|null $id Lead id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lead = $this->clientsTable->get($id, contain: []);
        $this->set(compact('lead'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($bid = null)
    {
        if ($bid) {
            $decodedBidId = base64_decode($bid);

            try {
                $bidData = $this->clientsTable->Bids->get($decodedBidId);
            } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                ControllerHelper::flashMessage($this, 'error', 'Bid not found.');
                return $this->redirect(['controller' => 'Bids', 'action' => 'index']);
            }
        } else {
            $this->request->getSession()->delete('BidData');
        }

        $this->set('title', 'Add Lead');

        $countries = $this->clientsTable->Countries->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $sources = $this->clientsTable->LeadSources->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        $bidData = $this->request->getSession()->read('BidData');

        $lead = $this->clientsTable->newEmptyEntity();

        if ($bidData && !empty($bidData->source)) {
            $lead->source = $bidData->source;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if ($bidData && !empty($bidData->id)) {
                $data['bid_id'] = $bidData->id;
            }

            $data['type'] = 'lead';

            $lastLead = $this->clientsTable->find()
                ->select(['lead_no'])
                ->where(['type' => 'lead'])
                ->order(['lead_no' => 'DESC'])
                ->first();

            $lastLeadNo = $lastLead ? (int) $lastLead->lead_no : 0;
            $newLeadNo = str_pad((string)($lastLeadNo + 1), 6, '0', STR_PAD_LEFT);
            $data['lead_no'] = $newLeadNo;

            $lead = $this->clientsTable->patchEntity($lead, $data);

            if ($lead->hasErrors()) {
                $errorMessages = [];
                foreach ($lead->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->clientsTable->save($lead)) {
                    $this->request->getSession()->delete('BidData');

                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_created');

                    return $this->redirect(['controller' => 'Leads', 'action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_created_error');
                }
            }
        }

        $this->set(compact('lead', 'countries', 'sources'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lead id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('title', 'Edit Lead');

        $lead = $this->clientsTable->get($id, contain: ['Countries']);

        $countries = $this->clientsTable->Countries->find('list', [
            'key' => 'id',
            'value' => 'name'
        ])->toArray();

        $sources = $this->clientsTable->LeadSources->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $lead = $this->clientsTable->patchEntity($lead, $data);

            if ($lead->hasErrors()) {
                $errorMessages = [];
                foreach ($lead->getErrors() as $field => $validationErrors) {
                    foreach ($validationErrors as $error) {
                        $errorMessages[] = $error;
                    }
                }

                $errorMessage = '<ul><li>' . implode('</li><li>', $errorMessages) . '</li></ul>';

                $this->Flash->error($errorMessage, ['escape' => false]);
            } else {
                if ($this->clientsTable->save($lead)) {
                    ControllerHelper::flashMessage($this, 'success', 'alerts.record_updated');

                    return $this->redirect(['action' => 'index']);
                } else {
                    ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
                }
            }
        }

        $this->set(compact('lead', 'countries', 'sources'));
    }

    public function convertToClient($id)
    {
        $this->autoRender = false;

        try {
            $lead = $this->clientsTable->get($id);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            ControllerHelper::flashMessage($this, 'error', 'This record not found');
            return $this->redirect(['action' => 'index']);
        }

        $lead->type = 'client';
        $lead->client_converted_at = date('Y-m-d H:i:s');

        if ($this->clientsTable->save($lead)) {
            ControllerHelper::flashMessage($this, 'success', 'Lead Convert to Client.');
        } else {
            ControllerHelper::flashMessage($this, 'error', 'alerts.record_updated_error');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lead id.
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
        $leadId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$leadId && empty($idsArray)) {
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

        if ($leadId) {
            // Single deletion
            try {
                $lead = $this->clientsTable->get($leadId);
                if (!$this->clientsTable->delete($lead)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            // Multiple deletions
            foreach ($idsArray as $id) {
                try {
                    $lead = $this->clientsTable->get($id);
                    if (!$this->clientsTable->delete($lead)) {
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
