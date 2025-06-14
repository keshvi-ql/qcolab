<?php

use Cake\Routing\Router;

?>
<table class="table-responsive" style="width: 100%; color: #444; border: 1px solid #f4f4f4;">
    <tr style="font-weight: semibold; background-color: #38a4f8; color:#fff; padding: 5px; font-size:25px; text-align: center; ">
        <td style="border-bottom: 1px solid lightgrey;"> QUEUELOOP SOLUTION LLP</td>
    </tr>
    <tr style="text-align: center; ">
        <td style="width:20%"> </td>
        <td style="width:60%; "><br><br> Surat, Gujrat <br></td>
        <td style="width:20%"> </td>
    </tr>
    <tr style="background-color: #0085ca; color:#fff; padding: 5px; font-size:18px; text-align: center; ">
        <td colspan="5"> Salary Slip for <?= $payroll->month ?> </td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Employee Name</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->users->first_name ?> <?= $payroll->users->last_name ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">Date of Joining</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;"><?= $payroll->users->date_of_joining ? $this->Date->format($payroll->users->date_of_joining, false) : "-"; ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Employee Code</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->employee_code ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">Total Working Days</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;"><?= $payroll->total_working_days ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Designation</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->users->job_title ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">Days Present</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;"><?= $payroll->days_present ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">PAN</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->pan_number ? $payroll->pan_number : "-" ?></td>
        <td rowspan="2" style="width:25%; border: 2px solid #fff; color:black; line-height:50px;">Leaves Taken</td>
        <td style="width:12.5%; border: 2px solid #fff; text-align:center; color:black;">Paid</td>
        <td style="width:12.5%; border: 2px solid #fff; text-align:center; color:black;">Unpaid</td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Bank Name</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->bank_name ?></td>
        <td style="width:12.5%; border: 2px solid #fff; text-align:center;"><?= $payroll->paid_leaves ?></td>
        <td style="width:12.5%; border: 2px solid #fff; text-align:center;"><?= $payroll->unpaid_leaves ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Bank Account Number</td>
        <td style="width:25%; border: 2px solid #fff;"><?= $payroll->bank_account_number ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">Balance Leaves</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;"><?= $payroll->total_balance_leaves ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:100%; border: 2px solid #fff;"></td>
    </tr>
    <tr style="background-color: #0085ca; color:#fff; padding: 5px; font-size:18px; text-align: center; ">
        <td style="width:50%; border: 2px solid #fff; text-align:center;">Earnings</td>
        <td style="width:50%; border: 2px solid #fff; text-align:center;">Deductions</td>
    </tr>
    <tr style="background-color: #6dbfff; color:#fff; padding: 5px; font-size:16px; text-align: center; ">
        <td style="width:25%; border: 2px solid #fff; text-align:center;">Particulars</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;">Amount</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;">Particulars</td>
        <td style="width:25%; border: 2px solid #fff; text-align:center;">Amount</td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Basic Salary</td>
        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($payroll->basic_salary, 2) ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">For Leaves Taken</td>
        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($payroll->deduction_of_leaves, 2) ?></td>
    </tr>

    <?php if (empty($payrollEarnings)): ?>
        <?php $totalDeduction = '0'; ?>
        <?php if (isset($payrollDeductions)): ?>
            <?php foreach ($payrollDeductions as $deductions): ?>
                <tr style="background-color: #f4f4f4; ">
                    <td style="width:25%; border: 2px solid #fff; color:black;"></td>
                    <td style="width:25%; border: 2px solid #fff; text-align:right;"></td>
                    <td style="width:25%; border: 2px solid #fff; color:black;"><?= $deductions->title ?></td>
                    <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($deductions->amount, 2) ?></td>
                </tr>
                <?php $totalDeduction += $deductions->amount; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php $totalDeduction = '0'; ?>
        <?php $totalEarning = '0'; ?>
        <?php if (count($payrollEarnings) < count($payrollDeductions)): ?>
            <?php foreach ($payrollDeductions as $deductions): ?>
                <tr style="background-color: #f4f4f4; ">
                    <?php
                    if (empty($payrollEarnings)) { ?>
                        <td style="width:25%; border: 2px solid #fff; color:black;"></td>
                        <td style="width:25%; border: 2px solid #fff; text-align:right;"></td>
                    <?php } ?>

                    <?php
                    foreach ($payrollEarnings as $earnings) { ?>
                        <td style="width:25%; border: 2px solid #fff; color:black;"><?= $earnings->title ?></td>
                        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($earnings->amount, 2) ?></td>
                        <?php $totalEarning += $earnings->amount; ?>
                    <?php array_shift($payrollEarnings);
                        break;
                    }
                    ?>
                    <td style="width:25%; border: 2px solid #fff; color:black;"><?= $deductions->title ?></td>
                    <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($deductions->amount, 2) ?></td>
                </tr>
                <?php $totalDeduction += $deductions->amount; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($payrollEarnings as $earnings): ?>
                <tr style="background-color: #f4f4f4; ">
                    <td style="width:25%; border: 2px solid #fff; color:black;"><?= $earnings->title ?></td>
                    <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($earnings->amount, 2) ?></td>

                    <?php
                    if (empty($payrollDeductions)) { ?>
                        <td style="width:25%; border: 2px solid #fff; color:black;"></td>
                        <td style="width:25%; border: 2px solid #fff; text-align:right;"></td>
                    <?php } ?>

                    <?php
                    foreach ($payrollDeductions as $deductions) { ?>
                        <td style="width:25%; border: 2px solid #fff; color:black;"><?= $deductions->title ?></td>
                        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($deductions->amount, 2) ?></td>
                        <?php $totalDeduction += $deductions->amount; ?>
                    <?php array_shift($payrollDeductions);
                        break;
                    }
                    ?>
                </tr>
                <?php $totalEarning += $earnings->amount; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <tr style="background-color: #f4f4f4; ">
        <td style="width:25%; border: 2px solid #fff; color:black;">Total Earnings</td>
        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format(($totalEarning ?? 0) + $payroll->basic_salary, 2) ?></td>
        <td style="width:25%; border: 2px solid #fff; color:black;">Total Deductions</td>
        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($payroll->deduction_of_leaves + ($totalDeduction ?? 0), 2) ?></td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:100%; border: 2px solid #fff;"> </td>
    </tr>
    <tr style="background-color: #6dbfff; ">
        <td style="width:50%; border: 2px solid #fff; text-align:center;">(Net Payable = Total Earnings - Total Deductions)</td>
        <td style="width:25%; border: 2px solid #fff; color:black; text-align:right;">Net Payable</td>
        <td style="width:25%; border: 2px solid #fff; text-align:right;">&#8377; <?= number_format($payroll->net_payable, 2) ?></td>
    </tr>
    <tr>
        <td style="width:50%; border: 2px solid #fff;"> </td>
        <td style="width:50%; border: 2px solid #fff; text-align:right;">
            <?= $this->Html->image(Router::url('/assets/images/signature.png', true), ['alt' => 'Signature']) ?>
        </td>
    </tr>
    <tr style="background-color: #f4f4f4; ">
        <td style="width:70%; border: 2px solid #fff; border-right:2px solid #f4f4f4;"></td>
        <td style="width:30%; border: 2px solid #fff; text-align:center;">Authorised Signatory</td>
    </tr>
</table>