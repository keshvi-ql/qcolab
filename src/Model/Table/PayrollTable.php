<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\I18n\FrozenTime;
use Cake\I18n\FrozenDate;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;

/**
 * Payroll Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PayrollDeductionsTable&\Cake\ORM\Association\HasMany $PayrollDeductions
 * @property \App\Model\Table\PayrollEarningsTable&\Cake\ORM\Association\HasMany $PayrollEarnings
 *
 * @method \App\Model\Entity\Payroll newEmptyEntity()
 * @method \App\Model\Entity\Payroll newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Payroll> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payroll get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Payroll findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Payroll patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Payroll> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payroll|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Payroll saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Payroll>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Payroll>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Payroll>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Payroll> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Payroll>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Payroll>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Payroll>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Payroll> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PayrollTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('payroll');
        $this->setDisplayField('month');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'propertyName' => 'users',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('PayrollDeductions', [
            'foreignKey' => 'payroll_id',
        ]);
        $this->hasMany('PayrollEarnings', [
            'foreignKey' => 'payroll_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('month')
            ->maxLength('month', 25)
            ->requirePresence('month', 'create')
            ->notEmptyString('month');

        $validator
            ->numeric('total_working_days')
            ->requirePresence('total_working_days', 'create')
            ->notEmptyString('total_working_days');

        $validator
            ->numeric('days_present')
            ->requirePresence('days_present', 'create')
            ->notEmptyString('days_present');

        $validator
            ->numeric('paid_leaves')
            ->requirePresence('paid_leaves', 'create')
            ->notEmptyString('paid_leaves');

        $validator
            ->numeric('unpaid_leaves')
            ->requirePresence('unpaid_leaves', 'create')
            ->notEmptyString('unpaid_leaves');

        $validator
            ->decimal('deduction_of_leaves')
            ->requirePresence('deduction_of_leaves', 'create')
            ->notEmptyString('deduction_of_leaves');

        $validator
            ->decimal('basic_salary')
            ->requirePresence('basic_salary', 'create')
            ->notEmptyString('basic_salary');

        $validator
            ->numeric('total_balance_leaves')
            ->requirePresence('total_balance_leaves', 'create')
            ->notEmptyString('total_balance_leaves');

        $validator
            ->decimal('net_payable')
            ->requirePresence('net_payable', 'create')
            ->notEmptyString('net_payable');

        $validator
            ->scalar('employee_code')
            ->maxLength('employee_code', 100)
            ->allowEmptyString('employee_code');

        $validator
            ->scalar('pan_number')
            ->maxLength('pan_number', 100)
            ->allowEmptyString('pan_number');

        $validator
            ->scalar('bank_name')
            ->maxLength('bank_name', 100)
            ->allowEmptyString('bank_name');

        $validator
            ->scalar('bank_account_number')
            ->maxLength('bank_account_number', 100)
            ->allowEmptyString('bank_account_number');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    public function payrollCalculate()
    {
        $currentDate = FrozenTime::now();
        $previousMonth = $currentDate->modify('-1 month')->format('F-Y');
        $startDate     = date('Y-m-d', strtotime($previousMonth));
        $endDate = date('Y-m-t', strtotime($startDate));

        //*************** To find total working days *********************/
        $monthStr     = date('Y-m', strtotime($startDate));
        $numDaysPreMonth = date('t', strtotime($monthStr));

        $total_offdays = $this->offdays_count($monthStr);

        $regularWorkingDays = $numDaysPreMonth - $total_offdays['regular'];
        $traineeWorkingDays = $numDaysPreMonth - $total_offdays['trainee'];

        //**************** To find Net salary of each users **************/
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $users = $usersTable->find()
            ->where(['is_admin' => 0, 'deleted' => 0])
            ->toArray();

        if (empty($users)) {
            return [
                'success' => false,
                'message' => 'No eligible users found for payroll generation.'
            ];
        }

        $payrollData = [];

        foreach ($users as $user) {

            $total_leaves = $this->leavedays_count($user->id, $user->is_trainee, $startDate, $endDate);

            $earning_amount   = 0;
            list($month_name) = explode('-', $previousMonth);

            if ($month_name == 'March') {
                $earning_amount = $this->get_earning_amount($user, $regularWorkingDays, $total_leaves["total_balance_leave"]);
            }

            if ($user->is_trainee) {
                $user->total_working_days = $traineeWorkingDays;
                $user->days_present        = $traineeWorkingDays - $total_leaves["total_leave_days"];
                $user->paid_leaves         = 0;
                $user->unpaid_leaves       = $total_leaves["total_leave_days"];
                $per_day_salary            = ($user->salary / $traineeWorkingDays);
                $user->deduction_of_leaves = $total_leaves["total_leave_days"] * $per_day_salary;
                $user->balance_leaves      = 0;
                $user->netpayable          = ($user->salary) - $user->deduction_of_leaves;
                $user->basic_salary        = $user->salary;
                $user->earning_amount      = 0;
            } else {
                $user->total_working_days  = $regularWorkingDays;
                $user->days_present        = $regularWorkingDays - $total_leaves["total_leave_days"];
                $user->paid_leaves         = ($total_leaves["paid_leaves"]) ? $total_leaves["paid_leaves"] : 0;
                $user->unpaid_leaves       = ($total_leaves["unpaid_leaves"]) ? $total_leaves["unpaid_leaves"] : 0;
                $per_day_salary            = ($user->salary / $regularWorkingDays);
                $user->deduction_of_leaves = $user->unpaid_leaves * $per_day_salary;
                $user->netpayable          = ($user->salary) - $user->deduction_of_leaves;
                $user->basic_salary        = $user->salary;

                if ($earning_amount) {
                    $user->balance_leaves = 0;
                    $user->earning_amount = $earning_amount;
                    $user->n_leaves =  $total_leaves["total_balance_leave"];
                } else {
                    $user->earning_amount = 0;
                    $user->balance_leaves = $total_leaves["total_balance_leave"];
                }
            }

            $user->start_date = $startDate;
            $user->end_date   = $endDate;
        }

        return $users;
    }

    private function get_earning_amount($user, $working_days, $total_balance_leave)
    {
        $balance_leaves = $total_balance_leave;

        $amount = 0;
        if (strpos($balance_leaves, '.') !== false) {
            $balance_leaves += 0.5;
        }

        if ($balance_leaves == 1) {
            $amount = ($user->salary / $working_days) * $total_balance_leave;
        } else if ($balance_leaves > 1) {
            $monthly_amount = 0;

            for ($i = 1; $i <= $balance_leaves; $i++) {
                if ($i == 1) {
                    $monthly_amount += ($user->salary / $working_days);
                } else {
                    $previousMonth = FrozenTime::now()->modify("-$i month")->format('F-Y');

                    $payroll = $this->find()
                        ->where(['user_id' => $user->id, 'month' => $previousMonth])
                        ->first();

                    $monthly_amount += ($payroll->basic_salary / $payroll->total_working_days);
                }
            }

            $avrage_amount = $monthly_amount / $balance_leaves;
            $amount        = $avrage_amount * ($total_balance_leave);
        }

        return $amount;
    }

    private function offdays_count($monthStr)
    {
        $numSundays   = 0;
        $numSaturdays = 0;
        $numDaysPreMonth = date('t', strtotime($monthStr));

        for ($day = 1; $day <= $numDaysPreMonth; $day++) {
            $dayOfWeek = date('l', strtotime("$monthStr-$day"));

            if ($dayOfWeek == 'Sunday') {
                $numSundays++;
            }

            if ($dayOfWeek == 'Saturday') {
                $numSaturdays++;
            }
        }

        $r_offDays    = $numSaturdays + $numSundays - 0.5;
        $t_offDays    = $numSundays + ($numSaturdays / 2);

        $offdays = ["regular" => $r_offDays, "trainee" => $t_offDays];
        return $offdays;
    }

    private function leavedays_count($user_id, $user_is_trainee, $startDate, $endDate)
    {
        $leavesTable = TableRegistry::getTableLocator()->get('LeaveApplications');
        $balanceLeavesTable = TableRegistry::getTableLocator()->get('BalanceLeaves');
        $monthStr     = date('Y-m', strtotime($startDate));

        $leave_dates = array();
        $total_leave_days = 0;

        $leaveData = $leavesTable->find()
            ->where([
                'deleted' => 0,
                'status' => 'approved',
                'applicant_id' => $user_id,
                'OR' => [
                    ['start_date BETWEEN :start AND :end'],
                    ['end_date BETWEEN :start AND :end']
                ]
            ])
            ->bind(':start', $startDate, 'date')
            ->bind(':end', $endDate, 'date')
            ->toArray();

        foreach ($leaveData as $leave) {
            $start_date = strtotime($leave->start_date->i18nFormat('yyyy-MM-dd'));
            $end_date = strtotime($leave->end_date->i18nFormat('yyyy-MM-dd'));

            if ($leave->start_date->i18nFormat('yyyy-MM-dd') >= $startDate && $leave->end_date->i18nFormat('yyyy-MM-dd') <= $endDate) {
                $total_leave_days += $leave->total_days;
            } else {
                while ($start_date <= $end_date) {
                    $leave_monthStr     = date('Y-m', $start_date);
                    if ($leave_monthStr == $monthStr) {
                        $leave_dates[] = date('Y-m-d', $start_date);
                    }
                    $start_date = strtotime('+1 day', $start_date);
                }
            }
        }

        $last_saturday = date("Y-m-d", strtotime("last saturday of $startDate"));

        foreach ($leave_dates as $date) {
            if ($this->holiday_ckeck($date)) {
                continue;
            }
            $dayOfWeek = date('l', strtotime($date));

            //echo $date." - ".$dayOfWeek. "<br>";

            if ($dayOfWeek == 'Sunday') {
                continue;
            } else if ($dayOfWeek == 'Saturday') {
                if ($user_is_trainee) {
                    $total_leave_days += 0.5;
                } else {
                    if ($date == $last_saturday) {
                        $total_leave_days += 0.5;
                    }
                }
            } else {
                $total_leave_days++;
            }
        }

        $total_balance_leaves = $balanceLeavesTable->find()
            ->where(['user_id' => $user_id])
            ->first();
        $balance_leaves = $total_balance_leaves->t_balance_leaves;

        $paid_leaves = min($balance_leaves, $total_leave_days);
        $unpaid_leaves = max(0, $total_leave_days - $balance_leaves);
        $total_balance_leaves = max(0, $balance_leaves - $total_leave_days);

        $total_leaves = ["total_leave_days" => $total_leave_days, "unpaid_leaves" => $unpaid_leaves, "paid_leaves" => $paid_leaves, "total_balance_leave" => $total_balance_leaves];

        return $total_leaves;
    }

    private function holiday_ckeck($date)
    {
        $holidaysTable = TableRegistry::getTableLocator()->get('Holidays');

        $holidaysData = $holidaysTable->find()
            ->where([
                'deleted' => 0,
                'start_date <=' => $date,
                'end_date >=' => $date
            ])
            ->toArray();

        if (!empty($holidaysData)) {
            return true;
        } else {
            return false;
        }
    }
}
