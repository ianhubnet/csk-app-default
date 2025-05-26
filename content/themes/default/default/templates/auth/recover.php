<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<h1 class="text-center h4 mb-2"><?php _e('lost_password') ?></h1>

		<div class="card">
			<?php echo form_open('lost-password', 'role="form" id="recover" class="card-body"', $hidden) ?>
				<p class="mb-3"><?php _e('login_recover_tip') ?></p>

				<!-- username/email address -->
				<div class="mb-2">
					<?php
					echo print_input($identity, array('class' => error_class('identity', 'form-control form-control-sm'))),
					form_error('identity');
					?>
				</div>

			<?php if (isset($captcha)): if ($this->config->item('use_recaptcha')): ?>
				<!-- google recaptcha -->
				<div class="mb-2 text-center">
					<?php echo $captcha, form_error('g-recaptcha-response'); ?>
				</div>

			<?php else: ?>
				<!-- captcha -->
				<div class="row row-cols-1 row-cols-md-2 mb-2">
					<div class="col" tabindex="-1">
						<?php echo $captcha_image ?>
					</div>
					<div class="col">
						<?php
						echo print_input($captcha, array('class' => error_class('captcha', 'form-control'))),
						form_error('captcha');
						?>
					</div>
				</div>
			<?php endif; endif; ?>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary btn-sm">
						<?php _e('send_link') ?>
					</button>
				</div><!--/.d-grid-->

			<?php echo form_close() ?>

			<div class="card-footer bg-body-secondary border-top-0">
				<div class="d-grid">
					<?php echo anchor('login', line('login'), 'class="btn btn-light btn-sm"') ?>
				</div><!--/.d-grid-->
			</div><!--/.card-footer-->
		</div><!--/.card-->

	</div><!--/.col-->
</div><!--/.row-->
