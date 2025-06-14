<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\BaseController;
use Cake\Http\Exception\ForbiddenException;
use Cake\Database\Expression\QueryExpression;

/**
 * TimeCards Controller
 *
 */
class TimeCardsController extends BaseController
{
    private $usersTable;
    private $timeLogsTable;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->usersTable = $this->getTableLocator()->get('Users');
        $this->timeLogsTable = $this->getTableLocator()->get('TimeLogs');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Time Cards');

        $loggedInUser = $this->request->getAttribute('identity');
        $session = $this->request->getSession();

        $viewType = $session->read('view_type') ?? ($loggedInUser->is_admin ? 'daily' : 'monthly');

        $selectedMember = $session->read('member_id');

        $query = $this->timeLogsTable->find()
            ->contain(['Users', 'Pauses']);

        if (!$loggedInUser->is_admin) {
            $query->where(['user_id' => $loggedInUser->id]);
        } elseif ($selectedMember) {
            $query->where(['user_id' => $selectedMember]);
        }

        if (!$selectedMember) {
            $query->where([]);
        }

        if ($viewType === 'daily') {
            $selectedDate = $session->read('selected_date') ? date('Y-m-d', strtotime($session->read('selected_date'))) : date('Y-m-d');
            $query->where(['date' => $selectedDate]);
        } elseif ($viewType === 'weekly') {
            $startOfWeek = $session->read('start_week') ?? date('Y-m-d', strtotime('monday this week'));
            $endOfWeek = $session->read('end_week') ?? date('Y-m-d', strtotime('sunday this week'));
            $query->where(function ($exp) use ($startOfWeek, $endOfWeek) {
                return $exp->between('date', $startOfWeek, $endOfWeek);
            });
        } elseif ($viewType === 'monthly') {
            $selectedMonth = $session->read('selected_month') ?? date('Y-m');
            $query->where(function ($exp) use ($selectedMonth) {
                return $exp->like('date', $selectedMonth . '%');
            });
        } elseif ($viewType === 'custom') {
            $startDate = $session->read('start_date') ? date('Y-m-d', strtotime(str_replace('-', '/', $session->read('start_date')))) : date('Y-m-d');
            $endDate = $session->read('end_date') ? date('Y-m-d', strtotime(str_replace('-', '/', $session->read('end_date')))) : date('Y-m-d');
            $query->where(function ($exp) use ($startDate, $endDate) {
                return $exp->between('date', $startDate, $endDate);
            });
        } elseif ($viewType === 'summary') {
            $startDate = $session->read('start_date') ? date('Y-m-d', strtotime(str_replace('-', '/', $session->read('start_date')))) : date('Y-m-d');
            $endDate = $session->read('end_date') ? date('Y-m-d', strtotime(str_replace('-', '/', $session->read('end_date')))) : date('Y-m-d');
            $query->where(function ($exp) use ($startDate, $endDate) {
                return $exp->between('date', $startDate, $endDate);
            });
        }

        $timeCards = $this->paginate($query);

        $totalDuration = '00:00:00';

        foreach ($timeCards as $timeCard) {
            $totalDuration = $this->addDurations($totalDuration, $timeCard->total_work_duration);
        }

        $users = $this->usersTable->find()->where(['deleted' => 0])->order(['first_name' => 'ASC'])->toArray();

        $this->set(compact('timeCards', 'viewType', 'users', 'totalDuration'));
    }

    private function addDurations($time1, $time2)
    {
        list($hours1, $minutes1, $seconds1) = explode(':', $time1);
        list($hours2, $minutes2, $seconds2) = explode(':', $time2);

        $totalSeconds1 = $hours1 * 3600 + $minutes1 * 60 + $seconds1;
        $totalSeconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

        $totalSeconds = $totalSeconds1 + $totalSeconds2;

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function setSession()
    {
        $this->request->allowMethod(['post']);

        // Check if the request is AJAX
        if ($this->request->is('ajax')) {
            $data = $this->request->getData();
            $session = $this->request->getSession();

            if (isset($data['view_type'])) {
                $session->write('view_type', $data['view_type']);
                if ($data['view_type'] === 'monthly') {
                    $session->write('selected_month', date('Y-m'));
                } elseif ($data['view_type'] === 'weekly') {
                    $session->write('start_week', date('Y-m-d', strtotime('monday this week')));
                    $session->write('end_week', date('Y-m-d', strtotime('sunday this week')));
                }
            }

            if (isset($data['selected_date'])) {
                $session->write('selected_date', $data['selected_date']);
            }
            if (isset($data['selected_month'])) {
                $session->write('selected_month', $data['selected_month']);
            }
            if (isset($data['start_week'])) {
                $session->write('start_week', $data['start_week']);
            }
            if (isset($data['end_week'])) {
                $session->write('end_week', $data['end_week']);
            }
            if (isset($data['start_date'])) {
                $session->write('start_date', $data['start_date']);
            }
            if (isset($data['end_date'])) {
                $session->write('end_date', $data['end_date']);
            }

            if (isset($data['member_id'])) {
                $session->write('member_id', $data['member_id']);
            } else {
                $session->delete('member_id');
            }

            return $this->response->withStatus(204);
        }

        throw new ForbiddenException('Invalid request'); // Handle invalid requests
    }

    /**
     * View method
     *
     * @param string|null $id Time Card id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $timeCard = $this->TimeCards->get($id, contain: []);
        $this->set(compact('timeCard'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $timeCard = $this->TimeCards->newEmptyEntity();
        if ($this->request->is('post')) {
            $timeCard = $this->TimeCards->patchEntity($timeCard, $this->request->getData());
            if ($this->TimeCards->save($timeCard)) {
                $this->Flash->success(__('The time card has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The time card could not be saved. Please, try again.'));
        }
        $this->set(compact('timeCard'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Time Card id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $timeCard = $this->TimeCards->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $timeCard = $this->TimeCards->patchEntity($timeCard, $this->request->getData());
            if ($this->TimeCards->save($timeCard)) {
                $this->Flash->success(__('The time card has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The time card could not be saved. Please, try again.'));
        }
        $this->set(compact('timeCard'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Time Card id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $timeCard = $this->TimeCards->get($id);
        if ($this->TimeCards->delete($timeCard)) {
            $this->Flash->success(__('The time card has been deleted.'));
        } else {
            $this->Flash->error(__('The time card could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
