<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-sm-8 col-md-6 col-lg-4">
		<div class="card">
			<?php echo form_open('', 'role="form" id="edit-user" class="card-body mb-0"', $hidden) ?>

			<?php foreach ($inputs as $name => $input): ?>
				<div class="mb-2">
					<?php
					echo print_input($input),
					form_error($name);
					?>
				</div>
			<?php endforeach; ?>
			<?php if ($this->user->level >= $user->level): ?>
				<div class="form-check form-switch mb-2">
					<?php
					// Enabled checkbox
					echo form_checkbox('enabled', 1, (1 == $user->enabled), array(
						'role'  => 'switch',
						'class' => 'form-check-input',
						'id'    => 'enabled'
					));
					?>
					<label class="custom-control-label" for="enabled"><?php _e('active') ?></label>
				</div>
			<?php endif; ?>

				<div class="clearfix">
					<button type="submit" class="btn btn-primary btn-sm float-end"><?php _e('save_changes') ?></button>
					<?php echo admin_anchor('users', line('cancel'), 'class="btn btn-default btn-sm"') ?>
				</div>
			<?php echo form_close() ?><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-->
</div><!--/.row-->
