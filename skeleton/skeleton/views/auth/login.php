<?php defined('BASEPATH') OR die; ?><div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<h1 class="text-center h4 mb-2"><?php _e('member_login') ?></h1>

		<?php if (isset($locked_message)): ?>
		<div class="card">
			<div class="card-body">
				<p class="alert alert-danger mb-0"><?php echo $locked_message ?></p>
			</div><!--/.card-body-->
		</div><!--/.card-->
		<?php else: ?>
		<div class="card">
			<?php echo form_open('login', 'role="form" id="login" class="card-body"', $hidden) ?>

				<!-- username/email address -->
				<div class="mb-2">
					<?php
					echo print_input($login, array('class' => error_class($login_type, 'form-control form-control-sm'))),
					form_error($login_type);
					?>
				</div>

				<!-- password -->
				<div class="mb-2">
					<?php
					echo print_input($password, array('class' => error_class('password', 'form-control form-control-sm'))),
					form_error('password');
					?>
				</div>

			<?php if (isset($captcha)): if ($this->config->item('use_recaptcha')): ?>
				<!-- google recaptcha -->
				<div class="mb-2 text-center">
					<?php echo $captcha, form_error('g-recaptcha-response'); ?>
				</div>

			<?php else: ?>
				<!-- captcha -->
				<div class="row row-cols-1 row-cols-md-2 mb-2">
					<div class="col" tabindex="-1">
						<?php echo $captcha_image ?>
					</div>
					<div class="col">
						<?php
						echo print_input($captcha, array('class' => error_class('captcha', 'form-control')));
						echo form_error('captcha');
						?>
					</div>
				</div>
			<?php endif; endif; ?>

			<?php if (isset($remember)): ?>
				<!-- remember me -->
				<div class="form-check form-switch mb-2">
					<?php
					echo print_input($remember, array('class' => 'form-check-input', 'role' => 'switch'));
					echo form_label(line('remember_me'), 'remember', 'class="form-check-label"');
					?>
				</div>
			<?php endif; ?>

				<!-- login button -->
				<div class="d-grid d-lg-flex gap-2">
					<button type="submit" class="btn btn-primary btn-sm flex-fill">
						<?php _e('login') ?>
					</button>
					<?php if ($this->config->item('allow_quick_login')): ?>
					<!-- quick-login button -->
						<?php echo anchor(
							'quick-login',
							line('quick_login'),
							'class="btn btn-outline-primary btn-sm" tabindex="-1"') ?>
					<?php endif; ?>
				</div>

			<?php echo form_close() ?><!--/.card-body-->

			<?php if ( ! $this->config->item('site_offline')): ?>
			<div class="card-footer bg-body-secondary border-top-0">
				<div class="d-grid d-lg-flex gap-2">
					<!-- lost password button -->
					<?php echo anchor('lost-password', line('lost_password'), array(
						'role' => 'button',
						'class' => 'btn btn-light btn-sm flex-fill',
						'tabindex' => '-1'
					)) ?>
				<?php if ($this->config->item('allow_registration')): ?>
					<!-- create account button -->
					<?php echo anchor('register', line('create_account'), 'class="btn btn-light btn-sm flex-fill"') ?>
				<?php endif; ?>
				</div>
			</div><!--/.card-footer-->
			<?php endif; ?>
		</div><!--/.card-->

		<?php if ( ! empty($this->oauth->providers)): ?>
		<!-- extra buttons -->
		<div class="mt-2">
			<div class="d-grid d-md-flex gap-2">
			<?php foreach ($this->oauth->providers as $name => $url): ?>
				<a href="<?php echo $url ?>" class="btn btn-light btn-sm flex-fill" tabindex="-1">
					<i class="fab fa-fw fa-<?php echo $name ?>"></i>
				</a>
			<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

	<?php endif; ?>
	</div><!--/.col-->
</div><!--/.row-->
