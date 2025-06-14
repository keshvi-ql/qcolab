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
            <h5 class="py-sm-2 my-sm-1">Leave</h5>
            <div>
                <?php if ($this->User->hasPermission('LeaveApplications', 'add')): ?>
                    <?php if ($this->User->isAdmin()) { ?>
                        <?= $this->Html->link(
                            __('Assign Leave') . ' <i class="ph-plus-circle ms-2"></i>',
                            '#',
                            [
                                'class' => 'btn btn-primary assign-leave',
                                'escape' => false,
                                'role' => 'button',
                            ]
                        ) ?>
                    <?php } else { ?>
                        <?= $this->Html->link(
                            __('Apply Leave') . ' <i class="ph-plus-circle ms-2"></i>',
                            '#',
                            [
                                'class' => 'btn btn-primary add-leave',
                                'escape' => false,
                                'role' => 'button',
                            ]
                        ) ?>
                    <?php } ?>
                <?php endif; ?>
            </div>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <?php if ($this->User->isAdmin()) { ?>
                        <th>Applicant</th>
                    <?php } ?>
                    <th>Leave Type</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th class="actions text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaveApplications as $leaveApplication): ?>
                    <tr>
                        <?php if ($this->User->isAdmin()) {
                            $imageName = $leaveApplication->applicant->profile_image; ?>
                            <td>
                                <p>
                                    <?= $this->User->profileImage($imageName, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'User Avatar']) ?>
                                    <?= $leaveApplication->applicant->first_name ?>&nbsp;<?= $leaveApplication->applicant->last_name ?>
                                </p>
                            </td>
                        <?php } ?>
                        <td>
                            <p><span style="background-color:<?= $leaveApplication->leave_type->color ?>" class="color-tag"></span><?= $leaveApplication->leave_type->title ?></p>
                        </td>
                        <td>
                            <?= $this->Date->format($leaveApplication->start_date, false) ?>&nbsp;<?= $leaveApplication->end_date ? ' to ' . $this->Date->format($leaveApplication->end_date, false) : '' ?>
                        </td>
                        <td>
                            <?= $leaveApplication->total_days ?> Days (<?= $leaveApplication->total_hours ?> Hours)
                        </td>
                        <td>
                            <?= $leaveApplication->status ?>
                        </td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('LeaveApplications', 'add')) { ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-secondary edit-leave',
                                        'escape' => false,
                                        'data-id' => $leaveApplication->id,
                                        'role' => 'button',
                                    ]
                                ) ?>
                            <?php } ?>
                            <?php /* if ($this->User->isAdmin()) { ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-trash"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-danger sweet_warning',
                                        'escape' => false,
                                        'id' => $leaveApplication->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $leaveApplication->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $leaveApplication->id]),
                                        'data-confirm' => 'Are you sure?',
                                    ]
                                ); ?>
                            <?php } */ ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addNewLeaveModal" tabindex="-1" aria-labelledby="addNewLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.add-leave, .edit-leave, .assign-leave').on('click', function(e) {
            e.preventDefault();
            const id = $(this).hasClass('edit-leave') ? $(this).data('id') : null;
            leave(id);
        });
    });

    function leave(id = null) {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build(['controller' => 'LeaveApplications', 'action' => 'add']); ?>/' + (id ? id : ''),
            success: function(response) {
                $("#addNewLeaveModal .modal-content").html(response);
                $("#addNewLeaveModal").modal('show');
                $('#addNewLeaveModalLabel').text(id ? 'Leave' : 'Add Leave');
                $('.upload-file-button').click(e => $('#uploadFilesInput').click());

                $('input[name="duration"]').change(function() {
                    toggleDurationFields();
                });

                initializeUploadImage();
                deleteUploadedFile();
                toggleDurationFields();
                updateStatus();
                DateTimePickers.init();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function toggleDurationFields() {
        var selectedDuration = $('input[name="duration"]:checked').val();

        $('.duration-section').hide();

        if (selectedDuration === 'single_day') {
            $('#single_day_section').show();
        } else if (selectedDuration === 'multiple_days') {
            $('#multiple_days_section').show();
        } else if (selectedDuration === 'half_day') {
            $('#half_day_section').show();
        }
    }
</script>