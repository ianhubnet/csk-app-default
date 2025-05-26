<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col">
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-hover table-striped mb-0">
					<thead>
						<tr>
							<th class="w-2"><input type="checkbox" class="check-all" autocomplete="off"<?php echo (empty($users) or count($users) === 1) ? ' disabled="disabled"' : '' ?>></th>
							<th class="w-25"><?php _e('full_name') ?></th>
							<th class="w-12"><?php _e('username') ?></th>
							<th class="w-21"><?php _e('email_address') ?></th>
							<th class="w-10 text-center"><?php _e('role') ?></th>
							<th class="w-12"><?php _e('status') ?></th>
							<th class="w-10 text-center"><?php _e('date_join') ?></th>
							<th class="w-8 text-end"><?php _e('actions') ?></th>
						</tr>
					</thead>
					<tbody id="users-list">
					<?php if ($users): foreach ($users as $user): ?>
						<tr id="user-<?php echo $user->id; ?>" data-id="<?php echo $user->id ?>">
							<td><input type="checkbox" class="check-this" autocomplete="off"<?php if ($user->id === $this->user->id): ?> disabled="disabled"<?php endif; ?>></td>
							<td><?php
							/**
							 * User's online status.
							 * @since 	2.16
							 */
							echo html_tag('span', 'class="text-'.($user->online ? (TIME < $user->check_online_at ? 'success' : 'warning') : 'transparent').' me-1"', '●');

							/**
							 * User user's full name.
							 * @since 	2.16
							 */
							echo admin_anchor('users/edit/'.$user->id, $user->full_name);
							?></td>
							<td><?php echo $user->username ?></td>
							<td><?php echo $this->core->has_demo_access(KB_LEVEL_MANAGER) ? $user->email : line('hidden_tag') ?></td>
							<td class="text-center"><?php echo admin_anchor('users?role='.$user->subtype, line($user->role_name), 'class="badge bg-secondary"') ?></td>
							<td class="text-small">
								<?php

								if ($user->deleted !== 0)
								{
									echo admin_anchor(
										'users?status=deleted',
										line('deleted'),
										'class="badge bg-danger"'
									);
								}
								elseif ($user->locked)
								{
									echo admin_anchor(
										'users?status=locked',
										line('locked'),
										'class="badge bg-dark"'
									);
								}
								elseif ($user->banned)
								{
									echo admin_anchor(
										'users?status=banned',
										line('banned'),
										'class="badge bg-dark"'
									);
								}
								elseif ($user->enabled !== 1)
								{
									echo admin_anchor(
										'users?status=inactive',
										line('inactive'),
										'class="badge bg-warning"'
									);
								}
								else
								{
									echo admin_anchor(
										'users?status=active',
										line('active'),
										'class="badge bg-success"'
									);
								}
								?>
							</td>
							<td class="text-center text-small"><?php echo date_formatter($user->created_at) ?></td>
							<td class="text-end">
								<?php
								if ($this->user->admin && $this->user->id !== $user->id)
								{
									echo anchor('#', fa_icon('sign-in text-danger'), array(
										'class' => 'btn btn-default btn-xs me-1',
										'title' => line('login'),
										'data-form' => esc_url(site_url('switch-account')),
										'data-fields' => 'id:'.$user->id,
										'data-confirm' => 'lang:default.switch'
									));
								}
								?>
								<div class="btn-group btn-group-xs"><?php
								echo admin_anchor('users/edit/'.$user->id, fa_icon('edit text-primary'), array(
									'class' => 'btn btn-default',
									'title' => line('edit')
								)
								);
								echo anchor('users/'.$user->username, fa_icon('eye'), array(
									'class' => 'btn btn-default',
									'title' => line('view_profile')
								));
								?></div>
							</td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td class="text-center" colspan="8"><?php _e('no_data_error') ?></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
		<?php echo $pagination; ?>
	</div><!--/.col-sm-->
</div><!--/.row-->
