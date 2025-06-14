<?php

$loggedInUser = $this->request->getAttribute('identity');
?>

<?php $this->start('css'); ?>
<?= $this->Html->css([
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css',
]) ?>
<?php $this->end(); ?>

<style>
    .view-type {
        color: #000;
        text-decoration: none;
        border-bottom: 2px solid transparent;
    }

    .view-type:hover {
        color: #0056b3;
        border-bottom: 2px solid #0056b3;
    }

    .view-type.active {
        color: #0056b3;
        border-bottom: 2px solid #0056b3;
    }

    @media only screen and (max-width: 575px) {
        #member {
            margin: 10px 0;
        }
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
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-sm-0">
            <div class="d-md-flex gap-4">
                <h5 class="py-sm-2 my-sm-1">
                    <?= $loggedInUser->is_admin == true ? 'Time Card' : 'My Time Card' ?>
                </h5>
                <div class="d-sm-flex gap-4 py-sm-2 my-2">
                    <?php if ($loggedInUser->is_admin == true): ?>
                        <a href="#" class="view-type <?= $viewType === 'daily' ? 'active' : '' ?>" data-type="daily">Daily</a>
                    <?php endif; ?>

                    <?php if ($loggedInUser->is_admin == false): ?>
                        <a href="#" class="view-type <?= $viewType === 'monthly' ? 'active' : '' ?>" data-type="monthly">Monthly</a>

                        <a href="#" class="view-type <?= $viewType === 'weekly' ? 'active' : '' ?>" data-type="weekly">Weekly</a>
                    <?php endif; ?>

                    <a href="#" class="view-type <?= $viewType === 'custom' ? 'active' : '' ?>" data-type="custom">Custom</a>

                    <a href="#" class="view-type <?= $viewType === 'summary' ? 'active' : '' ?>" data-type="summary">Summary</a>
                </div>
            </div>
        </div>

        <div class="card-body d-sm-flex">
            <?php if ($loggedInUser->is_admin == true): ?>
                <div class="px-3">
                    <select name="member" id="member" class="select form-select" style="max-width: 200px;" onchange="this.form.submit()">
                        <option value="">-- All Members --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user->id ?>" <?= $this->request->getQuery('member') == $user->id ? 'selected' : '' ?>>
                                <?= h($user->first_name) ?> <?= h($user->last_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($viewType === 'daily'): ?>
                <div class="d-sm-flex gap-4 px-3">
                    <input type="text" id="dailyDate" class="form-control" value="<?= h($this->request->getSession()->read('selected_date')) ? date('d-m-Y', strtotime($this->request->getSession()->read('selected_date'))) : date('d-m-Y') ?>" style="max-width: 200px;">
                </div>
            <?php elseif ($viewType === 'monthly'): ?>
                <div class="d-sm-flex gap-4 px-3">
                    <input type="text" id="monthPicker" class="form-control" value="<?= date('Y-m') ?>" style="max-width: 200px;">
                </div>
            <?php elseif ($viewType === 'weekly'): ?>
                <div class="d-sm-flex gap-4 px-3">
                    <input type="text" id="weekPicker" class="form-control" style="max-width: 300px;">
                </div>
            <?php elseif ($viewType === 'custom' || $viewType === 'summary'): ?>
                <div class="d-flex gap-3 px-3">
                    <input type="text" id="startDate" class="form-control"
                        value="<?= h($this->request->getSession()->read('start_date')) ? date('d-m-Y', strtotime($this->request->getSession()->read('start_date'))) : date('d-m-Y') ?>" style="max-width: 200px;">

                    <input type="text" id="endDate" class="form-control"
                        value="<?= h($this->request->getSession()->read('end_date')) ? date('d-m-Y', strtotime($this->request->getSession()->read('end_date'))) : date('d-m-Y') ?>" style="max-width: 200px;">
                </div>
            <?php endif; ?>
        </div>

        <table class="table datatable-basic table-striped">
            <thead>
                <tr>
                    <th width="1%"></th>
                    <?php if ($viewType === 'daily' || $viewType === 'custom' || $viewType === 'monthly' || $viewType === 'weekly'): ?>
                        <?php if ($loggedInUser->is_admin == true): ?>
                            <th width="15%">Team Member</th>
                        <?php endif; ?>
                        <th width="15%">Date</th>
                        <th width="15%">In Time</th>
                        <th width="15%">Out Time</th>
                        <th width="12%">Duration</th>
                    <?php endif; ?>

                    <?php if ($viewType === 'summary'): ?>
                        <?php if ($loggedInUser->is_admin == true): ?>
                            <th width="15%">Team Member</th>
                            <th width="15%">Date</th>
                        <?php else: ?>
                            <th width="15%">Date</th>
                        <?php endif; ?>
                        <th width="12%">Duration</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timeCards as $timeCard): ?>
                    <tr>
                        <td>
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?= $timeCard->id ?>" aria-expanded="false" aria-controls="collapse<?= $timeCard->id ?>">
                                <i class="ph-plus fs-6"></i>
                            </button>
                        </td>

                        <?php if ($viewType === 'daily' || $viewType === 'custom' || $viewType === 'monthly' || $viewType === 'weekly'): ?>
                            <?php if ($loggedInUser->is_admin == true): ?>
                                <td><?= h($timeCard->users->first_name) ?> <?= h($timeCard->users->last_name) ?></td>
                            <?php endif; ?>
                            <td><?= h((new \DateTime($timeCard->date))->format('jS M Y')) ?></td>
                            <td><?= !empty($timeCard->clock_in_time) ? h((new \DateTime($timeCard->clock_in_time))->format('jS M Y, H:i A')) : '' ?></td>
                            <td><?= !empty($timeCard->clock_out_time) ? h((new \DateTime($timeCard->clock_out_time))->format('jS M Y, H:i A')) : '' ?></td>
                            <td><?= h($timeCard->total_work_duration) ?></td>
                        <?php endif; ?>

                        <?php if ($viewType === 'summary'): ?>
                            <?php if ($loggedInUser->is_admin == true): ?>
                                <td><?= h($timeCard->users->first_name) ?> <?= h($timeCard->users->last_name) ?></td>
                                <td><?= h((new \DateTime($timeCard->date))->format('jS M Y')) ?></td>
                            <?php else: ?>
                                <td><?= h((new \DateTime($timeCard->date))->format('jS M Y')) ?></td>
                            <?php endif; ?>
                            <td><?= h($timeCard->total_work_duration) ?></td>
                        <?php endif; ?>
                    </tr>

                    <tr class="collapse" id="collapse<?= $timeCard->id ?>">
                        <td colspan="6" style="font-size: 13px; padding: 0;">
                            <table class="table" style="--table-cell-padding-y: 0.3rem;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pause Time</th>
                                        <th class="text-center">Resume Time</th>
                                        <th class="text-center">Pause Duration</th>
                                    </tr>
                                </thead>
                                <?php if (!empty($timeCard->pauses)): ?>
                                    <tbody>
                                        <?php
                                        $totalPauseDuration = 0;
                                        foreach ($timeCard->pauses as $pause):
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= !empty($pause->pause_time) ? h($pause->pause_time->format('H:i:s A')) : '' ?></td>
                                                <td class="text-center"><?= !empty($pause->resume_time) ? h($pause->resume_time->format('H:i:s A')) : '' ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($pause->pause_duration instanceof \Cake\I18n\Time) {
                                                        $pauseDuration = $pause->pause_duration->format('H') * 3600 +
                                                            $pause->pause_duration->format('i') * 60 +
                                                            $pause->pause_duration->format('s');
                                                    } else {
                                                        list($hours, $minutes, $seconds) = explode(':', $pause->pause_duration);
                                                        $pauseDuration = ($hours * 3600) + ($minutes * 60) + $seconds;
                                                    }

                                                    $totalPauseDuration += $pauseDuration;

                                                    echo h(gmdate('H:i:s', $pauseDuration));
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <tr>
                                            <td colspan="2" class="text-end">Total Pause Duration</td>
                                            <td class="text-center">
                                                <strong><?= h(gmdate('H:i:s', $totalPauseDuration)) ?></strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-center" colspan="3">No data found</td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <?php if ($loggedInUser->is_admin == true): ?>
                        <?php if ($viewType === 'summary'): ?>
                            <td colspan="3" class="text-end">Total Duration</td>
                        <?php else: ?>
                            <td colspan="5" class="text-end">Total Duration</td>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($viewType === 'summary'): ?>
                            <td colspan="2" class="text-end">Total Duration</td>
                        <?php else: ?>
                            <td colspan="4" class="text-end">Total Duration</td>
                        <?php endif; ?>
                    <?php endif; ?>
                    <td><strong><?= h($totalDuration) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php $this->start('script'); ?>
<?= $this->Html->script([
    'https://cdn.jsdelivr.net/npm/flatpickr',
    'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js',

]) ?>
<?php $this->end(); ?>

<script>
    $(document).ready(function() {
        const memberSelect = $('#member')
        const dailyDateInput = $('#dailyDate');
        const startDateInput = $('#startDate');
        const endDateInput = $('#endDate');
        const monthInput = $('#monthPicker');
        const weekInput = $('#weekPicker');

        // Get the CSRF token from the meta tag
        const csrfToken = $('meta[name="csrfToken"]').attr('content');
        const url = '<?= $this->Url->build(['action' => 'index']) ?>';

        memberSelect.on('change', function() {
            const selectedMember = $(this).val();
            const viewType = '<?= $viewType ?>';
            fetchData(viewType, null, null, selectedMember);
        });

        // On initial load, set dates for default view
        let currentDate = new Date().toISOString().slice(0, 10);
        const currentMonth = new Date().toISOString().slice(0, 7);

        if ('<?= $viewType ?>' === 'daily') {
            dailyDateInput.val(currentDate);
            fetchData('daily', currentDate);
        } else if ('<?= $viewType ?>' === 'monthly') {
            monthInput.val(currentMonth);
            fetchData('daily', currentMonth);
        } else if ('<?= $viewType ?>' === 'weekly') {
            const startOfWeek = new Date(new Date().setDate(new Date().getDate() - new Date().getDay() + 1)).toISOString().slice(0, 10);

            const endOfWeek = new Date(new Date().setDate(new Date().getDate() - new Date().getDay() + 7)).toISOString().slice(0, 10);

            weekInput.val(`${startOfWeek} - ${endOfWeek}`);
            fetchData('weekly', startOfWeek, endOfWeek);
        } else if ('<?= $viewType ?>' === 'custom' || '<?= $viewType ?>' === 'summary') {
            startDateInput.val(currentDate);
            endDateInput.val(currentDate);
            fetchData('<?= $viewType ?>', currentDate, currentDate);
        }

        // Event listeners for view type links
        $('.view-type').on('click', function(event) {
            event.preventDefault();
            const viewType = $(this).data('type');
            updateViewType(viewType);
        });

        dailyDateInput.flatpickr({
            dateFormat: "d-m-Y",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: "<?= date('d-m-Y') ?>",
            onChange: function(selectedDates, dateStr, instance) {
                const selectedMember = memberSelect.val();

                const formattedDate = dateStr.split('-').reverse().join('-');

                fetchData('daily', formattedDate, null, selectedMember);
            }
        });

        monthInput.flatpickr({
            dateFormat: "Y-m",
            altInput: true,
            altFormat: "F Y",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y-m",
                    altFormat: "F Y",
                    theme: "light"
                })
            ],
            onChange: function(selectedDates, dateStr, instance) {
                const selectedMember = memberSelect.val();
                fetchData('monthly', dateStr, null, selectedMember);
            }
        });

        function getCurrentWeekRange() {
            const now = new Date();
            const firstDayOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
            const lastDayOfWeek = new Date(firstDayOfWeek);
            lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6);

            return {
                startDate: firstDayOfWeek,
                endDate: lastDayOfWeek
            };
        }

        const currentWeek = getCurrentWeekRange();

        weekInput.flatpickr({
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: [currentWeek.startDate, currentWeek.endDate],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0].toISOString().slice(0, 10);
                    const endDate = selectedDates[1].toISOString().slice(0, 10);
                    const selectedMember = memberSelect.val();
                    fetchData('weekly', startDate, endDate, selectedMember);
                }
            }
        });

        fetchData('weekly', currentWeek.startDate.toISOString().slice(0, 10), currentWeek.endDate.toISOString().slice(0, 10), memberSelect.val());

        startDateInput.flatpickr({
            dateFormat: "d-m-Y",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: "<?= date('d-m-Y') ?>",
            onChange: function(selectedDates, dateStr, instance) {
                const endDate = endDateInput.val() || dateStr;
                const selectedMember = memberSelect.val();

                const formattedStartDate = dateStr.split('-').reverse().join('-');
                const formattedEndDate = endDate.split('-').reverse().join('-');
                fetchData('custom', formattedStartDate, formattedEndDate, selectedMember);
            }
        });

        endDateInput.flatpickr({
            dateFormat: "d-m-Y",
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: "<?= date('d-m-Y') ?>",
            onChange: function(selectedDates, dateStr, instance) {
                const startDate = startDateInput.val() || dateStr;
                const selectedMember = memberSelect.val();

                const formattedStartDate = startDate.split('-').reverse().join('-');
                const formattedEndDate = dateStr.split('-').reverse().join('-');
                fetchData('custom', formattedStartDate, formattedEndDate, selectedMember);
            }
        });

        function updateViewType(viewType) {
            // Set the view type in the session and reload data
            $.ajax({
                url: '<?= $this->Url->build(['action' => 'setSession']) ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    view_type: viewType
                }),
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                success: function() {
                    location.reload();
                }
            });
        }

        function fetchData(viewType, date = null, endDate = null, memberId = null) {
            let data = {};

            if (viewType === 'daily') {
                data = {
                    selected_date: date
                };
            } else if (viewType === 'monthly') {
                data = {
                    selected_month: date
                };
            } else if (viewType === 'weekly') {
                data = {
                    start_week: date,
                    end_week: endDate
                };
            } else if (viewType === 'custom' || viewType === 'summary') {
                data = {
                    start_date: date,
                    end_date: endDate
                };
            }

            if (memberId) {
                data.member_id = memberId;
            } else {
                data.member_id = null;
            }

            $.ajax({
                url: '<?= $this->Url->build(['action' => 'setSession']) ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                success: function() {
                    // Fetch and update the table body
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            const newTableBody = $(response).find('.table tbody').html();
                            const newTotalDuration = $(response).find('tfoot td strong').html();
                            $('.table tbody').html(newTableBody);
                            $('tfoot td strong').html(newTotalDuration);
                        }
                    });
                }
            });
        }
    });
</script>