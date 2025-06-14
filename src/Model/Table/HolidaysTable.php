<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Holidays Model
 *
 * @method \App\Model\Entity\Holiday newEmptyEntity()
 * @method \App\Model\Entity\Holiday newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Holiday> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Holiday get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Holiday findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Holiday patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Holiday> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Holiday|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Holiday saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Holiday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Holiday>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Holiday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Holiday> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Holiday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Holiday>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Holiday>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Holiday> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HolidaysTable extends Table
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

        $this->setTable('holidays');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDate('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmptyDate('end_date');

        $validator
            ->notEmptyString('deleted');

        return $validator;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
