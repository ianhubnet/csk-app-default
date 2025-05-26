<div class="row">
	<div class="col-12 col-md-9 order-md-2">
		<div class="card">
			<div class="card-header bg-body-secondary py-3">
				<h1 class="card-title h4 mb-0"><?php echo $this->page_title ?></h1>
			</div><!--/.card-header-->

			<div class="card-body">
				<?php echo form_open_multipart('', 'role="form" id="update-avatar"', $hidden) ?>
					<!-- Avatar field. -->
					<div class="row mb-3">
						<label for="avatar" class="col-sm-3 col-form-label"><?php _e('avatar'); ?></label>
						<div class="col-sm-9">
							<div class="d-flex">
								<div class="flex-shrink-0">
									<?php echo $this->user->avatar(50, 'class="rounded-circle"'); ?>
								</div>
								<div class="flex-grow-1 ms-3">
									<label for="avatar" class="btn btn-outline-secondary btn-sm"><?php _e('upload') ?></label>
									<?php
									echo form_upload('avatar', array(
										'id' => 'avatar',
										'class' => error_class('avatar', 'hidden'),
										'hidden' => 'hidden',
										'style' => 'display:none'
									));
									?>
								</div>
							</div>
						</div>
					</div>

					<!-- Use Gravatar checkbox -->
				<?php if (empty($this->user->gravatar)): ?>
					<div class="row justify-content-end mb-3">
						<div class="col-sm-9">
							<div class="form-check mb-0">
								<input type="checkbox"name="gravatar" id="gravatar" class="form-check-input" value="1">
								<label for="gravatar" class="form-check-label ms-1"><?php _e('use_gravatar') ?></label>
								<p class="form-text mb-0"><?php _e('use_gravatar_help'); ?></p>
							</div>
						</div>
					</div>
				<?php endif; ?>

					<div class="row justify-content-end">
						<div class="col-sm-9 d-grid">
							<button type="submit" class="btn btn-primary btn-sm"><?php _e('update') ?></button>
						</div>
					</div>

				<?php echo form_close() ?>
			</div><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.col-->

	<div class="col-12 col-md-3 order-md-1 mt-3 mt-md-0">
		<?php echo $this->theme->widget('navbar') ?>
	</div><!--/.col-->
</div>
