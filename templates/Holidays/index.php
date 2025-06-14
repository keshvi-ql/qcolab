<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Holiday> $holidays
 */
?>

<style>
    .datepicker {
        z-index: 1500 !important;
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
            <h5 class="py-sm-2 my-sm-1">Holiday List</h5>
            <div class="d-flex">
                <?php if ($this->User->hasPermission('Holidays', 'add')): ?>
                    <?= $this->Html->link(
                        __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                        '#addNewModal',
                        [
                            'class' => 'btn btn-primary',
                            'escape' => false,
                            'role' => 'button',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#addNewModal'
                        ]
                    ) ?>
                <?php endif; ?>

                <?php if ($this->User->hasPermission('Holidays', 'delete')): ?>
                    <?= $this->Form->create(null, ['url' => ['action' => 'delete'], 'id' => 'deleteSelectedForm']) ?>
                    <?= $this->Form->hidden('ids', ['id' => 'selectedIds']) ?>
                    <?= $this->Form->button(
                        __('Delete Selected') . ' <i class="ph-trash ms-1"></i>',
                        ['type' => 'button', 'class' => 'btn btn-danger ms-2', 'id' => 'delete_all', 'escapeTitle' => false]
                    ) ?>
                    <?= $this->Form->end() ?>
                <?php endif; ?>
            </div>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <th width="2%">
                        <?php if ($this->User->hasPermission('Holidays', 'delete')): ?>
                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                        <?php endif; ?>
                    </th>
                    <th width="25%">Title</th>
                    <th width="20%">Start Date</th>
                    <th width="20%">End Date</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($holidays as $holiday): ?>
                    <tr>
                        <td>
                            <?php if ($this->User->hasPermission('Holidays', 'delete')): ?>
                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $holiday->id ?>">
                            <?php endif; ?>
                        </td>
                        <td><?= h($holiday->title) ?></td>
                        <td><?= h((new DateTime($holiday->start_date))->format('jS M Y')) ?></td>
                        <td><?= h((new DateTime($holiday->end_date))->format('jS M Y')) ?></td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Holidays', 'add')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-secondary edit-holiday',
                                        'data-id' => $holiday->id,
                                        'data-title' => $holiday->title,
                                        'data-startdate' => $holiday->start_date,
                                        'data-enddate' => $holiday->end_date,
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#addNewModal',
                                        'escape' => false,
                                        'title' => 'Edit',
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Holidays', 'delete')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-trash"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-danger sweet_warning',
                                        'escape' => false,
                                        'id' => $holiday->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $holiday->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $holiday->id]),
                                        'data-confirm' => 'Are you sure?',
                                    ]
                                ); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'holidayId']) ?>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'holidayTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('duration', 'Duration', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->radio('duration', [
                                ['value' => 'single', 'text' => ' Single Day'],
                                ['value' => 'multiple', 'text' => ' Multiple Days']
                            ], [
                                'class' => 'form-check-input form-check-input-secondary',
                                'label' => ['class' => 'ms-2'],
                                'default' => 'single',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row single-day">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('date', 'Date <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('date', [
                                'id' => 'holidayDate',
                                'class' => 'form-control datepicker-basic',
                                'placeholder' => 'Enter Date',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row multiple-days">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('start_date', 'Start Date <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('start_date', [
                                'id' => 'holidayStartDate',
                                'class' => 'form-control datepicker-basic',
                                'placeholder' => 'Enter Start Date',
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('end_date', 'End Date <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('end_date', [
                                'id' => 'holidayEndDate',
                                'class' => 'form-control datepicker-basic',
                                'placeholder' => 'Enter End Date',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <?= $this->Form->end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveNewData">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        DateTimePickers.init();
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('input[name="duration"]').change(function() {
            if ($('input[name="duration"]:checked').val() === 'single') {
                $('.single-day').show();
                $('.multiple-days').hide();
            } else {
                $('.single-day').hide();
                $('.multiple-days').show();
            }
        }).trigger('change');

        $(document).on('click', '.edit-holiday', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var startDate = $(this).data('startdate');
            var endDate = $(this).data('enddate');

            $('#addNewModalLabel').text('Edit Holiday');

            $('#holidayId').val(id);
            $('#holidayTitle').val(title);

            if (startDate === endDate) {
                $('input[name="duration"][value="single"]').prop('checked', true).trigger('change');
                $('#holidayDate').val(startDate);
            } else {
                $('input[name="duration"][value="multiple"]').prop('checked', true).trigger('change');
                $('#holidayStartDate').val(startDate);
                $('#holidayEndDate').val(endDate);
            }
        });

        $(document).on('click', '[data-bs-target="#addNewModal"]', function() {
            if (!$(this).hasClass('edit-holiday')) {
                $('#addNewModalLabel').text('Add Holiday');
                $('#addNewForm')[0].reset();
                $('#holidayId').val('');
                $('.error-message').remove();
            } else {
                $('.error-message').remove();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Open modal for adding a new leave type
        $('#saveNewData').on('click', function() {

            $('.error-message').remove();

            let isValid = true;

            const titleField = $('#holidayTitle');
            if (titleField.val().trim() === '') {
                isValid = false;
                titleField.after('<div class="error-message text-danger">Title is required.</div>');
            }

            const duration = $('input[name="duration"]:checked').val();
            if (duration === 'single') {
                const dateField = $('#holidayDate');
                if (dateField.val() === '') {
                    isValid = false;
                    dateField.after('<div class="error-message text-danger">Date is required.</div>');
                }
            } else {
                const startDateField = $('#holidayStartDate');
                const endDateField = $('#holidayEndDate');
                if (startDateField.val() === '') {
                    isValid = false;
                    startDateField.after('<div class="error-message text-danger">Start Date is required.</div>');
                }
                if (endDateField.val() === '') {
                    isValid = false;
                    endDateField.after('<div class="error-message text-danger">End Date is required.</div>');
                }
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize();

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Holidays', 'action' => 'add']) ?>',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#addNewModal').modal('hide');
                            location.reload(); // Reload the page or dynamically update content
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

<script>
    // Select all checkbox when select_all checkbox is checked
    document.addEventListener("DOMContentLoaded", function() {
        var selectAllCheckbox = document.getElementById("select_all");
        if (selectAllCheckbox) {
            selectAllCheckbox.onclick = function() {
                var checkboxes = document.querySelectorAll(".select_checkbox");
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            };
        }
    });

    // If individul checkbox select then select_all checkbox unchecked
    var checkboxes = document.querySelectorAll(".select_checkbox");
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            var allChecked = Array.from(checkboxes).every((cb) => cb.checked);
            document.getElementById("select_all").checked = allChecked;
        });
    });

    // Handle multiple deletions
    document.addEventListener('DOMContentLoaded', function() {
        var deleteAllButton = document.getElementById('delete_all');

        if (deleteAllButton) {
            deleteAllButton.onclick = function(event) {
                event.preventDefault();

                var selected = [];
                var checkboxes = document.querySelectorAll('.select_checkbox');

                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked && !checkbox.disabled) {
                        selected.push(checkbox.value);
                    }
                });

                if (selected.length > 0) {
                    deleteRecord(
                        $('#deleteSelectedForm').attr('action'), {
                            '_method': 'DELETE',
                            'ids': selected.join(',')
                        },
                        selected
                    );
                } else {
                    swalInit.fire('Oh Snap!', 'Please select some records to delete.', 'warning');
                }
            };
        }
    });
</script>