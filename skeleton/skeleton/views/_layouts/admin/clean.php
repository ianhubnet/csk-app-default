<?php
/**
 * CodeIgniter Skeleton - Administration Clean Layout
 *
 * @package     CodeIgniter Skeleton
 * @subpackage  Administration
 */
defined('BASEPATH') OR die;
?>
<div class="container">
	<div class="card">
		<?php
		/**
		 * Skeleton logo filter.
		 * @since   2.0
		 */
		if ( ! empty($login_src = apply_filters('login_img_src', common_url('img/skeleton-inverse.png')))):
		?>
		<div class="card-body card-logo">
			<?php
			/**
			 * Top page image.
			 * @since 2.0
			 */
			$login_img = html_tag('img', array(
				'src'   => $login_src,
				'alt'   => apply_filters('login_img_alt', KPlatform::LABEL),
				'class' => 'login-logo',
			));

			echo anchor(apply_filters('login_img_url', site_url()), $login_img, 'tabindex="-1"');
			?>
		</div><!-- end of card-body --><?php endif; ?>
		<div class="card-body">
			<?php
			/**
			 * Display the alert.
			 * @since   2.0
			 */
			echo $this->theme->alert(),

			/**
			 * Disply the content.
			 * @since   2.0
			 */
			$this->theme->content();
			?>
		</div><!-- end of card-body -->
	</div><!-- end of card -->
</div><!-- end of container -->
<footer class="footer fixed-bottom" id="footer" role="contactinfo">
	<div class="container">
		<span>
			<?php
			/**
			 * Left side of the footer.
			 * @since   2.0
			 */
			echo anchor('', fa_icon('external-link me-1', line('go_homepage')));
			?>
		</span>
		<?php
		/**
		 * Display centered Skeleton logo.
		 * @since   2.0
		 */
		$login_logo = anchor(KPlatform::SITE_URL, null, array(
			'rel'    => 'tooltip',
			'title'  => sline('powered_by', KPlatform::LABEL),
			'class'  => 'skeleton-footer-logo position-absolute top-0 end-50',
			'target' => '_blank',
		));

		// apply filters first.
		$login_logo = apply_filters('admin_clean_logo_bottom', $login_logo);

		// disply it if not empty.
		if ( ! empty($login_logo))
		{
			echo $login_logo;
		}

		/**
		 * Filter Skeleton copyright on the clean layout.
		 * @since   2.0
		 */
		$default_copyright = sprintf('&copy; %s %s', date('Y'), KB_LABEL);
		$footer_copyright = apply_filters('login_copyright', $default_copyright);
		if ( ! empty($footer_copyright)) {
			echo '<span class="float-end">', $footer_copyright, '</span>';
		}
		?>
	</div><!-- end of container -->
</footer><!-- end of footer -->
