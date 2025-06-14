<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PayrollDeductionsFixture
 */
class PayrollDeductionsFixture extends TestFixture
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
                'created' => 1731045794,
                'modified' => 1731045794,
                'deleted' => 1,
            ],
        ];
        parent::init();
    }
}
