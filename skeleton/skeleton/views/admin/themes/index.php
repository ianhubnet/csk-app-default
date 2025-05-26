<?php
defined('BASEPATH') OR die;
if ($themes): ?>
<div class="row row-cols-md-3" id="themes-list">
<?php foreach ($themes as $t): ?>
	<div class="col theme-item" id="theme-<?php echo $t['folder'] ?>" data-id="<?php echo $t['name'] ?>">
		<div class="card theme-inner">
			<img data-src="<?php echo $t['screenshot'] ?>" alt="<?php echo $t['name'] ?>" class="theme-screenshot img-fluid" />
			<div class="theme-caption clearfix p-2">
				<h3 class="theme-title"><?php echo $t['name'] ?></h3>
				<div class="btn-group btn-group-sm float-end"><?php echo implode('', $t['actions']) ?></div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div><!--/row-->
<?php endif;
if (isset($theme) && null !== $theme): ?>
	<div class="modal modal-land" id="theme-modal" role="dialog" aria-hidden="false" aria-labelledby="modal-tital" tabindex="-1">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header clearfix">
					<h2 class="modal-title" id="modal-tital"><?php echo sprintf('%s: %s', line('details'), $theme['name']); ?></h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('close') ?>"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12 col-md-7">
							<img data-src="<?php echo $theme['screenshot']; ?>" alt="<?php echo $theme['name']; ?>" class="img-fluid" data-action="zoom">
						</div>
						<div class="col-sm-12 col-md-5">
							<h2 class="page-header clearfix"><?php echo $theme['name_uri']; ?> <small class="text-body-secondary"><?php echo $theme['version']; ?></small><small class="float-end"><?php echo label_condition($theme['enabled'], 'active', 'inactive'); ?></small></h2>
							<p><?php echo $theme['description']; ?></p><br />
							<div class="table-responsive-sm">
								<table class="table table-sm table-condensed table-striped">
									<tr><th class="w-35"><?php _e('author'); ?></th><td><?php echo $theme['author']; ?></td></tr>
									<?php if ($theme['author_email']): ?>
									<tr><th><?php _e('author_email'); ?></th><td><?php echo $theme['author_email']; ?></td></tr>
									<?php endif; ?>
									<tr><th><?php _e('license'); ?></th><td><?php echo $theme['license']; ?></td></tr>
									<tr><th><?php _e('tags'); ?></th><td class="text-wrap"><?php echo $theme['tags']; ?></td></tr>
								</table>
							</div>
							<?php if (true !== $theme['enabled']): ?>
							<p class="clearfix"><?php echo $theme['action_activate'], $theme['action_delete']; ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif;
