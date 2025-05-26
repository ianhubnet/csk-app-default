<?php defined('BASEPATH') OR die; ?>
<div class="<?php echo error_class('modulezip', 'row justify-content-center mb-3 collapse', 'show is-invalid') ?>" id="module-install">
	<div class="col-xs-12 col-sm-8 col-md-6 text-center">
		<p class="mb-1"><?php _e('admin_modules_upload_tip'); ?></p>
		<div class="card">
			<div class="card-body text-center">
				<?php echo form_open_multipart('admin/modules/upload', array(
					'id' => 'upload-module',
					'class' => error_class('modulezip', 'row row-cols-1 row-cols-md-3')
				), $hidden);
				?>
				<div class="<?php echo error_class('modulezip', 'col') ?>">
					<?php
					echo form_upload('modulezip', 'id="modulezip"');
					echo form_error('modulezip');
					?>
				</div>
				<div class="col">
					<?php
					// Location selection.
					echo form_dropdown('location', array(
						'-1' => line('admin_modules_location_select'),
						'0'  => line('admin_modules_location_public'),
						'1'  => line('admin_modules_location_application'),
						'2'  => line('admin_modules_location_core'),
					), '1', 'class="form-select form-select-sm"');
					?>
				</div>
				<div class="col d-grid">
					<?php echo form_submit('module-install', line('admin_modules_install'), 'class="btn btn-primary btn-sm"') ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3" role="navigation">
	<div class="container-fluid">
		<div class="navbar-brand"><span class="badge bg-light text-danger">0</span></div>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#modules-filter" aria-controls="modules-filter" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

		<div class="collapse navbar-collapse" id="modules-filter">
			<ul class="nav navbar-nav me-auto">
				<li class="nav-item"><?php echo anchor('#', line('featured'), 'class="nav-link"') ?></li>
				<li class="nav-item"><?php echo anchor('#', line('recommended'), 'class="nav-link"') ?></li>
				<li class="nav-item"><?php echo anchor('#', line('popular'), 'class="nav-link"') ?></li>
				<li class="nav-item"><?php echo anchor('#', line('new'), 'class="nav-link"') ?></li>
			</ul>
			<form class="d-flex" role="search" method="get" action="javascript:void(0);">
				<select name="type" id="type" class="form-select form-select-sm me-2">
					<option value="name" selected="selected"><?php _e('name'); ?></option>
					<option value="tags"><?php _e('tags'); ?></option>
					<option value="author"><?php _e('author'); ?></option>
				</select>
				<input type="text" class="form-control form-control-sm" id="search" name="search" placeholder="<?php _e('search_dots'); ?>">
			</form>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>
<div class="alert alert-info"><?php _e('developed_soon') ?></div>
<div id="module-modal-container"></div>
