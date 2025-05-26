<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<h1 class="text-center h4 mb-2"><?php _e('two_factor_auth') ?></h1>

		<div class="card">
			<?php echo form_open('login-2fa', 'role="form" id="login" class="card-body mb-0"', $hidden); ?>

				<!-- authentication code -->
				<div class="mb-3">
					<label for="tfa" class="form-label mb-1 sr-only"><?php _e('auth_code') ?></label>
					<?php
					// Username/Email field.
					echo print_input($tfa, array(
						'autofocus' => 'autofocus',
						'class'     => error_class('tfa', 'form-control form-control-sm')
					)),
					form_error('tfa');
					?>
				</div>

			<?php if (isset($captcha)): if ($this->config->item('use_recaptcha')): ?>
				<!-- google recaptcha -->
				<div class="mb-3 text-center">
					<?php echo $captcha, form_error('g-recaptcha-response'); ?>
				</div>

			<?php else: ?>
				<!-- captcha -->
				<div class="row row-cols-1 row-cols-md-2 mb-3">
					<div class="col" tabindex="-1">
						<?php echo $captcha_image ?>
					</div>
					<div class="col">
						<?php
						echo print_input($captcha, array('class' => error_class('captcha', 'form-control')));
						echo form_error('captcha');
						?>
					</div>
				</div>
			<?php endif; endif; ?>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary btn-sm">
						<?php _e('login') ?>
					</button>
				</div><!--/.d-grid-->

			<?php echo form_close() ?><!--/.card-body -->
		</div><!--/.card-->
	</div><!--/.col-->
</div><!--/.row-->
