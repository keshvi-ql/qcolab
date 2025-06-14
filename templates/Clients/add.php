<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
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
    <?= $this->Form->create(null, ['class' => 'needs-validation', 'novalidate', 'id' => 'add_client', 'name' => 'add_client', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Client</h5>
                </div>

                <div class="card-body">
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
                                <?= $this->Form->label('email', 'Email', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->email('email', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Email',
                                ]) ?>
                                <div class="invalid-feedback">Please Enter Valid Email</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('alt_email', 'Alternate Email', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->email('alt_email', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Alternate Email',
                                ]) ?>
                                <div class="invalid-feedback">Please Enter Valid Email</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('phone_no', 'Phone No', ['class' => 'form-label']) ?>
                                <?= $this->Form->number('phone_no', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Phone No'
                                ]) ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('skype', 'Skype', ['class' => 'form-label']) ?>
                                <?= $this->Form->text('skype', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Skype'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('country', 'Country', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->select('country', $countries, [
                                    'class' => 'select form-select',
                                    'empty' => '-- Select Country --',
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('source', 'Source', ['class' => 'form-label', 'escape' => false]) ?>
                                <?= $this->Form->select('source', $sources, [
                                    'class' => 'select form-select',
                                    'empty' => '-- Select Source --',
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <?= $this->Form->label('note', 'Note', ['class' => 'form-label']) ?>
                                <?= $this->Form->textarea('note', [
                                    'class' => 'form-control',
                                    'rows' => 4,
                                    'placeholder' => 'Enter Note'
                                ]) ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <p>Is Favorite ?</p>
                                <?= $this->Form->checkbox('favorite', [
                                    'id' => 'favorite',
                                    'value' => '1',
                                    'class' => 'form-check-input form-check-input-secondary',
                                ]) ?>
                                <?= $this->Form->label('favorite', 'Favorite', [
                                    'for' => 'favorite',
                                    'class' => 'ms-1'
                                ]) ?>
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
            ['controller' => 'Clients', 'action' => 'index'],
            ['class' => 'btn btn-light']
        ) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script([
    '/assets/js/vendor/forms/validation/validate.min.js',
    '/assets/js/custom/form_validation.js'
]) ?>
<?php $this->end(); ?>