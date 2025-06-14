<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">Leave Types</h5>
                    <?php if ($this->User->hasPermission('LeaveTypes', 'add')): ?>
                        <?= $this->Html->link(
                            __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                            '#addNewModal', // Change link to the modal ID
                            [
                                'class' => 'btn btn-primary',
                                'escape' => false,
                                'role' => 'button',
                                'data-bs-toggle' => 'modal', // Bootstrap attribute to toggle the modal
                                'data-bs-target' => '#addNewModal' // Target the modal with this ID
                            ]
                        ) ?>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <table class="table datatable-basic table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leaveTypes as $leaveType): ?>
                                <tr>
                                    <td>
                                        <span style="background-color:<?= $leaveType->color; ?>" class="color-tag"></span>
                                        &nbsp;<?= $leaveType->title; ?>
                                    </td>
                                    <td>
                                        <?= $leaveType->description; ?>
                                    </td>
                                    <td>
                                        <?= $leaveType->status; ?>
                                    </td>
                                    <td class="actions text-center">
                                        <?php if ($this->User->hasPermission('LeaveTypes', 'add')): ?>
                                            <?= $this->Html->link(
                                                __('<i class="ph-note-pencil"></i>'),
                                                '#',
                                                [
                                                    'class' => 'text-secondary edit-leave-type',
                                                    'data-id' => $leaveType->id,
                                                    'data-title' => $leaveType->title,
                                                    'data-color' => $leaveType->color,
                                                    'data-description' => $leaveType->description,
                                                    'data-status' => $leaveType->status,
                                                    'data-bs-toggle' => 'modal',
                                                    'data-bs-target' => '#addNewModal',
                                                    'escape' => false,
                                                    'title' => 'Edit',
                                                ]
                                            ) ?>
                                        <?php endif; ?>

                                        <?php if ($this->User->hasPermission('LeaveTypes', 'delete')): ?>
                                            <?= $this->Html->link(
                                                __('<i class="ph-trash"></i>'),
                                                '#',
                                                [
                                                    'class' => 'text-danger sweet_warning',
                                                    'escape' => false,
                                                    'id' => $leaveType->id,
                                                    'data-bs-popup' => 'tooltip',
                                                    'data-bs-placement' => 'top',
                                                    'title' => 'Delete',
                                                    'data-record-id' => $leaveType->id,
                                                    'data-url' => $this->Url->build(['action' => 'delete', $leaveType->id]),
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


<div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add Leave Type</h5> <!-- Title will be updated dynamically -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <!-- Hidden field for ID -->
                <?= $this->Form->hidden('id', ['id' => 'leaveTypeId']) ?>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('title', 'Title <span class="text-danger">*</span>', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->text('title', [
                                'id' => 'leaveTypeTitle',
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
                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('description', 'Description') ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->textarea('description', [
                                'id' => 'leaveTypeDescription',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Description',
                                'rows' => 3
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <?= $this->Form->label('status', 'Status') ?>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="mb-3">
                            <?= $this->Form->radio('status', [
                                ['value' => 'active', 'text' => ' Active'],
                                ['value' => 'inactive', 'text' => ' Inactive']
                            ], [
                                'id' => 'leaveTypeStatus',
                                'class' => 'form-check-input form-check-input-secondary',
                                'label' => ['class' => 'form-check-label ms-2'],
                                'default' => 'active',
                            ]) ?>
                        </div>
                    </div>
                </div>
                <!-- Add other fields as needed -->
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

        $(document).on('click', '.edit-leave-type', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var color = $(this).data('color');
            var description = $(this).data('description');
            var status = $(this).data('status');

            // Set the modal title for edit
            $('#addNewModalLabel').text('Edit Leave Type');

            // Fill the form with existing data
            $('#leaveTypeId').val(id);
            $('#leaveTypeTitle').val(title);
            $('#leaveTypeDescription').val(description);
            $("input[name='status'][value='" + status + "']").prop('checked', true);

            // Remove the active class from all color options
            $(".color-palet .active").removeClass("active");

            // Check if the color is in the predefined palette
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
            if (!$(this).hasClass('edit-leave-type')) {
                $('#addNewModalLabel').text('Add Leave Type');
                $('#addNewForm')[0].reset(); // Reset the form
                $('#leaveTypeId').val(''); // Clear hidden ID
                $(".color-palet .active").removeClass("active"); // Remove active class from all colors
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
            var titleField = $('#leaveTypeTitle');
            var titleValue = titleField.val().trim();
            if (titleValue === '') {
                isValid = false;
                var errorMessage = $('<div class="error-message text-danger">Title is required.</div>');
                titleField.after(errorMessage);
            }

            if (isValid) {
                var formData = $('#addNewForm').serialize(); // Serialize the form data

                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'LeaveTypes', 'action' => 'add']) ?>',
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