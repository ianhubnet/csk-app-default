<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-sm-8 col-md-6 col-lg-4">
		<div class="card">
			<?php echo form_open('', 'role="form" id="add-user" class="card-body mb-0"', $hidden) ?>

			<!-- first name -->
			<div class="mb-2">
				<label for="first_name" class="form-label sr-only"><?php _e('first_name') ?></label>
				<?php
				echo print_input($first_name, array(
					'autofocus' => 'autofocus',
					'class' => error_class('first_name', 'form-control')
				)),
				form_error('first_name');
				?>
			</div>

			<!-- last name -->
			<div class="mb-2">
				<label for="last_name" class="form-label sr-only"><?php _e('last_name') ?></label>
				<?php
				echo print_input($last_name),
				form_error('last_name');
				?>
			</div>

			<!-- email address -->
			<div class="mb-2">
				<label for="email" class="form-label sr-only"><?php _e('email_address') ?></label>
				<?php
				echo print_input($email),
				form_error('email');
				?>
			</div>

			<!-- username -->
			<div class="mb-2">
				<label for="username" class="form-label sr-only"><?php _e('username') ?></label>
				<?php
				echo print_input($username),
				form_error('username');
				?>
			</div>

			<!-- password -->
			<div class="mb-2">
				<label for="password" class="form-label sr-only"><?php _e('password') ?></label>
				<?php
				echo print_input($password),
				form_error('password');
				?>
			</div>

			<!-- confirm password -->
			<div class="mb-2">
				<label for="cpassword" class="form-label sr-only"><?php _e('confirm_password') ?></label>
				<?php
				echo print_input($cpassword),
				form_error('cpassword');
				?>
			</div>

			<!-- subtype -->
			<div class="mb-2">
				<label for="subtype" class="form-label sr-only"><?php _e('role') ?></label>
				<?php
				echo print_input($subtype),
				form_error('subtype');
				?>
			</div>

			<!-- enabled -->
			<div class="form-check form-switch mb-2">
				<?php
				// Enabled checkbox
				echo form_checkbox('enabled', 1, set_checkbox('enabled', '1', false), array(
					'role'  => 'switch',
					'class' => 'form-check-input',
					'id'    => 'enabled'
				));
				?>
				<label class="form-check-label" for="enabled"><?php _e('active') ?></label>
			</div>

			<div class="clearfix">
				<button type="submit" class="btn btn-primary btn-sm float-end"><?php _e('admin_users_add') ?></button>
				<?php echo admin_anchor('users', line('cancel'), 'class="btn btn-default btn-sm"') ?>
			</div>
			<?php echo form_close(); ?><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.column-->
</div><!--/.row-->
