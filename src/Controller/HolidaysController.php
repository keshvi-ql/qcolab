<?php

declare(strict_types=1);

namespace App\Controller;

use FrozenDate;
use App\Utility\ControllerHelper;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Holidays Controller
 *
 * @property \App\Model\Table\HolidaysTable $Holidays
 */
class HolidaysController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Holidays');

        $query = $this->Holidays->find()->where(['deleted' => 0]);
        $holidays = $this->paginate($query);

        $this->set(compact('holidays'));
    }

    /**
     * View method
     *
     * @param string|null $id Holiday id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $holiday = $this->Holidays->get($id, contain: []);
        $this->set(compact('holiday'));
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
            $holiday = $this->Holidays->get($data['id']);
        } else {
            $holiday = $this->Holidays->newEmptyEntity();
        }

        try {
            if ($data['duration'] === 'single') {
                $formattedDate = (new \DateTime($data['date']))->format('Y-m-d');
                $data['start_date'] = $formattedDate;
                $data['end_date'] = $formattedDate;
            } else {
                $data['start_date'] = (new \DateTime($data['start_date']))->format('Y-m-d');
                $data['end_date'] = (new \DateTime($data['end_date']))->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return AjaxResponseHelper::createResponse(false, 'Invalid date format.');
        }

        $holiday = $this->Holidays->patchEntity($holiday, [
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);

        if ($this->Holidays->save($holiday)) {
            return AjaxResponseHelper::createResponse(true, 'Holiday has been saved.');
        } else {
            return AjaxResponseHelper::createResponse(false, 'Unable to Save Holiday.');
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Holiday id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $holiday = $this->Holidays->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $holiday = $this->Holidays->patchEntity($holiday, $this->request->getData());
            if ($this->Holidays->save($holiday)) {
                $this->Flash->success(__('The holiday has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The holiday could not be saved. Please, try again.'));
        }
        $this->set(compact('holiday'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Holiday id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $ids = $this->request->getData('ids');
        $idsArray = $ids ? explode(',', $ids) : [];

        $holidayId = $this->request->getData('recordId') ?? ($idsArray ? null : $this->request->getParam('pass.0'));

        if (!$holidayId && empty($idsArray)) {
            $response = ['status' => 'error', 'message' => 'No record(s) selected for deletion.'];
            if ($this->request->is('ajax')) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
            }
            ControllerHelper::flashMessage($this, 'error', $response['message']);
            return $this->redirect(['action' => 'index']);
        }

        $allDeleted = true;

        if ($holidayId) {
            try {
                $holiday = $this->Holidays->get($holidayId);
                if (!$this->Holidays->delete($holiday)) {
                    $allDeleted = false;
                }
            } catch (RecordNotFoundException $e) {
                $allDeleted = false;
            }
        } else {
            foreach ($idsArray as $id) {
                try {
                    $holiday = $this->Holidays->get($id);
                    if (!$this->Holidays->delete($holiday)) {
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
