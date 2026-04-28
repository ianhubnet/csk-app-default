<footer class="footer" role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col d-flex flex-column flex-sm-row gap-1 gap-sm-2 justify-content-center align-items-center">
				<span>&copy; <?= date('Y') ?> <?= anchor('', $site_name) ?> &#10072; <?= line('all_rights_reserved') ?></span>
				<span class="d-none d-sm-inline">&#10072;</span>
				<span><?= sline('powered_by_url', Platform::LABEL, Platform::SITE_URL) ?></span>
				<?= $hub->menus->render('footer', [
					'container' => 'div',
					'container_attrs' => ['class' => 'col'],
					'menu_attrs' => ['class' => 'nav'],
					'item_attrs' => ['class' => 'nav-item'],
					'link_attrs' => ['class' => 'nav-link'],
				]) ?>
			</div>
		</div><!--/.row-->
	</div><!--/.container-->
</footer>
