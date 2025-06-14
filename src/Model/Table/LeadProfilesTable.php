<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LeadProfiles Model
 *
 * @method \App\Model\Entity\LeadProfile newEmptyEntity()
 * @method \App\Model\Entity\LeadProfile newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadProfile> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LeadProfile get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\LeadProfile findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\LeadProfile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\LeadProfile> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\LeadProfile|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\LeadProfile saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\LeadProfile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadProfile>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadProfile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadProfile> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadProfile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadProfile>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\LeadProfile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\LeadProfile> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadProfilesTable extends Table
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

        $this->setTable('lead_profiles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

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
