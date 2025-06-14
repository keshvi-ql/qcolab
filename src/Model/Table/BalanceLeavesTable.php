<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;

/**
 * BalanceLeaves Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\BalanceLeave newEmptyEntity()
 * @method \App\Model\Entity\BalanceLeave newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BalanceLeave> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BalanceLeave get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BalanceLeave findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BalanceLeave patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BalanceLeave> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BalanceLeave|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BalanceLeave saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BalanceLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BalanceLeave>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BalanceLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BalanceLeave> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BalanceLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BalanceLeave>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BalanceLeave>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BalanceLeave> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BalanceLeavesTable extends Table
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

        $this->setTable('balance_leaves');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->numeric('t_balance_leaves')
            ->requirePresence('t_balance_leaves', 'create')
            ->notEmptyString('t_balance_leaves');

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

        return $rules;
    }

    public function updateBalanceLeaves($userId, $totalBalanceLeaves)
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        $user = $usersTable->find()
            ->where(['id' => $userId, 'deleted' => 0])
            ->first();

        if ($user->is_trainee) {
            $balance_leaves = 0;
        } else {
            $balance_leaves = $totalBalanceLeaves + 1;
        }

        $balanceLeave = $this->find()
            ->where(['user_id' => $userId])
            ->first();

        if ($balanceLeave) {
            $balanceLeave->t_balance_leaves = $balance_leaves;
            return $this->save($balanceLeave);
        }
    }
}
