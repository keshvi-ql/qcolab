<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payroll $payroll
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>

<style>
    .table-responsive {
        width: 100%;
        max-width: 100%;
        border: none !important;
    }
</style>
<div class="content text-muted">
    <?= $this->Form->create($payroll, ['class' => 'needs-validation', 'novalidate', 'id' => 'edit_payroll', 'name' => 'edit_payroll', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= isset($title) ? $title : '' ?></h5>
                </div>

                <div class="card-body">
                    <h3 class="pb-3"><strong>Payslip for the month of <?= str_replace("-", " ", $payroll->month) ?></strong></h3>

                    <div class="row pb-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong>Employee Name:</strong>
                                </div>
                                <div class="col-md-7">
                                    <p><?= $payroll->users->first_name ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <strong>Employee Code:</strong>
                                </div>
                                <div class="col-md-7">
                                    <p><?= $payroll->users->employee_code ?></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <strong>Designation</strong>
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
                                    <p> <?= $payroll->bank_name ? $payroll->bank_name : "-" ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <strong>Bank Account No :</strong>
                                </div>
                                <div class="col-md-7">
                                    <p> <?= $payroll->bank_account_number ? $payroll->bank_account_number : "-" ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-5">
                                    <strong>Date of Joining</strong>
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
                    <hr>
                    <div class="row pb-3 my-3 text-center">
                        <div class="col-md-6" style="border-right:1px solid #e8eef3;">
                            <h2>Earnings</h2>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-borderless text-muted" id="earnings-table" class="display" width="100%">
                                    <thead>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th><i class="ph-list"></i></th>
                                    </thead>

                                    <tr>
                                        <td>Basic Salary</td>
                                        <td>&#8377; <?= number_format($payroll->basic_salary, 2) ?></td>
                                        <td class="text-center option"></td>
                                    </tr>

                                    <tbody>
                                        <?php $totalEarning = '0'; ?>
                                        <?php if (isset($payrollEarnings)): ?>
                                            <?php foreach ($payrollEarnings as $earnings): ?>
                                                <tr>
                                                    <td><?= $earnings->title ?></td>
                                                    <td>&#8377; <?= number_format($earnings->amount, 2) ?></td>
                                                    <td>
                                                        <?= $this->Html->link(
                                                            __('<i class="ph-note-pencil"></i>'),
                                                            '#',
                                                            [
                                                                'class' => 'text-secondary edit-earning',
                                                                'data-id' => $earnings->id,
                                                                'data-title' => $earnings->title,
                                                                'data-amount' => $earnings->amount,
                                                                'data-bs-toggle' => 'modal',
                                                                'data-bs-target' => '#addEarningModal',
                                                                'escape' => false,
                                                                'title' => 'Edit',
                                                            ]
                                                        ) ?>
                                                    </td>
                                                </tr>
                                                <?php $totalEarning += $earnings->amount; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="clearfix">
                                <div class="float-start">
                                    <?= $this->Html->link(
                                        __('Add') . ' <i class="ph-plus-circle ms-2"></i>',
                                        '#addEarningModal',
                                        [
                                            'class' => 'btn btn-primary',
                                            'escape' => false,
                                            'role' => 'button',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#addEarningModal'
                                        ]
                                    ) ?>
                                </div>
                                <div class="float-end">
                                    <table id="payroll-earnings-table" class="table display dataTable text-right strong table-responsive">
                                        <tbody>
                                            <tr>
                                                <td>Total</td>
                                                <td style="width: 120px;">&#8377; <?= $earnings = number_format($totalEarning + $payroll->basic_salary, 2) ?></td>
                                                <td style="width: 100px;"> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h2>Deductions</h2>
                            <hr>
                            <div class="table-responsive mt15 pl15 pr15">
                                <table class="table table-borderless text-muted" id="deductions-table" class="display" width="100%">
                                    <thead>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th><i class="ph-list"></i></th>
                                    </thead>

                                    <tr>
                                        <td>For Leaves Taken</td>
                                        <td>&#8377; <?= number_format($payroll->deduction_of_leaves, 2) ?></td>
                                        <td class="text-center option"></td>
                                    </tr>

                                    <tbody>
                                        <?php $totalDeduction = '0'; ?>
                                        <?php if (isset($payrollDeductions)): ?>
                                            <?php foreach ($payrollDeductions as $deductions): ?>
                                                <tr>
                                                    <td><?= $deductions->title ?></td>
                                                    <td>&#8377; <?= number_format($deductions->amount, 2) ?></td>
                                                    <td>
                                                        <?= $this->Html->link(
                                                            __('<i class="ph-note-pencil"></i>'),
                                                            '#',
                                                            [
                                                                'class' => 'text-secondary edit-deduction',
                                                                'data-id' => $deductions->id,
                                                                'data-title' => $deductions->title,
                                                                'data-amount' => $deductions->amount,
                                                                'data-bs-toggle' => 'modal',
                                                                'data-bs-target' => '#addDeductionModal',
                                                                'escape' => false,
                                                                'title' => 'Edit',
                                                            ]
                                                        ) ?>
                                                    </td>
                                                </tr>

                                                <?php $totalDeduction += $deductions->amount; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="clearfix">
                                <div class="float-start">
                                    <?= $this->Html->link(
                                        __('Add') . ' <i class="ph-plus-circle ms-2"></i>',
                                        '#addDeductionModal',
                                        [
                                            'class' => 'btn btn-primary',
                                            'escape' => false,
                                            'role' => 'button',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#addDeductionModal'
                                        ]
                                    ) ?>
                                </div>
                                <div class="float-end pr15">
                                    <table id="payroll-deductions-table" class="table display dataTable text-right strong table-responsive">
                                        <tbody>
                                            <tr>
                                                <td>Total</td>
                                                <td style="width: 120px;" id="total-deductions"> &#8377; <?= $deductions = number_format($payroll->deduction_of_leaves + ($totalDeduction ?? 0), 2) ?></td>
                                                <td style="width: 100px;"> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $earnings = isset($earnings) ? (float)str_replace(',', '', $earnings) : 0;
                    $deductions = isset($deductions) ? (float)str_replace(',', '', $deductions) : 0;

                    $netSalary = $earnings - $deductions;
                    ?>

                    <div class="clearfix">
                        <p class="b-t"></p>
                        <div class="text-center">
                            <input type="hidden" name="net_payable" id="net_payable" value="<?= number_format($netSalary, 2) ?>">
                            <h2 id="total-payable"><b>Net Salary:</b> &#8377; <?= number_format($netSalary, 2) ?>
                                <h5>Net Salary = (Gross Earnings - Total Deductions)</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end bottom-0 position-fixed pt-2 pb-2 pe-4 bg-white w-100 sticky-submit-btn">
        <?= $this->Form->button(__('Save') . ' <i class="ph-floppy-disk ms-2"></i>', [
            'class' => 'btn btn-success',
            'escapeTitle' => false
        ]) ?>
        <?= $this->Html->link(
            __('Back'),
            ['controller' => 'Payroll', 'action' => 'index'],
            ['class' => 'btn btn-light']
        ) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<div class="modal fade" id="addEarningModal" tabindex="-1" aria-labelledby="addEarningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEarningModalLabel">Add Earning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addEarningForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'earningId']) ?>

                <?= $this->Form->hidden('payroll_id', ['id' => 'earningPayrollId', 'value' => $payroll->id]) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'earningTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('amount', 'Amount <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('amount', [
                                'id' => 'earningAmount',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Amount',
                            ]) ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEarningData">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addDeductionModal" tabindex="-1" aria-labelledby="addDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDeductionModalLabel">Add Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addDeductionForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'deductionId']) ?>

                <?= $this->Form->hidden('payroll_id', ['id' => 'deductionPayrollId', 'value' => $payroll->id]) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'deductionTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('amount', 'Amount <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('amount', [
                                'id' => 'deductionAmount',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Amount',
                            ]) ?>
                        </div>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDeductionData">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.edit-earning', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var amount = $(this).data('amount');

            $('#addEarningModalLabel').text('Edit Earning');

            $('#earningId').val(id);
            $('#earningTitle').val(title);
            $('#earningAmount').val(amount);
        });

        $(document).on('click', '[data-bs-target="#addEarningModal"]', function() {
            if (!$(this).hasClass('edit-earning')) {
                $('#addEarningModalLabel').text('Add Earning');
                $('#addEarningForm')[0].reset();
                $('#earningId').val('');
                $('.error-message').remove();
            } else {
                $('.error-message').remove();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#saveEarningData').on('click', function() {

            $('.error-message').remove();

            var isValid = true;

            var titleField = $('#earningTitle');
            var titleValue = titleField.val().trim();
            if (titleValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Title is required.</div>');
                titleField.after(errorMessage);
            }

            var amountField = $('#earningAmount');
            var amountValue = amountField.val().trim();
            if (amountValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Amount is required.</div>');
                amountField.after(errorMessage);
            }

            if (isValid) {
                var formData = $('#addEarningForm').serialize();

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Payroll', 'action' => 'addEarning']) ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addEarningModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while saving the data.');
                    }
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.edit-deduction', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var amount = $(this).data('amount');

            $('#addDeductionModalLabel').text('Edit Deduction');

            $('#deductionId').val(id);
            $('#deductionTitle').val(title);
            $('#deductionAmount').val(amount);
        });

        $(document).on('click', '[data-bs-target="#addDeductionModal"]', function() {
            if (!$(this).hasClass('edit-deduction')) {
                $('#addDeductionModalLabel').text('Add Deduction');
                $('#addDeductionForm')[0].reset();
                $('#deductionId').val('');
                $('.error-message').remove();
            } else {
                $('.error-message').remove();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#saveDeductionData').on('click', function() {

            $('.error-message').remove();

            var isValid = true;

            var titleField = $('#deductionTitle');
            var titleValue = titleField.val().trim();
            if (titleValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Title is required.</div>');
                titleField.after(errorMessage);
            }

            var amountField = $('#deductionAmount');
            var amountValue = amountField.val().trim();
            if (amountValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Amount is required.</div>');
                amountField.after(errorMessage);
            }

            if (isValid) {
                var formData = $('#addDeductionForm').serialize();

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Payroll', 'action' => 'addDeduction']) ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addDeductionModal').modal('hide');
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while saving the data.');
                    }
                });
            }
        });
    });
</script>