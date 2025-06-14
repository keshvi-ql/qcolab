<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PayrollDeductions Model
 *
 * @method \App\Model\Entity\PayrollDeduction newEmptyEntity()
 * @method \App\Model\Entity\PayrollDeduction newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PayrollDeduction> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PayrollDeduction get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PayrollDeduction findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PayrollDeduction patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PayrollDeduction> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PayrollDeduction|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PayrollDeduction saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PayrollDeduction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PayrollDeduction>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PayrollDeduction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PayrollDeduction> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PayrollDeduction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PayrollDeduction>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PayrollDeduction>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PayrollDeduction> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PayrollDeductionsTable extends Table
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

        $this->setTable('payroll_deductions');
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
            ->integer('payroll_id')
            ->requirePresence('payroll_id', 'create')
            ->notEmptyString('payroll_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 100)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->notEmptyString('deleted');

        return $validator;
    }
}
