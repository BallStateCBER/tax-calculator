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
}
