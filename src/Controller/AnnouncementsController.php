<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Announcements Controller
 *
 */
class AnnouncementsController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Announcements');

        $query = $this->Announcements->find()
            ->where(['Announcements.deleted' => 0])
            ->order(['Announcements.created' => 'DESC']);
        $announcements = $this->paginate($query);

        $this->set(compact('announcements'));
    }

    /**
     * View method
     *
     * @param string|null $id Announcement id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $announcement = $this->Announcements->get($id, contain: []);
        $this->set(compact('announcement'));
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
            $announcement = $this->Announcements->get($data['id']);
        } else {
            $announcement = $this->Announcements->newEmptyEntity();
        }

        try {
            $data['start_date'] = (new \DateTime($data['start_date']))->format('Y-m-d');
            $data['end_date'] = (new \DateTime($data['end_date']))->format('Y-m-d');
        } catch (\Exception $e) {
            return AjaxResponseHelper::createResponse(false, 'Invalid date format.');
        }

        $announcement = $this->Announcements->patchEntity($announcement, $data);

        if ($this->Announcements->save($announcement)) {
            return AjaxResponseHelper::createResponse(true, 'Announcement has been saved.');
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to Save Announcement.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Announcement id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $announcement = $this->Announcements->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $announcement = $this->Announcements->patchEntity($announcement, $this->request->getData());
            if ($this->Announcements->save($announcement)) {
                $this->Flash->success(__('The announcement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The announcement could not be saved. Please, try again.'));
        }
        $this->set(compact('announcement'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Announcement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        // Get IDs from the request data
        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        // If no IDs are provided, get the single ID from the request data
        $announcementId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$announcementId && empty($idsArray)) {
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

        if ($announcementId) {
            // Single deletion
            try {
                $announcement = $this->Announcements->get($announcementId);
                if (!$this->Announcements->delete($announcement)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            // Multiple deletions
            foreach ($idsArray as $id) {
                try {
                    $announcement = $this->Announcements->get($id);
                    if (!$this->Announcements->delete($announcement)) {
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
