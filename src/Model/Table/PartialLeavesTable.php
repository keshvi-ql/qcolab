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
 * PartialLeaves Model
 *
 * @method \App\Model\Entity\PartialLeave newEmptyEntity()
 * @method \App\Model\Entity\PartialLeave newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PartialLeave> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PartialLeave get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PartialLeave findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PartialLeave patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PartialLeave> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PartialLeave|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PartialLeave saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PartialLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PartialLeave>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PartialLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PartialLeave> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PartialLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PartialLeave>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PartialLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PartialLeave> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PartialLeavesTable extends Table
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

        $this->setTable('partial_leaves');
        $this->setDisplayField('status');
        $this->setPrimaryKey('id');

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
            ->requirePresence('checked_by', 'create')
            ->notEmptyString('checked_by');

        $validator
            ->scalar('files')
            ->allowEmptyString('files');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }

    public function softDelete($id)
    {
        $partialLeave = $this->get($id);

        $partialLeave->deleted = 1;

        if ($this->save($partialLeave)) {

            $notificationsTable = TableRegistry::getTableLocator()->get('Notifications');
            $notificationRecipientsTable = TableRegistry::getTableLocator()->get('NotificationRecipients');

            $notifications = $notificationsTable->find()
                ->select(['id'])
                ->where(['entity_id' => $id, 'module' => 'partial-leaves'])
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
