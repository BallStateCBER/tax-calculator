<?php
namespace App\Test\TestCase\Calculator;

use App\Calculator\Calculator;
use Cake\TestSuite\TestCase;

class CalculatorTest extends TestCase
{
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
        $this->markTestIncomplete();
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
