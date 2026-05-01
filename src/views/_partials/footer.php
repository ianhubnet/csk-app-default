<footer class="footer" role="contentinfo">
	<div class="container">
		<div class="row">
			<div class="col d-flex flex-column flex-sm-row gap-1 gap-sm-2 justify-content-center align-items-center">
				<span>Copyright &copy; 2018-<?= date('Y', CI_REQUEST_TIME) ?> <?= anchor('', $site_name) ?></span>
				<span class="d-none d-sm-inline">&#10072;</span>
				<span><?= line('all_rights_reserved') ?></span>
				<span class="d-none d-md-inline">&#10072;</span>
				<span class="d-none d-md-inline"><?= sline('powered_by_url', 'platform_name', Platform::SITE_URL) ?></span>
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
