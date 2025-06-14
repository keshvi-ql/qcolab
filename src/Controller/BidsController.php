<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Bids Controller
 *
 */
class BidsController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Bids');

        $query = $this->Bids->find('all')
            ->where(['deleted' => 0])
            ->contain('LeadSources')
            ->contain('LeadProfiles');

        $bids = $this->paginate($query);

        $sources = $this->Bids->LeadSources->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        $profiles = $this->Bids->LeadProfiles->find('list', [
            'key' => 'id',
            'value' => 'title'
        ])->toArray();

        $this->set(compact('bids', 'sources', 'profiles'));
    }

    /**
     * View method
     *
     * @param string|null $id Bid id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bid = $this->Bids->get($id, contain: []);
        $this->set(compact('bid'));
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

        if (!empty($data['id'])) {
            // Edit mode
            $bid = $this->Bids->get($data['id']);
        } else {
            // Add mode
            $bid = $this->Bids->newEmptyEntity();
        }

        $bid = $this->Bids->patchEntity($bid, $data);

        if ($this->Bids->save($bid)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Bid has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to Save Bid.');
        }
    }

    public function convertToLead($id)
    {
        $this->autoRender = false;

        try {
            $bid = $this->Bids->get($id);

            $this->request->getSession()->write('BidData', $bid);

            return $this->redirect(['controller' => 'Leads', 'action' => 'add', base64_encode($id)]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            ControllerHelper::flashMessage($this, 'error', 'This record not found');
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Bid id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bid = $this->Bids->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bid = $this->Bids->patchEntity($bid, $this->request->getData());
            if ($this->Bids->save($bid)) {
                $this->Flash->success(__('The bid has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bid could not be saved. Please, try again.'));
        }
        $this->set(compact('bid'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bid id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $bidId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$bidId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($bidId) {
            try {
                $bid = $this->Bids->get($bidId);
                if (!$this->Bids->delete($bid)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $bid = $this->Bids->get($id);
                    if (!$this->Bids->delete($bid)) {
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
