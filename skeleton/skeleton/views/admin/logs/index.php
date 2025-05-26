<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col col-md-8">
		<?php
		/* Show alert about logs being disabled. */
		if ($log_threshold < 0)
			echo $this->theme->template('alert', line('admin_logs_error_disabled'), 'warning');
		?>
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-hover table-striped mb-0">
					<thead>
						<tr>
							<th class="w-2"><input type="checkbox" class="check-all" autocomplete="off"<?php echo (empty($logs)) ? ' disabled="disabled"' : '' ?>></th>
							<th><?php _e('date'); ?></th>
							<th><?php _e('file'); ?></th>
							<th class="text-end"><?php _e('size'); ?></th>
						</tr>
					</thead>
					<tbody id="logs-list">
					<?php if ( ! empty($logs) && is_array($logs)): foreach ($logs as $name => $info): ?>
						<tr id="<?php echo $name; ?>" data-id="<?php echo $name; ?>">
							<td><input type="checkbox" class="check-this" autocomplete="off"></td>
							<td><?php echo $info['date']; ?></td>
							<td><?php echo admin_anchor('logs/'.$name, $name); ?></td>
							<td class="text-end"><?php echo $info['size']; ?></td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td class="text-center" colspan="4"><?php _e('no_data_error') ?></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
		<?php echo $pagination; ?>
	</div><!--/.col-sm-->
</div><!--/.row-->
