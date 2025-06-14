<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payroll Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property float $total_working_days
 * @property float $days_present
 * @property float $paid_leaves
 * @property float $unpaid_leaves
 * @property string $deduction_of_leaves
 * @property string $basic_salary
 * @property float $total_balance_leaves
 * @property string $net_payable
 * @property string|null $employee_code
 * @property string|null $pan_number
 * @property string|null $bank_name
 * @property string|null $bank_account_number
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\PayrollDeduction[] $payroll_deductions
 * @property \App\Model\Entity\PayrollEarning[] $payroll_earnings
 */
class Payroll extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'user_id' => true,
        'month' => true,
        'total_working_days' => true,
        'days_present' => true,
        'paid_leaves' => true,
        'unpaid_leaves' => true,
        'deduction_of_leaves' => true,
        'basic_salary' => true,
        'total_balance_leaves' => true,
        'net_payable' => true,
        'employee_code' => true,
        'pan_number' => true,
        'bank_name' => true,
        'bank_account_number' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'payroll_deductions' => true,
        'payroll_earnings' => true,
    ];
}
