<?php
namespace App\Model\Table;

use App\Model\Entity\TaxRate;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TaxRates Model
 *
 * @method \App\Model\Entity\TaxRate get($primaryKey, $options = [])
 * @method \App\Model\Entity\TaxRate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TaxRate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TaxRate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TaxRate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TaxRate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TaxRate findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TaxRatesTable extends Table
{
    const COUNTY_INCOME = 1;
    const STATE_INCOME = 2;
    const PROPERTY = 3;
    const COUNTY_LOWEST_SALES = 4;
    const COUNTY_HIGHEST_SALES = 5;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tax_rates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('DataCategories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Counties', [
            'foreignKey' => 'loc_id'
        ]);
        $this->belongsTo('States', [
            'foreignKey' => 'loc_id'
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
            ->scalar('loc_type')
            ->requirePresence('loc_type', 'create')
            ->notEmpty('loc_type');

        $validator
            ->decimal('value')
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add(function ($entity, $options) use ($rules) {
            if ($entity->loc_type == 'state') {
                return $rules->existsIn('loc_id', 'States', 'Unknown state')($entity, $options);
            }

            if ($entity->loc_type == 'county') {
                return $rules->existsIn('loc_id', 'Counties', 'Unknown county')($entity, $options);
            }

            // Unknown location type
            throw new InternalErrorException('Unknown location type: ' . $entity->loc_type);
        }, 'locationExists');

        $rules->add($rules->existsIn(['category_id'], 'DataCategories'));

        return $rules;
    }

    /**
     * Returns state income tax rate
     *
     * @param int $stateId State ID
     * @return float
     * @throws NotFoundException
     */
    public function getStateIncomeTaxRate($stateId)
    {
        /** @var TaxRate $result */
        $result = $this->find()
            ->select(['value'])
            ->where([
                'loc_type' => 'state',
                'loc_id' => $stateId,
                'category_id' => TaxRatesTable::STATE_INCOME
            ])
            ->first();

        if (! $result) {
            throw new NotFoundException('State income tax rate not found for state #' . $stateId);
        }

        return $result->value;
    }

    /**
     * Returns county income tax rate
     *
     * @param int $countyId County ID
     * @return float
     * @throws NotFoundException
     */
    public function getCountyIncomeTaxRate($countyId)
    {
        /** @var TaxRate $result */
        $result = $this->find()
            ->select(['value'])
            ->where([
                'loc_type' => 'county',
                'loc_id' => $countyId,
                'category_id' => TaxRatesTable::COUNTY_INCOME
            ])
            ->first();

        if (! $result) {
            throw new NotFoundException('County income tax rate not found for county #' . $countyId);
        }

        return $result->value;
    }

    /**
     * Returns the property tax rate for the specified county
     *
     * @param int $countyId County ID
     * @return float
     * @throws NotFoundException
     */
    public function getPropertyTaxRate($countyId)
    {
        /** @var TaxRate $result */
        $result = $this->find()
            ->select(['value'])
            ->where([
                'loc_type' => 'county',
                'loc_id' => $countyId,
                'category_id' => TaxRatesTable::PROPERTY
            ])
            ->first();

        if (! $result) {
            throw new NotFoundException('Property tax rate not found for county #' . $countyId);
        }

        return $result->value;
    }

    /**
     * Returns the min and max sales tax rates for the specified expenditure type and state
     *
     * @param string $type Expenditure type
     * @param string $stateAbbrev State abbreviation
     * @param int $countyId County ID
     * @return array
     * @throws NotFoundException
     */
    public function getSalesTaxRate($type, $stateAbbrev, $countyId)
    {
        if ($stateAbbrev === 'IN') {
            if ($type === 'food at home') {
                return ['min' => 0, 'max' => 0];
            }

            return ['min' => 7, 'max' => 7];
        }

        if ($stateAbbrev === 'IL') {
            if ($type === 'food at home') {
                return ['min' => 1, 'max' => 1];
            }

            /** @var TaxRate $lowestRate */
            $lowestRate = $this->find()
                ->select(['value'])
                ->where([
                    'loc_type' => 'county',
                    'loc_id' => $countyId,
                    'category_id' => TaxRatesTable::COUNTY_LOWEST_SALES
                ])
                ->first();

            /** @var TaxRate $highestRate */
            $highestRate = $this->find()
                ->select(['value'])
                ->where([
                    'loc_type' => 'county',
                    'loc_id' => $countyId,
                    'category_id' => TaxRatesTable::COUNTY_HIGHEST_SALES
                ])
                ->first();

            return [
                'min' => $lowestRate->value,
                'max' => $highestRate->value
            ];
        }

        throw new NotFoundException('Unsupported state: ' . $stateAbbrev);
    }
}
