<?php
defined('BASEPATH') OR die;

if (isset($locked_message))
{
	echo html_tag('p', 'class="alert alert-danger mb-0"', $locked_message);

	return;
}

// Form open tag.
echo form_open('', 'id="login"', $hidden), validation_errors('<ul class="alert alert-danger">', '</ul>');
?>

<!-- username/email address -->
<div class="mb-2">
	<label for="username" class="form-control-label sr-only"><?php _e('username') ?></label>
	<?php
	// Username form.
	echo print_input($username, array(
		'autofocus' => 'autofocus',
		'class'     => error_class('username', 'form-control')
	)),
	form_error('username');
	?>
</div>

<!-- password -->
<div class="mb-2">
	<label for="password" class="form-control-label sr-only"><?php _e('password') ?></label>
	<?php
	// Password field.
	echo print_input($password, array('class' => error_class('password', 'form-control'))),
	form_error('password');
	?>
</div>

<?php if (null !== $languages): ?>
<!-- language selection -->
<div class="mb-2">
	<label for="language" class="form-control-label sr-only"><?php _e('language') ?></label>
	<?php echo print_input($languages, array('class' => 'form-select form-select-sm')) ?>
</div>
<?php endif; ?>

<?php if (isset($captcha)): ?>
<?php if ($this->config->item('use_recaptcha')): ?>
<!-- google recaptcha -->
<div class="mb-2">
	<?php echo $captcha, form_error('g-recaptcha-response');
else: ?>
<!-- captcha -->
<div class="row mb-3 g-0">
	<div class="col">
		<?php echo $captcha_image ?>
	</div>
	<div class="col">
		<?php
		echo print_input($captcha, array('class' => error_class('captcha', 'form-control'))),
		form_error('captcha');
		?>
	</div>
<?php endif; ?>
</div>
<?php endif; ?>

<!-- buttons -->
<div class="mt-3 mb-0 clearfix">
	<button type="submit" class="btn btn-primary btn-sm float-end">
		<i class="fa fa-fw fa-sign-in me-1"></i>
		<?php _e('login') ?>
	</button>
	<?php
	// Lost password button.
	if ( ! empty($recover_link = apply_filters('login_recover_link', site_url('lost-password')))) {
		$recover_text = apply_filters('login_recover_text', line('lost_password'));
		echo anchor($recover_link, fa_icon('lock me-1', $recover_text), array(
			'role'     =>'button',
			'class'    => 'btn btn-default btn-sm',
			'tabindex' => '-1',
		));
	}
	?>
</div><!--/.mb-2-->
<?php
// Close form
form_close();
