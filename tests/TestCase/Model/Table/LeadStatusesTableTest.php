<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LeadStatusesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LeadStatusesTable Test Case
 */
class LeadStatusesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LeadStatusesTable
     */
    protected $LeadStatuses;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.LeadStatuses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LeadStatuses') ? [] : ['className' => LeadStatusesTable::class];
        $this->LeadStatuses = $this->getTableLocator()->get('LeadStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LeadStatuses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LeadStatusesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
