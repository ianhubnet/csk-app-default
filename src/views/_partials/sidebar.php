<?= $hub->menus->render('sidebar', [
	'container' => 'nav',
	'container_attrs' => ['class' => 'navbar'],
	'menu_attrs' => ['class' => 'nav nav-pills nav-underline mb-2'],
	'item_attr' => ['class' => 'nav-item'],
	'link_attr' => ['class' => 'nav-link'],
]) ?>
<div class="card shadow-sm">
	<div class="card-header bg-body-secondary">
		<h2 class="card-title h6 mb-0"><?= line('sidebar_heading') ?></h2>
	</div>
	<div class="card-body">
		<p class="mb-0"><?= line('sidebar_content') ?></p>
	</div>
</div>
<?= $hub->theme->widget('newsletter') ?>
