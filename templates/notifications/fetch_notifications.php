<div class="bg-light fw-medium py-2 px-3">New notifications</div>
<?php if (count($newNotifications) > 0 || count($oldNotifications) > 0) { ?>
    <div class="p-3">
        <?php if (count($newNotifications) > 0) {
            foreach ($newNotifications as $new) {
                $createdAt = new DateTime($new['notification']['created_at']);
                $formattedDate = $createdAt->format('jS M Y h:i:sA');
                $imageName = $new['notification']['user']['profile_image'];
        ?>
                <a href="#" class="d-flex align-items-start mb-3 open-notification" data-id="<?= $new['notification']['id']; ?>" data-entity-id="<?= $new['notification']['entity_id']; ?>" data-module="<?= $new['notification']['module']; ?>">
                    <div class="me-3">
                        <?= $this->User->profileImage($imageName, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'User Avatar']) ?>
                    </div>
                    <div class="flex-fill">
                        <div class="fw-semibold"><?= $new['notification']['user']['first_name'] ?></div>
                        <div><?= $new['notification']['message'] ?></div>
                        <div class="fs-sm text-muted mt-1"><?= $formattedDate; ?></div>
                    </div>
                </a>
            <?php }
        } else { ?>
            <p>No new notifications.</p>
        <?php } ?>
    </div>
    <div class="bg-light fw-medium py-2 px-3">Older notifications</div>
    <div class="p-3">
        <?php if (count($oldNotifications) > 0) {
            foreach ($oldNotifications as $old) {
                $createdAt = new DateTime($old['notification']['created_at']);
                $formattedDate = $createdAt->format('jS M Y h:i:sA');
                $imageName = $old['notification']['user']['profile_image'];
        ?>
                <a href="#" class="d-flex align-items-start mb-3 open-notification" data-id="<?= $old['notification']['id']; ?>" data-entity-id="<?= $old['notification']['entity_id']; ?>" data-module="<?= $old['notification']['module']; ?>">
                    <div class="me-3">
                        <?= $this->User->profileImage($imageName, ['class' => 'w-40px h-40px rounded-pill', 'alt' => 'User Avatar']) ?>
                    </div>
                    <div class="flex-fill">
                        <div class="fw-semibold"><?= $old['notification']['user']['first_name'] ?></div>
                        <div><?= $old['notification']['message'] ?></div>
                        <div class="fs-sm text-muted mt-1"><?= $formattedDate; ?></div>
                    </div>
                </a>
            <?php }
        } else { ?>
            <p>No old notifications.</p>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="p-3">
        <p>No notifications.</p>
    </div>
<?php } ?>