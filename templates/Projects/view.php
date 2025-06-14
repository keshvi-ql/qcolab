<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Project $project
 */

$loggedInUser = $this->request->getAttribute('identity');

?>

<style>
    .nav-tabs-solid {
        --nav-tabs-bg: white;
    }
</style>

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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <!-- nav nav-tabs wmin-lg-200 me-lg-3 mb-3 mb-lg-0 -->
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item">
                                <a href="#tab1" class="nav-link <?= $activeTab == 'tab1' ? 'active' : '' ?>" data-bs-toggle="tab">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab2" class="nav-link <?= $activeTab == 'tab2' ? 'active' : '' ?>" data-bs-toggle="tab">Task List</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab3" class="nav-link <?= $activeTab == 'tab3' ? 'active' : '' ?>" data-bs-toggle="tab">Notes</a>
                            </li>

                            <li class="nav-item">
                                <a href="#tab4" class="nav-link <?= $activeTab == 'tab4' ? 'active' : '' ?>" data-bs-toggle="tab">Files </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content">
        <div class="tab-pane fade <?= $activeTab == 'tab1' ? 'show active' : '' ?>" id="tab1">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <ul class="list-group list-group-flush border-top">
                            <li class="list-group-item">
                                <span>Start Date: <?= $this->Date->format($project->start_date, false) ?></span>
                            </li>
                            <li class="list-group-item">
                                <span>Deadline: <?= $project->deadline ? $this->Date->format($project->deadline, false) : ""; ?></span>
                            </li>
                            <li class="list-group-item">
                                <span>Client: <span class="text-secondary"><?= $project->clients->first_name ?> <?= $project->clients->last_name ?></span></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0">Flow Chart</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0">Activity</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Project Members</h5>

                                <?php if ($this->User->hasPermission('Projects', 'addMember')): ?>
                                    <?= $this->Html->link(
                                        ' <i class="ph-plus-circle me-2"></i>' . __('Add New'),
                                        '#addNewModal',
                                        [
                                            'class' => 'btn btn-outline-light btn-sm',
                                            'escape' => false,
                                            'role' => 'button',
                                            'data-bs-toggle' => 'modal',
                                            'data-bs-target' => '#addNewModal'
                                        ]
                                    ) ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush border-top">
                            <?php foreach ($projectMembers as $members): ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php
                                            $imageName = $members->user->profile_image;
                                            echo $this->User->profileImage(
                                                $imageName,
                                                ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'],
                                                $members->user->first_name,
                                                $members->user->last_name
                                            );
                                            ?>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <div class="fw-semibold">
                                                        <?php
                                                        echo h($members->user->first_name) . ' ' . h($members->user->last_name);
                                                        ?>
                                                    </div>

                                                    <div class="text-muted">
                                                        <?php
                                                        if ($members->user->is_admin) {
                                                            echo 'Admin';
                                                        } else {
                                                            echo h($members->user->job_title);
                                                        }
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <?= $this->Html->link(
                                                        __('<i class="ph-trash"></i>'),
                                                        '#',
                                                        [
                                                            'class' => 'text-danger sweet_warning',
                                                            'escape' => false,
                                                            'id' => $members->id,
                                                            'data-bs-popup' => 'tooltip',
                                                            'data-bs-placement' => 'top',
                                                            'title' => 'Delete',
                                                            'data-record-id' => $members->id,
                                                            'data-url' => $this->Url->build(['action' => 'deleteMember', $members->id]),
                                                            'data-confirm' => 'Are you sure?',
                                                        ]
                                                    ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade <?= $activeTab == 'tab2' ? 'show active' : '' ?>" id="tab2">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Task List</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade <?= $activeTab == 'tab3' ? 'show active' : '' ?>" id="tab3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Notes</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade <?= $activeTab == 'tab4' ? 'show active' : '' ?>" id="tab4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Files</h3>
                        </div>
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
                <h5 class="modal-title" id="addNewModalLabel">Add Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['id' => 'addNewForm', 'type' => 'post']) ?>

                <?= $this->Form->hidden('id', ['id' => 'memberId']) ?>

                <?= $this->Form->hidden('project_id', ['id' => 'projectId', 'value' => $project->id]) ?>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <?= $this->Form->label('user_id', 'Member', ['class' => 'form-label', 'escape' => false]) ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3" id="membersContainer">
                            <div class="member-row d-flex">
                                <?= $this->Form->select('user_id[]', $users, [
                                    'class' => 'select form-select',
                                    'id' => 'projectMember',
                                    'style' => 'width:92%'
                                ]); ?>
                            </div>
                        </div>

                        <?= $this->Html->link(
                            ' <i class="ph-plus-circle me-1"></i>' . __('Add More'),
                            '#',
                            [
                                'class' => 'text-secondary',
                                'escape' => false,
                                'id' => 'addMore',
                            ]
                        ) ?>
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

<script>
    $(document).ready(function() {
        let memberIndex = 1;

        $('#addMore').on('click', function(e) {
            e.preventDefault();

            const newMemberRow = `
                <div class="member-row mt-2 d-flex">
                    <select name="user_id[]" class="select form-select" id="projectMember_${memberIndex}" style="width:92%">
                        ${$('#projectMember').html()}
                    </select>

                    <a href="#" class="text-danger remove-member ms-2 mt-2"><i class="ph-trash"></i></a>
                </div>
            `;

            $('#membersContainer').append(newMemberRow);
            memberIndex++;
        });

        // Remove Member Dropdown
        $(document).on('click', '.remove-member', function() {
            $(this).closest('.member-row').remove();
        });

        $('#saveNewData').on('click', function() {
            var formData = $('#addNewForm').serialize();
            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Projects', 'action' => 'addMember']) ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addNewModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while saving the data.');
                }
            });
        });
    });
</script>