<?php
    $sources = [
        'General' => [
            'http://www.bls.gov/cex/' => 'Consumer Expenditure Survey'
        ],
        'Illinois' => [
            'http://mytax.illinois.gov' => 'Illinois Sales Tax Rate (2017)',
            'http://revenue.state.il.us/Publications/Bulletins/2017/FY-2017-12.pdf' => 'What\'s New for Illinois Income Tax (2017)',
            'http://tax-rates.org/illinois/property-tax' => 'Illinois Property Taxes (2017)'
        ],
        'Indiana' => [
            'Indiana Handbook of Taxes Revenues and Appropriations',
            'http://in.gov/dor/5627.htm' => 'Individual income tax rate changes announced for 2017',
            'http://stats.indiana.edu/dms4/propertytaxes.asp' => 'Indiana County Property Taxes (2017)',
            'http://www.tax-rates.org/indiana/sales-tax' => 'Indiana State Sales Tax (2017)'
        ]
    ];
?>
<div id="sources">
    <?php foreach ($sources as $category => $cSources): ?>
        <h3>
            <?= $category ?>
        </h3>
        <ul>
            <?php foreach ($cSources as $url => $title): ?>
                <li>
                    <?php if (is_numeric($url)): ?>
                        <?= $title ?>
                    <?php else: ?>
                        <?= $this->Html->link($title, $url) ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
