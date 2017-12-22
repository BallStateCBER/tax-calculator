<?php
namespace App\Test\TestCase\Calculator;

use App\Calculator\Calculator;
use Cake\TestSuite\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.counties',
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
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::calculateSavings()
     *
     * @return void
     */
    public function testCalculateSavings()
    {
        $calculator = new Calculator($this->data);
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
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getAvgAnnualExpendituresPercent()
     *
     * @return void
     */
    public function testGetAvgAnnualExpPercent()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getSalesTaxTypes()
     *
     * @return void
     */
    public function testGetSalesTaxTypes()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getAGI()
     *
     * @return void
     */
    public function testGetAgi()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getExemptionsTotal()
     *
     * @return void
     */
    public function testExemptionsTotal()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getStateIncomeTax()
     *
     * @return void
     */
    public function testGetStateIncomeTax()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests Calculator::getCountyIncomeTax()
     *
     * @return void
     */
    public function testGetCountyIncomeTax()
    {
        $this->markTestIncomplete();
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
