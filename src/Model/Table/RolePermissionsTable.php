<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
// use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * RolePermissions Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\RolePermission newEmptyEntity()
 * @method \App\Model\Entity\RolePermission newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\RolePermission> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RolePermission get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\RolePermission findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\RolePermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\RolePermission> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\RolePermission|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\RolePermission saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\RolePermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RolePermission>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RolePermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RolePermission> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RolePermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RolePermission>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RolePermission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RolePermission> deleteManyOrFail(iterable $entities, array $options = [])
 */
class RolePermissionsTable extends AppTable
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

        $this->setTable('role_permissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
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
            ->integer('role_id')
            ->notEmptyString('role_id');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 100)
            ->requirePresence('controller', 'create')
            ->notEmptyString('controller');

        $validator
            ->scalar('action')
            ->maxLength('action', 100)
            ->requirePresence('action', 'create')
            ->notEmptyString('action');

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
        // Enforce uniqueness on the combination of role_id, controller, and action
        $rules->add($rules->isUnique(['role_id', 'controller', 'action']), [
            'errorField' => 'role_id',
            'message' => 'This combination of role, controller, and action already exists.'
        ]);

        // Ensure that the role_id exists in the Roles table
        $rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);

        return $rules;
    }
}
