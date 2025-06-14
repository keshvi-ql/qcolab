<div class="card">
    <div class="card-header align-items-sm-center py-sm-0">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link">
                    <span>App Settings</span>
                </a>
                <ul class="nav-group-sub collapse">
                    <li class=""><?= $this->Html->link('General Settings', ['controller' => 'Settings', 'action' => 'add'], ['class' => 'nav-link px-4']) ?></li>
                    <li class=""><?= $this->Html->link('Notifications', ['controller' => 'Notifications', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                </ul>
            </li>
        </ul>

        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link">
                    <span>Access Permission</span>
                </a>
                <ul class="nav-group-sub collapse">
                    <li class=""><?= $this->Html->link('Roles', ['controller' => 'Roles', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                </ul>
            </li>
        </ul>

        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link">
                    <span>Email Settings</span>
                </a>
                <ul class="nav-group-sub collapse">
                    <li class=""><?= $this->Html->link('Email Templates', ['controller' => 'EmailTemplates', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                </ul>
            </li>
        </ul>

        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <li class="nav-item nav-item-submenu">
                <a href="#" class="nav-link">
                    <span>Setup</span>
                </a>
                <ul class="nav-group-sub collapse">
                    <li class=""><?= $this->Html->link('Leave types', ['controller' => 'LeaveTypes', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                    <li class=""><?= $this->Html->link('Leads', ['controller' => 'LeadStatuses', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                    <li class=""><?= $this->Html->link('Projects', ['controller' => 'ProjectStatuses', 'action' => 'index'], ['class' => 'nav-link px-4']) ?></li>
                </ul>
            </li>
        </ul>
    </div>
</div>