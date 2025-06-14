<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    .select2-container {
        min-width: 100% !important;
    }
</style>

<div class="content">
    <div class="row">
        <div class="col-lg-2">
            <?= $this->element('inner-sidebar'); ?>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">Notifications Settings</h5>
                </div>

                <div class="card-body">
                    <table class="table datatable-basic table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Notify To
                                </th>
                                <th>
                                    Module
                                </th>
                                <th>
                                    Enable Email
                                </th>
                                <th>
                                    Enable System
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notification):
                                $yes = "<i class='ph-check-circle'></i>";
                                $no = "<i class='ph-check' style='opacity:0.2'></i>";
                                $notify_to = "";
                                if (!empty($notification->notify_to_team_members_names)) {
                                    $notify_to .= "<li>Team Members: " . implode(', ', $notification->notify_to_team_members_names) . "</li>";
                                }

                                if ($notify_to) {
                                    $notify_to = "<ul class='pl15'>" . $notify_to . "</ul>";
                                }
                            ?>
                                <tr>
                                    <td>
                                        <?= $notification->type; ?>
                                    </td>
                                    <td>
                                        <?= $notify_to; ?>
                                    </td>
                                    <td>
                                        <?= $notification->module; ?>
                                    </td>
                                    <td>
                                        <?= $notification->enable_email ? $yes : $no; ?>
                                    </td>
                                    <td>
                                        <?= $notification->enable_system ? $yes : $no; ?>
                                    </td>
                                    <td>
                                        <?php if ($this->User->hasPermission('Notifications', 'index')): ?>
                                            <?= $this->Html->link(__('<i class="ph-note-pencil"></i>'), '#editNotificationSettingModal', [
                                                'class' => 'text-secondary editNotification',
                                                'data-id' => $notification->id,
                                                'data-enable-email' => $notification->enable_email ? '1' : '0',
                                                'data-enable-system' => $notification->enable_system ? '1' : '0',
                                                'data-notify-to-team-members' => is_array($notification->notify_to_team_members)
                                                    ? implode(',', $notification->notify_to_team_members)
                                                    : $notification->notify_to_team_members,
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#editNotificationSettingModal',
                                                'escape' => false
                                            ]) ?>
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

<!-- Bootstrap Modal Structure -->
<div class="modal fade" id="editNotificationSettingModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- The form inside the modal -->
                <?= $this->Form->create(null, ['id' => 'notificationSettingsForm', 'url' => ['controller' => 'Notifications', 'action' => 'saveNotificationSettings'], 'type' => 'post']) ?>
                <?= $this->Form->control('id', ['type' => 'hidden', 'value' => '']) ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <?= $this->Form->label('enable_email', 'Enable Email', ['style' => 'cursor: pointer;']) ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <?= $this->Form->checkbox('enable_email', ['id' => 'enable_email']) ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <?= $this->Form->label('enable_system', 'Enable System', ['style' => 'cursor: pointer;']) ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <?= $this->Form->checkbox('enable_system', ['id' => 'enable_system']) ?>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <?= $this->Form->label('notify_to_team_members', 'Team Members') ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <select class="select2" name="notify_to_team_members[]" multiple="multiple" data-placeholder="Select team members">
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user->id ?>"><?= $user->first_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Add more fields as needed -->
                <?= $this->Form->end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveNotificationSettings">Save Settings</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: "bootstrap-5"
        });
    });

    $(document).on('click', '.editNotification', function() {
        var id = $(this).data('id');
        var enableEmail = $(this).data('enable-email');
        var enableSystem = $(this).data('enable-system');
        var notifyToTeamMembers = $(this).data('notify-to-team-members');

        // If notifyToTeamMembers is a string, split it into an array
        if (typeof notifyToTeamMembers === 'string') {
            notifyToTeamMembers = notifyToTeamMembers.split(',');
        }

        // Set the values in the modal fields
        $('#notificationSettingsForm input[name="id"]').val(id);
        $('#enable_email').prop('checked', enableEmail);
        $('#enable_system').prop('checked', enableSystem);

        // Set values into select2
        $('.select2').val(notifyToTeamMembers).trigger('change');
    });

    $(document).on('click', '#saveNotificationSettings', function(e) {
        e.preventDefault();

        var form = $('#notificationSettingsForm'); // Form inside the modal

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Success',
                    //     text: response.message,
                    // });
                    $('#editNotificationSettingModal').modal('hide');
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred',
                });
            }
        });
    });
</script>