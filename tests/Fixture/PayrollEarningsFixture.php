<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PayrollEarningsFixture
 */
class PayrollEarningsFixture extends TestFixture
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
                'payroll_id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'amount' => 1.5,
                'created' => 1731045816,
                'modified' => 1731045816,
                'deleted' => 1,
            ],
        ];
        parent::init();
    }
}
