<?php

use App\Utility\SettingHelper;

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */
$loggedInUserId = $this->User->getLoginUserAttribute('id', 'No user');
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
            <? //= SettingHelper::getSettingValue('company_name'); 
            ?>
            <h5 class="py-sm-2 my-sm-1">Staff List</h5>
            <?php if ($this->User->hasPermission('Users', 'add')): ?>
                <?= $this->Html->link(
                    __('Add New') . ' <i class="ph-plus-circle ms-2"></i>',
                    ['action' => 'add'],
                    [
                        'class' => 'btn btn-primary',
                        'escape' => false,
                        'role' => 'button'
                    ]
                ) ?>
            <?php endif; ?>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <th width="2%">Profile</th>
                    <th width="10%">Employee Code</th>
                    <th width="15%">Name</th>
                    <th width="20%">Job Title</th>
                    <th width="15%">Email</th>
                    <th width="10%">Phone</th>
                    <th width="12%">Last Login</th>
                    <?php if ($this->User->hasPermission('Users', 'updateStatus')): ?>
                        <th width="8%" class="text-center">Status</th>
                    <?php endif; ?>
                    <th width="8%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <a href="#" class="d-inline-block me-3">
                                <?php
                                $profileImage = $user->profile_image;
                                echo $this->User->profileImage($profileImage, ['class' => 'rounded-circle', 'id' => 'profileCropImage', 'alt' => 'User Image', 'width' => '40', 'height' => '40'], $user->first_name, $user->last_name)
                                ?>
                            </a>
                        </td>
                        <td>
                            <?= h($user->employee_code) ?>
                        </td>
                        <td>
                            <?php if ($this->User->hasPermission('Users', 'edit')): ?>
                                <?= $this->Html->link(h($user->first_name) . ' ' . h($user->middle_name) . ' ' . h($user->last_name), ['action' => 'edit', $user->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'Edit', 'escape' => false]) ?>
                            <?php else: ?>
                                <?= h($user->first_name) . ' ' . h($user->middle_name) . ' ' . h($user->last_name) ?>
                            <?php  endif; ?>
                        </td>
                        <td><?= h($user->job_title) ?></td>
                        <td>
                            <a href="mailto:<?= h($user->email) ?>"><?= h($user->email) ?></a>
                        </td>
                        <td><?= !empty($user->phone_no) ? $user->phone_no : $user->alt_phone_no ?></td>
                        <td>
                            <abbr data-bs-popup="tooltip" data-bs-placement="top" title="<?= h($this->Date->format($user->last_login_at)) ?>">
                                <?= $this->Time->timeAgo($user->last_login_at); ?> ago
                            </abbr>
                        </td>
                        <?php if ($this->User->hasPermission('Users', 'updateStatus')): ?>
                            <td class="text-center">
                                <label class="form-switch">
                                    <input type="checkbox" class="form-check-input is-valid" name="status" id="status_<?= $user->id ?>"
                                        value="1" <?= $user->status ? 'checked' : '' ?>
                                        onclick="updateUserStatus(<?= $user->id ?>, this.checked)" <?= ($user->id === $loggedInUserId) ? 'disabled' : '' ?>>
                                </label>
                            </td>
                        <?php endif; ?>
                        <td class="actions text-center">
                            <?php if ($this->User->hasPermission('Users', 'edit')): ?>
                                <?= $this->Html->link(__('<i class="ph-eye"></i>'), ['action' => 'edit', $user->id], ['class' => 'text-secondary', 'data-bs-popup' => 'tooltip', 'data-bs-placement' => 'top', 'title' => 'View', 'escape' => false]) ?>
                            <?php endif; ?>

                            <?php if ($this->User->hasPermission('Users', 'delete')): ?>
                                <?php if (($user->id !== $loggedInUserId)): ?>
                                    <?= $this->Html->link(
                                        __('<i class="ph-trash"></i>'),
                                        '#',
                                        [
                                            'class' => 'text-danger sweet_warning',
                                            'escape' => false,
                                            'id' => $user->id,
                                            'data-bs-popup' => 'tooltip',
                                            'data-bs-placement' => 'top',
                                            'title' => 'Delete',
                                            'data-record-id' => $user->id,
                                            'data-url' => $this->Url->build(['action' => 'delete', $user->id]),
                                            'data-confirm' => 'Are you sure?',
                                        ]
                                    ); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<script>
    // Update user status
    function updateUserStatus(userId, status) {
        var newStatus = status ? 1 : 0;

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Users', 'action' => 'updateStatus', '_full' => true]) ?>/" + userId,
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify({
                status: newStatus
            }),
            contentType: 'application/json; charset=utf-8',
            headers: {
                'X-CSRF-Token': csrfToken
            },
            success: function(data) {
                if (data.success) {
                    // Update the status label or any element showing the status without reloading the page
                    var statusElement = $('#status_' + userId);
                    if (data.data.newStatus == 1) {
                        statusElement.prop('checked', true);
                        statusElement.closest('label').addClass('is-valid');
                    } else {
                        statusElement.prop('checked', false);
                        statusElement.closest('label').removeClass('is-valid');
                    }
                    swalInit.fire(
                        "Success",
                        data.message,
                        "success"
                    );
                } else {
                    swalInit.fire(
                        "Error",
                        data.message,
                        "error"
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                swalInit.fire(
                    "Error",
                    "Failed to update status. Please try again.",
                    "error"
                );
            }
        });
    }
</script>