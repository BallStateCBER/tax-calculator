<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TaxRatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TaxRatesTable Test Case
 */
class TaxRatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TaxRatesTable
     */
    public $TaxRates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tax_rates',
        'app.data_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TaxRates') ? [] : ['className' => TaxRatesTable::class];
        $this->TaxRates = TableRegistry::get('TaxRates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TaxRates);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
