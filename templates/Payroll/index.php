<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Payroll> $payroll
 */

$loggedInUser = $this->request->getAttribute('identity');
?>

<?php $this->start('css'); ?>
<?= $this->Html->css([
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css',
]) ?>
<?php $this->end(); ?>

<style>
    @media only screen and (max-width: 575px) {
        #member {
            margin: 10px 0;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content d-lg-flex">
        <div class="d-flex">
            <h4 class="page-title mb-0">
                <?= isset($title) ? $title : '' ?>
            </h4>

            <a href="#page_header"
                class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                data-bs-toggle="collapse">
                <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
            <h5 class="py-sm-2 my-sm-1">Payroll List</h5>
            <div class="d-flex">
                <?php if ($this->User->hasPermission('Payroll', 'index')): ?>
                    <?= $this->Html->link(
                        __('Generate Payroll') . ' <i class="ph-plus-circle ms-2"></i>',
                        '#',
                        [
                            'class' => 'btn btn-primary',
                            'escape' => false,
                            'id' => 'generatePayroll'
                        ]
                    ) ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body d-sm-flex justify-content-end align-items-end">
            <div class="px-3">
                <select name="user" id="user" class="select form-select" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="">-- All Members --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user->id ?>" <?= $this->request->getQuery('user') == $user->id ? 'selected' : '' ?>>
                            <?= h($user->first_name) ?> <?= h($user->last_name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-sm-flex gap-4 px-3">
                <input type="text" id="monthPicker" class="form-control" value="" placeholder="--Select Month--" style="max-width: 200px;">
            </div>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <th width="2%">ID</th>
                    <th width="15%">Month</th>
                    <th width="15%">Name</th>
                    <th width="15%">Email</th>
                    <th width="10%">Job Title</th>
                    <th width="12%">Net Salary</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payrolls as $payroll): ?>
                    <tr>
                        <td><?= h($payroll->user_id) ?></td>
                        <td><?= h($payroll->month) ?></td>
                        <td>
                            <?= h($payroll->users->first_name) ?> <?= h($payroll->users->last_name) ?>
                        </td>
                        <td>
                            <a href="mailto:<?= !empty($payroll->users->email) ? $payroll->users->email : $payroll->users->alt_email ?>"><?= !empty($payroll->users->email) ? $payroll->users->email : $payroll->users->alt_email ?></a>
                        </td>
                        <td><?= h($payroll->users->job_title) ?></td>
                        <td><?= h($payroll->net_payable) ?></td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Payroll', 'edit')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    ['action' => 'edit', $payroll->id],
                                    [
                                        'class' => 'text-primary',
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Edit',
                                        'escape' => false,
                                        'target' => '_blank'
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?= $this->Html->link(
                                __('<i class="ph-eye"></i>'),
                                '#',
                                [
                                    'class' => 'text-secondary show-payroll',
                                    'escape' => false,
                                    'data-id' => $payroll->id,
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Slaray Slip',
                                ]
                            ) ?>

                            <?= $this->Html->link(
                                __('<i class="ph-file-text"></i>'),
                                [
                                    'action' => 'payrollPdf',
                                    $payroll->id,
                                    '?' => [
                                        'id' => $payroll->user_id,
                                        'month' => $payroll->month,
                                    ]
                                ],
                                [
                                    'class' => 'text-warning',
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Generate PDF',
                                    'escape' => false,
                                    'target' => '_blank'
                                ]
                            ) ?>

                            <?= $this->Html->link(
                                __('<i class="ph-file-xls"></i>'),
                                [
                                    'action' => 'export',
                                    $payroll->id,
                                ],
                                [
                                    'class' => 'text-yellow',
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Generate Excel',
                                    'escape' => false,
                                ]
                            ) ?>

                            <?= $this->Html->link(
                                __('<span id="icon-' . $payroll->user_id . '"><i class="ph-envelope-simple"></i></span><span class="spinner-border spinner-border-sm d-none" id="spinner-' . $payroll->user_id . '"></span>'),
                                '#',
                                [
                                    'class' => 'text-success',
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Send Email',
                                    'onclick' => "sendEmail('{$payroll->user_id}', '{$payroll->month}')",
                                    'escape' => false,
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="payrollModal" tabindex="-1" aria-labelledby="payrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>

<?= $this->Html->script([
    'https://cdn.jsdelivr.net/npm/flatpickr',
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js',

]) ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#generatePayroll').on('click', function() {
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Payroll', 'action' => 'gerenatePayroll']) ?>',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while saving the data.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        const monthInput = $('#monthPicker');
        const userSelect = $('#user')

        const csrfToken = $('meta[name="csrfToken"]').attr('content');
        const url = '<?= $this->Url->build(['action' => 'index']) ?>';

        userSelect.on('change', function() {
            const selectedUser = $(this).val();
            fetchData(null, selectedUser);
        });

        monthInput.flatpickr({
            dateFormat: "F-Y",
            altInput: true,
            altFormat: "F Y",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "F-Y",
                    altFormat: "F Y",
                    theme: "light"
                })
            ],
            onChange: function(selectedMonth, dateStr, instance) {
                const selectedUser = userSelect.val();
                fetchData(dateStr, selectedUser);
            }
        });

        function fetchData(month = null, userId = null) {
            let data = {};

            data = {
                selected_month: month
            };

            if (userId) {
                data.user_id = userId;
            } else {
                data.user_id = null;
            }

            $.ajax({
                url: '<?= $this->Url->build(['action' => 'setSession']) ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                success: function() {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            $('.table tbody').html($(response).find('.table tbody').html());
                        }
                    });
                }
            });
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('.show-payroll').on('click', function(e) {
            e.preventDefault();

            const id = $(this).data('id');
            payroll(id);
        });
    });

    function payroll(id) {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build(['controller' => 'Payroll', 'action' => 'showPayroll']); ?>/' + (id ? id : ''),
            success: function(response) {
                $("#payrollModal .modal-content").html(response);
                $("#payrollModal").modal('show');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>

<?= $this->Html->script([
    '/assets/js/vendor/notifications/noty.min.js',
    '/assets/js/custom/noty.js'
]) ?>

<script>
    function sendEmail(userId, month) {
        const icon = $('#icon-' + userId);
        const spinner = $('#spinner-' + userId);

        icon.addClass('d-none'); // hide envelope icon
        spinner.removeClass('d-none'); // show spinner

        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'Payroll', 'action' => 'sendMail']); ?>',
            type: 'POST',
            data: {
                userId: userId,
                month: month,
            },
            headers: {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content'),
            },
            success: function(response) {
                spinner.addClass('d-none'); // hide spinner
                icon.removeClass('d-none'); // show envelope back

                if (response.success) {
                    NotyDemo.init();

                    new Noty({
                        text: response.message,
                        type: 'success'
                    }).show();
                } else {
                    NotyDemo.init();

                    new Noty({
                        text: response.message,
                        type: 'error'
                    }).show();
                }
            },
            error: function(xhr) {
                spinner.addClass('d-none'); // hide spinner
                icon.removeClass('d-none'); // show envelope back
                console.error(xhr.responseText);
            }
        });
    }
</script>