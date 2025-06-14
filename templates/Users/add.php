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
    <!-- Default grid -->
    <?= $this->Form->create(null, ['class' => 'needs-validation', 'novalidate', 'id' => 'add_user', 'name' => 'add_user', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Staff</h5>
                </div>

                <div class="card-body">

                    <?= $this->Form->hidden('status', [
                        'value' => '1'
                    ]) ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('first_name', 'First Name <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->text('first_name', [
                                    'class' => 'form-control',
                                    'placeholder' => 'First Name',
                                    'required' => true
                                ]) ?>
                                <div class="invalid-feedback">Please Enter First Name</div>
                            </div>
                        </div>

                        <!-- <div class="col-lg-4">
                            <div class="mb-3">
                                <?= $this->Form->label('middle_name', 'Middle Name', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->text('middle_name', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Middle Name',
                                ]) ?>
                            </div>
                        </div> -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('last_name', 'Last Name', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->text('last_name', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Last Name',
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('email', 'Email <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->text('email', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Email',
                                    'required' => true
                                ]) ?>
                                <div class="invalid-feedback">Please Enter Email</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('phone_no', 'Phone No', ['class' => 'form-label']) ?>
                                <?= $this->Form->number('phone_no', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Phone No'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('password', 'Password <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->password('password', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Password',
                                    'required' => true
                                ]) ?>
                                <div class="invalid-feedback">Please Enter Password</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('confirm_password', 'Confirm Password <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->password('confirm_password', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Confirm Password',
                                    'required' => true
                                ]) ?>
                                <div class="invalid-feedback">Please Enter Confirm Password</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('role', 'Role <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->select('role_id', $roles, [
                                    'class' => 'select form-select',
                                    'empty' => '-- Select Role --',
                                    'id' => 'role',
                                    'required' => true
                                ]); ?>
                                <div class="invalid-feedback">Please Select Role</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <p>Gender</p>
                                <?= $this->Form->radio('gender', [
                                    ['value' => 'male', 'text' => ' Male'],
                                    ['value' => 'female', 'text' => ' Female']
                                ], [
                                    'class' => 'form-check-input form-check-input-secondary',
                                    'label' => ['class' => 'ms-2'],
                                    'default' => !empty($user->gender) ? $user->gender : 'male',
                                ]) ?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <p>Is Trainee ?</p>
                                <div class="d-inline-flex align-items-center me-3">
                                    <?= $this->Form->checkbox('is_trainee', [
                                        'id' => 'is_trainee',
                                        'value' => '1',
                                        'class' => 'form-check-input form-check-input-secondary',
                                    ]) ?>
                                    <?= $this->Form->label('is_trainee', 'Trainee', [
                                        'for' => 'is_trainee',
                                        'class' => 'ms-1'
                                    ]) ?>
                                </div>
                            </div>
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
            ['controller' => 'Users', 'action' => 'index'],
            ['class' => 'btn btn-light']
        ) ?>
    </div>
    <?= $this->Form->end() ?>
    <!-- /default grid -->
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script([
    '/assets/js/vendor/forms/validation/validate.min.js',
    '/assets/js/custom/form_validation.js'
]) ?>
<?php $this->end(); ?>