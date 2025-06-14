<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Role> $roles
 */
$loggedInUserRoleId = $this->User->getLoginUserAttribute('role_id', 'No role');
?>

<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">Roles List</h5>
                </div>

                <div class="card-body">
                    <div class="d-flex">
                        <?php if ($this->User->hasPermission('Roles', 'add')): ?>
                            <?= $this->Html->link(
                                __('Add New') . ' <i class="ph-plus-circle ms-1"></i>',
                                ['action' => 'add'],
                                [
                                    'class' => 'btn btn-primary',
                                    'escape' => false,
                                    'role' => 'button'
                                ]
                            ) ?>
                        <?php endif; ?>

                        <?php if ($this->User->hasPermission('Roles', 'delete')): ?>
                            <?= $this->Form->create(null, ['url' => ['action' => 'delete'], 'id' => 'deleteSelectedForm']) ?>
                            <?= $this->Form->hidden('ids', ['id' => 'selectedIds']) ?>
                            <?= $this->Form->button(
                                __('Delete Selected') . ' <i class="ph-trash ms-1"></i>',
                                ['type' => 'button', 'class' => 'btn btn-danger ms-2', 'id' => 'delete_all', 'escapeTitle' => false]
                            ) ?>
                            <?= $this->Form->end() ?>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable-basic table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="2%">
                                        <?php if ($this->User->hasPermission('Roles', 'delete')): ?>
                                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                                        <?php endif; ?>
                                    </th>
                                    <th width="90%">Name</th>
                                    <th width="8%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td>
                                            <?php if ($this->User->hasPermission('Roles', 'delete')): ?>
                                                <?php $disabled = ($role->name != 'Admin' && $loggedInUserRoleId != $role->id) ? '' : 'disabled' ?>
                                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $role->id ?>" <?= $disabled; ?>>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= h($role->name) ?></td>
                                        <td class="actions">
                                            <?php if ($this->User->hasPermission('Roles', 'edit')): ?>
                                                <?= $this->Html->link(__('<i class="ph-note-pencil"></i>'), ['action' => 'edit', $role->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Edit', 'escape' => false]) ?>
                                            <?php endif; ?>
                                            <?php if ($this->User->hasPermission('Roles', 'delete')): ?>
                                                <?php if ($role->name != 'Admin' && $loggedInUserRoleId != $role->id): ?>
                                                    <?= $this->Html->link(
                                                        __('<i class="ph-trash"></i>'),
                                                        '#', // This is just a placeholder
                                                        [
                                                            'class' => 'text-danger sweet_warning',
                                                            'escape' => false,
                                                            'id' => $role->id,
                                                            'data-bs-popup' => 'tooltip',
                                                            'data-bs-placement' => 'top',
                                                            'title' => 'Delete',
                                                            'data-record-id' => $role->id,
                                                            'data-url' => $this->Url->build(['action' => 'delete', $role->id]), // URL for AJAX request
                                                            'data-confirm' => 'Are you sure?'
                                                        ]
                                                    ) ?>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Basic datatable -->

        </div>
    </div>
</div>
<!-- /content area -->

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