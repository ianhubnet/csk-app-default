<?php echo $this->theme->partial('navbar'); ?>
<div class="container wrapper">
	<div class="row">
		<div class="col-12 col-lg-9 mb-3 mb-lg-0">
			<?php echo $this->theme->alert(); ?>
			<?php echo $this->theme->content(); ?>
		</div>
		<div class="col-12 col-lg-3"><?php echo $this->theme->partial('sidebar'); ?></div>
	</div>
</div>
<?php
echo $this->theme->partial('cookies');
echo $this->theme->partial('footer');
