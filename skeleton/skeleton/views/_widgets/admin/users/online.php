<?php defined('BASEPATH') OR die; ?><div class="col-md-3">
	<div class="card card-sm">
		<div class="card-header bg-body-secondary border-bottom-0">
			<h3 class="card-title h6 mb-0">
				<i class="fa fa-fw fa-users me-2"></i>
				<?php _e('admin_users_logged') ?>
			</h3>
		</div><!--/.card-header-->
		<div class="card-body table-responsive">
			<table class="table table-sm table-striped">
				<tbody>
				<?php foreach ($users as $user): ?><tr>
					<td class="w-60"><?php echo admin_anchor('users/edit/'.$user->id, $user->full_name) ?></td>
					<td class="w-40 text-end"><?php echo $user->ip_anchor; ?></td>
				</tr><?php endforeach; ?>
				</tbody>
			</table>
		</div><!--/.card-body-->
	</div><!--/.card-->
</div><!--/.col-md-3-->
