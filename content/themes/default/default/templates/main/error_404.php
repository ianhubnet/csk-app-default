<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col mt-4">
		<div class="page-404">
			<div class="outer">
				<div class="middle">
					<div class="inner">
						<div class="inner-circle"><i class="fa fa-home"></i><span>404</span></div>
						<span class="inner-status"><?php echo $heading ?></span>
						<span class="inner-detail"><?php echo $message; ?></span>
						<div class="d-grid d-md-flex gap-3 justify-content-center mt-3">
							<?php
							echo anchor('', line('return_home'), 'class="btn btn-primary"');
							echo anchor('contact', line('contact_us'), 'class="btn btn-light"');
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!--/.col-->
</div><!--/.row-->
