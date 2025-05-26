<?php defined('BASEPATH') OR die; ?><div class="row">
	<div class="col-sm">
		<div class="card card-sm">
			<?php if (empty($content)): ?>
			<div class="card-body text-center">
				<?php _e('admin_logs_error_missing') ?>
			</div><!--/.card-body-->
			<?php else: foreach ($content as $row):
				// Ignore line of php declaration.
				if (0 === strpos($row, '<?php'))
					continue;

				// Ignore empty lines.
				$row = trim($row);
				if (empty($row))
					continue;

			?>
			<div class="alert <?php
			if (strpos($row, 'CRITICAL') !== false)
				echo 'alert-critical';
			elseif (strpos($row, 'ERROR') !== false)
				echo 'alert-danger';
			elseif (strpos($row, 'DEBUG') !== false)
				echo 'alert-warning';
			elseif (strpos($row, 'INFO') !== false)
				echo 'alert-info';
			else
				echo 'alert-secondary';
		?> py-1 px-2 mb-0 rounded-0 border-top-0">
				<?php echo $row; ?>
			</div><!--/.alert-->
			<?php endforeach; endif; ?>
		</div><!--/.card-->
	</div><!--/.col-sm-->
</div><!--/.row-->
