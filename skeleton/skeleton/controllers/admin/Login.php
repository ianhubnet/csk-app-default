<?php
defined('BASEPATH') OR die;

/**
 * Dashboard login controller.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.11
 */
class Login extends KB_Controller
{
	/**
	 * Stuff to do before loading our page.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		if ($method === 'index')
		{
			// An already logged in admin?
			if ($this->auth->is_admin())
			{
				$this->theme->set_alert($this->lang->line('error_logged_in'), 'warning');
				redirect_back('admin');
			}

			// Remove all filters applied by themes.
			remove_all_filters();

			// Set default layout and body class.
			$this->theme
				->set_layout('clean')
				->body_class('csk-clean');

			// Load needed assets.
			$this->assets
				->fontawesome()
				->bootstrap()
				->jquery_validate();

			if ($this->core->is_live)
			{
				$this->assets->css('admin.min.css')->js('admin.min.js');
			}
			else
			{
				$this->assets->css('admin.css')->js('admin.js');
			}

		}

		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Dashboard login section.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function index()
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

		// Prepare form validation.
		$this->prep_form('admin.login', '#login', true);

		// Before the form is submitted.
		if ($this->form_validation->run() === false)
		{
			// hidden fields.
			$this->data['hidden'] = array(
				COOK_CSRF => $this->nonce->create('admin-login'),
				'persist' => '1'
			);

			// Add the username field.
			$this->data['username'] = array_merge(
				$this->config->item('username', 'inputs'),
				array('value' => $this->set_value('username'))
			);

			// Add the password field.
			$this->data['password'] = $this->config->item('password', 'inputs');

			// Do we allow remembering users?
			if ($this->config->item('allow_remember'))
			{
				$this->data['remember'] = $this->config->item('remember', 'inputs');
			}

			// Add languages field only if we have languages.
			$this->data['languages'] = null;
			if ($this->i18n->polylang)
			{
				// Array of available languages.
				// $idioms = $this->i18n->list();
				$idioms['default'] = $this->lang->line('language_default');
				$idioms = array_merge($idioms, $this->i18n->list());

				// Prepare language dropdown.
				$this->data['languages'] = array(
					'type'     => 'dropdown',
					'name'     => 'language',
					'id'       => 'language',
					'options'  => $idioms,
					'selected' => $this->post_data('language', 'default'),
				);
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
		elseif ($this->nonce->verify_request('admin-login') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin-login');
		}
		// After the form is submitted.
		else
		{
			// Attempt to login the current user.
			$status = $this->auth->login(
				$this->input->post('username', true),
				$this->input->post('password', true),
				'admin',
				$this->input->post('language', true)
			);

			// Something went wrong?
			if (is_int($status) && $status === 1)
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect('login-2fa?next=admin');
			}
			elseif ($status)
			{
				$this->theme->set_alert($this->auth->message, 'success');
				redirect_next('admin');
			}
			else
			{
				$this->theme->set_alert($this->auth->message, 'error');
				redirect('login');
			}
		}
	}
}
