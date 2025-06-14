<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
// use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * GeneralFiles Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\GeneralFile newEmptyEntity()
 * @method \App\Model\Entity\GeneralFile newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\GeneralFile> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GeneralFile get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\GeneralFile findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\GeneralFile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\GeneralFile> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\GeneralFile|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\GeneralFile saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\GeneralFile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GeneralFile>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GeneralFile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GeneralFile> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GeneralFile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GeneralFile>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\GeneralFile>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\GeneralFile> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GeneralFilesTable extends AppTable
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

        $this->setTable('general_files');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('UploadedByUsers', [
            'className' => 'Users',  // Reusing the Users table
            'foreignKey' => 'uploaded_by',
            'joinType' => 'INNER',  // Or LEFT, based on your needs
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
            ->scalar('file_name')
            ->requirePresence('file_name', 'create')
            ->notEmptyString('file_name');

        $validator
            ->scalar('file_id')
            ->allowEmptyString('file_id');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->decimal('file_size')
            ->requirePresence('file_size', 'create')
            ->notEmptyString('file_size');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->integer('uploaded_by')
            ->requirePresence('uploaded_by', 'create')
            ->notEmptyString('uploaded_by');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
