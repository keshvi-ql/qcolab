<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pauses Model
 *
 * @property \App\Model\Table\TimeLogsTable&\Cake\ORM\Association\BelongsTo $TimeLogs
 *
 * @method \App\Model\Entity\Pause newEmptyEntity()
 * @method \App\Model\Entity\Pause newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Pause> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pause get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Pause findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Pause patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Pause> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pause|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Pause saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Pause>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pause>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pause>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pause> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pause>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pause>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pause>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pause> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PausesTable extends Table
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

        $this->setTable('pauses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TimeLogs', [
            'foreignKey' => 'time_log_id',
            'joinType' => 'INNER',
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
            ->integer('time_log_id')
            ->notEmptyString('time_log_id');

        $validator
            ->dateTime('pause_time')
            ->requirePresence('pause_time', 'create')
            ->notEmptyDateTime('pause_time');

        $validator
            ->dateTime('resume_time')
            ->allowEmptyDateTime('resume_time');

        $validator
            ->time('pause_duration')
            ->allowEmptyTime('pause_duration');

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
        $rules->add($rules->existsIn(['time_log_id'], 'TimeLogs'), ['errorField' => 'time_log_id']);

        return $rules;
    }
}
