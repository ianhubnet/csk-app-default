<?php defined('BASEPATH') OR die; ?><ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="nav-item"><a href="#sysinfo" class="nav-link active" aria-controls="sysinfo" role="tab" data-bs-toggle="tab"><?php _e('settings_sysinfo'); ?></a></li>
	<li role="presentation" class="nav-item"><a href="#phpset" class="nav-link" aria-controls="phpset" role="tab" data-bs-toggle="tab"><?php _e('sysinfo_php_settings'); ?></a></li>
	<?php if (isset($phpinfo)): ?><li role="presentation" class="nav-item"><a href="#phpinfo" class="nav-link" aria-controls="phpinfo" role="tab" data-bs-toggle="tab"><?php _e('sysinfo_php_info'); ?></a></li><?php endif; ?>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="card card-sm border-top-0 tab-pane active" id="sysinfo">
		<div class="card-body table-responsive">
			<table class="table table-sm table-hover table-striped mb-0">
				<?php foreach ($info as $i_key => $i_val): ?>
					<tr><th><?php _e('sysinfo_'.$i_key); ?></th><td><?php echo $i_val; ?></td></tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div><!--/.tab-pane-->
	<div role="tabpanel" class="card card-sm border-top-0 tab-pane" id="phpset">
		<div class="card-body table-responsive">
			<table class="table table-sm table-hover table-striped mb-0">
				<?php foreach ($php as $p_key => $p_val): ?>
					<tr>
						<th><?php _e('sysinfo_'.$p_key); ?></th>
						<td><?php
						switch ($p_val) {
							case '1':
								_e('on');
								break;
							case '0':
								_e('off');
								break;
							case null:
							case empty($p_val):
								_e('none');
								break;
							default:
								echo $p_val;
								break;
						}
						?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div><!--/.tab-pane-->
	<?php if (isset($phpinfo)): ?>
	<div role="tabpanel" class="card border-top-0 tab-pane" id="phpinfo">
		<?php echo $phpinfo; ?></table>
	</div><!--/.tab-pane-->
	<?php endif; ?>
</div>
