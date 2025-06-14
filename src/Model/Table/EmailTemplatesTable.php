<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
// use App\Model\Table\AppTable;

/**
 * EmailTemplates Model
 *
 * @method \App\Model\Entity\EmailTemplate newEmptyEntity()
 * @method \App\Model\Entity\EmailTemplate newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\EmailTemplate> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EmailTemplate get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\EmailTemplate findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\EmailTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\EmailTemplate> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EmailTemplate|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\EmailTemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\EmailTemplate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailTemplate>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailTemplate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailTemplate> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailTemplate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailTemplate>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\EmailTemplate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\EmailTemplate> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EmailTemplatesTable extends Table
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

        $this->setTable('email_templates');
        $this->setDisplayField('name');
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
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug');

        $validator
            ->scalar('name')
            ->maxLength('name', 16777215)
            ->allowEmptyString('name');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 16777215)
            ->allowEmptyString('subject');

        $validator
            ->scalar('message')
            ->maxLength('message', 4294967295)
            ->allowEmptyString('message');

        $validator
            ->scalar('placeholders')
            ->maxLength('placeholders', 4294967295)
            ->allowEmptyString('placeholders');

        return $validator;
    }
}
