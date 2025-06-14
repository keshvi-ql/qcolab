<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TimeLogs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PausesTable&\Cake\ORM\Association\HasMany $Pauses
 *
 * @method \App\Model\Entity\TimeLog newEmptyEntity()
 * @method \App\Model\Entity\TimeLog newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\TimeLog> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TimeLog get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\TimeLog findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\TimeLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\TimeLog> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TimeLog|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\TimeLog saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\TimeLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TimeLog>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TimeLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TimeLog> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TimeLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TimeLog>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\TimeLog>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\TimeLog> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TimeLogsTable extends Table
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

        $this->setTable('time_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'propertyName' => 'users',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Pauses', [
            'foreignKey' => 'time_log_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->dateTime('clock_in_time')
            ->requirePresence('clock_in_time', 'create')
            ->notEmptyDateTime('clock_in_time');

        $validator
            ->dateTime('clock_out_time')
            ->allowEmptyDateTime('clock_out_time');

        $validator
            ->scalar('total_work_duration')
            ->allowEmptyTime('total_work_duration');

        $validator
            ->scalar('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

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
}
