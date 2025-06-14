<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 * @var array $controllers
 */
?>

<!-- Content area -->
<div class="content">
    <?= $this->Form->create($role, ['class' => 'needs-validation', 'novalidate', 'id' => 'edit_user', 'name' => 'edit_user', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>

        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= isset($title) ? $title : '' ?> : <?= $role->name ?></h6>

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

                        </div>
                        <div class="card-body border-top">
                            <div class="alert alert-warning alert-icon-start alert-dismissible fade show">
                                <span class="alert-icon bg-warning text-white">
                                    <i class="ph-warning-circle"></i>
                                </span>
                                <span class="fw-base"><?= __('Changing role permissions will affect current users permissions that are using this role.'); ?></span>
                            </div>
                            <div class="mb-3">
                                <small class="req text-danger">*</small>
                                <?= $this->Form->label('name', 'Role Name:', ['class' => 'form-label']) ?>
                                <?= $this->Form->text('name', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Role name'
                                ]) ?>
                            </div>
                            <!-- Permissions -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40%">Controllers</th>
                                        <th width="60%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($controllers as $controller => $actions): ?>
                                        <?php
                                        // Ensure $role->permissions is an array
                                        $rolePermissions = is_array($role->role_permissions) ? $role->role_permissions : [];
                                        ?>
                                        <tr>
                                            <th><?= h($controller) ?></th>
                                            <td>
                                                <?php foreach ($actions as $action): ?>
                                                    <div class="row mb-2">
                                                        <div class="col">
                                                            <?= $this->Form->checkbox('permissions[]', [
                                                                'value' => $controller . '-' . $action,
                                                                'class' => 'form-check-input form-check-input-secondary permission-checkbox',
                                                                'id' => $controller . '-' . $action,
                                                                'checked' => in_array($controller . '-' . $action, array_map(function ($perm) {
                                                                    return $perm->controller . '-' . $perm->action;
                                                                }, $rolePermissions))
                                                            ]) ?>
                                                            <?= $this->Form->label($controller . '-' . $action, ucfirst(h($action)), [
                                                                'for' => $controller . '-' . $action,
                                                                'style' => 'cursor: pointer;'
                                                            ]) ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <div id="checkbox-error" style="color: red; display: none;">
                                        Please select at least one Action of any Controller.
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0 pt-2 pb-1"><?= __('Users which are currently using the role:') ?>&nbsp;<?= $role->name ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($users)): ?>
                                <table id="userList" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="30%"><?= __('Name') ?></th>
                                            <th width="70%"><?= __('Email') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= h($user->first_name) ?></td>
                                                <td><a href="mailto:"><?= h($user->email) ?></a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p><?= __('No users assigned to this role.') ?></p>
                            <?php endif; ?>
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
                ['controller' => 'Roles', 'action' => 'index'],
                ['class' => 'btn btn-light']
            ) ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /Content area -->


<?php $this->start('script'); ?>
<?= $this->Html->script([
    '/assets/js/vendor/tables/datatables/datatables.min.js',
]) ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#userList').DataTable();
    });

    document.getElementById('edit_user').addEventListener('submit', function(event) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        // Prevent form submission if no checkbox is checked
        if (!isChecked) {
            event.preventDefault(); // Prevent the form from submitting
            document.getElementById('checkbox-error').style.display = 'block'; // Show error message
        } else {
            document.getElementById('checkbox-error').style.display = 'none'; // Hide error message
        }
    });

    // Hide the error message when a checkbox is clicked
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.getElementById('checkbox-error').style.display = 'none'; // Hide error message when any checkbox is clicked
        });
    });
</script>
<?php $this->end(); ?>