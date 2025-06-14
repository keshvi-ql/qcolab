<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PartialLeavesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PartialLeavesTable Test Case
 */
class PartialLeavesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PartialLeavesTable
     */
    protected $PartialLeaves;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.PartialLeaves',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('PartialLeaves') ? [] : ['className' => PartialLeavesTable::class];
        $this->PartialLeaves = $this->getTableLocator()->get('PartialLeaves', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->PartialLeaves);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\PartialLeavesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
