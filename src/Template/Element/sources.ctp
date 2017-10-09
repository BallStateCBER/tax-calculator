<?php $sources = [
	'General' => [
		'http://www.bls.gov/cex/' => 'Consumer Expenditure Survey'
	],
	'Illinois' => [
        'http://mytax.illinois.gov' => 'Illinois Sales Tax Rate (2017)',
		'http://tax.illinois.gov/taxforms/IncmCurrentYear/Individual/index.htm' => 'Individual Illinois Income Tax Forms (2010)',
		'http://www.taxfoundation.org/taxdata/topic/89.html' => 'Illinois Property Taxes'
	],
	'Indiana' => [
		'http://www.in.gov/legislative/pdf/TaxHandbook10_online.pdf' => 'Indiana Handbook of Taxes, Revenues, and Appropriations (FY 2010)',
		'http://www.in.gov/dor/4439.htm' => 'Individual Income Tax Forms (2010)'
	]
]; ?>
<div id="sources">
    <?php foreach ($sources as $category => $cSources): ?>
        <h3>
            <?= $category ?>
        </h3>
        <ul>
            <?php foreach ($cSources as $url => $title): ?>
                <li>
                    <?= $this->Html->link($title, $url) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
