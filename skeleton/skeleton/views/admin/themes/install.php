<?php defined('BASEPATH') OR die; ?>
<div class="<?php echo error_class('themezip', 'row justify-content-center mb-3 collapse', 'show is-invalid') ?>" id="theme-install">
	<div class="col-xs-12 col-sm-8 col-md-6 text-center">
		<p class="mb-1"><?php _e('upload_theme_tip'); ?></p>
		<div class="card">
			<div class="card-body text-center">
				<?php
				// Open form.
				echo form_open_multipart('admin/themes/upload', array(
					'id' => 'upload-theme',
					'class' => error_class('themezip', 'row row-cols-1 row-cols-md-2')
				), $hidden);
				?>
					<div class="<?php echo error_class('themezip', 'col') ?>">
						<?php
						echo form_upload('themezip', 'id="themezip"'),
						form_error('themezip');
						?>
					</div>
					<div class="col d-grid">
						<button type="submit" name="theme-install" class="btn btn-primary btn-sm"><?php _e('admin_themes_install') ?></button>
					</div>
				<?php echo form_close() ?>
			</div>
		</div>
	</div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3" role="navigation">
	<div class="container-fluid">
		<div class="navbar-brand"><span class="badge bg-light text-danger">0</span></div>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#themes-filter" aria-controls="themes-filter" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

		<div class="collapse navbar-collapse" id="themes-filter">
			<ul class="nav navbar-nav me-auto">
				<li class="nav-item"><?php echo anchor('#', line('featured'), 'class="nav-link"') ?></li>
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
<div id="theme-modal-container"></div>
