<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CountiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CountiesTable Test Case
 */
class CountiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CountiesTable
     */
    public $Counties;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.counties'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Counties') ? [] : ['className' => CountiesTable::class];
        $this->Counties = TableRegistry::get('Counties', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Counties);

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
     * Tests CountiesTable::getCountyOptions()
     *
     * @return void
     */
    public function testGetCountyOptions()
    {
        /** @var CountiesTable $countiesTable */
        $countiesTable = TableRegistry::get('Counties');
        $result = $countiesTable->getCountyOptions();
        $this->assertNotEmpty($result['IN']);
        $this->assertNotEmpty($result['IL']);
    }
}
