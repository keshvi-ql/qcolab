<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
// use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\EntityInterface;

/**
 * Users Model
 *
 * @property \App\Model\Table\RolesTable&\Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\User> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\User> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends AppTable
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

        $this->setTable('users');
        $this->setDisplayField('first_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER',
        ]);

        $this->hasMany('TimeLogs', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('Payroll', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('ProjectMembers', [
            'foreignKey' => 'user_id',
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
            ->scalar('first_name')
            ->maxLength('first_name', 255)
            ->requirePresence('first_name', 'create', 'First Name is required.')
            ->notEmptyString('first_name', 'First Name is required.');

        $validator
            ->scalar('middle_name')
            ->maxLength('middle_name', 255)
            ->allowEmptyString('middle_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 255)
            ->allowEmptyString('last_name');

        $validator
            ->boolean('is_admin')
            ->notEmptyString('is_admin');

        $validator
            ->scalar('phone_no')
            ->allowEmptyString('phone_no')
            ->add('phone_no', [
                'length' => [
                    'rule' => ['lengthBetween', 10, 10],
                    'message' => 'Phone number must be exactly 10 digits long',
                ],
                'numeric' => [
                    'rule' => 'numeric',
                    'message' => 'Phone number must be numeric',
                ]
            ]);

        $validator
            ->scalar('alt_phone_no')
            ->allowEmptyString('alt_phone_no')
            ->add('alt_phone_no', [
                'length' => [
                    'rule' => ['lengthBetween', 10, 10],
                    'message' => 'Alternet Phone number must be exactly 10 digits long',
                ],
                'numeric' => [
                    'rule' => 'numeric',
                    'message' => 'Alternet Phone number must be numeric',
                ]
            ]);

        $validator
            ->date('dob', ['ymd'], 'The provided value must be a date of one of these formats: Y-m-d')
            ->allowEmptyDate('dob');

        $validator
            ->allowEmptyFile('profile_image')
            ->add('profile_image', [
                'maxLength' => [
                    'rule' => ['maxLength', 255],
                    'message' => 'Image filename cannot be longer than 255 characters',
                    'on' => function ($context) {
                        return !is_array($context['data']['profile_image']);
                    }
                ],
                'fileType' => [
                    'rule' => ['mimeType', ['image/jpg', 'image/jpeg', 'image/png']],
                    'message' => 'Please upload a valid image (JPG, JPEG, PNG).'
                ]
            ]);

        $validator
            ->email('email', false, 'Please enter a valid email address.')
            ->requirePresence('email', 'create', 'Email is required.')
            ->notEmptyString('email', 'Email is required.')
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'This email address is already in use. Please enter another email.'
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create', 'Password is required.')
            ->notEmptyString('password', 'Password is required.')
            ->add('password', 'match', [
                'rule' => function ($value, $context) {
                    // Check if confirm_password is present in the context and matches password
                    return isset($context['data']['confirm_password']) && $value === $context['data']['confirm_password'];
                },
                'message' => 'Password and confirm password do not match.'
            ]);

        $validator
            ->scalar('confirm_password')
            ->maxLength('confirm_password', 255)
            ->requirePresence('confirm_password', 'create', 'Confirm Password is required.')
            ->notEmptyString('confirm_password', 'Confirm Password is required.');

        $validator
            ->scalar('job_title')
            ->maxLength('job_title', 100)
            ->allowEmptyString('job_title');

        $validator
            ->boolean('is_trainee')
            ->notEmptyString('is_trainee');

        $validator
            ->scalar('remember_me_token')
            ->maxLength('remember_me_token', 255)
            ->allowEmptyString('remember_me_token');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
            ->dateTime('email_verified_at')
            ->allowEmptyDateTime('email_verified_at');

        $validator
            ->dateTime('token_requested_at')
            ->allowEmptyDateTime('token_requested_at');

        $validator
            ->dateTime('password_updated_at')
            ->allowEmptyDateTime('password_updated_at');

        $validator
            ->dateTime('last_login_at')
            ->allowEmptyDateTime('last_login_at');

        $validator
            ->boolean('status')
            ->notEmptyString('status');

        $validator
            ->integer('role_id')
            ->notEmptyString('role_id', 'Role is required.')
            ->add('role_id', 'validRole', [
                'rule' => 'numeric',
                'message' => 'Please select a valid role.'
            ]);

        $validator
            ->scalar('gender')
            ->maxLength('gender', 10)
            ->allowEmptyString('gender');

        $validator
            ->scalar('alt_email')
            ->maxLength('alt_email', 255)
            ->allowEmptyString('alt_email');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        $validator
            ->scalar('alt_address')
            ->allowEmptyString('alt_address');

        $validator
            ->scalar('skype')
            ->maxLength('skype', 255)
            ->allowEmptyString('skype');

        $validator
            ->scalar('employee_code')
            ->maxLength('employee_code', 50)
            ->allowEmptyString('employee_code');

        $validator
            ->scalar('pan_no')
            ->maxLength('pan_no', 100)
            ->allowEmptyString('pan_no');

        $validator
            ->scalar('bank_name')
            ->maxLength('bank_name', 100)
            ->allowEmptyString('bank_name');

        $validator
            ->scalar('bank_account_no')
            ->maxLength('bank_account_no', 100)
            ->allowEmptyString('bank_account_no');

        $validator
            ->decimal('security_deposit_amount')
            ->allowEmptyString('security_deposit_amount');

        $validator
            ->decimal('salary')
            ->allowEmptyString('salary');


        $validator
            ->date('date_of_joining', ['ymd'], 'The provided value must be a date of one of these formats: Y-m-d')
            ->allowEmptyDate('date_of_joining');

        $validator
            ->scalar('increment_month')
            ->maxLength('increment_month', 50)
            ->allowEmptyString('increment_month');

        $validator
            ->boolean('is_bde')
            ->notEmptyString('is_bde');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);
        $rules->add($rules->existsIn(['role_id'], 'Roles'), ['errorField' => 'role_id']);

        return $rules;
    }

    public function delete(EntityInterface $entity, $options = []): bool
    {
        $entity->set('deleted', 1);
        return $this->save($entity, $options) !== false;
    }
}
