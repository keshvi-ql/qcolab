<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BalanceLeavesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BalanceLeavesTable Test Case
 */
class BalanceLeavesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BalanceLeavesTable
     */
    protected $BalanceLeaves;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BalanceLeaves',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BalanceLeaves') ? [] : ['className' => BalanceLeavesTable::class];
        $this->BalanceLeaves = $this->getTableLocator()->get('BalanceLeaves', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BalanceLeaves);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\BalanceLeavesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\BalanceLeavesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
