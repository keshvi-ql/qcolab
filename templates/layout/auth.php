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

$appName = 'Q-Collab';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= isset($title) ? $appName . ' | ' . $title : $appName ?>
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
    <?= $this->Flash->render() ?>
    <div class="navbar navbar-dark navbar-static py-2">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="<?= $this->Url->build('/') ?>" class="d-inline-flex align-items-center">
                    <?= $this->Html->image('/assets/images/logo_icon.svg', ['alt' => 'logo']) ?>
                    <?= $this->Html->image('/assets/images/logo_text_light.svg', ['class' => 'd-none d-sm-inline-block h-16px ms-3', 'alt' => 'logo']) ?>
                </a>
            </div>
        </div>
    </div>

    <div class="page-content">

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
    <!-- /Page content -->

    <!-- Common Scripts -->
    <?= $this->Html->script([
        '/assets/js/custom/form_validation.js'
    ]) ?>
    <!-- /Common Scripts -->

    <!-- Page Specific Scripts -->
    <?= $this->fetch('script') ?>
    <!-- /Page Specific Scripts -->
</body>

</html>