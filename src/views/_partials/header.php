<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" role="navigation">
	<div class="container">
		<?= anchor('', $site_name, 'class="navbar-brand notranslate"') ?>

		<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbar-offcanvas" aria-controls="navbar-offcanvas" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="offcanvas offcanvas-end bg-dark" id="navbar-offcanvas">
			<div class="offcanvas-body">
				<?= $hub->menus->render('primary', [
					'menu_attrs' => ['class' => 'navbar-nav justify-content-start flex-grow-1 px-md-3 pe-auto'],
					'item_attrs' => ['class' => 'nav-item'],
					'link_attrs' => ['class' => 'nav-link']
				]) ?>
				<ul class="navbar-nav ms-auto">
				<?php if ($this->hub->auth->previous_id > 0): ?>
					<li class="nav-item">
						<form class="navbar-form navbar-start pl-0 pr-0" action="<?= nonce_url('switch-account', 'switch-'.$this->hub->auth->previous_id) ?>" method="POST">
							<input type="hidden" name="id" value="<?= $hub->auth->previous_id ?>" hidden="hidden" style="display:none">
							<button type="submit" class="nav-link"><span class="fa fa-sign-in"></span></button>
						</form>
					</li>
				<?php endif; ?>
				<?php if (CI_POLYLANG): ?>
					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><?= $hub->lang->current('name_current') ?> <span class="caret"></span></a>
						<div class="dropdown-menu dropdown-menu-end dropdown-menu-scroll">
						<?php foreach ($this->hub->lang->others('current') as $folder => $lang): ?>
							<a href="<?= lang_url($folder) ?>" class="dropdown-item"><?= $lang['name_current'] ?><span class="text-muted float-end ms-2"><?= $lang['name'] ?></span></a>
						<?php endforeach; ?>
						</div>
					</li>
				<?php endif; ?>
				<?php if ($this->user): ?>
				<?php if ($this->user->has_dashboard): ?>
					<li class="nav-item"><?= anchor('admin', line('admin_panel'), 'class="nav-link"') ?></li>
				<?php endif; ?>
					<li class="nav-item dropdown user-menu">
						<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false"><?= $this->user->avatar(24, ['class' => 'rounded']).$this->user->first_name ?></a>
						<div class="dropdown-menu dropdown-menu-end">
							<?php
							// The following is hidden is maintenance mode.
							if ($this->user->has_offline()) {
								echo anchor('account', line('settings'), 'class="dropdown-item"');
								echo '<hr class="dropdown-divider">';
							}

							// Logout button.
							echo anchor('logout', line('logout'), 'class="dropdown-item"');
							?>
						</div>
					</li>
				</ul>
				<?php else: ?>
				</ul>
				<div class="btn-group btn-group-sm d-flex d-md-block mt-3 mt-md-1 ms-md-2">
					<?= anchor('login', line('login'), 'class="btn btn-primary"') ?>
					<?php
					// Registration button.
					if ($this->config->item('allow_registration')) {
						echo anchor('register', line('create_account'), 'class="btn btn-light"');
					}
					?>
				</div>
				<?php endif; ?>
			</div><!--/.offcanvas-body-->
		</div>
	</div>
</nav>
