<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\Cake\Datasource\EntityInterface> $leadStatuses
 */
?>
<style>
    .color-tag {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin: 2px 10px 0 0;
        transition: all 300ms ease;
        -moz-transition: all 0.1s;
        -webkit-transition: all 0.1s;
        transition: all 0.1s;
    }

    .color-tag.clickable:hover {
        -moz-transform: scale(1.5);
        -webkit-transform: scale(1.5);
        transform: scale(1.5);
    }

    .color-tag.active {
        border-radius: 50%;
    }

    .input-color {
        width: 50px !important;
        height: 15px !important;
        padding: 0 !important;
        border: none !important;
    }

    .input-color.active {
        overflow: hidden;
        border-radius: 20px !important;
    }
</style>

<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
                    <div class="d-flex gap-3">
                        <?php if ($this->User->hasPermission('LeadStatuses', 'index')): ?>
                            <?= $this->Html->link(
                                'Lead Status',
                                ['controller' => 'LeadStatuses', 'action' => 'index'],
                                [
                                    'class' => 'py-sm-2 my-sm-1',
                                    'escape' => false,
                                ]
                            ) ?>
                        <?php endif; ?>
                        <?php if ($this->User->hasPermission('LeadSources', 'index')): ?>
                            <?= $this->Html->link(
                                'Lead Source',
                                ['controller' => 'LeadSources', 'action' => 'index'],
                                [
                                    'class' => 'py-sm-2 my-sm-1 text-black',
                                    'escape' => false,
                                ]
                            ) ?>
                        <?php endif; ?>
                        <?php if ($this->User->hasPermission('LeadProfiles', 'index')): ?>
                            <?= $this->Html->link(
                                'Lead Profiles',
                                ['controller' => 'LeadProfiles', 'action' => 'index'],
                                [
                                    'class' => 'py-sm-2 my-sm-1 text-black',
                                    'escape' => false,
                                ]
                            ) ?>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex">
                        <?php if ($this->User->hasPermission('LeadStatuses', 'add')): ?>
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

                        <?php if ($this->User->hasPermission('LeadStatuses', 'delete')): ?>
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
                                        <?php if ($this->User->hasPermission('LeadStatuses', 'delete')): ?>
                                            <input type="checkbox" class="form-check-input form-check-input-secondary" id="select_all">
                                        <?php endif; ?>
                                    </th>
                                    <th>Title</th>
                                    <th width="8%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leadStatuses as $status): ?>
                                    <tr>
                                        <td>
                                            <?php if ($this->User->hasPermission('LeadStatuses', 'delete')): ?>
                                                <input type="checkbox" class="select_checkbox form-check-input form-check-input-secondary" value="<?= $status->id ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span style="background-color:<?= $status->color; ?>" class="color-tag"></span>
                                            &nbsp;<?= $status->title; ?>
                                        </td>
                                        <td class="actions text-center">
                                            <?php if ($this->User->hasPermission('LeadStatuses', 'add')): ?>
                                                <?= $this->Html->link(
                                                    __('<i class="ph-note-pencil"></i>'),
                                                    '#',
                                                    [
                                                        'class' => 'text-secondary edit-lead-status',
                                                        'data-id' => $status->id,
                                                        'data-title' => $status->title,
                                                        'data-color' => $status->color,
                                                        'data-bs-toggle' => 'modal',
                                                        'data-bs-target' => '#addNewModal',
                                                        'escape' => false,
                                                        'title' => 'Edit',
                                                    ]
                                                ) ?>
                                            <?php endif; ?>

                                            <?php if ($this->User->hasPermission('LeadStatuses', 'delete')): ?>
                                                <?= $this->Html->link(
                                                    __('<i class="ph-trash"></i>'),
                                                    '#',
                                                    [
                                                        'class' => 'text-danger sweet_warning',
                                                        'escape' => false,
                                                        'id' => $status->id,
                                                        'data-bs-popup' => 'tooltip',
                                                        'data-bs-placement' => 'top',
                                                        'title' => 'Delete',
                                                        'data-record-id' => $status->id,
                                                        'data-url' => $this->Url->build(['action' => 'delete', $status->id]),
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
                <h5 class="modal-title" id="addNewModalLabel">Add Lead Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'leadStatusId']) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'leadStatusTitle',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Title',
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('color', 'Color') ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <div class="color-palet">
                                <?php
                                $selected_color = "#4A8AF4";
                                $colors = array("#83c340", "#29c2c2", "#2d9cdb", "#aab7b7", "#f1c40f", "#e18a00", "#e74c3c", "#d43480", "#ad159e", "#37b4e1", "#34495e", "#dbadff");
                                $custom_color_active_class = "active";

                                foreach ($colors as $color) {
                                    $active_class = "";
                                    if ($selected_color === $color) {
                                        $active_class = "active";
                                        $custom_color_active_class = "";
                                    }
                                    echo "<span style='background-color:" . $color . "' class='color-tag clickable mr15 " . $active_class . "' data-color='" . $color . "'></span>";
                                }
                                ?>
                                <input type="color" id="custom-color" class="input-color <?php echo $custom_color_active_class; ?>" name="color" value="#4A8AF4" />
                            </div>
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
        $(".color-palet span").click(function() {
            $(".color-palet").find(".active").removeClass("active");
            $(this).addClass("active");
            $("#custom-color").val($(this).attr("data-color"));
        });

        $(".color-palet #custom-color").click(function() {
            $(".color-palet").find(".active").removeClass("active");
            $(this).addClass("active");
        });

        $(document).on('click', '.edit-lead-status', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var color = $(this).data('color');

            $('#addNewModalLabel').text('Edit Lead Status');

            $('#leadStatusId').val(id);
            $('#leadStatusTitle').val(title);

            $(".color-palet .active").removeClass("active");

            var colorFoundInPalette = false;
            $(".color-palet span").each(function() {
                if ($(this).data('color') === color) {
                    $(this).addClass("active"); // Set the active class for the matching color
                    $("#custom-color").val(color); // Update the custom color input value
                    colorFoundInPalette = true;
                }
            });

            // If the color is not found in the palette, set the custom color input as active
            if (!colorFoundInPalette) {
                $("#custom-color").val(color).addClass("active");
            }
        });

        $(document).on('click', '[data-bs-target="#addNewModal"]', function() {
            if (!$(this).hasClass('edit-lead-status')) {
                $('#addNewModalLabel').text('Add Lead Status');
                $('#addNewForm')[0].reset();
                $('#leadStatusId').val('');
                $(".color-palet .active").removeClass("active");
                $("#custom-color").removeClass("active").val("#4A8AF4");
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
            var titleField = $('#leadStatusTitle');
            var titleValue = titleField.val().trim();
            if (titleValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Title is required.</div>');
                titleField.after(errorMessage);
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize(); // Serialize the form data

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'LeadStatuses', 'action' => 'add']) ?>',
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