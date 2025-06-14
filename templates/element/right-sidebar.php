<?php

use Cake\I18n\FrozenTime;

$today = FrozenTime::now();

?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="demo_config">
    <div class="position-absolute top-50 end-100 visible">
        <button type="button" class="btn btn-primary btn-icon translate-middle-y rounded-end-0" data-bs-toggle="offcanvas" data-bs-target="#demo_config">
            <i class="ph-clock"></i>
        </button>
    </div>

    <div class="offcanvas-header border-bottom py-0">
        <h5 class="offcanvas-title py-3">Time Card</h5>
        <button type="button" class="btn btn-light btn-sm btn-icon border-transparent rounded-pill" data-bs-dismiss="offcanvas">
            <i class="ph-x"></i>
        </button>
    </div>

    <div class="offcanvas-body">
        <div class="fw-semibold fs-5"><?= $today->i18nFormat('dd-MMMM yyyy, EEEE'); ?></div>
        <p id="clocked_in"></p>
        <h1 id="timeDisplay" class="text-center mb-2 mt-4">00:00:00</h1>
        <button id="clockInBtn" class="btn btn-success fw-semibold w-100">
            <i class="ph-sign-out me-2"></i>
            Clock In
        </button>
        <div class="d-flex justify-content-between gap-1">
            <button id="pauseBtn" class="btn btn-info fw-semibold w-50 my-1" style="display:none;">
                <i class="ph-pause me-2"></i>
                Pause
            </button>
            <button id="resumeBtn" class="btn btn-success fw-semibold w-50 my-1" style="display:none;">
                <i class="ph-play me-2"></i>
                Resume
            </button>
            <button id="clockOutBtn" class="btn btn-danger fw-semibold w-50 my-1" style="display:none;">
                <i class="ph-sign-out me-2"></i>
                Clock Out
            </button>
        </div>

        <div id="clockOutConfirmation" style="display:none;">
            <textarea name="note" id="note" class="form-control my-2" rows="5" placeholder="Note"></textarea>
            <div class="d-flex justify-content-between gap-1">
                <button id="cancelClockOutBtn" class="btn btn-light w-50 my-1">Cancel</button>
                <button id="saveClockOutBtn" class="btn btn-info w-50 my-1">Save</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script([
    '/assets/js/bootstrap/bootstrap.bundle.min.js',
    '/assets/js/jquery/jquery.min.js',
    '/assets/js/app.js',
    '/assets/js/vendor/notifications/sweet_alert.min.js',
    '/assets/js/custom.js',
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var currentStatus = '<?= $currentStatus; ?>';
        var startTime = localStorage.getItem('startTime');
        var pausedTime = localStorage.getItem('pausedTime');
        const clockInTime = localStorage.getItem('clockInTime');

        if (clockInTime) {
            document.getElementById('clocked_in').innerHTML = "Clocked in at: " + clockInTime;
        }

        if (currentStatus === 'none') {
            // User has not clocked in yet, show Clock In button
            document.getElementById('clockInBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
            document.getElementById('clockOutBtn').style.display = 'none';
            document.getElementById('resumeBtn').style.display = 'none';
        } else if (currentStatus === 'active') {
            // User has clocked in, show Pause and Clock Out buttons
            document.getElementById('pauseBtn').style.display = 'block';
            document.getElementById('clockOutBtn').style.display = 'block';
            document.getElementById('clockInBtn').style.display = 'none';

            if (startTime) {
                var elapsedTime = Date.now() - startTime; // Calculate elapsed time based on the start time
                startTimer(elapsedTime); // Pass the elapsed time to startTimer
            }

        } else if (currentStatus === 'paused') {
            // User has paused, show Resume and Clock Out buttons
            document.getElementById('resumeBtn').style.display = 'block';
            document.getElementById('clockOutBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
            document.getElementById('clockInBtn').style.display = 'none';

            if (pausedTime) {
                elapsedTime = pausedTime; // Set elapsed time to paused time
                document.getElementById('timeDisplay').innerHTML = formatTime(elapsedTime); // Display paused time
            }

        } else if (currentStatus === 'completed') {
            // User has clocked out, reset buttons (optional)
            document.getElementById('clockInBtn').style.display = 'block';
            document.getElementById('pauseBtn').style.display = 'none';
            document.getElementById('clockOutBtn').style.display = 'none';
            document.getElementById('resumeBtn').style.display = 'none';
        }
    });

    let timerInterval;
    let startTime;
    let pausedTime;
    let elapsedTime = 0; // Total elapsed time

    function startTimer(elapsed = 0) {
        elapsedTime = elapsed; // Set elapsedTime to the given elapsed time
        startTime = Date.now() - elapsedTime; // Calculate the starting point

        timerInterval = setInterval(function() {
            elapsedTime = Date.now() - startTime; // Update total elapsed time
            document.getElementById('timeDisplay').innerHTML = formatTime(elapsedTime);
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        pausedTime = elapsedTime; // Save elapsed time at pause
        localStorage.setItem('pausedTime', pausedTime);
    }

    function resetTimer() {
        clearInterval(timerInterval);
        elapsedTime = 0; // Reset total elapsed time
        document.getElementById('timeDisplay').innerHTML = "00:00:00";
    }

    function formatTime(time) {
        const totalSeconds = Math.floor(time / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    document.getElementById('clockInBtn').addEventListener('click', function() {
        saveClockIn('clock_in');
        this.style.display = 'none';
        document.getElementById('pauseBtn').style.display = 'block';
        document.getElementById('clockOutBtn').style.display = 'block';
    });

    document.getElementById('pauseBtn').addEventListener('click', function() {
        savePause('pause');
        this.style.display = 'none';
        document.getElementById('resumeBtn').style.display = 'block';
    });

    document.getElementById('resumeBtn').addEventListener('click', function() {
        pausedTime = localStorage.getItem('pausedTime');
        saveResume('resume');
        this.style.display = 'none';
        document.getElementById('pauseBtn').style.display = 'block';
    });

    document.getElementById('clockOutBtn').addEventListener('click', function() {
        document.getElementById('pauseBtn').style.display = 'none';
        document.getElementById('resumeBtn').style.display = 'none';
        document.getElementById('clockOutBtn').style.display = 'none';

        document.getElementById('clockOutConfirmation').style.display = 'block';
    });

    document.getElementById('cancelClockOutBtn').addEventListener('click', function() {
        document.getElementById('clockOutConfirmation').style.display = 'none';

        // Restore the original Pause and Clock Out buttons
        document.getElementById('pauseBtn').style.display = 'block';
        document.getElementById('clockOutBtn').style.display = 'block';
    });

    document.getElementById('saveClockOutBtn').addEventListener('click', function() {
        var note = document.getElementById('note').value;
        if (note.trim() === '') {
            swalInit.fire("Error", "Please provide a note for clocking out.", "error");
            return;
        }

        saveClockOut('clock_out', note);

        document.getElementById('clockInBtn').style.display = 'block';
        document.getElementById('pauseBtn').style.display = 'none';
        document.getElementById('resumeBtn').style.display = 'none';
        this.style.display = 'none';

        clearLocalStorage();
    });

    function clearLocalStorage() {
        localStorage.removeItem('startTime');
        localStorage.removeItem('pausedTime');
        localStorage.removeItem('clockInTime');
        document.getElementById('clocked_in').innerHTML = '';
    }

    function saveClockIn(action) {
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'TimeLogs', 'action' => 'clockIn']); ?>',
            type: 'POST',
            data: {
                time: new Date(),
                _csrfToken: csrfToken
            }, // send action and time to server
            success: function(response) {
                if (response.status === 'success') {
                    startTimer();

                    document.getElementById('clockInBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'block';
                    document.getElementById('clockOutBtn').style.display = 'block';
                    document.getElementById('resumeBtn').style.display = 'none';

                    localStorage.setItem('startTime', Date.now());
                    localStorage.setItem('clockInTime', response.clock_in_time);

                    document.getElementById('clocked_in').innerHTML = "Clocked in at: " + response.clock_in_time;
                } else if (response.error) {
                    swalInit.fire(
                        "Error",
                        response.error,
                        "error"
                    );
                    // Prevent the timer from starting
                    resetTimer();

                    document.getElementById('resumeBtn').style.display = 'none';
                    document.getElementById('clockInBtn').style.display = 'block';
                    document.getElementById('pauseBtn').style.display = 'none';
                    document.getElementById('clockOutBtn').style.display = 'none';
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function savePause(action) {
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'TimeLogs', 'action' => 'pause']); ?>',
            type: 'POST',
            data: {
                time: new Date(),
                _csrfToken: csrfToken
            }, // send action and time to server
            success: function(response) {
                if (response.status === 'success') {
                    pauseTimer();

                    document.getElementById('resumeBtn').style.display = 'block';
                    document.getElementById('clockInBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'none';
                    document.getElementById('clockOutBtn').style.display = 'block';
                } else if (response.error) {
                    swalInit.fire(
                        "Error",
                        response.error,
                        "error"
                    );

                    document.getElementById('resumeBtn').style.display = 'none';
                    document.getElementById('clockInBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'block';
                    document.getElementById('clockOutBtn').style.display = 'block';
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function saveResume(action) {
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'TimeLogs', 'action' => 'resume']); ?>',
            type: 'POST',
            data: {
                time: new Date(),
                _csrfToken: csrfToken
            }, // send action and time to server
            success: function(response) {
                if (response.status === 'success') {
                    startTimer(pausedTime);

                    document.getElementById('resumeBtn').style.display = 'none';
                    document.getElementById('clockInBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'block';
                    document.getElementById('clockOutBtn').style.display = 'block';
                } else if (response.error) {
                    swalInit.fire(
                        "Error",
                        response.error,
                        "error"
                    );

                    document.getElementById('resumeBtn').style.display = 'block';
                    document.getElementById('clockInBtn').style.display = 'none';
                    document.getElementById('pauseBtn').style.display = 'none';
                    document.getElementById('clockOutBtn').style.display = 'block';
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function saveClockOut(action, note) {
        $.ajax({
            url: '<?= $this->Url->build(['controller' => 'TimeLogs', 'action' => 'clockOut']); ?>',
            type: 'POST',
            data: {
                time: new Date(),
                note: note,
                _csrfToken: csrfToken
            }, // send action and time to server
            success: function(response) {
                if (response.status === 'success') {
                    resetTimer();

                    document.getElementById('resumeBtn').style.display = 'none';
                    document.getElementById('clockInBtn').style.display = 'block';
                    document.getElementById('pauseBtn').style.display = 'none';
                    document.getElementById('clockOutBtn').style.display = 'none';
                    document.getElementById('clockOutConfirmation').style.display = 'none';
                } else if (response.error) {
                    swalInit.fire(
                        "Error",
                        response.error,
                        "error"
                    );
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>