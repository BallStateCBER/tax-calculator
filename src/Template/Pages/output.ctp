<div id="output-wrapper">
    <h1 class="page-header">
        Estimated Annual Tax Savings
    </h1>
    <table class="calc-output table">
        <thead>
            <tr>
                <td></td>
                <th class="display_mode">
                    Moving from <?= $countyName['before'] ?> County, IL
                </th>
                <th class="display_mode">
                    Moving to <?= $countyName['after'] ?> County, IN
                </th>
            </tr>
        </thead>
        <tfoot></tfoot>
        <tbody class="input">
            <tr>
                <th>Household Income</th>
                <td class="display_mode">
                    <?= $this->Calculator->moneyFormat($income) ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <th>Dependents</th>
                <td class="display_mode">
                    <?= $dependents ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <th>Home Value</th>
                <td class="display_mode">
                    <?= $this->Calculator->moneyFormat($homeValues['before']) ?>
                </td>
                <td class="display_mode">
                    <?= $this->Calculator->moneyFormat($homeValues['after']) ?>
                </td>
            </tr>
        </tbody>
        <tbody class="output">
            <tr>
                <th>
                    State taxes
                </th>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['state']['before']) ?>
                </td>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['state']['after']) ?>
                </td>
            </tr>
            <tr>
                <th>
                    County taxes
                </th>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['county']['before']) ?>
                </td>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['county']['after']) ?>
                </td>
            </tr>
            <tr>
                <th>
                    Property taxes
                </th>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['property']['before']) ?>
                </td>
                <td>
                    <?= $this->Calculator->moneyFormat($taxes['property']['after']) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <a href="#" id="toggle-sales-taxes" title="Show more details">
                        Sales taxes...
                    </a>
                </th>
                <td>
                    <?php
                        $before = $taxes['sales']['total']['before'];
                        echo $this->Calculator->formatMinMaxValue($before['min'], $before['max']);
                    ?>
                </td>
                <td>
                    <?php
                        $after = $taxes['sales']['total']['after'];
                        echo $this->Calculator->formatMinMaxValue($after['min'], $after['max']);
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="sales-tax-breakdown-wrapper">
        <table class="calc-output" id="sales-tax-breakdown">
            <thead></thead>
            <tfoot></tfoot>
            <tbody>
                <?php foreach ($salesTaxTypes as $salesTaxType): ?>
                    <tr>
                        <th>
                            On <?= $salesTaxType ?>
                        </th>
                        <td>
                            <?php
                                $before = $taxes['sales'][$salesTaxType]['before'];
                                echo $this->Calculator->formatMinMaxValue($before['min'], $before['max']);
                            ?>
                        </td>
                        <td>
                            <?php
                                $after = $taxes['sales'][$salesTaxType]['after'];
                                echo $this->Calculator->formatMinMaxValue($after['min'], $after['max']);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <table class="calc-output" id="calc_totals">
        <thead></thead>
        <tfoot class="savings">
        <tr>
            <th>
                Annual tax savings
            </th>
            <td colspan="2">
                <?= $this->Calculator->formatMinMaxValue($savings['min'], $savings['max']) ?>
            </td>
        </tr>
        </tfoot>
        <tbody class="total_taxes">
        <tr>
            <th>
                Total annual taxes
            </th>
            <td>
                <?php
                    $before = $taxes['total']['before'];
                    echo $this->Calculator->formatMinMaxValue($before['min'], $before['max']);
                ?>
            </td>
            <td>
                <?php
                    $after = $taxes['total']['after'];
                    echo $this->Calculator->formatMinMaxValue($after['min'], $after['max']);
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div id="additional-info">
	<h2>
        <button id="toggle-formulas" class="btn btn-lg btn-default">
            How was this calculated?
        </button>
	</h2>
	<?= $this->element('formulas_used') ?>

	<h2>
        <button id="toggle-sources" class="btn btn-lg btn-default">
            Data Sources
        </button>
	</h2>
	<?= $this->element('sources') ?>

	<h2>
        <button id="toggle-footnotes" class="btn btn-lg btn-default">
            Footnotes
        </button>
	</h2>
	<?= $this->element('footnotes') ?>
	
	<h2>
        <button id="toggle-resources" class="btn btn-lg btn-default">
            Additional Resources
        </button>
	</h2>
	<?= $this->element('resources') ?>
</div>

<?php $this->append('buffered'); ?>
    calculatorOutput.init();
<?php $this->end(); ?>
