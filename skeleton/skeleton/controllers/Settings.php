<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Controllers
 * @category 	Settings
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

/**
 * Settings
 *
 * Allows logged in users to update their settings.
 *
 * @since 2.71
 */
class Settings extends User_Controller
{
	/**
	 * Method that don't require logged-in users.
	 * @var array
	 */
	protected $open_methods = array('change_email');

	/**
	 * Page title.
	 * @var string
	 */
	public $page_title;

	/**
	 * Protected demo methods.
	 * @var array
	 */
	protected $demo_protected = array(
		'profile' => 'settings/profile',
		'avatar' => 'settings/avatar',
		'password' => 'settings/password',
		'email' => 'settings/email'
	);

	/**
	 * Class contructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->theme->add_widget('settings/navbar', array('uri' => strtolower($this->router->method)));
	}

	// --------------------------------------------------------------------

	/**
	 * Used to redirect user to profile settings page.
	 *
	 * @return 	void
	 */
	public function index()
	{
		redirect('settings/profile');
	}

	// --------------------------------------------------------------------

	/**
	 * Allows user to update their profile.
	 *
	 * @return void
	 */
	public function profile()
	{
		// Set page title.
		$this->page_title = $this->lang->sline('settings_com', $this->lang->line('profile'));

		$rules = array(
			array(  'field' => 'first_name',
					'label' => $this->lang->line('first_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'),
			array(  'field' => 'last_name',
					'label' => $this->lang->line('last_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[last_name_min]|max_length[last_name_max]'),
		);

		$this->prep_form($rules, '#update-profile');

		if ($this->form_validation->run() === false)
		{
			$this->assets->select2();

			function_exists('country_menu') OR $this->load->helper('country');

			$this->data['first_name'] = array_merge(
				$this->config->item('first_name', 'inputs'),
				array('value' => $this->set_value('first_name', $this->user->first_name))
			);

			$this->data['last_name'] = array_merge(
				$this->config->item('last_name', 'inputs'),
				array('value' => $this->set_value('last_name', $this->user->last_name))
			);

			$this->data['company'] = array_merge(
				$this->config->item('company', 'inputs'),
				array('value' => $this->set_value('company', $this->user->company))
			);

			$this->data['address'] = array_merge(
				$this->config->item('address', 'inputs'),
				array('value' => $this->set_value('address', $this->user->address))
			);

			$this->data['city'] = array_merge(
				$this->config->item('city', 'inputs'),
				array('value' => $this->set_value('city', $this->user->city))
			);

			$this->data['zipcode'] = array_merge(
				$this->config->item('zipcode', 'inputs'),
				array('value' => $this->set_value('zipcode', $this->user->zipcode))
			);

			$this->data['state'] = array_merge(
				$this->config->item('state', 'inputs'),
				array('value' => $this->set_value('state', $this->user->state))
			);

			$this->data['phone'] = array_merge(
				$this->config->item('phone', 'inputs'),
				array('value' => $this->set_value('phone', $this->user->phone))
			);

			function_exists('timezone_list') OR $this->load->helper('date');;
			$this->data['timezone'] = array_merge(
				$this->config->item('timezone', 'inputs'),
				array(
					'class' => error_class('timezone', 'form-select select2'),
					'options' => timezone_list($this->i18n->current('locale')),
					'selected' => $this->user->timezone ?: $this->config->item('time_reference')
				)
			);

			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('update-profile');

			$this->render();
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('update-profile') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('settings/profile');
		}
		else
		{
			// Collect data.
			$post_data = $this->input->post(null, true);

			foreach ($post_data as $key => $val)
			{
				if ($this->user->$key === $val OR in_array($key, $this->_ignored_fields))
				{
					unset($post_data[$key]);
				}
			}

			if (empty($post_data) OR false !== $this->user->update($post_data))
			{
				$this->theme->set_alert($this->lang->line('profile_update_success'), 'success');
			}
			else
			{
				$this->theme->set_alert($this->lang->line('profile_update_error'), 'error');
			}

			redirect('settings/profile');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Allows user to change their password.
	 *
	 * @return 	void
	 */
	public function password()
	{
		// Set page title.
		$this->page_title = $this->lang->sline('settings_com', $this->lang->line('password'));

		// Only new and confirm password are request by default.
		$rules = array(
			// New password field.
			array(	'field' => 'npassword',
					'label' => $this->lang->line('new_password'),
					'rules' => 'trim|min_length[password_min]|max_length[password_max]'),
			// Confirm password field.
			array(	'field' => 'cpassword',
					'label' => $this->lang->line('confirm_password'),
					'rules' => 'trim|min_length[password_min]|max_length[password_max]|matches[npassword]')
		);

		// Only add current password if set.
		if ( ! empty($this->user->password))
		{
			$rules[] = array(
				'field' => 'opassword',
				'label' => $this->lang->line('current_password'),
				'rules' => 'current_password'
			);
		}

		// Prepare form validation.
		$this->prep_form($rules, '#change-password');

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// Prepare form fields.
			$this->data['npassword'] = array_merge(
				$this->config->item('npassword', 'inputs'),
				array('value' => $this->set_value('npassword'))
			);
			$this->data['cpassword'] = array_merge(
				$this->config->item('cpassword', 'inputs'),
				array('value' => $this->set_value('cpassword'))
			);
			$this->data['opassword'] = array_merge(
				$this->config->item('opassword', 'inputs'),
				array('value' => $this->set_value('opassword'))
			);

			// Add CSRF protected.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('change-password');

			// Set page title and render view.
			$this->render();
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('change-password') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('settings/password');
		}
		else
		{
			// Collect post data.
			$post_data = $this->input->post(array('npassword', 'opassword', 'two_factor_auth'), true);

			// Start with new password.
			if (empty($post_data['npassword']) && ! empty($post_data['opassword']))
			{
				$this->theme->set_alert($this->lang->sline('required_field_error', $this->lang->line('new_password')), 'error');
				redirect('settings/password');
			}
			elseif (empty($post_data['opassword']) && ! empty($post_data['npassword']) && ! empty($this->user->password))
			{
				$this->theme->set_alert($this->lang->sline('required_field_error', $this->lang->line('current_password')), 'error');
				redirect('settings/password');
			}
			elseif (empty($post_data['npassword']) && empty($post_data['opassword']))
			{
				unset($post_data['npassword'], $post_data['opassword']);
			}

			// Start with new password.
			if ( ! empty($post_data['npassword']))
			{
				// Fake password change because of the same password
				if ($post_data['npassword'] === $post_data['opassword'])
				{
					$this->theme->set_alert($this->lang->line('password_change_success'), 'success');
				}
				// Unsuccessful password change?
				elseif ( ! $this->user->update('password', $post_data['npassword']))
				{
					$this->theme->set_alert($this->lang->line('password_change_error'), 'error');
				}
				// Successful password change?
				else
				{
					$this->theme->set_alert($this->lang->line('password_change_success'), 'success');

					// TODO: Log the activity.

					// Send email to user.
					$this->core->mail_user(
						$this->user,
						$this->lang->line('mail_password_changed'),
						'view:emails/users/password',
						array(
							'login_url' => $this->config->site_url('login'),
							'ip_address' => ip_address()
						)
					);
				}
			}

			// Now, two-factor authentication.
			$two_factor_auth = ('1' === $post_data['two_factor_auth']);
			if ($two_factor_auth !== $this->user->two_factor_auth)
			{
				if ($this->user->update('two_factor_auth', $two_factor_auth))
				{
					$this->theme->set_alert(
						$this->lang->line('two_factor_'.($two_factor_auth ? 'enable' : 'disable').'_success'),
						'success'
					);
				}
				else
				{
					$this->theme->set_alert(
						$this->lang->line('two_factor_'.($two_factor_auth ? 'enable' : 'disable').'_error'),
						'error'
					);
				}
			}

			redirect('settings/password');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Allows user to change their email address.
	 *
	 * @return 	void
	 */
	public function email()
	{
		// Set page title.
		$this->page_title = $this->lang->sline('settings_com', $this->lang->line('email_address'));

		// Purge old email change requests.
		$this->purge->email_codes();

		$rules = array(
			// New email field.
			array(	'field' => 'nemail',
					'label' => $this->lang->line('new_email_address'),
					'rules' => 'trim|required|valid_email'),
			// Current password field.
			array(	'field' => 'opassword',
					'label' => $this->lang->line('current_password'),
					'rules' => 'required|current_password'),
		);

		/**
		 * If the user provided a different email address then his/hers,
		 * we make sure it is a unique email address.
		 */
		if (null !== $set_email = $this->input->post('nemail'))
		{
			$rules[0]['rules'] .= '|unique_email';
		}

		// Prepare form validation.
		$this->prep_form($rules, "#change-email");

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// Prepare form fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('change-email');
			$this->data['nemail'] = array_merge(
				$this->config->item('nemail', 'inputs'),
				array('value' => $this->set_value('nemail'))
			);
			$this->data['opassword'] = array_merge(
				$this->config->item('opassword', 'inputs'),
				array('value' => $this->set_value('opassword'))
			);

			// Set page title and render view.
			$this->render();
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('change-email') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('settings/email');
		}
		else
		{
			/**
			 * If the user is attempting to use the same email address, we
			 * do like we updated. Otherwise, prepare email change.
			 */
			if (($email = $this->input->post('nemail', true)) === $this->user->email)
			{
				$this->theme->set_alert($this->lang->line('email_change_success'), 'success');
				redirect('settings/email');
			}

			// Successful?
			elseif (false !== $this->users->prep_email_code($this->user, $email))
			{
				$this->theme->set_alert($this->users->message, 'info');
				redirect('settings/email');
			}

			$this->theme->set_alert($this->users->message, 'error');
			redirect('settings/email');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Processes email change code.
	 *
	 * @access 	public
	 * @param 	string 	$code
	 * @return 	void
	 */
	public function change_email($code = null)
	{
		if (empty($code) OR 40 !== strlen($code))
		{
			redirect_back('');
		}

		elseif (false !== $this->users->process_email_code($code))
		{
			$this->theme->set_alert($this->lang->line('email_change_success'), 'success');
		}
		else
		{
			$this->theme->set_alert($this->lang->line('email_change_error'), 'error');
		}

		redirect($this->auth->online() ? 'settings/email' : '');
	}

	// --------------------------------------------------------------------

	/**
	 * Allows user to upload/change their avatar.
	 *
	 * @return 	void
	 */
	public function avatar()
	{
		if ($this->config->item('use_gravatar'))
		{
			redirect('settings/profile');
		}
		// Set page title.
		$this->page_title = $this->lang->sline('settings_com', $this->lang->line('avatar'));

		// Prepare form validation.
		$this->prep_form(array(
			array(	'field' => 'avatar',
					'label' => 'avatar',
					'rules' => 'trim')
		));

		// Before submitting the form.
		if ($this->form_validation->run() === false)
		{
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('update-avatar');

			// Set page title and render view.
			$this->render();
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('update-avatar') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('settings/avatar');
		}
		else
		{
			$this->upload_avatar();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Account settings: Disable / Delete.
	 *
	 * @return 	void
	 */
	public function account()
	{
		// Last 20 login attempts.
		if ( ! empty($logs = $this->user->login_devices))
		{
			function_exists('date_formatter') OR $this->load->helper('date');

			foreach ($logs as &$log)
			{
				$log['ip_address'] = ip_anchor($log['ip_address'], null, 'target="_blank"');
			}

			$this->data['logs'] = $logs;
		}

		$this->render($this->page_title = $this->lang->sline('settings_com', $this->lang->line('account')));
	}

	// --------------------------------------------------------------------
	// Private Methods.
	// --------------------------------------------------------------------

	/**
	 * Handles uploading avatars.
	 *
	 * @return 	void
	 */
	private function upload_avatar()
	{
		// Using gravatar instead? Simply delete uploaded avatar.
		if ('1' === $this->input->post('gravatar'))
		{
			@array_map('unlink', glob($this->config->uploads_path('avatars/'.$this->user->avatar.'*.*')));
			$this->theme->set_alert($this->lang->line('avatar_update_success'), 'success');
			redirect('settings/avatar');
		}

		// We generate the file name based on user's email address.
		$file_name = $this->user->avatar.'.jpg';

		$config['upload_path']   = $this->config->uploads_path('avatars');
		$config['upload_folder'] = 'avatars';
		$config['file_name']     = $file_name;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['overwrite']     = true;
		$config['skip_date']     = true;

		$this->load->library('upload', $config);
		unset($config);

		// Proceed to upload.
		if ( ! $this->upload->do_upload('avatar'))
		{
			log_message('error', $this->upload->display_errors());

			$this->theme->set_alert($this->lang->line('avatar_update_error'), 'error');
			redirect('settings/avatar');
		}

		// Everything went well, proceed.
		$data = $this->upload->data();

		$this->load->library('image_lib');

		$config['image_library']  = 'GD2';
		$config['source_image']   = $data['full_path'];
		$config['maintain_ratio'] = true;

		if ($data['image_width'] > $data['image_height'])
		{
			$config['height'] = 100;
			$config['width'] = ($data['image_width'] * 100) / $data['image_height'];
		}
		else
		{
			$config['width'] = 100;
			$config['height'] = ($data['image_height'] * 100) / $data['image_width'];
		}
		$this->image_lib->initialize($config);

		// Error resizing?
		if ( ! $this->image_lib->resize())
		{
			$this->theme->set_alert($this->lang->line('avatar_update_error'), 'error');
			redirect('settings/avatar');
		}

		// Continue.
		$this->image_lib->clear();
		$_config = $config;
		unset($config);

		$config['image_library']  = 'GD2';
		$config['source_image']   = $data['full_path'];
		$config['maintain_ratio'] = false;
		$config['width']          = 100;
		$config['height']         = 100;

		if ($_config['width'] > $_config['height'])
		{
			$config['x_axis'] = ($_config['width'] - 100) / 2;
		}
		else
		{
			$config['y_axis'] = ($_config['height'] - 100) / 2;
		}

		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->crop())
		{
			log_message('error', $this->upload->display_errors());

			$this->theme->set_alert($this->lang->line('avatar_update_error'), 'error');
			redirect('settings/avatar');
		}

		// Log the activity.
		// log_activity($this->user->id, 'lang:act_settings_user::'.__FUNCTION__);

		$this->theme->set_alert($this->lang->line('avatar_update_success'), 'success');
		redirect('settings/avatar');
	}

}
