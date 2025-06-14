<?php

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
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->Html->css([
        '/assets/fonts/inter/inter.css',
        '/assets/icons/phosphor/styles.min.css',
        '/assets/css/all.min.css',
    ]) ?>
    <?= $this->fetch('css') ?>

</head>

<body>

    <?php if ($this->request->getAttribute('identity')) {
        echo $this->element('header');
    } else {
        echo $this->element('auth-header');
    }; ?>

    <div class="page-content pt-0">

        <!-- Main Content -->
        <div class="content-wrapper">

            <!-- Inner Content -->
            <div class="content-inner">

                <?= $this->fetch('content') ?>

                <?= $this->element('footer'); ?>

            </div>
            <!-- /Inner Content -->

        </div>
        <!-- /Main Content -->

    </div>

    <!-- Common Scripts -->
    <?= $this->Html->script([
        '/assets/js/bootstrap/bootstrap.bundle.min.js',
        '/assets/js/jquery/jquery.min.js',
        '/assets/js/vendor/tables/datatables/datatables.min.js',
        '/assets/js/custom/datatables.js',
        '/assets/js/app.js'
    ]) ?>
    <!-- /Common Scripts -->
</body>

</html>