<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NotificationSettings Model
 *
 * @method \App\Model\Entity\NotificationSetting newEmptyEntity()
 * @method \App\Model\Entity\NotificationSetting newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\NotificationSetting> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSetting get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\NotificationSetting findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\NotificationSetting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\NotificationSetting> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationSetting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\NotificationSetting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationSetting>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationSetting> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationSetting>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\NotificationSetting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\NotificationSetting> deleteManyOrFail(iterable $entities, array $options = [])
 */
class NotificationSettingsTable extends Table
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

        $this->setTable('notification_settings');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');
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
            ->boolean('enable_email')
            ->notEmptyString('enable_email');

        $validator
            ->boolean('enable_system')
            ->notEmptyString('enable_system');

        $validator
            ->scalar('notify_to_team_members')
            ->allowEmptyString('notify_to_team_members');

        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }
}
