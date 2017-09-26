<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class CalculatorForm extends Form
{
    /**
     * Builds schema
     *
     * @param Schema $schema Schema object
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('from_county', 'string')
            ->addField('to_county', 'string')
            ->addField('home_value_before', 'number')
            ->addField('home_value_after', 'number')
            ->addField('income', 'number')
            ->addField('dependents', 'number');
    }

    /**
     * Builds validation rules
     *
     * @param Validator $validator Validator object
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        foreach (['home_value_before', 'home_value_after', 'income'] as $field) {
            $validator->add($field, 'minimum', [
                'rule' => function ($value) {
                    return $value > 0;
                },
                'message' => 'You must enter a positive number',
            ]);
        }

        return $validator;
    }
}
