<?php
    $footnotes = [
        'Income Tax' => [
            'Indiana & Illinois' => [
                "Household Income assessed at imputed value less the applicable personal and dependency exemptions. CBER has excluded all Add-Backs, Deductions, Other Taxes, and exemptions for the blind and individuals over 65. CBER has also elected to exclude all Tax Credits for the purpose of calculating income tax due.",
                "Household Income is defined as total receipts (salary, tips, professional fees, etc.) less non-income items, which is the equivalent to Federal adjusted gross income.",
                "Indiana has an additional deduction for dependents under 18 of $1500 which is not included, but would apply to several dependency incidences.",
            ]
        ],
        'Property Tax' => [
            'Indiana' => [
                "Home value entered by user is assumed to be Assessed Gross Home Value",
                "In order to calculate the assessed home value, there is an adjustment for the Homestead Standard Deduction Credit and also the Supplemental Homestead Deduction. The assessed home value is then multiplied by the property tax rate. Although this adjustment is made to all participants, this adjustment is only made in reality if you qualify according to the Indiana Code. As required to qualify, your Indiana homestead must be your primary residence. Thus, our calculations are based on the premises that you receive the Homestead Standard Deduction Credit and also the Supplemental Homestead Deduction.",
                "According to Indiana legislation, all property taxes have been capped at 1%. Thus, the maximum property tax bill for your homestead is equal to 1% of the home value.",
                "Property taxes are calculated by district, thus the county rates are an estimate of the exact value. These rates do not include any local tax credits.",
                "CBER has not considered township-based property tax reliefs or tax cap increases due to referendum."
            ],
            'Illinois' => [
                "Rates reflect the fact that residential property is assessed at 33% of its market value (excluding Cook County which is assessed at 10%)."
            ]
        ],
        'Sales Tax' => [
            'Indiana & Illinois' => [
                "The Consumer Expenditure Survey was used to determine the value of expenditures and also how expenditures were distributed amongst food, housekeeping supplies, personal care items, etc. These expenditures were then used to evaluate sales tax expense.",
                "Since Illinois has a local option sales tax, the sales tax expense is determined based on a countywide range. This reflects the minimum and maximum total sales tax rate of a county in the state.  For a more precise determination of sales tax rates please visit <a href=\"https://www.revenue.state.il.us/app/trii/\">https://www.revenue.state.il.us/app/trii/</a>."
            ]
        ]
    ];
?>

<div id="footnotes">
    <?php foreach ($footnotes  as $category => $locations): ?>
        <h3>
            <?= $category ?>
        </h3>
        <ul>
            <?php foreach ($locations as $location => $lFootnotes): ?>
                <li>
                    <?= $location ?>
                    <ul>
                        <?php foreach ($lFootnotes as $footnote): ?>
                            <li>
                                <?= $footnote ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>
