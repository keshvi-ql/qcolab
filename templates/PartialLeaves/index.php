<?php

use Cake\Routing\Router;
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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
            <h5 class="py-sm-2 my-sm-1">Partial Leaves</h5>
            <?php if ($this->User->hasPermission('PartialLeaves', 'add')): ?>
                <?php if ($this->User->isAdmin()) { ?>
                    <?= $this->Html->link(
                        __('Assign Leave') . ' <i class="ph-plus-circle ms-2"></i>',
                        '#',
                        [
                            'class' => 'btn btn-primary assign-partial-leave',
                            'escape' => false,
                            'role' => 'button',
                        ]
                    ) ?>
                <?php } else { ?>
                    <?= $this->Html->link(
                        __('Apply Leave') . ' <i class="ph-plus-circle ms-2"></i>',
                        '#',
                        [
                            'class' => 'btn btn-primary add-partial-leave',
                            'escape' => false,
                            'role' => 'button',
                        ]
                    ) ?>
                <?php } ?>
            <?php endif; ?>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <?php if ($this->User->isAdmin()) { ?>
                        <th>Applicant</th>
                    <?php } ?>
                    <th>Date</th>
                    <th>Total Hours</th>
                    <th>Status</th>
                    <th class="actions text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partialLeaves as $partialLeave): ?>
                    <tr>
                        <?php if ($this->User->isAdmin()) {
                            $imageName = $partialLeave->applicant->profile_image; ?>
                            <td>
                                <p>
                                    <?= $this->User->profileImage($imageName, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'User Avatar']) ?>
                                    <?= $partialLeave->applicant->first_name ?>&nbsp;<?= $partialLeave->applicant->last_name ?>
                                </p>
                            </td>
                        <?php } ?>
                        <td>
                            <?= $this->Date->format($partialLeave->start_date, false) ?>
                        </td>
                        <td>
                            <?= $partialLeave->total_hours ?> Hours
                        </td>
                        <td>
                            <?= $partialLeave->status ?>
                        </td>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('PartialLeaves', 'add')) { ?>
                                <?= $this->Html->link(
                                    __('<i class="ph-note-pencil"></i>'),
                                    '#',
                                    [
                                        'class' => 'text-secondary edit-partial-leave',
                                        'escape' => false,
                                        'data-id' => $partialLeave->id,
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
                                        'id' => $partialLeave->id,
                                        'data-bs-popup' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                        'title' => 'Delete',
                                        'data-record-id' => $partialLeave->id,
                                        'data-url' => $this->Url->build(['action' => 'delete', $partialLeave->id]),
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

<div class="modal fade" id="addNewPartialLeaveModal" tabindex="-1" aria-labelledby="addNewPartialLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Trigger Add/Edit Partial Leave modal
        $('.add-partial-leave, .edit-partial-leave, .assign-partial-leave').on('click', function(e) {
            e.preventDefault();
            const id = $(this).hasClass('edit-partial-leave') ? $(this).data('id') : null;
            partialLeave(id);
        });
    });

    function partialLeave(id = null) {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build(['controller' => 'PartialLeaves', 'action' => 'add']); ?>/' + (id ? id : ''),
            success: function(response) {
                $("#addNewPartialLeaveModal .modal-content").html(response);
                $("#addNewPartialLeaveModal").modal('show');
                $('#addNewPartialLeaveModalLabel').text(id ? 'Partial Leave' : 'Add Partial Leave');
                $('.upload-file-button').click(e => $('#uploadFilesInput').click());

                DateTimePickers.init();
                initializeUploadImage();
                deleteUploadedFile();
                updateStatus();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>