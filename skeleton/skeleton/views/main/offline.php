<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col">
		<div class="px-4 py-3">
			<h1 class="h2 fw-bold text-body-emphasis text-center">
				<?php echo apply_filters('site_offline_notice', line('site_offline_notice')) ?>
			</h1>
			<p class="lead text-center mt-2 mb-0">
				<?php echo apply_filters('site_offline_message', line('site_offline_message')) ?>
			</p>
			<?php if ( ! $this->auth->online()): ?>
			<div class="d-flex justify-content-center mt-3">
				<?php echo anchor('login', line('login'), array('class' => 'btn btn-primary px-5')) ?>
			</div>
			<?php endif; ?>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
