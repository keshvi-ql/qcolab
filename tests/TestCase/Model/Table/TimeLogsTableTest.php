<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TimeLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TimeLogsTable Test Case
 */
class TimeLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TimeLogsTable
     */
    protected $TimeLogs;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.TimeLogs',
        'app.Users',
        'app.Pauses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TimeLogs') ? [] : ['className' => TimeLogsTable::class];
        $this->TimeLogs = $this->getTableLocator()->get('TimeLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TimeLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TimeLogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TimeLogsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
