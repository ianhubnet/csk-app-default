<footer class="navbar fixed-bottom bg-body-secondary" role="contentinfo">
	<div class="container justify-content-center">
		<p class="navbar-text p-0 m-1"><?php echo anchor('', $site_name) ?>. &copy; <?php _e('all_rights_reserved') ?> <?php echo date('Y') ?>.<?php if (ENVIRONMENT !== 'production' && $this->auth->is_admin()): ?> <abbr title="Render Time">RT</abbr>: <strong>{elapsed_time}</strong>. <abbr title="Theme Render Time">TT</abbr>: <strong>{theme_time}</strong>.<?php endif; ?> <?php echo $this->lang->sline('powered_by_url', KPlatform::LABEL, KPlatform::SITE_URL) ?></p>

<?php
if ($this->core->has_menu('footer-menu')) {
	echo $this->menus->build_menu(array(
		'location' => 'footer-menu',
		'menu_attr' => array('class' => 'nav navbar-nav navbar-right'),
		'container' => false,
	));
}
?>
	</div>
</footer>
