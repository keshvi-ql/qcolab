<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GeneralFilesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GeneralFilesTable Test Case
 */
class GeneralFilesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GeneralFilesTable
     */
    protected $GeneralFiles;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.GeneralFiles',
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
        $config = $this->getTableLocator()->exists('GeneralFiles') ? [] : ['className' => GeneralFilesTable::class];
        $this->GeneralFiles = $this->getTableLocator()->get('GeneralFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->GeneralFiles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\GeneralFilesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\GeneralFilesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
