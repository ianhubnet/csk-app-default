<?php echo $this->theme->partial('navbar'); ?>
<div class="container">
	<?php echo $this->theme->alert(); ?>
	<?php echo $this->theme->content(); ?>
</div>
<?php
echo $this->theme->partial('cookies');
echo $this->theme->partial('footer');
