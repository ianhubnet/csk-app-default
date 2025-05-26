<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<h1 class="text-center h4 mb-2"><?php _e('reset_password') ?></h1>

		<div class="card">
			<?php echo form_open('', 'role="form" id="reset-password" class="card-body"') ?>

				<!-- new password -->
				<div class="mb-2">
					<?php
					// New password field.
					echo print_input($npassword, array('class' => error_class('npassword', 'form-control form-control-sm'))),
					form_error('npassword');
					?>
				</div>

				<!-- confirm password -->
				<div class="mb-2">
					<?php
					// Confirm password field.
					echo print_input($cpassword, array('class' => error_class('cpassword', 'form-control form-control-sm'))),
					form_error('cpassword');
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
						<?php _e('reset_password') ?>
					</button>
				</div><!--/.d-grid-->
			<?php echo form_close() ?><!--/.card-body-->

			<div class="card-footer bg-body-secondary border-top-0">
				<div class="d-grid">
					<?php echo anchor('login', line('login'), 'class="btn btn-light btn-sm"') ?>
				</div><!--/.d-grid-->
			</div><!--/.card-footer-->
		</div><!--/.card-->
	</div><!--/.col-->
</div><!--/.row-->
