<?php
namespace App\Test\TestCase\Calculator;

use App\Calculator\Calculator;
use App\Model\Table\TaxRatesTable;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;

class CalculatorTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.counties',
        'app.states',
        'app.tax_rates'
    ];

    private $data = [
        'from-county' => 93,
        'to-county' => 1,
        'home-value-before' => 250000,
        'home-value-after' => 250000,
        'income' => 55000,
        'dependents' => 0,
        'is_married' => 0
    ];

    /** @var Calculator */
    private $calculator;

    public function setUp()
    {
        parent::setUp();
        $this->calculator = new Calculator($this->data);
    }

    /**
     * Tests Calculator::cleanNumber()
     *
     * @return void
     */
    public function testCleanNumber()
    {
        $dirtyNumber = '$1,234.56 USD';
        $result = Calculator::cleanNumber($dirtyNumber);
        $this->assertEquals('1234', $result);
    }

    /**
     * Tests Calculator::calculateTaxes()
     *
     * @return void
     */
    public function testCalculateTaxes()
    {
        $calculator = $this->calculator;
        $result = $calculator->calculateTaxes();
        $expected = [
            'state' => ['before' => 1980.9375, 'after' => 1744.2],
            'county' => ['before' => 0, 'after' => 876.96],
            'property' => ['before' => 1398.6, 'after' => 1332.5],
            'sales' => [
                'food at home' => [
                    'before' => ['min' => 36.300000000000004, 'max' => 36.300000000000004],
                    'after' => ['min' => 0.0, 'max' => 0.0]
                ],
                'food away from home' => [
                    'before' => ['min' => 173.03, 'max' => 212.96,],
                    'after' => ['min' => 186.34000000000003, 'max' => 186.34000000000003,],
                ],
                'housekeeping supplies' => [
                    'before' => ['min' => 37.752, 'max' => 46.464000000000006],
                    'after' => ['min' => 40.656000000000006, 'max' => 40.656000000000006]
                ], 'apparel and services' => [
                    'before' => ['min' => 97.52600000000001, 'max' => 120.03200000000001],
                    'after' => ['min' => 105.02800000000002, 'max' => 105.02800000000002]
                ],
                'household furnishings and equipment' => [
                    'before' => ['min' => 97.52600000000001, 'max' => 120.03200000000001],
                    'after' => ['min' => 105.02800000000002, 'max' => 105.02800000000002],
                ],
                'personal care products' => [
                    'before' => ['min' => 37.752, 'max' => 46.464000000000006,],
                    'after' => ['min' => 40.656000000000006, 'max' => 40.656000000000006]
                ],
                'total' => [
                    'before' => ['min' => 479.8860000000001, 'max' => 582.2520000000002],
                    'after' => ['min' => 477.7080000000001, 'max' => 477.7080000000001]
                ]
            ],
            'total' => [
                'before' => ['min' => 3859.4235, 'max' => 3961.7895],
                'after' => ['min' => 4431.368, 'max' => 4431.368]
            ]
        ];

        // Flatten for easier traversal
        $expected = Hash::flatten($expected);
        $result = Hash::flatten($result);

        foreach ($expected as $key => $value) {
            // Account for floating-point math weirdness
            $expectedVal = round($value, 6);
            $actualVal = round($result[$key], 6);

            $this->assertEquals($expectedVal, $actualVal, "Tax key: $key");
        }
    }

    /**
     * Tests Calculator::calculateSavings()
     *
     * @return void
     */
    public function testCalculateSavings()
    {
        $calculator = $this->calculator;
        $before = $calculator->taxes['total']['before'];
        $after = $calculator->taxes['total']['after'];
        $expected = [
            'min' => $before['min'] - $after['max'],
            'max' => $before['max'] - $after['min']
        ];
        $result = $calculator->calculateSavings();
        $this->assertEquals($result, $expected);
    }

    /**
     * Tests Calculator::getAvgAnnualExpenditures()
     *
     * @return void
     */
    public function testGetAvgAnnualExp()
    {
        $calculator = $this->calculator;
        $calculator->income = 100000;
        $expected = $calculator->income * 78 / 100;
        $actual = $calculator->getAvgAnnualExpenditures();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Calculator::getAvgAnnualExpendituresPercent()
     *
     * @return void
     */
    public function testGetAvgAnnualExpPercent()
    {
        $calculator = $this->calculator;

        $calculator->income = 200000;
        $actualPercent = $calculator->getAvgAnnualExpendituresPercent();
        $this->assertEquals(46, $actualPercent);

        $calculator->income = 200001;
        $actualPercent = $calculator->getAvgAnnualExpendituresPercent();
        $this->assertEquals(46, $actualPercent);

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
        foreach ($values as $incomeLimit => $expectedPercent) {
            // Test that the expected percent is returned for incomes equal to or less than each limit
            foreach ([0, 1] as $subtrahend) {
                $calculator->income = $incomeLimit - $subtrahend;
                if ($calculator->income == 200000) {
                    continue;
                }

                $actualPercent = $calculator->getAvgAnnualExpendituresPercent();
                $this->assertEquals($expectedPercent, $actualPercent);
            }
        }
    }

    /**
     * Tests Calculator::getSalesTaxTypes()
     *
     * @return void
     */
    public function testGetSalesTaxTypes()
    {
        $expected = [
            'food at home',
            'food away from home',
            'housekeeping supplies',
            'apparel and services',
            'household furnishings and equipment',
            'personal care products'
        ];
        $actual = Calculator::getSalesTaxTypes();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Calculator::getAGI()
     *
     * @return void
     */
    public function testGetAgi()
    {
        $calculator = $this->calculator;

        foreach (['IN', 'IL'] as $state) {
            $exemptions = $calculator->getExemptionsTotal($state);
            $adjustedIncome = $calculator->income - $exemptions;
            $expected = max(0, $adjustedIncome);
            $actual = $calculator->getAGI($state);
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Calculator::getExemptionsTotal()
     *
     * @return void
     */
    public function testExemptionsTotal()
    {
        $calculator = $this->calculator;

        foreach ([0, 1] as $dependents) {
            $calculator->dependents = $dependents;
            foreach ([true, false] as $married) {
                $calculator->isMarried = $married;

                $expected = (1500 * $calculator->dependents) + ($calculator->isMarried ? 1000 : 0) + 1000;
                $actual = $calculator->getExemptionsTotal('IN');
                $this->assertEquals($expected, $actual);

                $expected = ($calculator->dependents + ($calculator->isMarried ? 1 : 0) + 1) * 2175;
                $actual = $calculator->getExemptionsTotal('IL');
                $this->assertEquals($expected, $actual);
            }
        }

        $this->expectException(NotFoundException::class);
        $calculator->getExemptionsTotal('invalid state');
    }

    /**
     * Tests Calculator::getStateIncomeTax()
     *
     * @return void
     */
    public function testGetStateIncomeTax()
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $calculator = $this->calculator;
        $states = [
            13 => 'IL',
            14 => 'IN'
        ];
        foreach ($states as $stateId => $stateAbbrev) {
            $agi = $calculator->getAGI($stateAbbrev);
            $rate = $taxRatesTable->getStateIncomeTaxRate($stateId);
            $expected = $agi * ($rate / 100);
            $actual = $calculator->getStateIncomeTax($agi, $stateId);
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Calculator::getCountyIncomeTax()
     *
     * @return void
     */
    public function testGetCountyIncomeTax()
    {
        /** @var TaxRatesTable $taxRatesTable */
        $taxRatesTable = TableRegistry::get('TaxRates');
        $calculator = $this->calculator;
        $states = [
            13 => 'IL',
            14 => 'IN'
        ];
        foreach ($states as $stateId => $stateAbbrev) {
            $agi = $calculator->getAGI($stateAbbrev);
            $rate = $taxRatesTable->getCountyIncomeTaxRate($stateId);
            $expected = $agi * ($rate / 100);
            $actual = $calculator->getCountyIncomeTax($agi, $stateId);
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Calculator::getPropertyTax()
     *
     * @return void
     */
    public function testGetPropertyTax()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getNetAHV()
     *
     * @return void
     */
    public function testGetNetAhv()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getRHV()
     *
     * @return void
     */
    public function testGetRhv()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getSHD()
     *
     * @return void
     */
    public function testGetShd()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getSalesTax()
     *
     * @return void
     */
    public function testGetSalesTax()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getExpenditureRate()
     *
     * @return void
     */
    public function testGetExpenditureRate()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getFormulas()
     *
     * @return void
     */
    public function testGetFormulas()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getExemptionsFormula()
     *
     * @return void
     */
    public function testGetExemptionsFormula()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getSHDFormula()
     *
     * @return void
     */
    public function testGetShdFormula()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getRHVFormula()
     *
     * @return void
     */
    public function testGetRhvFormula()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getAHVFormula()
     *
     * @return void
     */
    public function testGetAhvFormula()
    {
        $this->markTestIncomplete();
    }
}
