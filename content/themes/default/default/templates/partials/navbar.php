<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" role="navigation">
	<div class="container">
		<?php echo anchor('', $site_name, 'class="navbar-brand"') ?>

		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="navbar-collapse collapse" id="navbar-collapse">
<?php
if ($this->core->has_menu('header-menu')) {
	echo $this->menus->build_menu(array(
		'location'   => 'header-menu',
		'menu_attr'  => array('class' => 'navbar-nav'),
		'item_attr' => array('class' => 'nav-item'),
		'link_attr' => array('class' => 'nav-link'),
		'container'  => false,
	));
}
?>
			<ul class="navbar-nav ms-auto">
			<?php if ( ! empty($this->prev_user_id)): ?>
				<form class="navbar-form navbar-start pl-0 pr-0" action="<?php echo site_url('switch') ?>" method="POST">
					<input type="hidden" name="id" value="<?php echo $this->prev_user_id ?>" hidden="hidden" style="display:none">
					<button type="submit" class="btn btn-link"><span class="fa fa-sign-in"></span></button>
				</form>
			<?php endif; ?>
			<?php if ($this->i18n->polylang): ?>
				<li class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><?php echo line($this->i18n->current('id')); ?> <span class="caret"></span></a>
					<div class="dropdown-menu dropdown-menu-end">
					<?php foreach($this->i18n->others('current') as $folder => $lang): ?>
						<a href="<?php echo lang_url($folder); ?>" class="dropdown-item"><?php echo line($lang['id']); ?><span class="text-muted float-end ms-2"><?php echo $lang['name_en']; ?></span></a>
					<?php endforeach; unset($folder, $lang); ?>
					</div>
				</li>
			<?php endif; ?>
<?php if ($this->auth->online()): ?>
			<?php if ($this->auth->has_dashboard()): ?>
				<li class="nav-item"><?php echo anchor('admin', line('admin_panel'), 'class="nav-link"') ?></li>
			<?php endif; ?>
				<li class="nav-item dropdown user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><?php echo $this->user->first_name; ?><?php echo $this->user->avatar(24, 'class="rounded-circle"'); ?></a>
					<div class="dropdown-menu dropdown-menu-end">
						<?php echo anchor('user/'.$this->user->username, line('view_profile'), 'class="dropdown-item"') ?>
						<?php echo anchor('settings', line('settings'), 'class="dropdown-item"') ?>
						<div class="dropdown-divider"></div>
						<?php echo anchor('logout', line('logout'), 'class="dropdown-item"') ?>
					</div>
				</li>
			</ul>
<?php else: ?>
			</ul>
			<div class="btn-group btn-group-sm ms-2">
				<?php
				// Login button.
				echo anchor('login', line('login'), 'class="btn btn-primary"');

				// Registration button.
				if ($this->config->item('allow_registration'))
				{
					echo anchor('register', line('create_account'), 'class="btn btn-light"');
				}
				?>
			</div>
<?php endif; ?>
		</div>
	</div>
</nav>
