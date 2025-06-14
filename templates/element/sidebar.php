<?php
$loggedInUser = $this->request->getAttribute('identity');
?>

<div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar header -->
        <div class="sidebar-section">
            <div class="sidebar-section-body d-flex justify-content-center">
                <h5 class="sidebar-resize-hide flex-grow-1 my-auto">Navigation</h5>

                <div>
                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-control sidebar-main-resize d-none d-lg-inline-flex">
                        <i class="ph-arrows-left-right"></i>
                    </button>

                    <button type="button" class="btn btn-flat-white btn-icon btn-sm rounded-pill border-transparent sidebar-mobile-main-toggle d-lg-none">
                        <i class="ph-x"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- /sidebar header -->

        <!-- Main navigation -->
        <div class="sidebar-section">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header pt-0">
                    <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Main</div>
                    <i class="ph-dots-three sidebar-resize-show"></i>
                </li>
                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-house"></i> <span>Dashboard</span>', ['controller' => 'Dashboard', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-currency-circle-dollar"></i> <span>Bids</span>', ['controller' => 'Bids', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-stack"></i> <span>Leads</span>', ['controller' => 'Leads', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-users-three"></i> <span>Clients</span>', ['controller' => 'Clients', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-command"></i> <span>Projects</span>', ['controller' => 'Projects', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <?php if ($loggedInUser->is_admin == true): ?>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="ph-currency-dollar"></i> <span>Payroll</span>', ['controller' => 'Payroll', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                    </li>
                <?php endif; ?>

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link">
                        <i class="ph-users"></i>
                        <span>Staff</span>
                    </a>
                    <ul class="nav-group-sub collapse">
                        <li class="nav-item">
                            <?= $this->Html->link('<span>Members</span>', ['controller' => 'Users', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<span>Time Cards</span>', ['controller' => 'TimeCards', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<span>Leave</span>', ['controller' => 'LeaveApplications', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<span>Partial Leave</span>', ['controller' => 'PartialLeaves', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('<span>Announcements</span>', ['controller' => 'Announcements', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <?= $this->Html->link('<i class="ph-calendar-blank"></i> <span>Holidays</span>', ['controller' => 'Holidays', 'action' => 'index'], ['class' => 'nav-link', 'escape' => false]) ?>
                </li>

                <!-- Forms -->
                <?php if ($this->User->hasPermission('Settings', 'index')): ?>
                    <li class="nav-item-header">
                        <div class="text-uppercase fs-sm lh-sm opacity-50 sidebar-resize-hide">Settings</div>
                        <i class="ph-dots-three sidebar-resize-show"></i>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('<i class="ph-gear"></i> <span>Settings</span>', ['controller' => 'Settings', 'action' => 'add'], ['class' => 'nav-link', 'escape' => false]) ?>
                    </li>
                <?php endif; ?>
                <!-- /forms -->
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>