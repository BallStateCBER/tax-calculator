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
            ->addField('from-county', 'string')
            ->addField('to-county', 'string')
            ->addField('home-value_before', 'number')
            ->addField('home-value-after', 'number')
            ->addField('income', 'number')
            ->addField('dependents', 'number')
            ->addField('is_married', 'boolean');
    }

    /**
     * Builds validation rules
     *
     * @param Validator $validator Validator object
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        foreach (['home-value-before', 'home-value-after', 'income'] as $field) {
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
