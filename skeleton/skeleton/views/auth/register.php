<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<h1 class="text-center h4 mb-2"><?php _e('create_account') ?></h1>

		<div class="card">
			<?php echo form_open('register', 'role="form" id="register" class="card-body"', $hidden); ?>

				<!-- first name -->
				<div class="mb-2">
					<?php
					// First name.
					echo print_input($first_name, array('class' => error_class('first_name', 'form-control form-control-sm'))),
					form_error('first_name');
					?>
				</div>

				<!-- last name -->
				<div class="mb-2">
					<?php
					// Last name.
					echo print_input($last_name, array('class' => error_class('last_name', 'form-control form-control-sm'))),
					form_error('last_name');
					?>
				</div>

				<!-- email address -->
				<div class="mb-2">
					<?php
					// Email address.
					echo print_input($email, array('class' => error_class('email', 'form-control form-control-sm')));
					form_error('email');
					?>
				</div>

				<!-- username -->
				<div class="mb-2">
					<?php
					// Username.
					echo print_input($username, array('class' => error_class('username', 'form-control form-control-sm'))),
					form_error('username');
					?>
				</div>

				<!-- password -->
				<div class="mb-2">
					<?php
					// Password.
					echo print_input($password, array('class' => error_class('password', 'form-control form-control-sm'))),
					form_error('password');
					?>
				</div>

				<!-- confirm password -->
				<div class="mb-2">
					<?php
					// Confirm password.
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
						echo print_input($captcha, array('class' => error_class('captcha', 'form-control form-control-sm')));
						echo form_error('captcha');
						?>
					</div>
				</div>
			<?php endif; endif; ?>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary btn-sm">
						<?php _e('create_account') ?>
					</button>
				</div><!--/.d-grid-->

			<?php echo form_close() ?><!--/.card-body-->

			<div class="card-footer bg-body-secondary border-top-0">
				<div class="d-grid">
					<!-- login button -->
					<?php echo anchor('login', line('login'), array(
						'class' => 'btn btn-light btn-sm flex-fill',
						'tabindex' => '-1'
					)) ?>
				</div><!--/.d-grid-->
			</div><!--/.card-footer-->
		</div><!--/.card-->
	</div><!--/.col-->
</div><!--/.row-->
