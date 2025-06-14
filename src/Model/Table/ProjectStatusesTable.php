<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * ProjectStatuses Model
 *
 * @method \App\Model\Entity\ProjectStatus newEmptyEntity()
 * @method \App\Model\Entity\ProjectStatus newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProjectStatus> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProjectStatus get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProjectStatus findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProjectStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProjectStatus> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProjectStatus|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProjectStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectStatus>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectStatus> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectStatus>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectStatus> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProjectStatusesTable extends Table
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

        $this->setTable('project_statuses');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Projects', [
            'foreignKey' => 'status_id',
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
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('color')
            ->maxLength('color', 10)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        return $validator;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
