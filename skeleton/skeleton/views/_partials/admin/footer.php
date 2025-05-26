<?php defined('BASEPATH') OR die; ?><!-- start of dashboard footer -->
<footer class="footer fixed-bottom border-top pt-2 pb-2" id="footer" role="contactinfo">
	<div class="container clearfix">
		<div class="row d-flex">
			<div class="col-12 col-md-6 text-center text-md-start">
				<?php
				/**
				 * Fires right after the opening tag of the admin footer.
				 * @since   1.4
				 */
				do_action('in_admin_footer');
				?>
				<span class="text-body-secondary" id="footer-thankyou"><?php echo sline('admin_footer_left', KPlatform::SITE_URL, KPlatform::LABEL) ?></span>
			</div><!--/.col-12-->
			<div class="col-12 col-md-6 text-center text-md-end d-none d-md-block">
				<?php
					/**
					 * Footer version text.
					 * @since   1.4
					 * Can be removed or overridden using the "admin_footer_right" fitler.
					 */
					empty($version = apply_filters('admin_footer_right', '')) && $version = sline('admin_footer_right', KB_VERSION);
					$this->auth->is_admin() && $version .= ' &#124; {elapsed_time}';
				?>
				<span class="text-body-secondary" id="footer-upgrade"><?php echo $version ?></span>
			</div><!--/.col-12-->
		</div><!--/.row-->
	</div>
</footer>
<!-- end of dashboard footer -->

<!-- start of alert template -->
<script type="text/x-handlebars-template" id="csk-alert-template">
<div id="csk-alert" class="alert alert-{{type}} alert-dismissible fade show" role="alert">
	{{message}}
	<button type="button" class="close" data-dismiss="alert" aria-label="<?php echo line('close') ?>>
		<span aria-hidden="true">&times;</span>
	</button>
</div>
</script>
<!-- end of alert template -->
