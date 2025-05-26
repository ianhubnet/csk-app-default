<?php
defined('BASEPATH') OR die;

echo form_open('', 'role="form" id="profile" class="row"', $hidden);
?>
	<div class="col-12 col-lg-6">
		<div class="card">

			<div class="card-header bg-body-secondary border-bottom-0">
				<h2 class="card-title h6 mb-0">
					<i class="fa fa-id-card me-2"></i>
					<?php _e('personal_info') ?>
				</h2><!--/.card-title-->
			</div><!--/.card-header-->

			<div class="card-body">

				<div class="row row-cols-1 row-cols-md-2 g-3">

					<div class="col">
						<?php
						// first name
						echo form_label(line('first_name'), 'first_name', 'class="form-label mb-1"'),
						print_input($first_name),
						form_error('first_name');
						?>
					</div>

					<div class="col">
						<?php
						// last name
						echo form_label(line('last_name'), 'last_name', 'class="form-label mb-1"'),
						print_input($last_name),
						form_error('last_name');
						?>
					</div>

				</div><!--/.row-->

				<div class="row mt-3">

					<div class="col">
						<?php
						// company number
						echo form_label(line('company'), 'company', 'class="form-label mb-1"'),
						print_input($company),
						form_error('company');
						?>
					</div>

				</div><!--/.row-->

				<div class="row mt-3">

					<div class="col">
						<?php
						// phone number
						echo form_label(line('phone_num'), 'phone', 'class="form-label mb-1"'),
						print_input($phone),
						form_error('phone');
						?>
					</div>

				</div><!--/.row-->

				<div class="row g-2 mt-5">

					<div class="col-12">
						<?php
						// address
						echo form_label(line('address'), 'address', 'class="form-label mb-1"'),
						print_input($address),
						form_error('address');
						?>
					</div>

					<div class="col-sm-6">
						<?php
						// address: city
						echo form_label(line('city'), 'city', 'class="form-label mb-1"'),
						print_input($city),
						form_error('city');
						?>
					</div>

					<div class="col-sm-6">
						<?php
						// address: zipcode
						echo form_label(line('zip_code'), 'zipcode', 'class="form-label mb-1"'),
						print_input($zipcode),
						form_error('zipcode');
						?>
					</div>

				</div><!--/.row-->

				<div class="row g-2 mt-3">

					<div class="col-sm-6">
						<?php
						// address: state
						echo form_label(line('state'), 'state', 'class="form-label mb-1"'),
						print_input($state),
						form_error('state');
						?>
					</div>

					<div class="col-sm-6">
						<?php
						// address: country
						echo form_label(line('country'), 'country', 'class="form-label mb-1"'),
						country_menu($this->user->country, error_class('country', 'form-select select2'), 'country'),
						form_error('country');
						?>
					</div>

				</div><!--/.row-->

			</div><!--/.card-body-->
		</div>
	</div><!--/.col[personal info] -->

	<div class="col-12 col-lg-6">
		<div class="card">

			<div class="card-header bg-body-secondary border-bottom-0">
				<h2 class="card-title h6 mb-0">
					<i class="fa fa-cog me-2"></i>
					<?php _e('settings') ?>
				</h2><!--/.card-title-->
			</div><!--/.card-header-->

			<div class="card-body">

				<div class="row">
					<label for="language" class="col-sm-3 col-form-label"><?php _e('language') ?></label>
					<div class="col-sm-9">
						<?php
						echo print_input($language),
						form_error('language');
						?>
					</div><!--/.col-sm-9-->
				</div>

				<div class="row mt-3">
					<?php echo form_label(line('timezone'), 'timezone', 'class="col-sm-3 col-form-label"') ?>
					<div class="col-sm-9">
						<?php
						echo print_input($timezone),
						form_error('timezone');
						?>
					</div><!--/.col-sm-9-->
				</div>

				<div class="row mt-3">
					<?php echo form_label(line('email_address'), 'email', 'class="col-sm-3 col-form-label"') ?>
					<div class="col-sm-9">
						<?php
						echo print_input($email),
						form_error('email', null, null, line('new_email_address_notice'));
						?>
					</div><!--/.col-sm-9-->
				</div>

				<div class="row mt-3">
					<?php echo form_label(line('password'), 'password', 'class="col-sm-3 col-form-label"') ?>
					<div class="col-sm-9">
						<?php
						echo print_input($password),
						form_error('password');
						?>
					</div><!--/.col-sm-9-->
				</div>

				<div class="row justify-content-end mt-4">

					<div class="col-sm-9">
						<div class="form-check">
							<?php
							// two-factor authentication
							echo form_checkbox(
								'two_factor_auth',
								'1',
								$this->user->two_factor_auth,
								'id="two_factor_auth" class="form-check-input"'
							),

							form_label(line('two_factor_auth'), 'two_factor_auth', 'class="form-check-label ms-1"'),
							line('two_factor_auth_tip', null, '<p class="mb-0"><small>', '</small></p>');
							?>
						</div><!--/.form-check-->
					</div><!--/.col-->

				</div><!--/.row-->

				<div class="d-grid mt-4">
					<button type="submit" class="btn btn-primary"><?php _e('save_changes') ?></button>
				</div>

			</div><!--/.card-body-->

			<div class="card-footer bg-body-secondary border-top-0 p-1">
				<div class="alert alert-warning mb-0 p-3">
					<h4 class="alert-heading h6 mb-1"><?php _e('important_notes') ?></h4>
					<ul class="text-small m-0 pt-0 pb-0 ps-3 pe-3">
						<?php
						echo line('account_disable_notice', null, '<li>', '</li>'),
						line('account_delete_notice', null, '<li class="text-danger">', '</li>');
						?>
					</ul>
				</div>
			</div>
		</div>
	</div><!--/col[personal info] -->
<?php
echo form_close();
