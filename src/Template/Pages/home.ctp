<?php
/**
 * @var \Cake\View\View $this
 * @var \App\Form\CalculatorForm $calculatorForm
 * @var string[] $counties
 */
?>

<div id="calc-input" class="row">
    <div class="col-sm-4 col-md-offset-4">
        <?= $this->Form->create($calculatorForm, ['id' => 'calculator-input']); ?>

        <?= $this->Form->control('from-county', [
            'label' => 'What Illinois county are you moving from?',
            'options' => $counties['IL']
        ]); ?>

        <?= $this->Form->control('to-county', [
            'label' => 'What Indiana county are you moving to?',
            'options' => $counties['IN']
        ]); ?>

        <?php $this->Form->setTemplates(require(ROOT . DS . 'config' . DS . 'bootstrap_currency_form.php')); ?>

        <label for="calc-input-home-value-before">What is the value of your home in Illinois?</label>
        <?= $this->Form->control('home-value-before', [
            'label' => 'What is the value of your home in Illinois?',
            'value' => isset($homeValue) ? $homeValue : null,
            'id' => 'calc-input-home-value-before'
        ]); ?>

        <label for="calc-input-home-value-after">What is the value of your home in Indiana?</label>
        <?= $this->Form->control('home-value-after', [
            'label' => 'What is the value of your home in Indiana?',
            'value' => isset($homeValue) ? $homeValue : null,
            'id' => 'calc-input-home-value-after',
            'type' => 'number',
            'min' => 1,
            'required' => true
        ]); ?>

        <label for="calc-input-income">What is your household's annual income?</label>
        <?= $this->Form->control('income', [
            'label' => 'What is your household\'s annual income?',
            'value' => isset($income) ? $income : null,
            'id' => 'calc-input-income',
            'type' => 'number',
            'min' => 1,
            'required' => true
        ]); ?>

        <?php $this->Form->setTemplates(require(ROOT . DS . 'config' . DS . 'bootstrap_form.php')); ?>

        <?= $this->Form->control('dependents', [
            'label' => 'How many dependents can you claim on your tax return?',
            'options' => [
                'None',
                1,
                2,
                3,
                '4 or more'
            ],
            'value' => isset($dependents) ? $dependents : null
        ]); ?>

        <?= $this->Form->control('is_married', [
            'label' => 'Are you married?',
            'type' => 'radio',
            'options' => [
                1 => 'Yes',
                0 => 'No'
            ]
        ]); ?>

        <?= $this->Form->submit('Calculate Tax Savings') ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php $this->append('buffered'); ?>
    calculatorInput.init();
<?php $this->end(); ?>
