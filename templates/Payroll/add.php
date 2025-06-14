<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payroll $payroll
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Payroll'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="payroll form content">
            <?= $this->Form->create($payroll) ?>
            <fieldset>
                <legend><?= __('Add Payroll') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('month');
                    echo $this->Form->control('total_working_days');
                    echo $this->Form->control('days_present');
                    echo $this->Form->control('paid_leaves');
                    echo $this->Form->control('unpaid_leaves');
                    echo $this->Form->control('deduction_of_leaves');
                    echo $this->Form->control('basic_salary');
                    echo $this->Form->control('total_balance_leaves');
                    echo $this->Form->control('net_payable');
                    echo $this->Form->control('employee_code');
                    echo $this->Form->control('pan_number');
                    echo $this->Form->control('bank_name');
                    echo $this->Form->control('bank_account_number');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
