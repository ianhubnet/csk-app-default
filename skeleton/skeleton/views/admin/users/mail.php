<?php
defined('BASEPATH') OR die;
/**
 * Open the form.
 */
echo form_open('', 'role="form" id="mail" class="row"', $hidden);
?>
<div class="col-sm-9">
	<!-- subject -->
	<div class="mb-3">
		<label for="subject" class="form-label sr-only"><?php _e('subject') ?></label>
		<?php
		echo print_input($subject),
		form_error('subject');
		?>
	</div>

	<!-- message -->
	<div class="mb-0">
		<label for="message" class="form-label sr-only"><?php _e('message') ?></label>
		<?php
		echo print_input($message),
		form_error('message');
		?>
	</div>
</div><!--/.col-sm-9-->

<div class="col-sm-3">
	<div class="card">
		<div class="card-body">
			<!-- disabled accounts -->
			<div class="form-check form-switch">
				<?php
				echo form_checkbox('disabled', '1', set_checkbox('disabled', '1'), array(
					'role'  => 'switch',
					'class' => 'form-check-input',
					'id'    => 'disabled',
				));
				?>
				<label class="custom-control-label" for="disabled"><?php _e('admin_users_mailer_to_disabled') ?></label>
			</div>

			<!-- banned accounts -->
			<div class="form-check form-switch">
				<?php
				echo form_checkbox('banned', '1', set_checkbox('banned', '1'), array(
					'role'  => 'switch',
					'class' => 'form-check-input',
					'id'    => 'banned',
				));
				?>
				<label class="custom-control-label" for="banned"><?php _e('admin_users_mailer_to_banned') ?></label>
			</div>

			<!-- deleted accounts -->
			<div class="form-check form-switch">
				<?php
				echo form_checkbox('deleted', '1', set_checkbox('deleted', '1'), array(
					'role'  => 'switch',
					'class' => 'form-check-input',
					'id'    => 'deleted',
				));
				?>
				<label class="custom-control-label" for="deleted"><?php _e('admin_users_mailer_to_deleted') ?></label>
			</div>

			<?php if (isset($roles)): ?>
			<!-- custom user groups -->
			<div class="mt-3">
				<label for="subtype" class="form-label"><?php _e('admin_users_groups') ?></label>
				<?php
				echo form_dropdown(array(
					'id'       => 'subtype',
					'name'     => 'subtype',
					'class'    => 'form-select form-select-sm',
					'selected' => set_select('subtype', null, '**')
				), $roles);
				?>
			</div>
			<?php endif; ?>

			<div class="d-grid">
				<button type="submit" class="btn btn-primary btn-sm mt-3"><?php _e('send') ?></button>
			</div>
		</div><!--/.card-body-->
	</div><!--/.card-->
</div><!--/.col-sm-3-->
<?php
echo form_close();
