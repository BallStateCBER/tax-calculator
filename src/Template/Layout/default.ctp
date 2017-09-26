<?php
    /**
     * @var \App\View\AppView $this
     */
    $this->extend('DataCenter.default');
    $this->assign('sidebar', $this->element('sidebar'));
    $this->Html->script('script.js', ['block' => 'scriptBottom']);
?>

<?php $this->start('subsite_title'); ?>
    <h1 id="subsite_title" class="max_width">
        <a href="/">
            <img src="/img/ILINTaxSavings.jpg" alt="Illinois-to-Indiana Tax Savings Calculator" />
        </a>
    </h1>
<?php $this->end(); ?>

<?php $this->append('scriptBottom'); ?>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>$.fn.modal || document.write('<script src="/js/bootstrap.min.js"><\/script>')</script>
<?php $this->end(); ?>

<div id="content">
    <?= $this->element('DataCenter.flash_messages_bootstrap') ?>
    <?= $this->fetch('content') ?>
</div>
