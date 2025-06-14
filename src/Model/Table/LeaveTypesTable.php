<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LeaveTypes Model
 *
 * @method \App\Model\Entity\LeaveType newEmptyEntity()
 * @method \App\Model\Entity\LeaveType newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LeaveType> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeaveType get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LeaveType findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LeaveType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LeaveType> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeaveType|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LeaveType saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveType>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveType> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveType>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeaveType>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeaveType> deleteManyOrFail(iterable $entities, array $options = [])
 */
class LeaveTypesTable extends Table
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

        $this->setTable('leave_types');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');
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
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('status')
            ->notEmptyString('status');

        $validator
            ->scalar('color')
            ->maxLength('color', 7)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }
}
