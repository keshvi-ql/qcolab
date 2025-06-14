<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PayrollFixture
 */
class PayrollFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'payroll';
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
                'month' => 'Lorem ipsum dolor sit a',
                'total_working_days' => 1,
                'days_present' => 1,
                'paid_leaves' => 1,
                'unpaid_leaves' => 1,
                'deduction_of_leaves' => 1.5,
                'basic_salary' => 1.5,
                'total_balance_leaves' => 1,
                'net_payable' => 1.5,
                'employee_code' => 'Lorem ipsum dolor sit amet',
                'pan_number' => 'Lorem ipsum dolor sit amet',
                'bank_name' => 'Lorem ipsum dolor sit amet',
                'bank_account_number' => 'Lorem ipsum dolor sit amet',
                'created' => 1731045650,
                'modified' => 1731045650,
            ],
        ];
        parent::init();
    }
}
