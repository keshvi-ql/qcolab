<?php

use Cake\I18n\FrozenTime;

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$appName = 'Q-Collab';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>
    <title>
        <?= isset($title) ? $appName . ' | ' . $title : $appName ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->Html->css([
        '/assets/fonts/inter/inter.css',
        '/assets/icons/phosphor/styles.min.css',
        '/assets/css/all.min.css',
        '/assets/css/custom.css',
    ]) ?>

    <style>
        .hidden {
            display: none;
        }

        .color-tag {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin: 2px 10px 0 0;
            transition: all 300ms ease;
            -moz-transition: all 0.1s;
            -webkit-transition: all 0.1s;
            transition: all 0.1s;
        }

        .color-tag.clickable:hover {
            -moz-transform: scale(1.5);
            -webkit-transform: scale(1.5);
            transform: scale(1.5);
        }

        .color-tag.active {
            border-radius: 50%;
        }

        .input-color {
            width: 50px !important;
            height: 15px !important;
            padding: 0 !important;
            border: none !important;
        }

        .input-color.active {
            overflow: hidden;
            border-radius: 20px !important;
        }
    </style>

    <?= $this->fetch('css') ?>

    <?= $this->Html->script([
        '/assets/js/jquery/jquery.min.js',
    ]) ?>
    <script>
        var csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute("content");
        var baseUrl = "<?= $this->Url->build('/', ['fullBase' => true]); ?>";
    </script>

</head>

<body class="navbar-top">

    <?php if ($this->request->getAttribute('identity')) {
        echo $this->element('header');
    } else {
        echo $this->element('auth-header');
    }; ?>

    <?= $this->Flash->render() ?>
    <!-- /Main navbar -->

    <div class="page-content">

        <?= $this->element('sidebar') ?>
        <!-- Main Content -->
        <div class="content-wrapper">

            <!-- Inner Content -->
            <div class="content-inner">

                <?= $this->fetch('content') ?>

                <?= $this->element('footer'); ?>

            </div>
            <!-- /Inner Content -->

        </div>
        <!-- /Main content -->

    </div>
    <!-- /Page content -->


    <!-- Demo config -->
    <?= $this->element('right-sidebar'); ?>
    <!-- /demo config -->

    <!-- Notifications -->
    <?= $this->element('notification-sidebar'); ?>
    <!-- /Notifications -->

    <!-- Common JavaScript Files -->
    <?= $this->Html->script([
        '/assets/js/bootstrap/bootstrap.bundle.min.js',
        // '/assets/js/jquery/jquery.min.js',
        '/assets/js/app.js',
        '/assets/js/vendor/notifications/sweet_alert.min.js',
        '/assets/js/vendor/pickers/datepicker.min.js',
        '/assets/js/custom.js',
        '/assets/js/vendor/tables/datatables/datatables.min.js',
        '/assets/js/custom/datatables.js',
    ]) ?>

    <script>
        // Handle single item deletion
        $(document).on('click', '.sweet_warning', function(event) {
            event.preventDefault();

            const $link = $(this);
            const recordId = $link.data('record-id');
            const url = $link.data('url');

            if (recordId) {
                deleteRecord(
                    url, {
                        '_method': 'DELETE',
                        'recordId': recordId
                    },
                    recordId
                );
            }
        });
    </script>

    <script>
        function countNotifications() {
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'countNotifications']); ?>",
                type: 'POST',
                data: {
                    _csrfToken: csrfToken
                },
                dataType: 'json',
                success: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    document.getElementById('notification-badge').innerHTML = response.data.count;
                },
                error: function(response) {
                    if (typeof response === "string") {
                        response = JSON.parse(response);
                    }
                    console.error(response.message);
                }
            });
        }
        setInterval(countNotifications, 10000);
    </script>

    <!-- Page Specific JavaScript -->
    <?= $this->fetch('script') ?>
</body>

</html>