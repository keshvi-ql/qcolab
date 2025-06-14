<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LeadSourcesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LeadSourcesTable Test Case
 */
class LeadSourcesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LeadSourcesTable
     */
    protected $LeadSources;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.LeadSources',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LeadSources') ? [] : ['className' => LeadSourcesTable::class];
        $this->LeadSources = $this->getTableLocator()->get('LeadSources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LeadSources);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LeadSourcesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
