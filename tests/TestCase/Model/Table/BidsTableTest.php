<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BidsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BidsTable Test Case
 */
class BidsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BidsTable
     */
    protected $Bids;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Bids',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Bids') ? [] : ['className' => BidsTable::class];
        $this->Bids = $this->getTableLocator()->get('Bids', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Bids);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\BidsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
