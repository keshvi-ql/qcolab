<?php $this->start('css'); ?>
<?= $this->Html->css([
    'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css',
]) ?>
<?php $this->end(); ?>

<!-- Content area -->
<div class="content">
    <?= $this->Form->create($emailTemplate, ['class' => 'needs-validation', 'novalidate', 'id' => 'edit_email', 'name' => 'edit_email', 'type' => 'file']) ?>
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Edit Temaplate : <?= $emailTemplate->name ?></h6>
                        </div>
                        <div class="card-body border-top">
                            <div class="mb-3">
                                <?= $this->Form->label('name', 'Name:', ['class' => 'form-label']) ?>
                                <?= $this->Form->text('name', [
                                    'class' => 'form-control',
                                    'readonly' => 'readonly',
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Can not change template name',
                                    'placeholder' => 'name'
                                ]) ?>
                            </div>
                            <div class="mb-3">
                                <?= $this->Form->label('slug', 'Slug:', ['class' => 'form-label']) ?>
                                <?= $this->Form->text('slug', [
                                    'class' => 'form-control',
                                    'readonly' => 'readonly',
                                    'data-bs-popup' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                    'title' => 'Can not change template slug',
                                    'placeholder' => 'slug'
                                ]) ?>
                            </div>
                            <div class="mb-3">
                                <small class="req text-danger">*</small>
                                <?= $this->Form->label('subject', 'Subject:', ['class' => 'form-label']) ?>
                                <?= $this->Form->text('subject', [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Subject'
                                ]) ?>
                            </div>
                            <div class="mb-3">
                                <small class="req text-danger">*</small>
                                <?= $this->Form->label('message', 'Message Body:', ['class' => 'form-label']) ?>
                                <?= $this->Form->textarea('message', [
                                    'class' => 'form-control summernote',
                                    'id' => 'message',
                                    'rows' => 10,
                                    'placeholder' => 'Enter message'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><?= __('Available Placeholders for this template:') ?></h6>
                        </div>
                        <div class="card-body">
                            <!-- Panel body -->
                            <div class="panel-body">
                                <table id="" class="table">
                                    <tbody>
                                        <?php
                                        foreach ($placeholders as $key => $value) {
                                        ?>
                                            <tr>
                                                <td width="40%" align='right'><b><?php echo $value; ?>:</b></td>
                                                <td><a data-popup="tooltip" title="Click to add" data-bs-popup="tooltip" data-bs-placement="top" href="javascript:void(0);" class="copy"><?php echo $key; ?></a></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /Panel body -->
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
                ['controller' => 'EmailTemplates', 'action' => 'index'],
                ['class' => 'btn btn-light']
            ) ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /Content area -->

<?php $this->start('script'); ?>
<?= $this->Html->script([
    'https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js',
]) ?>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300, // Set editor height
            toolbar: [ // Customize toolbar
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    $('.copy').on('click', function(e) {
        e.preventDefault();
        $('.summernote').summernote('editor.insertText', $(this).text());
    });
</script>
<?php $this->end(); ?>