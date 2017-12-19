<?php
    /** @var \App\Calculator\Calculator $calculator */
    $formulas = $calculator->getFormulas();
?>
<div id="formulas-used">
	<div>
		<div>
			<table class="calc-output">
				<thead>
					<tr>
						<td></td>
						<th>
							Moving from
                            <br />
							<?= $calculator->countyNames['before'] ?>
						</th>
						<th>
							Moving to
                            <br />
							<?= $calculator->countyNames['after'] ?>
						</th>
					</tr>
				</thead>
				<tbody class="formulas">
					<tr>
						<th>
                            Total Tax Exemptions
                        </th>
						<td>
                            <?= $formulas['exemptions']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['exemptions']['after'] ?>
                        </td>
					</tr>
                    <tr>
                        <th>
                            Adjusted Gross Income (AGI)
                        </th>
                        <td>
                            <?= $formulas['agi']['before'] ?>
                        </td>
                        <td>
                            <?= $formulas['agi']['after'] ?>
                        </td>
                    </tr>
					<tr>
						<th>
                            State taxes
                        </th>
						<td>
                            <?= $formulas['taxes']['state']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['taxes']['state']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            County taxes
                        </th>
						<td>
                            <?= $formulas['taxes']['county']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['taxes']['county']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            Remainder Home Value (RHV)
                        </th>
						<td>
                            <?= $formulas['rhv']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['rhv']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            Supplemental Homestead Deduction (SHD)
                        </th>
						<td>
                            <?= $formulas['shd']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['shd']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            Net Adjusted Home Value (Net AHV)
                        </th>
						<td>
                            <?= $formulas['net_ahv']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['net_ahv']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            Property taxes
                        </th>
						<td>
                            <?= $formulas['taxes']['property']['before'] ?>
                        </td>
						<td>
                            <?= $formulas['taxes']['property']['after'] ?>
                        </td>
					</tr>
					<tr>
						<th>
                            Average Annual Expenditures (AAE)
                        </th>
						<td>
                            <?= $formulas['aae'] ?>
                        </td>
						<td>
                            <?= $formulas['aae'] ?>
                        </td>
					</tr>
					<?php foreach ($calculator->getSalesTaxTypes() as $salesTaxType): ?>
						<tr>
							<th colspan="3" class="sales-tax-type">
								<?= ucfirst($salesTaxType) ?>
							</th>
						</tr>
						<tr>
							<th>
                                Estimated Expenditures
                            </th>
							<td>
                                <?= $formulas['expenditures'][$salesTaxType] ?>
                            </td>
							<td>
                                <?= $formulas['expenditures'][$salesTaxType] ?>
                            </td>
						</tr>
						<tr>
							<th>
                                Sales tax paid
                            </th>
							<td>
                                <?= $formulas['taxes']['sales'][$salesTaxType]['before'] ?>
                            </td>
							<td>
                                <?= $formulas['taxes']['sales'][$salesTaxType]['after'] ?>
                            </td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p class="calc_footnote">
				All values estimated.
			</p>
		</div>
	</div>
</div>
