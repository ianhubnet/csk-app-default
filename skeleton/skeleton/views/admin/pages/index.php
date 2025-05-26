<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col">
		<div class="card card-sm">
			<div class="card-body table-responsive">
				<table class="table table-sm table-responsive-md table-hover table-striped mb-0">
					<thead>
						<tr>
							<th class="w-2">ID</th>
							<th><?php _e('title') ?></th>
							<th class="w-15"><?php _e('author') ?></th>
							<th class="w-15 text-center"><?php _e('date_created') ?></th>
							<th class="w-15 text-center"><?php _e('date_updated') ?></th>
							<th class="w-10 text-center"><?php _e('views') ?></th>
							<th class="w-20 text-end"><?php _e('actions') ?></th>
						</tr>
					</thead>
					<tbody id="pages-list">
					<?php if ( ! empty($pages)): foreach ($pages as $page): ?>
						<tr id="page-<?php echo $page->id ?>" data-id="<?php echo $page->id ?>">
							<td><strong><?php echo $page->id ?></strong></td>
							<td>
								<?php
								// Page name.
								echo admin_anchor('pages/edit/'.$page->id, $page->name);

								// Deleted?
								if ($page->deleted) {
									echo sprintf(
										'<small class="fw-bold ms-1">&#8212; %s</small>',
										line('deleted')
									);
								}
								// Draft?
								elseif (-1 == $page->enabled) {
									echo sprintf(
										'<small class="text-danger fw-bold ms-1">&#8212; %s</small>',
										line('draft')
									);
								}
								// Pending?
								elseif (0 == $page->enabled) {
									echo sprintf(
										'<small class="text-warning fw-bold ms-1">&#8212; %s</small>',
										line('pending')
									);
								}

								?>
							</td>
							<td><?php echo admin_anchor('pages?author='.$page->owner->id, $page->owner->full_name) ?></td>
							<td class="text-center"><?php echo date_formatter($page->created_at, 'datetime') ?></td>
							<td class="text-center"><?php echo date_formatter($page->updated_at, 'datetime') ?></td>
							<td class="text-center"><?php echo $page->views ?: 0 ?></td>
							<td class="text-end">
								<?php if ($this->i18n->polylang): ?>
								<div class="dropdown d-inline position-static">
									<a href="javascript:void(0)" class="btn btn-primary btn-xs" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-globe"></i></a>
									<div class="dropdown-menu dropdown-menu-end">
									<?php
									foreach ($this->i18n->others($page->language) as $folder => $lang)
									{
										echo admin_anchor(
											"pages/edit/$page->id/$folder",
											'<i class="flag flag-'.$lang['flag'].'" data-text="'.line($lang['id']).'"></i>',
											'class="dropdown-item"'
										);
									}
									?>
									</div>
								</div>
								<?php endif; ?>
								<div class="btn-group btn-group-xs">
									<?php
									/**
									 * Actions buttons.
									 * @since   2.16
									 */

									/* Restore/Delete page. */
									if ($page->deleted)
									{
										echo html_tag('button', array(
											'class'        => 'btn btn-default',
											'title'        => line('restore'),
											'aria-label'   => $page->name,
											'data-confirm' => 'lang:pages.restore',
											'data-form'    => esc_url(nonce_admin_url('pages/action/restore', 'page-restore_'.$page->id)),
											'data-fields'  => "id:$page->id"
										), fa_icon('refresh text-success'));
									}
									else
									{
										/* View page. */
										echo anchor('page/'.$page->username, fa_icon('eye'), array(
											'class'  => 'btn btn-default',
											'target' => '_blank',
											'title'  => line('view')
										)),


										/* Edit page. */
										admin_anchor('pages/edit/'.$page->id, fa_icon('edit text-primary'), array(
											'class' => 'btn btn-default',
											'title' => line('edit')
										)),

										html_tag('button', array(
											'class'        => 'btn btn-default',
											'title'        => line('delete'),
											'aria-label'   => $page->name,
											'data-confirm' => 'lang:pages.delete',
											'data-form'    => esc_url(nonce_admin_url('pages/action/delete', 'page-delete_'.$page->id)),
											'data-fields'  => "id:$page->id"
										), fa_icon('times text-danger'));
									}

									/* Remove page. */
									echo anchor('#', fa_icon('trash'), array(
										'class'        => 'btn btn-danger',
										'title'        => line('remove'),
										'aria-label'   => $page->name,
										'data-confirm' => 'lang:pages.remove',
										'data-form'    => esc_url(nonce_admin_url('pages/action/remove', 'page-remove_'.$page->id)),
										'data-fields'  => "id:$page->id"
									));
									?>
								</div>
							</td>
						</tr>
					<?php endforeach; else: ?>
						<tr><td class="text-center" colspan="7"><?php _e('no_data_error') ?></td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div><!--/.card-body-->
		</div><!--/.card-->
		<?php echo $pagination; ?>
	</div><!--/.col-->
</div><!--/.row-->
