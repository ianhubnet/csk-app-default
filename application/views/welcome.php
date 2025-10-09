<div class="row row-cols-1 gap-3">
	<div class="col">
		<div class="p-5 text-center bg-body-tertiary rounded-3">

			<h1 class="text-body-emphasis"><?= line('welcome_h1') ?></h1>
			<p class="col-lg-8 mx-auto fs-5 text-muted">
				<?= line('welcome_p1') ?>
			</p>
			<?php if (!$this->user): ?>
			<div class="d-inline-flex gap-2 mt-4">
				<?php if ($this->config->item('allow_registration')): ?>
				<?= anchor('register', fa_icon('user-plus me-2', line('create_account')), [
					'role' => 'button',
					'class' => 'btn btn-primary btn-lg px-4 rounded-pill'
				]) ?>
				<?php endif ?>
				<?= anchor('login', fa_icon('sign-in me-2', line('login')), [
					'role' => 'button',
					'class' => 'btn btn-outline-secondary btn-lg px-4 rounded-pill'
				]) ?>
			</div>
			<?php endif ?>
		</div>
	</div><!--/.col-->

	<div class="col mt-3 d-flex flex-column gap-3">
		<p class="m-0">
			<?= line('welcome_p2') ?><br><code>application\views\welcome.php</code>
		</p>
		<p class="m-0">
			<?= line('welcome_p3') ?><br><code>application\controllers\Welcome.php</code>
		</p>
		<p class="m-0">
			<?= sline('welcome_p4', 'https://www.codeigniter.com/userguide3/') ?>
		</p>
		<p class="m-0">
			<?= sline('welcome_footer', CI_VERSION, CSK_VERSION) ?>
		</p>
	</div><!--/.col-->
</div><!--/.row-->
