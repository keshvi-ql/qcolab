<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
?>

<!-- Content area -->
<div class="content">
    <?= $this->Form->create($role, ['class' => 'needs-validation', 'novalidate', 'id' => 'add_role', 'name' => 'add_role', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?= __('Role') ?></h5>
                </div>

                <div class="card-body border-top">
                    <div class="mb-3">
                        <small class="req text-danger">*</small>
                        <?= $this->Form->label('name', 'Role Name:', ['class' => 'form-label']) ?>
                        <?= $this->Form->text('name', [
                            'class' => 'form-control',
                            'placeholder' => 'Role name'
                        ]) ?>
                        <div class="invalid-feedback">Please Enter Role</div>
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
                                <tr>
                                    <th><?= h($controller) ?></th>
                                    <td>
                                        <?php foreach ($actions as $action): ?>
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <input type="checkbox" name="permissions[]" id="<?= $controller . '-' . $action ?>" value="<?= $controller . '-' . $action ?>" class="form-check-input form-check-input-secondary permission-checkbox">
                                                    <?= $this->Form->label($controller . '-' . $action, ucfirst(h($action)), [
                                                        'for' => $controller . '-' . $action
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
    '/assets/js/vendor/forms/validation/validate.min.js',
    '/assets/js/custom/form_validation.js'
]) ?>
<script>
    document.getElementById('add_role').addEventListener('submit', function(event) {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        if (!isChecked) {
            event.preventDefault();
            document.getElementById('checkbox-error').style.display = 'block'; // Show error message
        }
    });

    // Hide the error message when a checkbox is clicked
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            document.getElementById('checkbox-error').style.display = 'none'; // Hide error message when a checkbox is checked
        });
    });
</script>
<?php $this->end(); ?>