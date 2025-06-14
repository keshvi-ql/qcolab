<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payroll $payroll
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Payroll'), ['action' => 'edit', $payroll->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Payroll'), ['action' => 'delete', $payroll->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payroll->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Payroll'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Payroll'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="payroll view content">
            <h3><?= h($payroll->month) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $payroll->hasValue('user') ? $this->Html->link($payroll->user->first_name, ['controller' => 'Users', 'action' => 'view', $payroll->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Month') ?></th>
                    <td><?= h($payroll->month) ?></td>
                </tr>
                <tr>
                    <th><?= __('Employee Code') ?></th>
                    <td><?= h($payroll->employee_code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pan Number') ?></th>
                    <td><?= h($payroll->pan_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bank Name') ?></th>
                    <td><?= h($payroll->bank_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bank Account Number') ?></th>
                    <td><?= h($payroll->bank_account_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($payroll->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Working Days') ?></th>
                    <td><?= $this->Number->format($payroll->total_working_days) ?></td>
                </tr>
                <tr>
                    <th><?= __('Days Present') ?></th>
                    <td><?= $this->Number->format($payroll->days_present) ?></td>
                </tr>
                <tr>
                    <th><?= __('Paid Leaves') ?></th>
                    <td><?= $this->Number->format($payroll->paid_leaves) ?></td>
                </tr>
                <tr>
                    <th><?= __('Unpaid Leaves') ?></th>
                    <td><?= $this->Number->format($payroll->unpaid_leaves) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deduction Of Leaves') ?></th>
                    <td><?= $this->Number->format($payroll->deduction_of_leaves) ?></td>
                </tr>
                <tr>
                    <th><?= __('Basic Salary') ?></th>
                    <td><?= $this->Number->format($payroll->basic_salary) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Balance Leaves') ?></th>
                    <td><?= $this->Number->format($payroll->total_balance_leaves) ?></td>
                </tr>
                <tr>
                    <th><?= __('Net Payable') ?></th>
                    <td><?= $this->Number->format($payroll->net_payable) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($payroll->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($payroll->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Payroll Deductions') ?></h4>
                <?php if (!empty($payroll->payroll_deductions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Payroll Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($payroll->payroll_deductions as $payrollDeduction) : ?>
                        <tr>
                            <td><?= h($payrollDeduction->id) ?></td>
                            <td><?= h($payrollDeduction->payroll_id) ?></td>
                            <td><?= h($payrollDeduction->title) ?></td>
                            <td><?= h($payrollDeduction->amount) ?></td>
                            <td><?= h($payrollDeduction->created) ?></td>
                            <td><?= h($payrollDeduction->modified) ?></td>
                            <td><?= h($payrollDeduction->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'PayrollDeductions', 'action' => 'view', $payrollDeduction->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'PayrollDeductions', 'action' => 'edit', $payrollDeduction->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'PayrollDeductions', 'action' => 'delete', $payrollDeduction->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payrollDeduction->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Payroll Earnings') ?></h4>
                <?php if (!empty($payroll->payroll_earnings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Payroll Id') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($payroll->payroll_earnings as $payrollEarning) : ?>
                        <tr>
                            <td><?= h($payrollEarning->id) ?></td>
                            <td><?= h($payrollEarning->payroll_id) ?></td>
                            <td><?= h($payrollEarning->title) ?></td>
                            <td><?= h($payrollEarning->amount) ?></td>
                            <td><?= h($payrollEarning->created) ?></td>
                            <td><?= h($payrollEarning->modified) ?></td>
                            <td><?= h($payrollEarning->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'PayrollEarnings', 'action' => 'view', $payrollEarning->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'PayrollEarnings', 'action' => 'edit', $payrollEarning->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'PayrollEarnings', 'action' => 'delete', $payrollEarning->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payrollEarning->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
