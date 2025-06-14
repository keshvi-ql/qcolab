<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * LeaveApplications Model
 *
 * @property \App\Model\Table\LeaveTypesTable&\Cake\ORM\Association\BelongsTo $LeaveTypes
 *
 * @method \App\Model\Entity\LeaveApplication newEmptyEntity()
 * @method \App\Model\Entity\LeaveApplication newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LeaveApplication> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeaveApplication get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LeaveApplication findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LeaveApplication patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LeaveApplication> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeaveApplication|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LeaveApplication saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveApplication>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveApplication>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveApplication>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveApplication> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveApplication>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveApplication>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveApplication>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveApplication> deleteManyOrFail(iterable $entities, array $options = [])
 */
class LeaveApplicationsTable extends Table
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

        $this->setTable('leave_applications');
        $this->setDisplayField('status');
        $this->setPrimaryKey('id');

        // $this->belongsTo('LeaveTypes', [
        //     'foreignKey' => 'leave_type_id',
        //     'joinType' => 'INNER',
        // ]);

        $this->belongsTo('Applicant', [
            'className' => 'Users',
            'foreignKey' => 'applicant_id',
            'joinType' => 'LEFT',
        ]);

        // Define the belongsTo relationship for checker
        $this->belongsTo('Checker', [
            'className' => 'Users',
            'foreignKey' => 'checked_by',
            'joinType' => 'LEFT',
        ]);

        // Define the belongsTo relationship for leave types
        $this->belongsTo('LeaveTypes', [
            'foreignKey' => 'leave_type_id',
            'joinType' => 'LEFT',
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
            ->integer('leave_type_id')
            ->notEmptyString('leave_type_id');

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDate('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmptyDate('end_date');

        $validator
            ->decimal('total_hours')
            ->requirePresence('total_hours', 'create')
            ->notEmptyString('total_hours');

        $validator
            ->decimal('total_days')
            ->requirePresence('total_days', 'create')
            ->notEmptyString('total_days');

        $validator
            ->scalar('half_day_type')
            ->maxLength('half_day_type', 50)
            ->allowEmptyString('half_day_type');

        $validator
            ->integer('applicant_id')
            ->requirePresence('applicant_id', 'create')
            ->notEmptyString('applicant_id');

        $validator
            ->scalar('reason')
            ->maxLength('reason', 16777215)
            ->requirePresence('reason', 'create')
            ->notEmptyString('reason');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->dateTime('checked_at')
            ->allowEmptyDateTime('checked_at');

        $validator
            ->integer('checked_by')
            ->notEmptyString('checked_by');

        $validator
            ->scalar('files')
            ->allowEmptyString('files');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

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
        $rules->add($rules->existsIn(['leave_type_id'], 'LeaveTypes'), ['errorField' => 'leave_type_id']);

        return $rules;
    }

    public function softDelete($id)
    {

        $leaveApplication = $this->get($id);

        $leaveApplication->deleted = 1;

        // Save the leave application
        if ($this->save($leaveApplication)) {
            // Perform additional actions, e.g., mark related records as deleted

            $notificationsTable = TableRegistry::getTableLocator()->get('Notifications');
            $notificationRecipientsTable = TableRegistry::getTableLocator()->get('NotificationRecipients');

            $notifications = $notificationsTable->find()
                ->select(['id'])
                ->where(['entity_id' => $id, 'module' => 'leave-applications'])
                ->all();

            $notificationIds = $notifications->extract('id')->toArray();

            if (!empty($notificationIds)) {
                $notificationRecipientsTable->updateAll(
                    ['deleted' => 1],
                    ['notification_id IN' => $notificationIds]
                );

                $notificationsTable->updateAll(
                    ['deleted' => 1],
                    ['id IN' => $notificationIds]
                );
            }

            return true;
        }

        return false;
    }
}
