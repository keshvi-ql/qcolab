<?php

use Cake\Routing\Router;

$isEditMode = !empty($leave);
$leave_id = $isEditMode ? $leaveDetails['leave_application']['id'] : '';
$leave_type_id = $isEditMode ? $leaveDetails['leave_application']['leave_type_id'] : '';
$applicant_id = $isEditMode ? $leaveDetails['leave_application']['applicant_id'] : '';
$startDate = $isEditMode ? $leaveDetails['leave_application']['start_date'] : '';
$endDate = $isEditMode ? $leaveDetails['leave_application']['end_date'] : '';
$total_hours = $isEditMode ? $leaveDetails['leave_application']['total_hours'] : '';
$total_days = $isEditMode ? $leaveDetails['leave_application']['total_days'] : '';
$half_day_type = $isEditMode ? $leaveDetails['leave_application']['half_day_type'] : '';
$reason = $isEditMode ? $leaveDetails['leave_application']['reason'] : '';
$status = $isEditMode ? $leaveDetails['leave_application']['status'] : '';
$leaveImages = $isEditMode ? $leaveDetails['leave_application']['files'] : '';

if ($total_days == 1 && !$half_day_type) {
    $duration = 'single_day';
} else if ($total_days > 1) {
    $duration = 'multiple_days';
} else if ($half_day_type) {
    $duration = 'half_day';
}

$imageName = $leaveDetails['applicant_avatar'];
$approveName = $leaveDetails['checker_name'];
$approve_img = $leaveDetails['checker_avatar'];

?>

<?= $this->Form->create(null, ['type' => 'post', 'files' => true]) ?>

<div class="modal-header">
    <h5 class="modal-title" id="addNewLeaveModalLabel">Apply Leave</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <?php if ((empty($status) && $this->User->isAdmin())) { ?>
        <div class="row">
            <div class="col-lg-2">
                <div class="mb-3">
                    <?= $this->Form->label('applicant_id', 'Applicant Name') ?>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="mb-3">
                    <div class="mb-3">
                        <?= $this->Form->select('applicant_id', $users, [
                            'class' => 'select form-select',
                            'empty' => '-- Select Staff --',
                            'id' => 'applicant_id',
                            'required' => true
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($leave_id) { ?>
        <?= $this->Form->hidden('applicant_id', ['value' => $applicant_id]) ?>
    <?php } ?>

    <?php if (!empty($status) && $status != 'pending') { ?>
        <div class="d-flex bg-white">
            <div class="flex-shrink-0">
                <span class="avatar avatar-sm">
                    <?= $this->User->profileImage($imageName, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'User Avatar']) ?>
                </span>
            </div>
            <div class="ps-2 w-100 pt5">
                <div class="m0">
                    <?= $leaveDetails['applicant_name'] ?> </div>
                <p><span class="badge bg-primary"><?= $leaveDetails['applicant_job_title'] ?></span> </p>
            </div>
        </div>
    <?php } ?>

    <!-- Hidden field for ID -->
    <?= $this->Form->hidden('id', ['id' => 'leaveId', 'value' => $leave_id]) ?>
    <?= $this->Form->hidden('uploadedFiles[]', ['id' => 'uploadFiles']) ?>

    <div class="row">
        <div class="col-lg-2">
            <div class="mb-3">
                <?= $this->Form->label('leave_type_id', 'Leave type') ?>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="mb-3">
                <?php if (empty($status) || $status == 'pending') { ?>
                    <?= $this->Form->select('leave_type_id', $LeaveTypes, [
                        'class' => 'select form-select',
                        'empty' => '--',
                        'id' => 'leave_type_id',
                        'value' => $leave_type_id
                    ]); ?>
                <?php } else { ?>
                    <p><span style="background-color:<?= $leaveDetails['leave_application']['leave_type']['color'] ?>" class="color-tag"></span><?= $leaveDetails['leave_application']['leave_type']['title'] ?></p>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if (empty($status) || $status == 'pending') { ?>
        <div class="row">
            <div class="col-lg-2">
                <div class="mb-3">
                    <?= $this->Form->label('duration', 'Duration') ?>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="mb-3">
                    <?= $this->Form->radio('duration', [
                        ['value' => 'single_day', 'text' => ' Single day'],
                        ['value' => 'multiple_days', 'text' => ' Multiple days'],
                        ['value' => 'half_day', 'text' => ' Half Day']
                    ], [
                        'id' => 'duration',
                        'class' => 'form-check-input form-check-input-secondary',
                        'label' => ['class' => 'form-check-label ms-2'],
                        'default' => $duration ?? 'single_day',
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Single Day Section -->
        <div id="single_day_section" class="duration-section">
            <div class="row">
                <div class="col-lg-2">
                    <div class="mb-3">
                        <?= $this->Form->label('single_date', 'Date') ?>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="mb-3">
                        <?= $this->Form->text('single_date', [
                            'id' => 'single_date',
                            'class' => 'form-control datepicker-basic',
                            'placeholder' => 'Enter Date',
                            'value' => $startDate
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Multiple Days Section -->
        <div id="multiple_days_section" class="duration-section hidden">
            <div class="row">
                <div class="col-lg-2">
                    <div class="mb-3">
                        <?= $this->Form->label('start_date', 'Start Date') ?>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="mb-3">
                        <?= $this->Form->text('start_date', [
                            'id' => 'start_date',
                            'class' => 'form-control datepicker-basic',
                            'placeholder' => 'Enter start Date',
                            'value' => $startDate
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <div class="mb-3">
                        <?= $this->Form->label('end_date', 'End Date') ?>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="mb-3">
                        <?= $this->Form->text('end_date', [
                            'id' => 'end_date',
                            'class' => 'form-control datepicker-basic',
                            'placeholder' => 'Enter start Date',
                            'value' => $endDate
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hours Section -->
        <div id="half_day_section" class="duration-section hidden">
            <div class="row">
                <div class="col-lg-2">
                    <div class="mb-3">
                        <?= $this->Form->label('hour_date', 'Date') ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <?= $this->Form->text('hour_date', [
                            'id' => 'hour_date',
                            'class' => 'form-control datepicker-basic',
                            'placeholder' => 'Enter Date',
                            'value' => $startDate
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <?= $this->Form->label('half_day_type', 'Type') ?>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <select name="half_day_type" id="half_day_type" class='form-control' data-placeholder="Select Hours">
                            <option value="pre_lunch" <?= $half_day_type == 'pre_lunch' ? 'selected' : '' ?>>Pre Lunch</option>
                            <option value="post_lunch" <?= $half_day_type == 'post_lunch' ? 'selected' : '' ?>>Post Lunch</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { ?>

        <div class="row">
            <div class="col-lg-2">
                <div class="mb-3">
                    <?= $this->Form->label('date', 'Date') ?>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="mb-3">
                    <p><?= $startDate ?>&nbsp;<?= $endDate ? ' to ' . $endDate : '' ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-2">
                <div class="mb-3">
                    <?= $this->Form->label('duration', 'Duration') ?>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="mb-3">
                    <p><?= $total_days ?> Days (<?= $total_hours ?> Hours)</p>
                </div>
            </div>
        </div>

    <?php } ?>

    <div class="row">
        <div class="col-lg-2">
            <div class="mb-3">
                <?= $this->Form->label('reason', 'Reason') ?>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="mb-3">
                <?php if (empty($status) || $status == 'pending') { ?>
                    <?= $this->Form->textarea('reason', [
                        'id' => 'reason',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Reason',
                        'rows' => 3,
                        'value' => $reason
                    ]) ?>
                <?php } else { ?>
                    <p><?= $reason ?></p>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if (!empty($status)) { ?>
        <div class="row">
            <div class="col-lg-2">
                <div class="mb-3">
                    <?= $this->Form->label('status', 'Status') ?>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="mb-3">
                    <?= $status ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($status == 'approved' || $status == 'rejected') { ?>
        <div class="row">
            <div class="col-lg-3">
                <div class="mb-3">
                    <?= $this->Form->label('approved_by', 'Approved by') ?>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="mb-3">
                    <p>
                        <?= $this->User->profileImage($approve_img, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'Checker Avatar']) ?><?= $approveName ?>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>

    <?= $this->Form->file('file[]', ['type' => 'file', 'id' => 'uploadFilesInput', 'style' => 'display:none;', 'accept' => 'image/*', 'multiple' => true]) ?>

    <div id="image-preview-container" class="d-flex flex-wrap">
        <?php if ($leaveImages): ?>
            <?php $leaveimgs = explode(',', $leaveImages);
            foreach ($leaveimgs as $leaveimg):
                if ($leaveimg):
                    $imgUrl = Router::url('/uploads/' . $leaveimg, true); ?>
                    <div class="uploaded-image" data-filename="<?= $leaveimg ?>" style="position: relative; display: inline-block; margin-right:5px">
                        <img src="<?= $imgUrl; ?>" alt="uploaded image" class="img-thumbnail" width="100" />
                        <?php if (empty($status) || $status == 'pending') { ?>
                            <button type="button" class="btn btn-danger btn-sm remove-image-during-edit" data-filename="<?= $leaveimg ?>" data-module="LeaveApplications" data-id="<?= $leave_id ?>" style="position: absolute; top: 5px; right: 5px; padding: 2px 5px;">X</button>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer">
    <?php if (empty($status) || $status == 'pending') { ?>
        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round dz-clickable" type="button" style="color:#7988a2" id="japmejtycgpntxd"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-camera icon-16">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                <circle cx="12" cy="13" r="4"></circle>
            </svg> Upload File</button>
    <?php } ?>

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

    <?php if (empty($status) || $status == 'pending') { ?>
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
    <?php } ?>

    <?= $this->Form->end() ?>

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'LeaveApplications', 'action' => 'updateStatus'],
        'class' => 'general-form status-form',
        'role' => 'form',
    ]) ?>
    <?= $this->Form->hidden('id', ['value' => $leave_id]) ?>
    <?= $this->Form->hidden('notification_id', ['id' => 'set_notification_id']) ?>
    <?= $this->Form->hidden('status', ['id' => 'status_input']) ?>

    <?php if ($this->User->isAdmin() && $status === "pending") { ?>
        <button data-status="rejected" type="button" class="btn btn-danger btn-sm update-status">
            <span data-feather="x-circle" class="icon-16"></span> <?= __('Reject') ?>
        </button>
        <button data-status="approved" type="button" class="btn btn-success btn-sm update-status">
            <span data-feather="check-circle" class="icon-16"></span> <?= __('Approve') ?>
        </button>
    <?php } ?>
    <?= $this->Form->end() ?>
</div>