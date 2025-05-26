<?php defined('BASEPATH') OR die; ?>
<div class="card card-sm mb-2">
	<div class="card-body table-responsive">
		<table class="table table-sm table-hover table-striped mb-0">
			<thead>
				<tr>
					<th class="w-40"><?php _e('actions') ?></th>
					<th class="w-20"><?php _e('module') ?></th>
					<th class="text-center"><?php _e('ip_address') ?></th>
					<th class="text-end"><?php _e('date') ?></th>
				</tr>
			</thead>
		<?php if ($reports): ?>
			<tbody id="reports-list">
			<?php foreach ($reports as $report): ?>
				<tr id="report-<?php echo $report->id ?>" data-id="<?php echo $report->id ?>" class="report-item">
					<td><?php echo $report->output ?></td>
					<td><?php echo $report->module_anchor, $report->controller_anchor , $report->method_anchor; ?></td>
					<td class="text-center"><?php echo $report->ip_anchor ?></td>
					<td class="text-end"></i><?php echo date_formatter($report->created_at, 'datetime') ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		<?php endif; ?>
		</table>
	</div><!--/.card-body-->
</div><!--/.card -->
<?php
// Pagination.
echo $pagination;
