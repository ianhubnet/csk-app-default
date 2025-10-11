<div class="row row-cols-1 py-5 text-center">
	<div class="col">
		<h1 class="display-5 fw-bold text-primary mb-0"><?= line('welcome_h1') ?></h1>
		<p class="lead text-muted mt-3 mb-0">
			<?= line('welcome_p1') ?>
		</p>

		<div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
			<a href="<?= Platform::WIKI_URL ?>" target="_blank" rel="noopener" class="btn btn-primary">
				<i class="fa fa-fw fa-book me-2"></i><?= line('documentation') ?>
			</a>
			<?php
			if (!$this->user) {
				echo anchor('login', fa_icon('sign-in-alt me-2', line('login')), [
					'class' => 'btn btn-outline-secondary'
				]);
			} elseif ($this->user->dashboard) {
				echo anchor('admin', fa_icon('dashboard me-2', line('dashboard')), [
					'class' => 'btn btn-outline-secondary'
				]);
			}
			?>
		</div>
	</div><!--/.col-->

	<div class="col mt-5">
		<div class="d-flex flex-column justify-content-center gap-2 small text-muted">
			<p class="mb-0">
				<?= line('welcome_p2') ?><code class="ms-1">application\views\welcome.php</code>
			</p>
			<p class="mb-0">
				<?= line('welcome_p3') ?><code class="ms-1">application\controllers\Welcome.php</code>
			</p>
			<p class="mb-0">
				<?= sline('welcome_p4', Platform::WIKI_URL) ?>
			</p>
			<p class="mb-0">
				<?= sline('welcome_footer', '{elapsed_time}', CI_VERSION, CSK_VERSION) ?>
			</p>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
