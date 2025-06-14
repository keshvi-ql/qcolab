<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $technologies
 */
?>
<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
                    <div class="d-flex gap-3">
                        <?php if ($this->User->hasPermission('ProjectStatuses', 'index')): ?>
                            <?= $this->Html->link(
                                'Project Status',
                                ['controller' => 'ProjectStatuses', 'action' => 'index'],
                                [
                                    'class' => 'py-sm-2 my-sm-1 text-black',
                                    'escape' => false,
                                ]
                            ) ?>
                        <?php endif; ?>
                        <?php if ($this->User->hasPermission('Technologies', 'index')): ?>
                            <?= $this->Html->link(
                                'Technologies',
                                ['controller' => 'Technologies', 'action' => 'index'],
                                [
                                    'class' => 'py-sm-2 my-sm-1',
                                    'escape' => false,
                                ]
                            ) ?>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex">
                        <?php if ($this->User->hasPermission('Technologies', 'add')): ?>
                            <?= $this->Html->link(
                                __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                                '#addNewModal', // Change link to the modal ID
                                [
                                    'class' => 'btn btn-primary',
                                    'escape' => false,
                                    'role' => 'button',
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#addNewModal'
                                ]
                            ) ?>
                        <?php endif; ?>

                        <?php if ($this->User->hasPermission('Technologies', 'delete')): ?>
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable-basic table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="2%">
                                        <?php if ($this->User->hasPermission('Technologies', 'delete')): ?>
                                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                                        <?php endif; ?>
                                    </th>
                                    <th>Title</th>
                                    <th width="8%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($technologies as $technology): ?>
                                    <tr>
                                        <td>
                                            <?php if ($this->User->hasPermission('Technologies', 'delete')): ?>
                                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $technology->id ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $technology->title; ?>
                                        </td>
                                        <td class="actions text-center">
                                            <?php if ($this->User->hasPermission('Technologies', 'add')): ?>
                                                <?= $this->Html->link(
                                                    __('<i class="ph-note-pencil"></i>'),
                                                    '#',
                                                    [
                                                        'class' => 'text-secondary edit-technology',
                                                        'data-id' => $technology->id,
                                                        'data-title' => $technology->title,
                                                        'data-bs-toggle' => 'modal',
                                                        'data-bs-target' => '#addNewModal',
                                                        'escape' => false,
                                                        'title' => 'Edit',
                                                    ]
                                                ) ?>
                                            <?php endif; ?>

                                            <?php if ($this->User->hasPermission('Technologies', 'delete')): ?>
                                                <?= $this->Html->link(
                                                    __('<i class="ph-trash"></i>'),
                                                    '#',
                                                    [
                                                        'class' => 'text-danger sweet_warning',
                                                        'escape' => false,
                                                        'id' => $technology->id,
                                                        'data-bs-popup' => 'tooltip',
                                                        'data-bs-placement' => 'top',
                                                        'title' => 'Delete',
                                                        'data-record-id' => $technology->id,
                                                        'data-url' => $this->Url->build(['action' => 'delete', $technology->id]),
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add Technology</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'technologyId']) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'technologyTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
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

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.edit-technology', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            $('#addNewModalLabel').text('Edit Technology');

            $('#technologyId').val(id);
            $('#technologyTitle').val(title);
        });

        $(document).on('click', '[data-bs-target="#addNewModal"]', function() {
            if (!$(this).hasClass('edit-technology')) {
                $('#addNewModalLabel').text('Add Technology');
                $('#addNewForm')[0].reset();
                $('#technologyId').val('');
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

            var isValid = true;

            // Validate the Title field
            var titleField = $('#technologyTitle');
            var titleValue = titleField.val().trim();
            if (titleValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Title is required.</div>');
                titleField.after(errorMessage);
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize(); // Serialize the form data

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'Technologies', 'action' => 'add']) ?>',
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