<div class="navbar navbar-dark navbar-expand-lg navbar-slide-top border-bottom border-bottom-white border-opacity-10 fixed-top">
    <div class="container-fluid">
        <div class="navbar-brand">
            <a href="<?= $this->Url->build('/') ?>" class="d-inline-flex align-items-center">
                <?= $this->Html->image('/assets/images/logo_icon.svg', ['alt' => 'logo']) ?>
                <?= $this->Html->image('/assets/images/logo_text_light.svg', ['class' => 'd-none d-sm-inline-block h-16px ms-3', 'alt' => 'logo']) ?>
            </a>
        </div>

        <div class="d-lg-none ms-2">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbar-mobile" aria-expanded="false">
                <i class="ph-squares-four"></i>
            </button>
        </div>

        <ul class="nav gap-sm-2 order-1 order-lg-2 ms-auto">
            <li class="nav-item">
                <a href="#" id="notification-btn" class="navbar-nav-link navbar-nav-link-icon rounded-pill">
                    <i class="ph-bell"></i>
                    <span
                        class="badge bg-yellow text-black position-absolute top-0 end-0 translate-middle-top zindex-1 rounded-pill mt-1 me-1" id="notification-badge">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="navbar-nav-link navbar-nav-link-icon rounded">
                    <i class="ph-chats"></i>
                </a>
            </li>
            <li class="nav-item nav-item-dropdown-lg dropdown">
                <a href="#" class="navbar-nav-link align-items-center rounded p-1" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="status-indicator-container">
                        <?php if ($this->User->getLoginUserAttribute('profile_image')): ?>
                            <?= $this->Html->image(h($this->User->getLoginUserAttribute('profile_image')), ['class' => 'w-32px h-32px rounded', 'alt' => 'image']) ?>
                        <?php else: ?>
                            <?= $this->Html->image('/assets/images/avatar.jpg', ['class' => 'w-32px h-32px rounded', 'alt' => 'Default Avatar']) ?>
                        <?php endif; ?>
                        <span class="status-indicator bg-success"></span>
                    </div>
                    <span class="d-none d-lg-inline-block mx-lg-2"><?= $this->User->getLoginUserAttribute('first_name', 'Guest'); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <?= $this->Html->link(
                        '<i class="ph-user"></i>&nbsp;My Profile',
                        ['controller' => 'Users', 'action' => 'edit', $this->User->getLoginUserAttribute('id'), '?' => ['tab' => 'tab1']],
                        ['class' => 'dropdown-item', 'escape' => false]
                    ); ?>
                    <?= $this->Html->link(
                        '<i class="ph-key"></i>&nbsp;Change Password',
                        ['controller' => 'Users', 'action' => 'edit', $this->User->getLoginUserAttribute('id'), '?' => ['tab' => 'tab3']],
                        ['class' => 'dropdown-item', 'escape' => false]
                    ); ?>
                    <?= $this->Html->link('<i class="ph-sign-out"></i>&nbsp;Logout', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'dropdown-item text-danger', 'escape' => false]); ?>
                </div>
            </li>
        </ul>
    </div>
</div>

<script>
    $('#notification-btn').on('click', function(e) {
        e.preventDefault();
        fetchNotifications();
    });

    function fetchNotifications() {
        $.ajax({
            type: "GET",
            url: '<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'fetchNotifications']); ?>',
            data: {
                _csrfToken: csrfToken
            },
            success: function(response) {
                $("#notificationBody").html(response);

                var offcanvasElement = document.getElementById('notifications');
                var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
                bsOffcanvas.show();

                document.querySelectorAll('.open-notification').forEach(function(element) {
                    element.addEventListener('click', function(event) {
                        event.preventDefault();
                        var notificationId = this.getAttribute('data-id');
                        var notificationModule = this.getAttribute('data-module');
                        var entityId = this.getAttribute('data-entity-id');
                        fetchNotificationDetails(notificationId, notificationModule, entityId);
                    });
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    function durationFields() {
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

    function fetchNotificationDetails(notificationId = '', notificationModule = '', entityId = '') {
        $("#set_notification_id").val(notificationId);
        $.ajax({
            type: "GET",
            url: baseUrl + notificationModule + '/add/' + entityId,
            data: {
                notification_id: notificationId,
                _csrfToken: csrfToken
            },
            success: function(response) {
                $("#notificationDetailModal .modal-content").html(response);
                $("#notificationDetailModal").modal('show');
                $("#set_notification_id").val(notificationId);

                $('input[name="duration"]').change(function() {
                    durationFields();
                });

                durationFields();
                fetchNotifications();
                updateStatus();
                $('.upload-file-button').click(e => $('#uploadFilesInput').click());
                initializeUploadImage();
                deleteUploadedFile();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
</script>