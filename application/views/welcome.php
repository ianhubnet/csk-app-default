<?= $this->hub->theme->widget('welcome') ?>

<div class="row py-5 text-center">
	<div class="col">
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
