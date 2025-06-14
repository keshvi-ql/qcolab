<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TechnologiesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TechnologiesTable Test Case
 */
class TechnologiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TechnologiesTable
     */
    protected $Technologies;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Technologies',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Technologies') ? [] : ['className' => TechnologiesTable::class];
        $this->Technologies = $this->getTableLocator()->get('Technologies', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Technologies);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TechnologiesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
