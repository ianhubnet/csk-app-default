<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col">
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-hover table-striped mb-0">
					<thead>
						<tr>
							<th class="w-25"><?php _e('module') ?></th>
							<th class="w-50"><?php _e('description') ?></th>
							<th class="text-end"><?php _e('actions') ?></th>
						</tr>
					</thead>
					<tbody id="modules-list">
					<?php if ($modules): foreach ($modules as $folder => $module): ?>
						<tr id="module-<?php echo $folder ?>" data-id="module-<?php echo $folder ?>">
							<td class="<?php echo $module['enabled'] ? 'fw-bold' : ''; ?>">
								<?php echo $module['name']; ?>
							</td>
							<td class="text-wrap">
								<p class="mb-2"><?php echo $module['description']; ?></p>
								<p class="text-small mb-0"><?php echo implode(' &#124; ', $module['details']); ?></p>
							</td>
							<td class="text-end">
								<div class="btn-group btn-group-xs" role="toolbar">
									<?php echo implode('', $module['actions']) ?>
								</div>
							</td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td class="text-center" colspan="3"><?php _e('no_data_error') ?></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-sm-->
</div><!--/.row-->
