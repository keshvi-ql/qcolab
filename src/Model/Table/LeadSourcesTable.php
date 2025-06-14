<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LeadSources Model
 *
 * @method \App\Model\Entity\LeadSource newEmptyEntity()
 * @method \App\Model\Entity\LeadSource newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadSource> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeadSource get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LeadSource findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LeadSource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadSource> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeadSource|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LeadSource saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LeadSource>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadSource>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadSource>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadSource> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadSource>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadSource>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadSource>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadSource> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadSourcesTable extends Table
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

        $this->setTable('lead_sources');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Clients', [
            'foreignKey' => 'source',
        ]);

        $this->hasMany('Bids', [
            'foreignKey' => 'source',
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
            ->requirePresence('title', 'create', 'Title is required.')
            ->notEmptyString('title', 'Title is required.');

        return $validator;
    }
}
