<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * ProjectMembers Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ProjectsTable&\Cake\ORM\Association\BelongsTo $Projects
 *
 * @method \App\Model\Entity\ProjectMember newEmptyEntity()
 * @method \App\Model\Entity\ProjectMember newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ProjectMember> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMember get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ProjectMember findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ProjectMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ProjectMember> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProjectMember|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ProjectMember saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectMember>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectMember>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectMember>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectMember> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectMember>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectMember>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ProjectMember>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ProjectMember> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ProjectMembersTable extends Table
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

        $this->setTable('project_members');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Projects', [
            'foreignKey' => 'project_id',
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->integer('project_id')
            ->notEmptyString('project_id');

        $validator
            ->allowEmptyString('is_leader');

        $validator
            ->notEmptyString('deleted');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['project_id'], 'Projects'), ['errorField' => 'project_id']);

        return $rules;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
