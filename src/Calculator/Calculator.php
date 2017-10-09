<?php
namespace App\Calculator;

use App\Model\Table\StatesTable;
use App\Model\Table\TaxRatesTable;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class Calculator
{
    /**
     * Conducts tax savings calculation and returns various output
     *
     * @param array $input Keys expected:
     *     from_county, to_county, income, dependents, home_value_before, home_value_after
     * @return array
     */
    public function calculate($input) {
        $countyIds = [
            'before' => $input['from_county'],
            'after' => $input['to_county']
        ];
        $stateIds = [
            'before' => StatesTable::ILLINOIS,
            'after' => StatesTable::INDIANA
        ];
        $stateAbbrevs = [
            'before' => 'IL',
            'after' => 'IN'
        ];
        $homeValues = [
            'before' => $this->cleanNumber($input['home_value_before']),
            'after' => $this->cleanNumber($input['home_value_after'])
        ];
        $income = $this->cleanNumber($input['income']);
        $dependents = $input['dependents'];
        $isMarried = (bool)$input['is_married'];
        $avgAnnualExpenditures = $this->getAvgAnnualExpenditures($income);

        // ------ VALIDATE INPUT ------
        // Validate counties by attempting to retrieve their names
        $countyName = [];
        $countiesTable = TableRegistry::get('Counties');
        foreach (['before', 'after'] as $key) {
            $county = $countiesTable->get($countyIds[$key]);
            $countyName[$key] = $county->name . ' County, ' . $stateAbbrevs[$key];
        }

        // ------ GENERATE OUTPUT ------
        $taxes = [];
        $salesTaxTypes = $this->getSalesTaxTypes();
        foreach (['before', 'after'] as $key) {
            // Adjusted gross income
            $stateAbbrev = $stateAbbrevs[$key];
            $agi = $this->getAGI($income, $dependents, $isMarried, $stateAbbrev);

            // Taxes paid
            $stateId = $stateIds[$key];
            $taxes['state'][$key] = $this->getStateIncomeTax($agi, $stateId);
            $countyId = $countyIds[$key];
            $taxes['county'][$key] = $this->getCountyIncomeTax($agi, $countyId);
            $homeValue = $homeValues[$key];
            $taxes['property'][$key] = $this->getPropertyTax($homeValue, $countyId, $stateAbbrev);
            foreach ($salesTaxTypes as $salesTaxType) {
                $taxes['sales'][$salesTaxType][$key] = $this->getSalesTax(
                    $salesTaxType,
                    $income,
                    $stateAbbrev,
                    $countyId
                );
            }

            // Calculate total sales tax
            foreach (['min', 'max'] as $bound) {
                $taxes['sales']['total'][$key][$bound] = 0;
                foreach ($salesTaxTypes as $salesTaxType) {
                    $taxAmount = $taxes['sales'][$salesTaxType][$key][$bound];
                    $taxes['sales']['total'][$key][$bound] += $taxAmount;
                }
            }

            // Total taxes
            foreach (['min', 'max'] as $bound) {
                $taxes['total'][$key][$bound] =
                    $taxes['state'][$key] +
                    $taxes['county'][$key] +
                    $taxes['property'][$key] +
                    $taxes['sales']['total'][$key][$bound];
            }
        }

        // Total savings
        $before = $taxes['total']['before'];
        $after = $taxes['total']['after'];
        $savings = [
            'min' => $before['min'] - $after['max'],
            'max' => $before['max'] - $after['min']
        ];

        return compact(
            'avgAnnualExpenditures',
            'countyIds',
            'countyName',
            'dependents',
            'homeValues',
            'income',
            'salesTaxTypes',
            'savings',
            'stateAbbrevs',
            'stateIds',
            'taxes'
        );
    }

    /**
     * Takes a string and strips it to just a whole number (e.g. $50,000.99 -> 50000)
     *
     * @param string $number Numeric string
     * @return int
     */
    public function cleanNumber($number)
    {
        // Remove cents (if decimal point found)
        $decimalPoint = strpos($number, '.');
        if ($decimalPoint !== false) {
            $number = substr($number, 0, $decimalPoint);
        }

        // Remove non-numeric characters
        return (int)preg_replace('/\D/', '', $number);
    }

    /**
     * Returns the average annual expenditures in dollars for the provided income level
     *
     * @param int $income Income in dollars
     * @return float
     */
    public function getAvgAnnualExpenditures($income)
    {
        return $income * $this->getAvgAnnualExpendituresPercent($income) / 100;
    }

    /**
     * Returns the average annual expenditures as a percent of income for the provided income level
     *
     * Data current as of 2016
     *
     * @param int $income Income in dollars
     * @return float
     */
    public function getAvgAnnualExpendituresPercent($income)
    {
        // Less than or equal to $key dollars => $value percent
        $values = [
            15000 => 282,
            30000 => 144,
            40000 => 116,
            50000 => 99,
            70000 => 88,
            100000 => 78,
            150000 => 70,
            200000 => 64
        ];

        foreach ($values as $incomeLimit => $percent) {
            if ($income <= $incomeLimit) {
                return $percent;
            }
        }

        // If income >= $200,000
        return 46;
    }

    /**
     * Returns an array of sales tax types
     *
     * @return array
     */
    public function getSalesTaxTypes()
    {
        return [
            'food at home',
            'food away from home',
            'housekeeping supplies',
            'apparel and services',
            'household furnishings and equipment',
            'personal care products'
        ];
    }

    /**
     * Returns adjusted gross income
     *
     * @param int $income Income in dollars
     * @param int $dependents Number of dependents
     * @param bool $isMarried Is taxpayer married?
     * @param string $stateAbbrev State abbreviation
     * @return int
     */
    public function getAGI($income, $dependents, $isMarried, $stateAbbrev)
    {
        $exemptions = $this->getExemptionsTotal($dependents, $isMarried, $stateAbbrev);
        $adjustedIncome = $income - $exemptions;

        return max(0, $adjustedIncome);
    }

    /**
     * Returns the total exemptions in dollars, based on the number of dependents reported
     *
     * These formulas are current as of 2017
     *
     * @param int $dependents Number of dependents
     * @param bool $isMarried Is the taxpayer married?
     * @param string $stateAbbrev State abbreviation
     * @return int
     * @throws NotFoundException
     */
    public function getExemptionsTotal($dependents, $isMarried, $stateAbbrev)
    {
        if ($stateAbbrev === 'IN') {
            return (1500 * $dependents) + ($isMarried ? 1000 : 0) + 1000;
        }

        if ($stateAbbrev === 'IL') {
            return ($dependents + ($isMarried ? 1 : 0) + 1) * 2175;
        }

        throw new NotFoundException('State "' . $stateAbbrev . '" not recognized');
    }

    /**
     * Returns total state income tax in dollars
     *
     * @param int $agi Adjusted gross income
     * @param int $stateId State ID
     * @return float|int
     */
    public function getStateIncomeTax($agi, $stateId)
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $rate = $taxRatesTable->getStateIncomeTaxRate($stateId);

        return $agi * ($rate / 100);
    }

    /**
     * Returns total county income tax in dollars
     *
     * @param int $agi Adjusted gross income
     * @param int $countyId County ID
     * @return float|int
     */
    public function getCountyIncomeTax($agi, $countyId)
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $rate = $taxRatesTable->getCountyIncomeTaxRate($countyId);

        return $agi * ($rate / 100);
    }

    /**
     * Returns the total property tax in dollars
     *
     * HV: Home Value
     * Adjustment = (HV < 75k) ? (60% of HV) : (HV - 45k)
     * RV (Remainder Value) = HV - Adjustment
     * SHD (Supplemental Homestead Deduction) = (RV <= 600k) ? (35% of RV) : (25% of RV)
     * AHV (Assessed Home Value) = RV - SHD
     * Property tax = AHV * Property tax rate
     * Maximum Property tax = 1% of HV
     * Property tax due = Lesser of Property tax or Maximum Property tax
     *
     * @param int $homeValue Value of home in dollars
     * @param int $countyId County ID
     * @param string $stateAbbrev State abbreviation
     * @return float|int
     * @throws NotFoundException
     */
    public function getPropertyTax($homeValue, $countyId, $stateAbbrev)
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $rate = $taxRatesTable->getPropertyTaxRate($countyId);
        $netAhv = $this->getNetAHV($homeValue, $stateAbbrev);
        $uncappedValue = $netAhv * ($rate / 100);

        switch ($stateAbbrev) {
            case 'IN':
                $cappedValue = $homeValue * 0.01;

                return min($uncappedValue, $cappedValue);
            case 'IL':
                return $uncappedValue;
            default:
                throw new NotFoundException('Unsupported state: ' . $stateAbbrev);
        }
    }

    /**
     * Returns net adjusted home value
     *
     * @param int $homeValue Value of home in dollars
     * @param string $stateAbbrev State abbreviation
     * @return int|float
     * @throws NotFoundException
     */
    public function getNetAHV($homeValue, $stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                // Remainder home value
                $rv = $this->getRV($homeValue, $stateAbbrev);

                // Supplemental homestead deduction
                $shd = $this->getSHD($homeValue, $stateAbbrev);

                return $rv - $shd;
            case 'IL':
                return $homeValue;
            default:
                throw new NotFoundException('Unsupported state: ' . $stateAbbrev);
        }
    }

    /**
     * Returns remainder value (after standard deduction)
     *
     * @param int $homeValue Value of home in dollars
     * @param string $stateAbbrev State abbreviation
     * @return float|int
     * @throws NotFoundException
     */
    public function getRV($homeValue, $stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                return ($homeValue < 75000) ? ($homeValue * .6) : ($homeValue - 45000);
            case 'IL':
                return $homeValue;
            default:
                throw new NotFoundException('Unsupported state: ' . $stateAbbrev);
        }
    }

    /**
     * Returns Supplemental homestead deduction
     *
     * @param int $homeValue Value of home in dollars
     * @param string $stateAbbrev State abbreviation
     * @return float|int
     * @throws NotFoundException
     */
    public function getSHD($homeValue, $stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                $rv = $this->getRV($homeValue, $stateAbbrev);
                if ($rv <= 600000) {
                    return ($rv * .35);
                }

                return (600000 * .35) + (($rv - 600000) * .25);
            case 'IL':
                return 0;
            default:
                throw new NotFoundException('Unsupported state: ' . $stateAbbrev);
        }
    }

    /**
     * Returns the estimated annual sales tax in dollars
     *
     * @param string $type Expenditure type
     * @param int $income Income in dollars
     * @param string $stateAbbrev State abbreviation
     * @param int $countyId County ID
     * @return array
     */
    public function getSalesTax($type, $income, $stateAbbrev, $countyId)
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $taxRateRange = $taxRatesTable->getSalesTaxRate($type, $stateAbbrev, $countyId);
        $expenditureRate = $this->getExpenditureRate($type, $income) / 100;
        $aae = $income * $this->getAvgAnnualExpendituresPercent($income) / 100;
        $spent = $aae * $expenditureRate;

        return [
            'min' => $spent * ($taxRateRange['min'] / 100),
            'max' => $spent * ($taxRateRange['max'] / 100)
        ];
    }

    /**
     * Returns the share of income spent on the specified type of expenditure
     *
     * Data current as of 2016
     *
     * @param string $type Type of expenditure
     * @param int $income Income in dollars
     * @return float
     * @throws NotFoundException
     */
    public function getExpenditureRate($type, $income)
    {
        // Rates for various income levels < $200,000
        $incomeThresholds = [15000, 30000, 40000, 50000, 70000, 100000, 150000, 200000];
        $rates = [
            'food at home' => [10.4, 9.1, 7.6, 8.3, 7.5, 7.3, 6.6, 6.1],
            'food away from home' => [5.6, 4.8, 5.4, 5.4, 5.5, 5.6, 5.7, 6.2],
            'housekeeping supplies' => [1.6, 1.3, 1.4, 1.4, 1.2, 1.1, 1.2, 1.1],
            'apparel and services' => [3.6, 3.0, 3.3, 3.2, 3.1, 3.1, 2.9, 3.4],
            'household furnishings and equipment' => [2.6, 2.8, 3.1, 3.2, 3.1, 3.1, 3.5, 3.4],
            'personal care products' => [1.3, 1.3, 1.4, 1.2, 1.2, 1.2, 1.3, 1.3]
        ];

        foreach ($incomeThresholds as $incomeKey => $incomeThreshold) {
            if ($income < $incomeThreshold) {
                if (isset($rates[$type])) {
                    return $rates[$type][$incomeKey];
                }

                throw new NotFoundException('Unknown expenditure type "' . $type . '"');
            }
        }

        // For income >= $200,000
        $rates = [
            'food at home' => 4.5,
            'food away from home' => 5.6,
            'housekeeping supplies' => 0.7,
            'apparel and services' => 3.3,
            'household furnishings and equipment' => 3.5,
            'personal care products' => 1.1
        ];

        if (isset($rates[$type])) {
            return $rates[$type];
        }

        throw new NotFoundException('Unknown expenditure type "' . $type . '"');
    }

    /**
     * Generates formulas that explain to the user how our calculations are made
     *
     * @param array $input Array of parameters
     * @return array
     */
    public function getFormulas($input)
    {
        /**
         * @var $avgAnnualExpenditures
         * @var $countyIds
         * @var $countyName
         * @var $dependents
         * @var $homeValues
         * @var $income
         * @var $salesTaxTypes
         * @var $savings
         * @var $stateAbbrevs
         * @var $stateIds
         * @var $taxes
         */
        extract($input);

        $formulas = [];

        $formulas['aae'] = $this->getAvgAnnualExpendituresPercent($income) . '% of income';

        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        foreach (['before', 'after'] as $key) {
            $state = $stateAbbrevs[$key];
            $formulas['exemptions'][$key] = $this->getExemptionsFormula($state);

            $formulas['agi'][$key] = 'income &#8722; exemptions';

            $stateTaxRate = $taxRatesTable->getStateIncomeTaxRate($stateIds[$key]);
            $formulas['taxes']['state'][$key] = round($stateTaxRate, 2) . '% of AGI';

            $countyId = $countyIds[$key];
            $countyTaxRate = $taxRatesTable->getCountyIncomeTaxRate($countyId);
            $formulas['taxes']['county'][$key] = round($countyTaxRate, 2) . '% of AGI';

            // Property taxes
            $homeValue = $homeValues[$key];
            $formulas['rhv'][$key] = $this->getRHVFormula($homeValue, $state);
            $formulas['shd'][$key] = $this->getSHDFormula($homeValue, $state);
            $formulas['net_ahv'][$key] = $this->getAHVFormula($state);
            $propertyTaxRate = $taxRatesTable->getPropertyTaxRate($countyId);
            $percent = round($propertyTaxRate, 2);
            switch ($state) {
                case 'IN':
                    $formulas['taxes']['property'][$key] = $percent . '% of Net AHV (capped at 1% of home value)';
                    break;
                case 'IL':
                    $formulas['taxes']['property'][$key] = $percent . '% of home value';
                    break;
            }

            foreach ($salesTaxTypes as $salesTaxType) {
                $eRate = $this->getExpenditureRate($salesTaxType, $income);
                $taxRates = $taxRatesTable->getSalesTaxRate($salesTaxType, $state, $countyId);
                $taxRateString = $taxRates['min'] == $taxRates['max']
                    ? $taxRates['min'] . '%'
                    : $taxRates['min'] . '% to ' . $taxRates['max'] . '%';
                $formulas['taxes']['sales'][$salesTaxType][$key] = $taxRateString . ' of AAE';
                $formulas['expenditures'][$salesTaxType] = $eRate . '% of income';
            }
        }
        return $formulas;
    }

    /**
     * Returns the formula used to calculate tax exemptions for the specified state
     *
     * These formulas are current as of 2017
     *
     * @param string $stateAbbrev State abbreviation
     * @return string
     * @throws InternalErrorException
     */
    public function getExemptionsFormula($stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                return '$1,000 + (number of dependents &times; $1,500), + $1,000 if married';
            case 'IL':
                return '$2,175 &times; (1 + number of dependents), + $2,175 if married';
        }

        throw new InternalErrorException('Unsupported state: ' . $stateAbbrev);
    }

    /**
     * Returns the formula used to calculate Supplemental Homestead Deduction
     *
     * @param int $homeValue Home value in dollars
     * @param string $stateAbbrev State abbreviation
     * @return string
     * @throws InternalErrorException
     */
    public function getSHDFormula($homeValue, $stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                return ($this->getRV($homeValue, $stateAbbrev) <= 600000)
                    ? '35% of RHV'
                    : '$210,000 + 25% of (RHV - $600,000)';
            case 'IL':
                return '';
        }

        throw new InternalErrorException('Unsupported state: ' . $stateAbbrev);
    }

    /**
     * Returns the formula used to calculate Remainder Home Value
     *
     * @param int $homeValue Home value in dollars
     * @param string $stateAbbrev State abbreviation
     * @return string
     * @throws InternalErrorException
     */
    public function getRHVFormula($homeValue, $stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                return ($homeValue < 75000)
                    ? "60% of home value"
                    : "home value - $45,000";
            case 'IL':
                return '';
        }

        throw new InternalErrorException('Unsupported state: ' . $stateAbbrev);
    }

    /**
     * Returns the formula used to calculate Net Adjusted Home Value
     *
     * @param string $stateAbbrev State abbreviation
     * @return string
     * @throws InternalErrorException
     */
    public function getAHVFormula($stateAbbrev)
    {
        switch ($stateAbbrev) {
            case 'IN':
                return 'RHV - SHD';
            case 'IL':
                return '';
        }

        throw new InternalErrorException('Unsupported state: ' . $stateAbbrev);
    }
}
