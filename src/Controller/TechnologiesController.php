<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Technologies Controller
 *
 */
class TechnologiesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Technologies');

        $query = $this->Technologies->find()->where(['deleted' => 0]);
        $technologies = $this->paginate($query);

        $this->set(compact('technologies'));
    }

    /**
     * View method
     *
     * @param string|null $id Technology id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $technology = $this->Technologies->get($id, contain: []);
        $this->set(compact('technology'));
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
            $technology = $this->Technologies->get($data['id']);
        } else {
            $technology = $this->Technologies->newEmptyEntity();
        }

        $technology = $this->Technologies->patchEntity($technology, $data);

        if ($this->Technologies->save($technology)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Technology has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save technology.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Technology id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $technology = $this->Technologies->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $technology = $this->Technologies->patchEntity($technology, $this->request->getData());
            if ($this->Technologies->save($technology)) {
                $this->Flash->success(__('The technology has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The technology could not be saved. Please, try again.'));
        }
        $this->set(compact('technology'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Technology id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $technologyId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$technologyId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($technologyId) {
            try {
                $technology = $this->Technologies->get($technologyId);
                if (!$this->Technologies->delete($technology)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $technology = $this->Technologies->get($id);
                    if (!$this->Technologies->delete($technology)) {
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
