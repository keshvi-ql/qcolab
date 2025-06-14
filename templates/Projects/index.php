<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Announcement> $announcements
 */

$loggedInUser = $this->request->getAttribute('identity');
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
            <h5 class="py-sm-2 my-sm-1">Project List</h5>
            <div class="d-flex">
                <?php if ($this->User->hasPermission('Projects', 'add')): ?>
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

                <?php if ($this->User->hasPermission('Projects', 'delete')): ?>
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
                        <?php if ($this->User->hasPermission('Projects', 'delete')): ?>
                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                        <?php endif; ?>
                    </th>
                    <th width="10%">Project No</th>
                    <th width="10%">Status</th>
                    <th width="15%">Title</th>
                    <th width="10%">Url</th>
                    <th width="15%">Client</th>
                    <th width="15%">Start Date</th>
                    <th width="10%">Type</th>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td>
                            <?php if ($this->User->hasPermission('Projects', 'delete')): ?>
                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $project->id ?>">
                            <?php endif; ?>
                        </td>
                        <td><?= h($project->project_no) ?? ' - '; ?></td>
                        <td>
                            <span style="background-color:<?= $project->statuses->color; ?>; padding: 3px 8px; border-radius: 5px; font-size:small; font-weight:500"><?= $project->statuses->title; ?></span>
                        </td>
                        <td>
                            <?= $this->Html->link(h($project->title), ['action' => 'view', $project->id]) ?>
                        </td>
                        <td>
                            <?php if (!empty($project->url)): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-link"></i>'),
                                    $project->url,
                                    [
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => $project->url,
                                        'target' => '_blank',
                                        'escape' => false
                                    ]
                                ) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= h($project->clients->first_name) ?> <?= h($project->clients->last_name) ?></td>
                        <td><?= h((new DateTime($project->start_date))->format('jS M Y')) ?></td>
                        <td>
                            <?php if ($project->type == 'fixed'): ?>
                                <span class="badge bg-yellow">
                                    <?= ucfirst($project->type) ?>
                                </span>
                            <?php elseif ($project->type == 'hourly'): ?>
                                <span class="badge bg-secondary">
                                    <?= ucfirst($project->type) ?>
                                </span>
                            <?php elseif ($project->type == 'monthly'): ?>
                                <span class="badge bg-teal">
                                    <?= ucfirst($project->type) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Projects', 'add')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-secondary edit-project',
                                        'data-id' => $project->id,
                                        'data-title' => $project->title,
                                        'data-client_id' => $project->client_id,
                                        'data-status_id' => $project->status_id,
                                        'data-description' => $project->description,
                                        'data-url' => $project->url,
                                        'data-startdate' => $project->start_date,
                                        'data-deadline' => $project->deadline,
                                        'data-type' => $project->type,
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#addNewModal',
                                        'escape' => false,
                                        'title' => 'Edit',
                                    ]
                                ) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Projects', 'delete')): ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-trash"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-danger sweet_warning',
                                        'escape' => false,
                                        'id' => $project->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $project->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $project->id]),
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
                <h5 class="modal-title" id="addNewModalLabel">Add Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'projectId']) ?>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'projectTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('client_id', 'Client <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->select('client_id', $clients, [
                                'class' => 'select form-select',
                                'id' => 'projectClient',
                                'empty' => '-- Select Client --',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('status_id', 'Status <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->select('status_id', $statuses, [
                                'class' => 'select form-select',
                                'id' => 'projectStatus',
                            ]); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('description', 'Description', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->textarea('description', [
                                'class' => 'form-control',
                                'id' => 'projectDescription',
                                'placeholder' => 'Enter Description'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('url', 'URL', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('url', [
                                'id' => 'projectUrl',
                                'class' => 'form-control',
                                'placeholder' => 'Enter URL',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('start_date', 'Start Date <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('start_date', [
                                'id' => 'projectStartDate',
                                'class' => 'form-control datepicker-basic',
                                'placeholder' => 'Enter Start Date',
                            ]) ?>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('deadline', 'Deadline', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <?= $this->Form->text('deadline', [
                                'id' => 'projectDeadline',
                                'class' => 'form-control datepicker-basic',
                                'placeholder' => 'Enter Deadline',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('type', 'Type <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <select name="type" id="projectType" class="select form-select">
                                <option value="">-- Select Type --</option>
                                <option value="fixed">Fixed</option>
                                <option value="hourly">Hourly</option>
                                <option value="monthly">Monthly</option>
                            </select>
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
        $(document).on('click', '.edit-project', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var client_id = $(this).data('client_id');
            var status_id = $(this).data('status_id');
            var description = $(this).data('description');
            var url = $(this).data('url');
            var startDate = $(this).data('startdate');
            var deadline = $(this).data('deadline');
            var type = $(this).data('type');

            $('#addNewModalLabel').text('Edit Project');

            $('#projectId').val(id);
            $('#projectTitle').val(title);
            $('#projectClient').val(client_id);
            $('#projectStatus').val(status_id);
            $('#projectDescription').val(description);
            $('#projectUrl').val(url);
            $('#projectStartDate').val(startDate);
            $('#projectDeadline').val(deadline);
            $('#projectType').val(type);

            $('#projectStatus').closest('.row').show();
        });

        $(document).on('click', '[data-bs-target="#addNewModal"]', function() {
            if (!$(this).hasClass('edit-project')) {
                $('#addNewModalLabel').text('Add Project');
                $('#addNewForm')[0].reset();
                $('#projectId').val('');

                $('#projectStatus').closest('.row').hide();
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

            const titleField = $('#projectTitle');
            if (titleField.val().trim() === '') {
                isValid = false;
                titleField.after('<div class="error-message text-danger">Title is required.</div>');
            }

            var clientField = $('#projectClient');
            var clientValue = clientField.val();
            if (clientValue === '') {
                isValid = false;
                clientField.after('<div class="error-message text-danger">Client is required.</div>');
            }

            const startDateField = $('#projectStartDate');
            const endDateField = $('#announcementEndDate');
            if (startDateField.val() === '') {
                isValid = false;
                startDateField.after('<div class="error-message text-danger">Start Date is required.</div>');
            }
            if (endDateField.val() === '') {
                isValid = false;
                endDateField.after('<div class="error-message text-danger">End Date is required.</div>');
            }

            var typeField = $('#projectType');
            var typeValue = typeField.val();
            if (typeValue === '') {
                isValid = false;
                typeField.after('<div class="error-message text-danger">Type is required.</div>');
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize();

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Projects', 'action' => 'add']) ?>',
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