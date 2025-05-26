<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col col-md-4">
		<div class="card">
			<?php
			echo form_open($action, 'role="form" class="card-body" id="settings-'.$tab.'"', $hidden);
			foreach ($inputs as $name => $input): $class = ($input['type'] === 'dropdown') ? 'select' : 'control'; ?>
				<div class="mb-3">
					<?php
					echo form_label(line($name), $name, 'class="form-label mb-1"');
					echo print_input($input, array('class' => error_class($name, "form-{$class} form-{$class}-sm")));
					echo form_error($name, null, null, line($name.'_tip'));
					?>
				</div>
			<?php endforeach; ?>
				<div class="d-grid">
					<button type="submit" class="btn btn-primary btn-sm"><?php _e('save_changes') ?></button>
				</div>
			<?php echo form_close() ?><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-->
</div><!--/.row-->
