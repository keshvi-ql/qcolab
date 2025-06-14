<?php

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<?php $this->start('script'); ?>
<?= $this->Html->script([
    '/assets/js/vendor/notifications/noty.min.js',
    '/assets/js/custom/noty.js'
]) ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        NotyDemo.init();

        new Noty({
            text: '<?= $message ?>',
            type: 'success'
        }).show();
    });
</script>
<?php $this->end(); ?>