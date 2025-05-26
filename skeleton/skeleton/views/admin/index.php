<?php
defined('BASEPATH') OR die;

/**
 * Fires at the top of dashboard main page content.
 * @since   2.1
 */
do_action('admin_index_header');

/**
 * Fires within the dashboard top stats cards.
 * @since   2.1
 */
if (has_action('admin_index_stats')): ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-3 mb-3">
	<?php do_action('admin_index_stats'); ?>
</div>
<?php endif; ?>
<div class="row g-3">
	<?php
	/**
	 * Fires within the dashboard main page content.
	 * @since   2.1
	 */
	do_action('admin_index_content');

	/**
	 * Display latest 10 actions.
	 * @since   2.16
	 */
	echo $this->theme->widget('latest-actions');

	/**
	 * Logged-in users.
	 * @since   2.116
	 */
	echo $this->theme->widget('logged-in');
	?>
</div>
<?php

/**
 * Fires below the dashboard main page content.
 * @since   2.1
 */
do_action('admin_index_footer');
