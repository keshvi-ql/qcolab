<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Announcements Model
 *
 * @method \App\Model\Entity\Announcement newEmptyEntity()
 * @method \App\Model\Entity\Announcement newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Announcement> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Announcement get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Announcement findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Announcement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Announcement> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Announcement|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Announcement saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Announcement>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Announcement>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Announcement>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Announcement> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Announcement>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Announcement>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Announcement>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Announcement> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnnouncementsTable extends Table
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

        $this->setTable('announcements');
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
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDate('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmptyDate('end_date');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

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
