<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use App\Controller\AppController;
use App\Controller\BaseController;
use App\Utility\AjaxResponseHelper;

/**
 * Dashboard Controller
 *
 */
class DashboardController extends BaseController
{
    private $usersTable;
    private $holidaysTable;
    private $announcementsTable;
    private $leaveApplicationsTable;
    private $partialLeavesTable;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->usersTable = $this->getTableLocator()->get('Users');
        $this->holidaysTable = $this->getTableLocator()->get('Holidays');
        $this->announcementsTable = $this->getTableLocator()->get('Announcements');
        $this->leaveApplicationsTable = $this->getTableLocator()->get('LeaveApplications');
        $this->partialLeavesTable = $this->getTableLocator()->get('PartialLeaves');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->set('title', 'Dashboard');

        $currentYear = date('Y');
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        // Query to fetch all upcoming holidays (not deleted and end_date >= today)
        $query = $this->holidaysTable->find()
            ->where(['deleted' => 0])
            ->order(['start_date' => 'DESC']); // Show latest first

        $holidays = $query->all(); // No pagination, show all

        $loggedInUserId = $this->Authentication->getIdentity()->get('id');
        $stickyNote = $this->usersTable->get($loggedInUserId);

        $currentDateIST = FrozenTime::now('Asia/Kolkata')->format('Y-m-d');

        $announcements = $this->announcementsTable->find()
            ->where([
                'start_date <=' => $currentDateIST,
                'end_date >=' => $currentDateIST,
                'deleted' => 0,
            ])
            ->toArray();

        $todayDay = date('d');
        $todayMonth = date('m');

        $birthdays = $this->usersTable->find()
            ->where([
                'deleted' => 0,
                'MONTH(dob)' => $todayMonth,
                'DAY(dob) >=' => $todayDay,
            ])
            ->order(['dob' => 'ASC'])
            ->toArray();

        $membersClockedTime = $this->usersTable->find()
            ->contain(['TimeLogs' => function ($q) use ($today) {
                return $q->where([
                    'TimeLogs.date' => $today,
                ]);
            }])
            ->where([
                'deleted' => 0,
            ])
            ->toArray();

        $todayLeaves = $this->leaveApplicationsTable->find()
            ->where([
                'LeaveApplications.status' => 'approved',
                'LeaveApplications.deleted' => 0,
                'OR' => [
                    ['start_date <=' => $today, 'end_date >=' => $today],
                    ['start_date' => $today],
                    ['end_date' => $today],
                ]
            ])
            ->contain(['LeaveTypes', 'Applicant', 'Checker'])
            ->toArray();
        $tomorrowLeaves = $this->leaveApplicationsTable->find()
            ->where([
                'LeaveApplications.status' => 'approved',
                'LeaveApplications.deleted' => 0,
                'OR' => [
                    ['start_date <=' => $tomorrow, 'end_date >=' => $tomorrow],
                    ['start_date' => $tomorrow],
                    ['end_date' => $tomorrow],
                ]
            ])
            ->contain(['LeaveTypes', 'Applicant', 'Checker'])
            ->toArray();

        $todayPartialLeaves = $this->partialLeavesTable->find()
            ->where([
                'PartialLeaves.status' => 'approved',
                'PartialLeaves.deleted' => 0,
                'PartialLeaves.start_date' => $today
            ])
            ->contain(['Applicant', 'Checker'])
            ->toArray();

        $tomorrowPartialLeaves = $this->partialLeavesTable->find()
            ->where([
                'PartialLeaves.status' => 'approved',
                'PartialLeaves.deleted' => 0,
                'PartialLeaves.start_date' => $tomorrow
            ])
            ->contain(['Applicant', 'Checker'])
            ->toArray();

        $this->set(compact('holidays', 'stickyNote', 'announcements', 'birthdays', 'membersClockedTime', 'todayLeaves', 'tomorrowLeaves', 'todayPartialLeaves', 'tomorrowPartialLeaves'));
    }

    public function saveStickyNote()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        $loggedInUserId = $this->Authentication->getIdentity()->get('id');
        $user = $this->usersTable->get($loggedInUserId);
        $noteContent = $this->request->getData('note');
        $user->sticky_note = $noteContent;
        if ($this->usersTable->save($user)) {
            return AjaxResponseHelper::createResponse(true, 'Sticky note saved successfully');
        } else {
            return AjaxResponseHelper::createResponse(false, 'Failed to save sticky note');
        }
    }

    /**
     * View method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dashboard = $this->Dashboard->get($id, contain: []);
        $this->set(compact('dashboard'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $dashboard = $this->Dashboard->newEmptyEntity();
        if ($this->request->is('post')) {
            $dashboard = $this->Dashboard->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboard->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dashboard = $this->Dashboard->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dashboard = $this->Dashboard->patchEntity($dashboard, $this->request->getData());
            if ($this->Dashboard->save($dashboard)) {
                $this->Flash->success(__('The dashboard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dashboard could not be saved. Please, try again.'));
        }
        $this->set(compact('dashboard'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dashboard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dashboard = $this->Dashboard->get($id);
        if ($this->Dashboard->delete($dashboard)) {
            $this->Flash->success(__('The dashboard has been deleted.'));
        } else {
            $this->Flash->error(__('The dashboard could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
