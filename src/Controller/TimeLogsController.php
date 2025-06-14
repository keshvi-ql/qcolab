<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\BaseController;
use League\Container\Exception\NotFoundException;

/**
 * TimeLogs Controller
 *
 * @property \App\Model\Table\TimeLogsTable $TimeLogs
 */
class TimeLogsController extends BaseController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $userId = $this->Authentication->getIdentity()->get('id');
        $timeLog  = $this->TimeLogs->find()
            ->where(['user_id' => $userId, 'status !=' => 'completed'])
            ->order(['created' => 'DESC'])
            ->first();

        $this->set(compact('timeLogs'));
        $this->set('currentStatus', $timeLog ? $timeLog->status : 'none');
    }

    public function clockIn()
    {
        $this->autoRender = false;
        $userId = $this->Authentication->getIdentity()->get('id');
        $currentDate = date('Y-m-d');

        // Check if user has already clocked in today
        $existingLog = $this->TimeLogs->find()
            ->where(['user_id' => $userId, 'date' => $currentDate, 'status' => 'completed'])
            ->first();

        if ($existingLog) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Already clocked in for today.']));
        }

        $clockInTime = date('Y-m-d H:i:s');

        // Create new time log entry
        $timeLog = $this->TimeLogs->newEntity([
            'user_id' => $userId,
            'date' => $currentDate,
            'clock_in_time' => $clockInTime,
            'status' => 'active'
        ]);

        $formattedClockInTime = date('h:i:s A', strtotime($clockInTime));

        if ($this->TimeLogs->save($timeLog)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'clock_in_time' => $formattedClockInTime]));
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Clock in failed.']));
        }
    }

    public function pause()
    {
        $this->autoRender = false;
        $userId = $this->Authentication->getIdentity()->get('id');

        // Find active time log
        $activeLog = $this->TimeLogs->find()
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();

        if (!$activeLog) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'No active session found.']));
        }

        $pausesTable = TableRegistry::getTableLocator()->get('Pauses');

        // Create a new pause entry
        $pause = $pausesTable->newEntity([
            'time_log_id' => $activeLog->id,
            'pause_time' => date('Y-m-d H:i:s')
        ]);

        if ($pausesTable->save($pause)) {
            // Update the time log status to 'paused'
            $activeLog->status = 'paused';
            $this->TimeLogs->save($activeLog);

            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'message' => 'Paused successfully.', 'pause_time' => $pause->pause_time]));
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Pause failed.']));
        }
    }

    public function resume()
    {
        $this->autoRender = false;
        $userId = $this->Authentication->getIdentity()->get('id');

        // Find paused time log
        $pausedLog = $this->TimeLogs->find()
            ->where(['user_id' => $userId, 'status' => 'paused'])
            ->first();

        if (!$pausedLog) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'No paused session found.']));
        }

        $pausesTable = TableRegistry::getTableLocator()->get('Pauses');

        // Find the last pause record for this time log
        $pauseRecord = $pausesTable->find()
            ->where(['time_log_id' => $pausedLog->id, 'resume_time IS' => null])
            ->order(['id' => 'DESC'])
            ->first();

        if ($pauseRecord) {
            $time = new \Cake\I18n\Time();
            // Update the resume time and calculate pause duration
            $pauseRecord->resume_time = date('Y-m-d H:i:s');

            $pauseTimeString = $pauseRecord->pause_time->format('Y-m-d H:i:s');
            $resumeTimeString = $pauseRecord->resume_time;

            $pauseRecord->pause_duration = gmdate('H:i:s', strtotime($resumeTimeString) - strtotime($pauseTimeString));

            if ($pausesTable->save($pauseRecord)) {
                // Update the time log status to 'active'
                $pausedLog->status = 'active';
                $this->TimeLogs->save($pausedLog);

                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['status' => 'success', 'message' => 'Resumed successfully.', 'resume_time' => date('Y-m-d H:i:s')]));
            } else {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['error' => 'Resume failed.']));
            }
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Resume failed.']));
        }
    }

    public function clockOut()
    {
        $this->autoRender = false;
        $userId = $this->Authentication->getIdentity()->get('id');
        $note = $this->request->getData('note');

        // Find active time log
        $activeLog = $this->TimeLogs->find()
            ->where(['user_id' => $userId, 'status !=' => 'completed'])
            ->first();

        if (!$activeLog) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'No active session found.']));
        }

        $pausesTable = TableRegistry::getTableLocator()->get('Pauses');

        // Update clock out time and calculate total work duration
        $activeLog->clock_out_time = date('Y-m-d H:i:s');

        $clockInTimeString = $activeLog->clock_in_time->format('Y-m-d H:i:s');
        $clockOutTimeString = $activeLog->clock_out_time;

        // Calculate total work duration excluding pauses
        $totalWorked = strtotime($clockOutTimeString) - strtotime($clockInTimeString);

        // Sum up all pause durations
        $pauseDurations = $pausesTable->find()
            ->where(['time_log_id' => $activeLog->id])
            ->select(['total_pause' => 'SUM(TIME_TO_SEC(pause_duration))'])
            ->first()
            ->total_pause;

        $totalWorkDuration = $totalWorked - ($pauseDurations ?: 0);
        $activeLog->total_work_duration = gmdate('H:i:s', $totalWorkDuration);
        $activeLog->status = 'completed';
        $activeLog->note = $note;

        if ($this->TimeLogs->save($activeLog)) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['status' => 'success', 'message' => 'Clocked out successfully.', 'clock_out_time' => $activeLog->clock_out_time]));
        } else {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Clock out failed.']));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Time Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $timeLog = $this->TimeLogs->get($id, contain: ['Users', 'Pauses']);
        $this->set(compact('timeLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $timeLog = $this->TimeLogs->newEmptyEntity();
        if ($this->request->is('post')) {
            $timeLog = $this->TimeLogs->patchEntity($timeLog, $this->request->getData());
            if ($this->TimeLogs->save($timeLog)) {
                $this->Flash->success(__('The time log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The time log could not be saved. Please, try again.'));
        }
        $users = $this->TimeLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('timeLog', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Time Log id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $timeLog = $this->TimeLogs->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $timeLog = $this->TimeLogs->patchEntity($timeLog, $this->request->getData());
            if ($this->TimeLogs->save($timeLog)) {
                $this->Flash->success(__('The time log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The time log could not be saved. Please, try again.'));
        }
        $users = $this->TimeLogs->Users->find('list', limit: 200)->all();
        $this->set(compact('timeLog', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Time Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $timeLog = $this->TimeLogs->get($id);
        if ($this->TimeLogs->delete($timeLog)) {
            $this->Flash->success(__('The time log has been deleted.'));
        } else {
            $this->Flash->error(__('The time log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
