<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BalanceLeavesFixture
 */
class BalanceLeavesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                't_balance_leave' => 1,
                'created' => 1731061788,
                'modified' => 1731061788,
            ],
        ];
        parent::init();
    }
}
