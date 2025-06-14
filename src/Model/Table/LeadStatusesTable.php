<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LeadStatuses Model
 *
 * @method \App\Model\Entity\LeadStatus newEmptyEntity()
 * @method \App\Model\Entity\LeadStatus newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadStatus> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeadStatus get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LeadStatus findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LeadStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadStatus> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeadStatus|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LeadStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LeadStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadStatus>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadStatus> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadStatus>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadStatus>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadStatus> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadStatusesTable extends Table
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

        $this->setTable('lead_statuses');
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
            ->requirePresence('title', 'create', 'Title is required.')
            ->notEmptyString('title', 'Title is required.');

        $validator
            ->scalar('color')
            ->maxLength('color', 10)
            ->requirePresence('color', 'create', 'Color is required.')
            ->notEmptyString('color', 'Color is required.');

        return $validator;
    }
}
