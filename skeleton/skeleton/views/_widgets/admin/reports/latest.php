<?php
/**
 * CodeIgniter Skeleton - Administration Reports Widget
 * @package     CodeIgniter Skeleton
 * @subpackage  Views/Widgets
 * @since   2.16
 */
defined('BASEPATH') OR die; ?><div class="col-md-9">
	<div class="card card-sm">
		<div class="card-header bg-body-secondary border-bottom-0">
			<h3 class="card-title h6 mb-0">
				<i class="fa fa-fw fa-history me-1"></i>
				<?php _e('admin_reports_latest_actions')?>
			</h3>
		</div><!--/.card-header-->
		<div class="card-body table-responsive">
			<table class="table table-sm table-striped">
				<tbody>
				<?php foreach ($reports as $report): ?><tr>
					<td class="w-50"><?php echo $report->output; ?></td>
					<td class="w-25"><?php echo $report->ip_anchor; ?></td>
					<td class="w-25 text-end"><i class="fa fa-fw fa-calendar me-2"></i> <?php echo date_formatter($report->created_at, 'datetime'); ?></td>
				</tr><?php endforeach; ?>
				</tbody>
			</table>
		</div><!--/.card-body-->
	</div><!--/.card-->
</div><!--/.col-md-9-->
