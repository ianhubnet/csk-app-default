<div class="row row-cols-1 pb-5 text-center">
	<div class="col">
		<div class="d-flex flex-column justify-content-center gap-2 small text-muted">
			<p class="mb-0">
				<?= line('front_home_p2') ?><code class="ms-1">application\views\front\index.php</code>
			</p>
			<p class="mb-0">
				<?= line('front_home_p3') ?><code class="ms-1">application\controllers\Front.php</code>
			</p>
			<p class="mb-0">
				<?= sline('front_home_p4', 'platform_name', Platform::WIKI_URL) ?>
			</p>
			<p class="mb-0">
				<?= sline('front_home_footer', '{elapsed_time}', CI_VERSION, CSK_VERSION) ?>
			</p>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
