<?php defined('BASEPATH') OR die; ?>
<div class="row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3 dropZone" id="list" data-upload="<?php echo admin_url('ajax/upload') ?>">
	<?php if (empty($files)): ?>
	<p class="dz-message"><?php _e('drop_to_upload') ?></p>
	<?php else: foreach ($files as $file): ?>
	<div class="col">
		<div class="card card-file border-top-0" id="<?php echo $file->id; ?>" data-id="<?php echo $file->id; ?>">
			<div class="card-body">
				<?php if ($file->is_image): ?>
				<label class="file-inner" data-src="<?php echo isset($file->thumbnail) ? $file->thumbnail : $file->url ?>" style="background-image: url('<?php echo blank_img_src() ?>')" data-text="<?php echo $file->name ?>">
					<input type="checkbox" class="check-this position-absolute top-0 start-0" autocomplete="off">
				</label>
				<?php else: ?>
				<label class="file-inner" data-text="<?php echo $file->name ?>">
					<i class="fa fa-file-<?php echo $file->is_video ? 'video' : $file->file_ext ?>-o"></i>
					<input type="checkbox" class="check-this position-absolute top-0 start-0" autocomplete="off">
				</label>
				<?php endif; ?>
				<div class="d-grid">
					<?php echo admin_anchor('media?item='.$file->id, line('details'), 'class="btn btn-primary btn-sm" tabindex="-1"'); ?>
				</div>
			</div><!--/.card-body-->
		</div><!--/.card-->
	</div><!--/.column-->
	<?php endforeach; endif; ?>
</div><!--/.dropzone-->
<?php if (isset($pagination)): ?>
	<div class="row">
		<div class="col"><?php echo $pagination ?></div><!--/.col--->
	</div><!--/.row-->
<?php endif ?>

<?php if ( ! empty($item)): ?>
<div class="modal modal-land fade" id="media-modal" role="dialog" aria-hidden="false" aria-labelledby="modal-title" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h3 class="modal-title" id="modal-title"><?php _e('details'); ?></h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('close') ?>"></button>
			</div><!--/.modal-header-->
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-7">
						<div class="attachment-preview">
						<?php
						if ($item->is_pdf OR $item->is_txt)
						{
							echo html_tag('object', array(
								'data'   => $item->url,
								'width'  => '100%',
								'height' => '350px',
							), sline('cannot_display_download', $item->url));
						}
						elseif ($item->is_image)
						{
							echo html_tag('img', array(
								'src'         => blank_img_src(),
								'data-src'    => $item->url,
								'loading'     => 'lazy',
								'alt'         => isset($item->image_alt) ? $item->image_alt : $item->name,
								'data-action' => 'zoom',
							));
						}
						elseif ($item->is_video)
						{
							echo html_tag('video', array(
								'width'    => '100%',
								'height'   => '250px',
								'controls' => '',
							), html_tag('source', array(
								'src'      => blank_img_src(),
								'data-src' => $item->url,
								'loading'  => 'lazy',
								'type'     => $item->content['mime_type'],
							)));
						}
						elseif ($item->is_pptx)
						{
							echo html_tag('iframe', array(
								'src'         => 'https://view.officeapps.live.com/op/embed.aspx?src='.esc_url($item->url),
								'width'       => '100%',
								'height'      => '375px',
								'frameborder' => 0,
							));
						}
						else
						{
							echo sline('cannot_display_download', $file->url);
						}
						?>
						</div><!--/.attachment-preview-->
					</div><!--/.col-md-7-->
					<div class="col-sm-12 col-md-5">
						<span class="txof fw-bold"><?php _e('type'); ?></span>: <span><?php echo $item->content['mime_type']; ?></span><br />
						<span class="txof fw-bold"><?php _e('date_created'); ?></span>: <span><?php echo date_formatter($item->created_at, 'date'); ?></span><br />
						<span class="txof fw-bold"><?php _e('size'); ?></span>: <span><?php echo byte_format($item->content['filesize'] * 1024); ?></span><br />
						<?php if ($item->is_image): ?>
						<span class="txof fw-bold"><?php _e('dimensions'); ?></span>: <span dir="ltr"><?php echo $item->content['width']; ?> x <?php echo $item->content['height']; ?></span>
						<?php endif; ?>
						<hr />
						<?php echo form_open('admin/media/update/'.$item->id, 'role="form" id="media-update" class="row"') ?>
							<div class="mb-3">
								<label for="title"><?php _e('name'); ?></label>
								<input class="form-control" type="text" name="name" id="name" value="<?php echo $item->name; ?>" placeholder="<?php _e('title'); ?>">
							</div>
							<div class="mb-3">
								<?php
								echo form_label($this->lang->line('description'), 'description'),
								form_textarea(array(
									'id' => 'description',
									'name' => 'description',
									'rows'        => 2,
									'class'       => 'form-control form-control-sm no-resize',
									'placeholder' => $this->lang->line('description'),
									'value'       => html_unescape($item->description)
								));
								?>
							</div>

							<div class="mb-3">
								<?php

								echo form_label($this->lang->line('url'), 'url'),
								form_input(array(
									'value' => $item->url,
									'class' => 'form-control form-control-sm text-small',
									'readonly' => 'readonly',
									'onclick' => 'this.select()'
								));

								?>
							</div>

							<?php if ($item->is_image && isset($item->content['sizes'])): $sizes = $item->content['sizes']; ?>
							<div class="d-flex flex-wrap gap-1 mb-3">
								<?php foreach ($sizes as $s): ?>
									<button type="button" class="btn btn-secondary btn-xs" onclick="csk.ui.clipboard(this)" data-text="<?php echo $this->config->uploads_url($s['file']) ?>" tabindex="-1"><?php echo sprintf('%sx%s', $s['width'], $s['height']); ?></button>
								<?php endforeach; ?>
							</div>
							<?php unset($sizes); endif; ?>

							<div class="d-grid">
								<button type="submit" class="btn btn-sm btn-primary"><?php _e('save_changes'); ?></button>
							</div>
						<?php echo form_close(); ?>
					</div><!--/.col-md-5-->
				</div><!--/.row-->
			</div><!--/.modal-body-->
		</div><!--/.modal-content-->
	</div><!--/.modal-dialog-->
</div><!--/.modal-->
<?php endif; ?>

<script type="text/x-handlebars-template" id="attachment-template">
<div class="col">
	<div class="card card-file border-top-0" id="{{id}}" data-id="{{id}}">
		<div class="card-body">
			{{#if is_image}}
			<label class="file-inner" data-text="{{name}}" style="background-image:url('{{url}}')">
			{{else}}
			<label class="file-inner" data-text="{{name}}">
				{{#if is_video}}<i class="fa fa-file-video-o"></i>{{else}}<i class="fa fa-file-{{file_ext}}-o"></i>{{/if}}
			{{/if}}
				<input type="checkbox" class="check-this position-absolute top-0 start-0" autocomplete="off">
			</label>
			<div class="d-grid">
				<a href="<?php echo admin_url('media?item={{id}}') ?>" class="btn btn-primary btn-sm" tabindex="-1"><?php _e('details') ?></a>
			</div>
		</div><!--/.card-body-->
	</div><!--/.card-->
</div><!--/.column-->
</script>
