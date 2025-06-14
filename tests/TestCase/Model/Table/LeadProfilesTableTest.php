<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LeadProfilesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LeadProfilesTable Test Case
 */
class LeadProfilesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LeadProfilesTable
     */
    protected $LeadProfiles;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.LeadProfiles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('LeadProfiles') ? [] : ['className' => LeadProfilesTable::class];
        $this->LeadProfiles = $this->getTableLocator()->get('LeadProfiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->LeadProfiles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\LeadProfilesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
