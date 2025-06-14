<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;

/**
 * LeaveTypes Controller
 *
 * @property \App\Model\Table\LeaveTypesTable $LeaveTypes
 */
class LeaveTypesController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Leave Types');

        $query = $this->LeaveTypes->find();
        $leaveTypes = $this->paginate($query);

        $this->set(compact('leaveTypes'));
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
            $leaveType = $this->LeaveTypes->get($data['id']);
        } else {
            // Add mode
            $leaveType = $this->LeaveTypes->newEmptyEntity();
        }

        $leaveType = $this->LeaveTypes->patchEntity($leaveType, $data);

        if ($this->LeaveTypes->save($leaveType)) {
            return AjaxResponseHelper::createResponse(
                true,
                'Leave type has been saved.'
            );
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to save leave type.');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Leave Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $leaveType = $this->LeaveTypes->get($id);
        if ($this->LeaveTypes->delete($leaveType)) {
            $this->Flash->success(__('The leave type has been deleted.'));
        } else {
            $this->Flash->error(__('The leave type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
