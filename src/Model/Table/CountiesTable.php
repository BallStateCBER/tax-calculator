<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Counties Model
 *
 * @method \App\Model\Entity\County get($primaryKey, $options = [])
 * @method \App\Model\Entity\County newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\County[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\County|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\County patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\County[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\County findOrCreate($search, callable $callback = null, $options = [])
 */
class CountiesTable extends Table
{
    const COOK_COUNTY = 108;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('counties');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasOne('IncomeTaxRates', [
            'className' => 'TaxRates'
        ])
            ->setForeignKey('loc_id')
            ->setConditions([
                'loc_type' => 'county',
                'category_id' => TaxRatesTable::COUNTY_INCOME
            ]);
        $this->hasOne('PropertyTaxRates', [
            'className' => 'TaxRates'
        ])
            ->setForeignKey('loc_id')
            ->setConditions([
                'loc_type' => 'county',
                'category_id' => TaxRatesTable::PROPERTY
            ]);
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
            ->scalar('state')
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('simplified')
            ->requirePresence('simplified', 'create')
            ->notEmpty('simplified');

        $validator
            ->integer('fips')
            ->requirePresence('fips', 'create')
            ->notEmpty('fips');

        return $validator;
    }

    /**
     * Returns an array of IN and IL counties to use as <select> options
     *
     * @return array
     */
    public function getCountyOptions()
    {
        $counties = [];
        foreach (['IN', 'IL'] as $state) {
            $counties[$state] = $this
                ->find('list')
                ->where(['state' => $state])
                ->orderAsc('name')
                ->toArray();
        }

        return $counties;
    }
}
