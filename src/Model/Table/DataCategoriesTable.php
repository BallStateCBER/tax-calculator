<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DataCategories Model
 *
 * @method \App\Model\Entity\DataCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\DataCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DataCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DataCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DataCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DataCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DataCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class DataCategoriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('data_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('notes')
            ->requirePresence('notes', 'create')
            ->notEmpty('notes');

        return $validator;
    }
}
