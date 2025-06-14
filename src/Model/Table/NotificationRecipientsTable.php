<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotificationRecipients Model
 *
 * @property \App\Model\Table\NotificationsTable&\Cake\ORM\Association\BelongsTo $Notifications
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\NotificationRecipient newEmptyEntity()
 * @method \App\Model\Entity\NotificationRecipient newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NotificationRecipient> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationRecipient get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NotificationRecipient findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NotificationRecipient patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NotificationRecipient> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationRecipient|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NotificationRecipient saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationRecipient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationRecipient>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationRecipient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationRecipient> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationRecipient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationRecipient>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationRecipient>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationRecipient> deleteManyOrFail(iterable $entities, array $options = [])
 */
class NotificationRecipientsTable extends Table
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

        $this->setTable('notification_recipients');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Notifications', [
            'foreignKey' => 'notification_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('notification_id')
            ->notEmptyString('notification_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->boolean('is_read')
            ->notEmptyString('is_read');

        $validator
            ->dateTime('read_at')
            ->allowEmptyDateTime('read_at');

        $validator
            ->boolean('deleted')
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
        $rules->add($rules->existsIn(['notification_id'], 'Notifications'), ['errorField' => 'notification_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
