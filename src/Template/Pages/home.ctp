<div id="calc-input">
    <div>
        <?= $this->Form->create(false, ['id' => 'initial_input_form']); ?>

        <?= $this->Form->input('from_county', [
            'label' => 'What Illinois county are you moving from?',
            'options' => $counties['IL']
        ]); ?>

        <?= $this->Form->input('to_county', [
            'label' => 'What Indiana county are you moving to?',
            'options' => $counties['IN']
        ]); ?>

        <?php $this->Form->setTemplates(require(ROOT . DS . 'config' . DS . 'bootstrap_currency_form.php')); ?>

        <label for="calc-input-home-value-before">What is the value of your home in Illinois?</label>
        <?= $this->Form->input('home_value_before', [
            'label' => 'What is the value of your home in Illinois?',
            'value' => isset($home_value) ? $home_value : null,
            'id' => 'calc-input-home-value-before'
        ]); ?>

        <label for="calc-input-home-value-after">What is the value of your home in Indiana?</label>
        <?= $this->Form->input('home_value_after', [
            'label' => 'What is the value of your home in Indiana?',
            'value' => isset($home_value) ? $home_value : null,
            'id' => 'calc-input-home-value-after',
            'type' => 'number',
            'min' => 1,
            'required' => true
        ]); ?>

        <label for="calc-input-income">What is your household's annual income?</label>
        <?= $this->Form->input('income', [
            'label' => 'What is your household\'s annual income?',
            'value' => isset($income) ? $income : null,
            'id' => 'calc-input-income',
            'type' => 'number',
            'min' => 1,
            'required' => true
        ]); ?>

        <?php $this->Form->setTemplates(require(ROOT . DS . 'config' . DS . 'bootstrap_form.php')); ?>

        <?= $this->Form->input('dependents', [
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

        <img src="/data_center/img/loading_small.gif" alt="Loading" title="Loading" id="calc-loading" />

        <?= $this->Form->submit('Calculate Tax Savings') ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php $this->append('buffered'); ?>
    setup_input();
<?php $this->end(); ?>
