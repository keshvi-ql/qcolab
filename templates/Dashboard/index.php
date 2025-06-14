<?php

/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\User> $users
 */

$loggedInUser = $this->request->getAttribute('identity');
?>

<style>
    .sticky-note {
        outline: none;
        height: 250px;
        padding: 5px;
        border: none;
        color: #000;
        font-size: 110%;
        background: #fff6b3;
        width: 100%;
    }

    .border-right {
        border-right: 1px solid #ddd;
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

    <?= $this->Flash->render() ?>

    <?php if (isset($announcements)): ?>
        <?php foreach ($announcements as $announcement): ?>
            <div class="mb-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-primary alert-icon-start alert-dismissible fade show">
                            <span class="alert-icon bg-primary text-white">
                                <i class="ph-bell-ringing"></i>
                            </span>
                            <span class="fw-bold"><?= $announcement->title ?></span> | <span><?= $announcement->description ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


    <div class="row">
        <div class="col-lg-4">
            <?php if ($loggedInUser->is_admin == true): ?>
                <div class="card">
                    <div class="card-body">
                        <?php
                        $membersWithClockIn = 0;
                        $membersWithClockOut = 0;

                        foreach ($membersClockedTime as $members) {
                            if ((isset($members->time_logs))) {
                                foreach ($members->time_logs as $member) {
                                    if ((empty($member->clock_out_time))) {
                                        $membersWithClockIn++;
                                    } else {
                                        $membersWithClockOut++;
                                    }
                                }
                            }
                        } ?>

                        <div class="row text-center">
                            <div class="col border-right">
                                <p class="display-6"><?= $membersWithClockIn ?></p>
                                <p class="text-muted">MEMBERS CLOCKED IN</p>
                            </div>
                            <div class="col">
                                <p class="display-6"><?= $membersWithClockOut ?></p>
                                <p class="text-muted">MEMBERS CLOCKED OUT</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0"><i class="ph-calendar-blank"></i> Holidays</h5>
                </div>

                <ul class="list-group list-group-flush border-top">
                    <?php foreach ($holidays as $holiday): ?>
                        <li class="list-group-item d-flex">
                            <div class="row align-items-center">
                                <div class="col-auto rounded-circle bg-light w-40px h-40px d-flex align-items-center justify-content-center">
                                    <i class="ph-calendar-blank"></i>
                                </div>

                                <div class="col">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-semibold">
                                            <?= h($holiday->title); ?>
                                        </div>
                                    </div>

                                    <div class="text-muted">
                                        <div><?php if ($holiday->start_date == $holiday->end_date): ?>
                                                <?= h((new DateTime($holiday->start_date))->format('l, jS M Y')) ?>
                                            <?php else: ?>
                                                <?= h((new DateTime($holiday->start_date))->format('l, jS M Y')) ?>
                                                To
                                                <?= h((new DateTime($holiday->end_date))->format('l, jS M Y')) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <?php if ($loggedInUser->is_admin == true): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0"><i class="ph-list-bullets"></i> Leaves</h5>
                    </div>

                    <ul class="list-group list-group-flush border-top">
                        <?php if (empty($todayLeaves)): ?>
                            <li class="list-group-item text-center">
                                <span>No one is on today leave</span>
                            </li>
                        <?php else: ?>
                            <?php foreach ($todayLeaves as $todayLeave): ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php
                                            $imageName = $todayLeave->applicant->profile_image;
                                            echo $this->User->profileImage($imageName, ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'], $todayLeave->applicant->first_name, $todayLeave->applicant->last_name);
                                            ?>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="fw-semibold"><?= h($todayLeave->applicant->first_name); ?> <?= h($todayLeave->applicant->last_name); ?></span> (Today)
                                                </div>

                                                <div class="text-end text-muted">
                                                    <?= h($todayLeave->leave_type->title); ?>
                                                </div>
                                            </div>

                                            <div class="text-muted">
                                                <div>Duration: <?= $todayLeave->total_hours == '4.00' ? 'Half Day (' . ucwords(str_replace('_', ' ', $todayLeave->half_day_type)) . ')' : 'Full Day'; ?></div>
                                                <div>Reason: <?= h($todayLeave->reason); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (empty($tomorrowLeaves)): ?>
                            <li class="list-group-item text-center">
                                <span>No one is on tomorrow leave</span>
                            </li>
                        <?php else: ?>
                            <?php foreach ($tomorrowLeaves as $tomorrowLeave): ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php
                                            $imageName = $tomorrowLeave->applicant->profile_image;
                                            echo $this->User->profileImage($imageName, ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'], $tomorrowLeave->applicant->first_name, $tomorrowLeave->applicant->last_name);
                                            ?>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="fw-semibold"><?= h($tomorrowLeave->applicant->first_name); ?> <?= h($tomorrowLeave->applicant->last_name); ?></span> (Tomorrow)
                                                </div>

                                                <div class="text-end text-muted">
                                                    <?= h($tomorrowLeave->leave_type->title); ?>
                                                </div>
                                            </div>

                                            <div class="text-muted">
                                                <div>Duration: <?= $tomorrowLeave->total_hours == '4.00' ? 'Half Day (' . ucwords(str_replace('_', ' ', $tomorrowLeave->half_day_type)) . ')' : 'Full Day'; ?></div>
                                                <div>Reason: <?= h($tomorrowLeave->reason); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($loggedInUser->is_admin == true): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-0"><i class="ph-list-bullets"></i> Partial Leaves</h5>
                    </div>

                    <ul class="list-group list-group-flush border-top">
                        <?php if (empty($todayPartialLeaves)): ?>
                            <li class="list-group-item text-center">
                                <span>No one is on today partial leave</span>
                            </li>
                        <?php else: ?>
                            <?php foreach ($todayPartialLeaves as $todayPartialLeave): ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php
                                            $imageName = $todayPartialLeave->applicant->profile_image;
                                            echo $this->User->profileImage($imageName, ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'], $todayPartialLeave->applicant->first_name, $todayPartialLeave->applicant->last_name);
                                            ?>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="fw-semibold"><?= h($todayPartialLeave->applicant->first_name); ?> <?= h($todayPartialLeave->applicant->last_name); ?></span> (Today)
                                                </div>
                                            </div>

                                            <div class="text-muted">
                                                <div>Duration: <?= $todayPartialLeave->total_hours; ?> <?= $todayPartialLeave->total_hours > 1 ? 'Hours' : 'Hour'; ?></div>
                                                <div>Reason: <?= h($todayPartialLeave->reason); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (empty($tomorrowPartialLeaves)): ?>
                            <li class="list-group-item text-center">
                                <span>No one is on tomorrow partial leave</span>
                            </li>
                        <?php else: ?>
                            <?php foreach ($tomorrowPartialLeaves as $tomorrowPartialLeave): ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <?php
                                            $imageName = $tomorrowPartialLeave->applicant->profile_image;
                                            echo $this->User->profileImage($imageName, ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'], $tomorrowPartialLeave->applicant->first_name, $tomorrowPartialLeave->applicant->last_name);
                                            ?>
                                        </div>

                                        <div class="col">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="fw-semibold"><?= h($tomorrowPartialLeave->applicant->first_name); ?> <?= h($tomorrowPartialLeave->applicant->last_name); ?></span> (Tomorrow)
                                                </div>
                                            </div>

                                            <div class="text-muted">
                                                <div>Duration: <?= $tomorrowPartialLeave->total_hours; ?> <?= $tomorrowPartialLeave->total_hours > 1 ? 'Hours' : 'Hour'; ?></div>
                                                <div>Reason: <?= h($tomorrowPartialLeave->reason); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0"><i class="ph-cake"></i> Birthdays</h5>
                </div>

                <ul class="list-group list-group-flush border-top">
                    <?php if (empty($birthdays)): ?>
                        <li class="list-group-item text-center">
                            <span>No Upcoming Birthdays Found</span>
                        </li>
                    <?php else: ?>
                        <?php foreach ($birthdays as $birthday): ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <?php
                                    $dob = new DateTime($birthday->dob);
                                    $today = new DateTime();
                                    $tomorrow = (new DateTime())->modify('+1 day');
                                    ?>

                                    <div class="col-auto">
                                        <?php
                                        $imageName = $birthday->profile_image;
                                        echo $this->User->profileImage(
                                            $imageName,
                                            ['class' => 'rounded-circle', 'width' => '40', 'height' => '40', 'alt' => 'User Avatar'],
                                            $birthday->first_name,
                                            $birthday->last_name
                                        );
                                        ?>
                                    </div>

                                    <div class="col">
                                        <div class="d-flex justify-content-between">
                                            <div class="fw-semibold">
                                                <?php
                                                if ($dob->format('m-d') === $today->format('m-d')) {
                                                    echo '<span class="text-success fw-semibold">' . h($birthday->first_name) . ' ' . h($birthday->last_name) . '</span>';
                                                } elseif ($dob->format('m-d') === $tomorrow->format('m-d')) {
                                                    echo '<span class="text-primary fw-semibold">' . h($birthday->first_name) . ' ' . h($birthday->last_name) . '</span>';
                                                } else {
                                                    echo h($birthday->first_name) . ' ' . h($birthday->last_name);
                                                }
                                                ?>
                                            </div>

                                            <div class="text-end">
                                                <?php
                                                if ($dob->format('m-d') === $today->format('m-d')) {
                                                    echo '<span class="text-success fw-semibold">Today</span>';
                                                } elseif ($dob->format('m-d') === $tomorrow->format('m-d')) {
                                                    echo '<span class="text-primary fw-semibold">Tomorrow</span>';
                                                } else {
                                                    $currentYear = date('Y');
                                                    $dob->setDate($currentYear, $dob->format('m'), $dob->format('d'));

                                                    echo h($dob->format('l, jS M Y'));
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0"><i class="ph-notebook"></i> Sticky Notes <small>(Private)</small></h5>
                </div>

                <?= $this->Form->textarea('sticky-note', [
                    'class' => 'sticky-note',
                    'id' => 'sticky-note',
                    'value' => $stickyNote->sticky_note,
                ]) ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#sticky-note').on('input', function() {
            const noteContent = $(this).val();
            console.log(noteContent);

            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Dashboard', 'action' => 'saveStickyNote']) ?>",
                type: "POST",
                data: {
                    note: noteContent,
                    _csrfToken: "<?= $this->request->getAttribute('csrfToken') ?>"
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Sticky note saved successfully');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving sticky note:', error);
                }
            });
        });
    });
</script>