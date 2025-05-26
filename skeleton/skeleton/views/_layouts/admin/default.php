<?php
/**
 * CodeIgniter Skeleton - Administration Default Layout
 *
 * @package     CodeIgniter Skeleton
 * @subpackage  Administration
 */
defined('BASEPATH') OR die;

/**
 * Separated dashboard header.
 * @since 	2.12
 */
echo $this->theme->partial('header');

?>

<main id="wrapper" role="main" class="pt-3 pb-5">
	<div class="container">
		<?php

		// Display the alert.
		echo $this->theme->alert();

		/**
		 * Fires at the top of page content.
		 * @since   1.4
		 */
		do_action('admin_page_header');

		// Display the page content.
		echo $this->theme->content();

		/**
		 * Fires at the end of page content.
		 * @since   1.4
		 */
		do_action('admin_page_footer');

		?>
	</div><!--/.container-->
</main><!--/#wrapper-->

<?php
/**
 * Separated dashboard footer.
 * @since 	2.12
 */
echo $this->theme->partial('footer');
