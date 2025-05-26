<?php defined('BASEPATH') OR die; ?><div class="card">
	<?php echo form_open('contact', 'id="contact" role="form" class="card-body"', $hidden);	?>
	<?php if ( ! $this->auth->online()): ?>
		<!-- first name -->
		<div class="mb-2">
			<label for="first_name" class="form-label mb-1 sr-only"><?php _e('first_name') ?></label>
			<?php
			echo print_input($first_name, array('class' => error_class('first_name', 'form-control form-control-sm'))),
			form_error('first_name');
			?>
		</div>

		<!-- last name -->
		<div class="mb-2">
			<label for="last_name" class="form-label mb-1 sr-only"><?php _e('last_name') ?></label>
			<?php
			echo print_input($last_name, array('class' => error_class('last_name', 'form-control form-control-sm'))),
			form_error('last_name');
			?>
		</div>

		<!-- email address -->
		<div class="mb-2">
			<label for="email" class="form-label mb-1 sr-only"><?php _e('email_address') ?></label>
			<?php
			echo print_input($email, array('class' => error_class('email', 'form-control form-control-sm'))),
			form_error('email');
			?>
		</div>
	<?php else: ?>
		<!-- first name -->
		<div class="mb-2">
			<span class="form-control form-control-sm"><?php echo $this->user->first_name; ?></span>
		</div>

		<!-- last name -->
		<div class="mb-2">
			<span class="form-control form-control-sm"><?php echo $this->user->last_name; ?></span>
		</div>

		<!-- email address -->
		<div class="mb-2">
			<span class="form-control form-control-sm"><?php echo $this->user->email; ?></span>
		</div>
	<?php endif; do_action('contact_form'); ?>

		<!-- message -->
		<div class="mb-2">
			<label for="message" class="form-label mb-1 sr-only"><?php _e('message') ?></label>
			<?php
			echo print_input($message, array('rows' => 3, 'class' => error_class('message', 'form-control no-resize'))),
			form_error('message');
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
			<button type="submit" class="btn btn-primary"><?php _e('submit') ?></button>
		</div>
	<?php echo form_close() ?><!--/.card-body-->
</div><!--/.card-->
