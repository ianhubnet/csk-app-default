<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-lg-8">
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-striped table-hover mb-0">
					<thead>
						<tr>
							<th class="w-5 text-start d-none d-md-table-cell">#</th>
							<th class="w-40"><?php _e('language') ?></th>
							<th class="text-center d-none d-md-table-cell"><?php _e('code') ?></th>
							<th class="w-35 text-end no-sort"><?php _e('actions') ?></th>
						</tr>
					</thead>
				<?php if ($languages): ?>
					<tbody id="languages-list">
					<?php $i = 1; foreach ($languages as $folder => $lang): ?>
						<tr id="lang-<?php echo $folder ?>" data-id="<?php echo $lang['name'] ?>" class="bg-body-secondary">
							<td class="text-start d-none d-md-table-cell"><?php echo str_pad($i++, 2, '0', STR_PAD_LEFT) ?></td>
							<td><i class="flag flag-<?php echo $lang['flag'] ?>" data-text="<?php echo line($lang['id']) ?>"></i><span class="text-body-secondary d-none d-md-inline ms-2"><?php echo $lang['name_en'] ?></span></td>
							<td class="text-center d-none d-md-table-cell"><?php echo $lang['code'] ?><small class="text-body-secondary ms-2"><?php echo $lang['locale'] ?></small></td>
							<td class="text-end">
								<div class="btn-group btn-group-xs"><?php
								echo isset($lang['actions']) ? implode('', $lang['actions']) : '';
								?></div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				<?php endif; ?>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-sm-->
</div><!--/.row-->
