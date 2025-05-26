<?php
defined('BASEPATH') OR die;

/**
 * Dashboard clean layout.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Views - Layouts.
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.0
 * @version 	2.0
 */
?>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-6 col-lg-4">
			<div class="card card-sm"><?php
				/**
				 * Skeleton logo filter.
				 * @since 	2.0
				 */
				$login_src = apply_filters('login_img_src', common_url('img/skeleton-inverse.png'));
				$login_alt = apply_filters('login_img_alt', KB_LABEL);
				$login_url = apply_filters('login_img_url', site_url());

			if ( ! empty($login_src)):
				?>
				<div class="card-body card-logo">
					<?php
					$login_img = html_tag('img', array(
						'src'   => $login_src,
						'class' => 'login-logo',
						'alt'   => $login_alt
					));

					echo empty($login_url) ? $login_img : "<a href=\"{$login_url}\" tabindex=\"-1\">{$login_img}</a>";
					?>
				</div><?php
			endif;
				?><div class="card-body">
					<?php
					// Display the alert.
					echo $this->theme->alert(),

					// Display the content.
					$this->theme->content();
					?>
				</div><!-- end of .card-body -->
			</div><!-- end of .card -->
		</div>
	</div><!-- end of .row -->
</div><!-- end of container -->
<footer class="footer" id="footer" role="contactinfo">
	<div class="container">
		<span><?php echo anchor('', fa_icon('external-link me-1', line('go_homepage'))) ?></span>
		<?php
		/**
		 * Display centered Skeleton logo.
		 * @var string
		 */
		$login_logo = html_tag('a', array(
			'href'   => KPlatform::SITE_URL,
			'target' => '_blank',
			'rel'    => 'tooltip',
			'title'  => sline('powered_by', KPlatform::LABEL),
			'class'  => 'skeleton-footer-logo',
		));
		$login_logo = apply_filters('login_logo', $login_logo);
		if ( ! empty($login_logo)) {
			echo $login_logo;
		}

		/**
		 * Footer right text filter.
		 * @since 	2.0
		 */
		$default_copyright = sprintf('&copy; %s %s', date('Y'), $site_name);
		$footer_copyright = apply_filters('login_copyright', $default_copyright);
		if ( ! empty($footer_copyright))
		{
			echo html_tag('span', 'class="float-end"', $footer_copyright);
		}
		?>
	</div>
</footer>
