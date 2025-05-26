<?php
defined('BASEPATH') OR die;

/**
 * Auth Module - Auth Controllers
 *
 * This module allow users to exists on the website. It handles users
 * registration, activation, authentication and password management.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Modules\Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.0
 */
class Auth extends Public_Controller
{
	/**
	 * __construct
	 *
	 * Simply call parent's constructor and allow access to logout method
	 * only for already logged-in users.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		// Block direct access to '/auth/'.
		$this->_block('auth');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to do before any method is called.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		// Logout feature (logout method no longer needed).
		if ($method === 'logout')
		{
			$this->auth->online() && $this->auth->logout();
			redirect('');
		}
		// Prevent access to all other method when the user is logged in.
		elseif ($this->auth->online())
		{
			$this->theme->set_alert($this->lang->line('error_logged_in'), 'error');
			redirect('');
		}
		elseif ($method !== 'login' && $this->auth->is_locked())
		{
			redirect('login');
		}
		else
		{
			// Nothing but 'login(/verify)' is accessible in demo mode.
			($method !== 'login' && $method !== 'verify') && $this->core->redirect_demo();

			/**
			 * We force using the *auth* layout unless the theme
			 * decides to use something else.
			 *
			 * @since 	2.16
			 */
			$this->theme->set_layout(apply_filters('theme_layout', 'auth'));

			return parent::_remap($method, $params);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method kept as a backup only, it does absolutely nothing.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function index($provider = null)
	{
		/**
		 * We redirect the user to homepage if:
		 * 	1. No oauth method given.
		 * 	2. Invalid provider given.
		 * 	3. Missing provider's actual login.
		 */
		if (empty($provider = strtolower($provider)))
		{
			redirect('');
		}
		// CSRF protection layer.
		elseif ( ! $this->oauth->check_state($provider, $this->input->get('state')))
		{
			$this->theme->set_alert($this->oauth->message, 'error');
			redirect('');
		}
		// Case of Twitter?
		elseif ($provider === 'twitter')
		{
			$args = array(
				$this->input->get('oauth_token', true),
				$this->input->get('oauth_verifier', true)
			);
		}
		/**
		 * Other providers share same thing.
		 * Google, Facebook, LinkedIn, Github, Discord
		 */
		else
		{
			$args[] = $this->input->get('code', true);
		}

		// Empty args.
		if (empty($args = array_clean($args)))
		{
			redirect('');
		}
		// Failed to authenticate?
		elseif ( ! call_user_func_array(array($this->oauth, $provider), $args))
		{
			$this->theme->set_alert($this->oauth->message, 'error');
			redirect('login');
		}
		else
		{
			$this->theme->set_alert($this->oauth->message, 'success');
			redirect('');
		}
	}

	// --------------------------------------------------------------------
	// Account management methods.
	// --------------------------------------------------------------------

	/**
	 * register
	 *
	 * Method for users registration on the site.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function register()
	{
		// Are registrations allowed?
		if ( ! $this->config->item('allow_registration'))
		{
			redirect('');
		}

		// Prepare form validation.
		$this->prep_form('auth.register', '#register', true);

		// Before form processing.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'] = array(
				COOK_CSRF => $this->nonce->create('auth-register'),
				'persist' => '1'
			);

			// Prepare form fields.
			$this->data['first_name'] = array_merge(
				$this->config->item('first_name', 'inputs'),
				array('value' => $this->set_value('first_name'))
			);
			$this->data['last_name'] = array_merge(
				$this->config->item('last_name', 'inputs'),
				array('value' => $this->set_value('last_name'))
			);
			$this->data['email'] = array_merge(
				$this->config->item('email', 'inputs'),
				array('value' => $this->set_value('email'))
			);
			$this->data['username'] = array_merge(
				$this->config->item('username', 'inputs'),
				array('value' => $this->set_value('username'))
			);
			$this->data['password'] = array_merge(
				$this->config->item('password', 'inputs'),
				array('value' => $this->set_value('password'))
			);
			$this->data['cpassword'] = array_merge(
				$this->config->item('cpassword', 'inputs'),
				array('value' => $this->set_value('cpassword'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('register'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-register') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('register');
		}
		// After the form is processed.
		else
		{
			// Attempt to register the user.
			$guid = $this->auth->register($this->input->post(array(
				'first_name',
				'last_name',
				'email',
				'username',
				'password'
			), true));

			if (false !== $guid)
			{
				$this->theme->set_alert($this->auth->message, 'success');
			}
			else
			{
				$this->theme->set_alert($this->auth->message, 'error');
			}

			redirect('register');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * activate
	 *
	 * Method for activating a user by the given activation code.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function activate($code = null)
	{
		// No code provided? Safely redirect to homepage.
		if (empty($code) && empty($code = $this->input->get_post('code', true)))
		{
			redirect('');
		}

		// Successfully enabled?
		if (false !== $this->auth->activate_by_code($code))
		{
			$this->theme->set_alert($this->auth->message, 'success');
			redirect('login');
		}

		// Otherwise, simply redirect to homepage.
		$this->theme->set_alert($this->auth->message, 'error');
		redirect('');
	}

	// --------------------------------------------------------------------

	/**
	 * resend
	 *
	 * Method for resend account activation links.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function resend()
	{
		// Are registrations allowed?
		if ( ! $this->config->item('allow_registration'))
		{
			redirect('');
		}

		// Prepare form validation.
		$this->prep_form('auth.resend', '#resend-link', true);

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('auth-resend');

			// Prepare form fields.
			$this->data['identity'] = array_merge(
				$this->config->item('identity', 'inputs'),
				array('value' => $this->set_value('identity'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('resend_activation_link'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-resend') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('resend-link');
		}
		// After form processing.
		else
		{
			// Attempt to resend activation link.
			if (false !== $this->auth->resend_link($this->input->post('identity', true)))
				$this->theme->set_alert($this->auth->message, 'success');
			else
				$this->theme->set_alert($this->auth->message, 'error');

			// Redirect back to the same page.
			redirect('resend-link');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * restore
	 *
	 * Method for restoring a previously soft-deleted account.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function restore()
	{
		// Prepare form validation.
		$this->prep_form('auth.restore', '#restore-account', true);

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('auth-restore');

			// Prepare form fields.
			$this->data['identity'] = array_merge(
				$this->config->item('identity', 'inputs'),
				array('value' => $this->set_value('identity'))
			);
			$this->data['password'] = $this->config->item('password', 'inputs');

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('restore_account'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-restore') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('restore-account');
		}
		// After form processing.
		else
		{
			// Attempt to restore the account.
			$status = $this->auth->restore_account(
				$this->input->post('identity', true),
				$this->input->post('password', true)
			);

			// The redirection depends on the restore status.
			if (false !== $status)
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect('login');
			}

			$this->theme->set_alert($this->auth->message, 'error');
			redirect('restore-account');
		}
	}

	// --------------------------------------------------------------------
	// Authentication methods.
	// --------------------------------------------------------------------

	/**
	 * login
	 *
	 * Method for site's members login.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function login()
	{
		// Is the user login-locked?
		if ($this->auth->is_locked())
		{
			$this->data['locked_message'] = $this->auth->message;

			/**
			 * Filter the login page title.
			 * @since   2.0
			 */
			$this->render(apply_filters('login_title', $this->lang->line('login')));

			return;
		}

		// What type of login to use?
		$login_type = $this->config->item('login_type', null, 'both');
		('both' === $login_type) && $login_type = 'identity';

		// Prepare form validation.
		$this->prep_form("auth.login_{$login_type}", '#login', true);

		// Before form processing!
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('auth-login');

			// Prepare form fields.
			$this->data['login_type'] = ($login_type === 'username_or_email' ? 'identity' : $login_type);
			$this->data['login'] = array_merge(
				$this->config->item($login_type, 'inputs'),
				array('value' => $this->set_value($login_type))
			);
			$this->data['password'] = $this->config->item('password', 'inputs');

			// Do we allow remembering users?
			if ($this->config->item('allow_remember'))
			{
				$this->data['remember'] = $this->config->item('remember', 'inputs');
			}

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			/**
			 * Filter the login page title.
			 * @since   2.0
			 */
			$this->render(apply_filters('login_title', $this->lang->line('login')));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-login') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('login');
		}
		// After the form is processed.
		else
		{
			// Attempt to login the current user.
			$status = $this->auth->login(
				$this->input->post($login_type, true),
				$this->input->post('password', true),
				'site'
			);

			// Success? Redirect to homepage
			if (false !== $status)
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect((1 === $status) ? 'login-2fa' : next_url());
			}

			// Error? Redirect  back to login page.
			$this->theme->set_alert($this->auth->message, 'error');
			redirect('login');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * verify
	 *
	 * Method for verifying user's authentication code.
	 *
	 * @param 	none
	 * @return 	void
	 */
	public function verify()
	{
		if (empty($user_id = $this->session->userdata(SESS_USER_2FA))
			OR 0 >= $user_id OR ! ($user = $this->users->get($user_id)))
		{
			redirect('login');
		}

		// Prepare form validation.
		$this->prep_form('auth.login_2fa', '#login', true);

		// Before form processing!
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('auth-2fa');

			// Prepare form fields.
			$this->data['tfa'] = array_merge(
				$this->config->item('tfa', 'inputs'),
				array('value' => $this->set_value('tfa'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			/**
			 * Filter the login page title.
			 * @since   2.0
			 */
			$this->render(apply_filters('login_title', $this->lang->line('login')));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-2fa') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('login-2fa');
		}
		// After the form is processed.
		else
		{
			// Success? Redirect to homepage
			if (true === $this->auth->login_2fa($user_id, $this->input->post('tfa', true), 'site'))
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect_next('');
			}

			// Error? Redirect  back to login page.
			$this->theme->set_alert($this->auth->message, 'error');
			redirect('login-2fa');
		}
	}

	// --------------------------------------------------------------------
	// Password management methods.
	// --------------------------------------------------------------------

	/**
	 * recover
	 *
	 * Method for requesting a password reset link.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function recover()
	{
		// Prepare form validation and rules.
		$this->prep_form('auth.recover', '#recover', true);

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'] = array(
				COOK_CSRF => $this->nonce->create('auth-recover'),
				'persist' => '1'
			);

			// Prepare form fields.
			$this->data['identity'] = array_merge(
				$this->config->item('identity', 'inputs'),
				array('value' => $this->set_value('identity'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('lost_password'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-recover') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('lost-password');
		}
		// After the form is processed.
		else
		{
			// Attempt to prepare password reset.
			if (false !== $this->auth->prep_password_reset($this->input->post('identity', true)))
				$this->theme->set_alert($this->auth->message, 'success');
			else
				$this->theme->set_alert($this->auth->message, 'error');

			// Redirect back to the same page.
			redirect('lost-password');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * reset
	 *
	 * Method for resetting account's password.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   void
	 * @return  void
	 */
	public function reset($code = null)
	{
		// No code provided? Safely redirect to homepage.
		if (empty($code) && empty($code = $this->input->get_post('code', true)))
		{
			redirect('');
		}

		// The code is invalid?
		if (false === ($guid = $this->auth->check_password_code($code)))
		{
			$this->theme->set_alert($this->auth->message, 'error');
			redirect('');
		}

		// Prepare form validation and rules.
		$this->prep_form('auth.reset', '#reset-password', true);

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('auth-reset_'.$code);

			// Prepare form fields.
			$this->data['npassword'] = $this->config->item('npassword', 'inputs');
			$this->data['cpassword'] = $this->config->item('cpassword', 'inputs');

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('reset_password'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-reset_'.$code) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('reset-password/'.$code);
		}
		// After the form is processed.
		else
		{
			// The redirection depends on the reset status.
			if (false !== $this->auth->reset_password($guid, $this->input->post('npassword', true)))
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect('login');
			}

			$this->theme->set_alert($this->auth->message, 'error');
			redirect('reset-password/'.$code);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * link
	 *
	 * Method for requesting quick-login link but also used to quick-login.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.134
	 *
	 * @access 	public
	 * @param 	string 	$code
	 * @return 	void
	 */
	public function link($code = null)
	{
		// Quick Login disabled?
		if ( ! $this->config->item('allow_quick_login'))
		{
			redirect('');
		}
		// Code provided?
		elseif ( ! empty($code))
		{
			// Unable to find user or login?
			if ( ! $this->auth->check_quick_login($code))
			{
				$this->theme->set_alert($this->auth->message, 'error');
				redirect('');
			}

			// Successful login.
			redirect('');
		}

		// Prepare form validation and rules.
		$this->prep_form('auth.recover', '#quick-login', true);

		// Before the form is processed.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'] = array(
				COOK_CSRF => $this->nonce->create('auth-link'),
				'persist' => '1'
			);

			// Prepare form fields.
			$this->data['identity'] = array_merge(
				$this->config->item('identity', 'inputs'),
				array('value' => $this->set_value('identity'))
			);

			// Prepare captcha if enabled.
			empty($captcha = $this->core->captcha()) OR $this->data = array_merge($this->data, $captcha);

			// Set page title and render view.
			$this->render($this->lang->line('quick_login'));
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('auth-link') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('quick-login');
		}
		// After the form is processed.
		else
		{
			// Attempt to prepare password reset.
			if (false !== $this->auth->prep_quick_login($this->input->post('identity', true)))
				$this->theme->set_alert($this->auth->message, 'success');
			else
				$this->theme->set_alert($this->auth->message, 'error');

			// Redirect back to the same page.
			redirect('quick-login');
		}

	}

}
