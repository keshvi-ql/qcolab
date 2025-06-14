<?php
/**
 * @var \App\View\AppView $this
 * @var string $message
 * @var string $url
 */
use Cake\Core\Configure;

$this->layout = 'error';

$statusCode = $this->request->getAttribute('error');
?>
<!-- Page content -->
<div class="page-content">

	<!-- Main Content -->
	<div class="content-wrapper">

		<!-- Inner Content -->
		<div class="content-inner">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Container -->
				<div class="flex-fill">

					<!-- Error title -->
					<div class="text-center mb-4">
						<?= $this->Html->image('/assets/images/error_bg.svg', ['class' => 'img-fluid mb-3', 'height' => '230', 'alt' => '']) ?>
						<h1 class="display-3 fw-semibold lh-1 mb-3"><?= h($code) ?></h1>
						<h6 class="w-md-25 mx-md-auto"><?= h($message) ?></h6>
					</div>
					<!-- /error title -->

					<!-- Error content -->
					<div class="text-center">
						<!-- <a href="<?//= $this->Url->build('/') ?>" class="btn btn-primary">
							<i class="ph-house me-2"></i>
							Return to dashboard
						</a> -->
						<?= $this->Html->link(__('Back'), 'javascript:history.back()') ?>
					</div>
					<!-- /error wrapper -->

				</div>
				<!-- /container -->

			</div>
			<!-- /content area -->

		</div>
		<!-- /Inner Content -->

	</div>
	<!-- Main Content -->

</div>
<!-- /Page content -->
