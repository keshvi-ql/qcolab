<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Bids Model
 *
 * @method \App\Model\Entity\Bid newEmptyEntity()
 * @method \App\Model\Entity\Bid newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Bid> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bid get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Bid findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Bid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Bid> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bid|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Bid saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Bid>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bid>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bid>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bid> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bid>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bid>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Bid>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Bid> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BidsTable extends Table
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

        $this->setTable('bids');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Clients', [
            'foreignKey' => 'bid_id',
        ]);

        $this->belongsTo('LeadSources', [
            'foreignKey' => 'source',
            'propertyName' => 'lead_sources',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('LeadProfiles', [
            'foreignKey' => 'profile',
            'propertyName' => 'lead_profiles',
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
            ->scalar('url')
            ->requirePresence('url', 'create')
            ->notEmptyString('url');

        $validator
            ->integer('source')
            ->requirePresence('source', 'create')
            ->notEmptyString('source');

        $validator
            ->integer('profile')
            ->requirePresence('profile', 'create')
            ->notEmptyString('profile');

        $validator
            ->scalar('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->decimal('rate')
            ->requirePresence('rate', 'create')
            ->notEmptyString('rate');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
