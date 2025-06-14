<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Notifications Model
 *
 * @property \App\Model\Table\NotificationRecipientsTable&\Cake\ORM\Association\HasMany $NotificationRecipients
 *
 * @method \App\Model\Entity\Notification newEmptyEntity()
 * @method \App\Model\Entity\Notification newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Notification> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Notification get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Notification findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Notification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Notification> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Notification|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Notification saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Notification>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Notification>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Notification>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Notification> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Notification>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Notification>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Notification>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Notification> deleteManyOrFail(iterable $entities, array $options = [])
 */
class NotificationsTable extends Table
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

        $this->setTable('notifications');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');

        $this->hasMany('NotificationRecipients', [
            'foreignKey' => 'notification_id',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'created_by', // Assuming created_by links to Users.id
            'joinType' => 'INNER'
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
            ->scalar('type')
            ->maxLength('type', 250)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('module')
            ->maxLength('module', 250)
            ->requirePresence('module', 'create')
            ->notEmptyString('module');

        $validator
            ->integer('entity_id')
            ->allowEmptyString('entity_id');

        $validator
            ->integer('created_by')
            ->requirePresence('created_by', 'create')
            ->notEmptyString('created_by');

        $validator
            ->scalar('message')
            ->maxLength('message', 4294967295)
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }
}
