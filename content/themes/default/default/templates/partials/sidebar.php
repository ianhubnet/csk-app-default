<?php
if ($this->core->has_menu('sidebar-menu')) {
	echo $this->menus->build_menu(array(
		'location' => 'sidebar-menu',
		'menu_attr' => array(
			'class' => 'nav nav-pills nav-stacked nav-sidebar'
		),
		'container' => 'div',
		'container_attr' => array('class' => 'card')
	));
}
?>
<div class="card card-default">
	<div class="card-header bg-body-secondary">
		<h2 class="card-title h4 mb-0"><?php _e('theme_sidebar_heading', 'default'); ?></h2>
	</div>
	<div class="card-body">
		<p class="mb-0"><?php _e('theme_sidebar_content', 'default') ?></p>
	</div>
</div>
