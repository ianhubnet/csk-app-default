<?php
defined('BASEPATH') OR die;

// Open form.
echo form_open('', 'role="form" id="add-page" class="row" rel="persist" autocomplete="off"', $hidden);
?>
	<div class="col-12 col-lg-9 mb-3 mb-lg-0">

		<!-- page name/title -->
		<div class="mb-2">
			<?php

			/**
			 * The page's title, also used to create slug.
			 * @since   1.0
			 */
			echo print_input($field_name),

			/* Form error. */
			form_error('name');

			?>
		</div>

		<?php if (isset($field_username)): ?>
		<!-- page username/slug -->
		<div class="mb-2">
			<?php

			/**
			 * The page's title, also used to create slug.
			 * @since   1.0
			 */
			echo print_input($field_username),

			/* Form error. */
			form_error('username');

			?>
		</div>
		<?php endif; ?>

		<!-- page content -->
		<div class="mb-2">
			<?php

			/**
			 * The page's content area.
			 * @since   2.16
			 */
			echo print_input($field_content),

			/* Form error. */
			form_error('content');

			?>
		</div>

	</div><!--/.col-lg-9-->

	<div class="col-12 col-lg-3">

		<div class="card card-sm">
			<div class="card-header text-center border-bottom-0 bg-body-secondary">
				<h2 class="card-title h6 mb-0"><?php _e('excerpt') ?></h2>
			</div><!--/.card-header-->
			<div class="card-body">
				<!-- excerpt/description -->
				<div class="mt-0">
					<label for="description" class="form-label sr-only"><?php _e('excerpt') ?></label>
					<?php

					/**
					 * The description acts like except.
					 * @since   2.16
					 */
					echo print_input($field_except),

					/* Form error. */
					form_error('description');

					?>
				</div>
			</div><!--/.card-body-->
		</div><!--/.card.card-sm-->

		<div class="card card-sm mt-3">
			<a href="#" class="card-header text-center border-bottom-0 bg-body-secondary" data-bs-toggle="collapse" data-bs-target="#page-metadata">
				<h2 class="card-title h6 mb-0"><?php _e('metadata') ?></h2>
			</a>
			<div class="card-body collapse" id="page-metadata">

				<!-- meta title -->
				<div class="mb-1">
					<label for="meta_title" class="form-label sr-only"><?php _e('meta_title') ?></label>
					<?php

					/**
					 * The meta_title.
					 * @since   2.16
					 */
					echo print_input($field_meta_title),

					/* Form error. */
					form_error('meta_title');

					?>
				</div>

				<!-- meta description -->
				<div class="mb-1">
					<label for="meta_description" class="form-label sr-only"><?php _e('meta_description') ?></label>
					<?php

					/**
					 * The meta_description.
					 * @since   2.16
					 */
					echo print_input($field_meta_description),

					/* Form error. */
					form_error('meta_description');

					?>
				</div>

				<!-- meta keywords -->
				<div class="mb-0">
					<label for="meta_keywords" class="form-label sr-only"><?php _e('meta_keywords') ?></label>
					<?php

					/**
					 * The meta_keywords.
					 * @since   2.16
					 */
					echo print_input($field_meta_keywords),

					/* Form error. */
					form_error('meta_keywords');

					?>
				</div>

			</div><!--/.card-body--->
		</div><!--/.card.card-sm-->

		<div class="card card-sm mt-3">
			<div class="card-header text-center border-bottom-0 bg-body-secondary">
				<h2 class="card-title h6 mb-0"><?php _e('publish') ?></h2>
			</div><!--/.card-header-->
			<div class="card-body">
				<!-- language -->
				<div class="mt-0">
					<label for="enabled" class="sr-only"><?php _e('language'); ?></label>
					<?php

					/**
					 * Language.
						 * @since   2.66
					 */
					echo print_input($field_language);

					/* Form error. */
					echo form_error('language');

					?>
				</div>

				<!-- status -->
				<div class="mt-1">
					<label for="enabled" class="sr-only"><?php _e('status'); ?></label>
					<?php

					/**
					 * Publish status.
					 * @since   1.0
					 */
					echo form_dropdown('enabled', array(
						-1 => line('draft'),
						0  => line('pending_review'),
						1  => line('published'),
					), set_select('enabled', null, -1), array(
						'class' => error_class('enabled', 'form-control form-control-sm')
					));

					/* Form error. */
					echo form_error('enabled');

					?>
				</div>

				<!-- privacy -->
				<div class="mt-1 mb-3">
					<label for="privacy" class="sr-only"><?php _e('privacy') ?></label>
					<?php

					/**
					 * Publish status.
					 * @since   1.0
					 */
					echo form_dropdown('privacy', array(
						2 => line('public'),
						1 => line('private'),
						0 => line('password_protected'),
					), set_select('privacy', null, 2), array(
						'class' => error_class('privacy', 'form-control form-control-sm')
					));

					/* Form error. */
					echo form_error('privacy');

					?>
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary btn-sm"><?php _e('save_changes'); ?></button>
				</div>
			</div><!--/.card-body-->
		</div><!--/.card.card-sm-->

	</div><!--/.col-lg-3-->

<?php
/**
 * Close the form and page.
 * @since   1.0
 */
echo form_close();
