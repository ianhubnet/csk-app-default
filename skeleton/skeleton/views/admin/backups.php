<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col col-md-6">
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-hover table-striped mb-0">
				<thead>
					<tr>
						<th class="w-2"><input type="checkbox" class="check-all" autocomplete="off"<?php echo empty($files) ? ' disabled="disable"': '' ?>></th>
						<th><?php _e('name') ?></th>
						<th class="w-10 text-center"><?php _e('locked') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( ! empty($files)): foreach ($files as $file): ?>
					<tr id="<?php echo $file['filename'] ?>" data-id="<?php echo $file['filename'] ?>">
						<td><input type="checkbox" class="check-this" autocomplete="off" /></td>
						<td><?php echo $file['name']; ?></td>
						<td class="text-center"><?php echo label_condition($file['locked']); ?></td>
					</tr>
					<?php endforeach; else: ?>
					<tr><td colspan="3" class="text-center"><?php _e('no_data_error') ?></td></tr>
					<?php endif; ?>
				</tbody>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-sm-6-->
</div><!--/.row-->
