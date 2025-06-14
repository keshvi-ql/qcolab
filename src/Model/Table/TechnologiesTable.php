<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Technologies Model
 *
 * @method \App\Model\Entity\Technology newEmptyEntity()
 * @method \App\Model\Entity\Technology newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Technology> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Technology get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Technology findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Technology patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Technology> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Technology|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Technology saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Technology>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Technology>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Technology>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Technology> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Technology>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Technology>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Technology>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Technology> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TechnologiesTable extends Table
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

        $this->setTable('technologies');
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
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        return $validator;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
