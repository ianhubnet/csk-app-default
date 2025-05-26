<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col">
		<div class="px-4 py-3">
			<h1 class="h2 fw-bold text-body-emphasis text-center">
				<?php echo $heading ?>
			</h1>
			<p class="lead text-center mt-2 mb-0"><?php echo $message ?></p>
			<div class="d-grid d-md-flex gap-3 justify-content-center mt-3">
				<?php
					echo anchor('', line('return_home'), 'class="btn btn-primary"');
					echo anchor('contact', line('contact_us'), 'class="btn btn-light"');
					?>
			</div>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
