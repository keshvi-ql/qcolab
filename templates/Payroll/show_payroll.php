<style>
    .amount {
        float: right;
        padding-right: 80px;
    }
</style>

<div class="modal-header">
    <h5 class="modal-title" id="payrollModalLabel">Salary Slip for <?= $payroll->month ?></h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="container-fluid mt-3">
        <div class="row" style="border-bottom:1px solid #e8eef3;">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Employee Name :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->users->first_name ?> <?= $payroll->users->last_name ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <strong>Employee Code :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->employee_code ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Designation :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->users->job_title ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>PAN :</strong>
                    </div>
                    <div class="col-md-7">
                        <p> <?= $payroll->pan_number ? $payroll->pan_number : "-" ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Bank Name :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->bank_name ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Bank Account No :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->bank_account_number ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Date of Joining :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->users->date_of_joining ? $this->Date->format($payroll->users->date_of_joining, false) : "-"; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Total Working Days :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->total_working_days ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Days Present :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->days_present ?></p>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <strong>Leaves Taken :</strong>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Paid</strong>
                                <p class="ps-2"><?= $payroll->paid_leaves ?></p>
                            </div>
                            <div class="col-md-6">
                                <strong>Unpaid</strong>
                                <p class="ps-4"><?= $payroll->unpaid_leaves ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <strong>Balance Leaves :</strong>
                    </div>
                    <div class="col-md-7">
                        <p><?= $payroll->total_balance_leaves ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="border-bottom:1px solid #e8eef3;">
            <div class="col-md-6">
                <h5 class="text-center p-1" style="border-bottom:1px solid #e8eef3; font-weight: normal;">Earnings</h5>

                <div class="row">
                    <div class="col-md-5">
                        <strong>Basic Salary:</strong>
                    </div>
                    <div class="col-md-7">
                        <p class="amount"><?= "₹ " . number_format($payroll->basic_salary, 2) ?></p>
                    </div>
                </div>

                <?php $totalEarning = '0'; ?>
                <?php if ($payrollEarnings): ?>
                    <?php foreach ($payrollEarnings as $earnings): ?>
                        <div class="row">
                            <div class="col-md-5">
                                <strong><?= $earnings->title ?> :</strong>
                            </div>
                            <div class="col-md-7">
                                <p class="amount">₹ <?= number_format($earnings->amount, 2) ?></p>
                            </div>
                        </div>

                        <?php $totalEarning += $earnings->amount; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <h5 class="text-center p-1" style="border-bottom:1px solid #e8eef3; font-weight: normal;">Deductions</h5>

                <div class="row">
                    <div class="col-md-5">
                        <strong>For Leaves Taken :</strong>
                    </div>
                    <div class="col-md-7">
                        <p class="amount"><?= "₹ " . number_format($payroll->deduction_of_leaves, 2) ?></p>
                    </div>
                </div>

                <?php $totalDeduction = '0'; ?>
                <?php if ($payrollDeductions): ?>
                    <?php foreach ($payrollDeductions as $deductions): ?>
                        <div class="row">
                            <div class="col-md-5">
                                <strong><?= $deductions->title ?> :</strong>
                            </div>
                            <div class="col-md-7">
                                <p class="amount">₹ <?= number_format($deductions->amount, 2) ?></p>
                            </div>
                        </div>

                        <?php $totalDeduction += $deductions->amount; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Total Earnings :</strong>
                    </div>
                    <div class="col-md-7">
                        <p class="amount"><?= "₹ " . number_format($totalEarning + $payroll->basic_salary, 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Total Deductions :</strong>
                    </div>
                    <div class="col-md-7">
                        <p class="amount">₹ <?= number_format($payroll->deduction_of_leaves + ($totalDeduction ?? 0), 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-3">
            <div class="col-md-6">
                <p>(Net Payable = Total Earnings - Total Deductions)</p>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-5">
                        <strong>Net Payable :</strong>
                    </div>
                    <div class="col-md-7">
                        <p class="amount"><?= "₹ " . number_format($payroll->net_payable, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>