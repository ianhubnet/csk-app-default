<?php
defined('BASEPATH') OR die;

/**
 * Admin Controller
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.16
 */
class Admin extends Admin_Controller
{
	/**
	 * Class constructor
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Stuff to do before loading our page.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		if ($method === 'profile')
		{
			// load select2
			$this->assets->select2();

			// Page title and icon.
			$this->page_title = $this->lang->line('edit_profile');
			$this->page_icon = 'user-edit';
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * Main admin panel page.
	 * @access  public
	 * @return  void
	 */
	public function index()
	{
		// Set page title and render view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Allow users with access to dashboard to update their profile.
	 * @access 	public
	 * @return 	void
	 */
	public function profile()
	{
		// Prepare form rules.
		$rules = array(
			// First name.
			array(	'field' => 'first_name',
					'label' => $this->lang->line('first_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'),
			// Last name.
			array(	'field' => 'first_name',
					'label' => $this->lang->line('last_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'),
			// Phone number.
			array(	'field' => 'phone',
					'label' => $this->lang->line('phone_num'),
					'rules' => 'trim|valid_phone'),
			// Company.
			array(	'field' => 'company',
					'label' => $this->lang->line('company'),
					'rules' => 'trim|alpha_spaces|max_length[100]'),
			// Address.
			array(	'field' => 'address',
					'label' => $this->lang->line('address'),
					'rules' => 'trim|min_length[5]'),
			// City.
			array(	'field' => 'city',
					'label' => $this->lang->line('city'),
					'rules' => 'trim|min_length[3]'),
			// State.
			array(	'field' => 'state',
					'label' => $this->lang->line('state'),
					'rules' => 'trim|min_length[2]'),
			// Zip code.
			array(	'field' => 'zipcode',
					'label' => $this->lang->line('city'),
					'rules' => 'trim|numeric'),
			// New email addresss.
			array(	'field' => 'email',
					'label' => $this->lang->line('new_email_address'),
					'rules' => 'trim|valid_email|unique_email'),
			// Password.
			array(	'field' => 'password',
					'label' => $this->lang->line('password'),
					'rules' => 'trim|min_length[password_min]|max_length[password_max]'),
		);

		$this->prep_form($rules, '#profile');

		// Form not yet submitted? prepare form fields:
		if ($this->form_validation->run() === false)
		{
			// used for countries menu.
			function_exists('country_menu') OR $this->load->helper('country');

			// CSRF protection nonce.
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('edit-profile');

			// first name
			$this->data['first_name'] = array_merge(
				$this->config->item('first_name', 'inputs'),
				array(
					'class' => error_class('first_name', 'form-control'),
					'value' => $this->set_value('first_name', $this->user->first_name)
				)
			);

			// last name
			$this->data['last_name'] = array_merge(
				$this->config->item('last_name', 'inputs'),
				array(
					'class' => error_class('last_name', 'form-control'),
					'value' => $this->set_value('last_name', $this->user->last_name)
				)
			);

			// company
			$this->data['company'] = array_merge(
				$this->config->item('company', 'inputs'),
				array(
					'class' => error_class('company', 'form-control'),
					'value' => $this->set_value('company', $this->user->company)
				)
			);

			// phone
			$this->data['phone'] = array_merge(
				$this->config->item('phone', 'inputs'),
				array(
					'class' => error_class('phone', 'form-control'),
					'value' => $this->set_value('phone', $this->user->phone)
				)
			);

			// address
			$this->data['address'] = array_merge(
				$this->config->item('address', 'inputs'),
				array(
					'class' => error_class('address', 'form-control'),
					'value' => $this->set_value('address', $this->user->address)
				)
			);

			// address: city
			$this->data['city'] = array_merge(
				$this->config->item('city', 'inputs'),
				array(
					'class' => error_class('city', 'form-control'),
					'value' => $this->set_value('city', $this->user->city)
				)
			);

			// address: zipcode
			$this->data['zipcode'] = array_merge(
				$this->config->item('zipcode', 'inputs'),
				array(
					'class' => error_class('zipcode', 'form-control'),
					'value' => $this->set_value('zipcode', $this->user->zipcode)
				)
			);

			// address: state
			$this->data['state'] = array_merge(
				$this->config->item('state', 'inputs'),
				array(
					'class' => error_class('state', 'form-control'),
					'value' => $this->set_value('state', $this->user->state)
				)
			);

			// language
			$this->data['language'] = array_merge(
				$this->config->item('language', 'inputs'),
				array(
					'class' => error_class('language', 'form-select select2'),
					'options' => $this->i18n->list(),
					'selected' => $this->user->language
				)
			);

			// timezone
			$this->data['timezone'] = array_merge(
				$this->config->item('timezone', 'inputs'),
				array(
					'class' => error_class('timezone', 'form-select select2'),
					'options' => timezone_list($this->i18n->current('locale')),
					'selected' => $this->user->timezone ?: $this->config->item('time_reference')
				)
			);

			// email address
			$this->data['email'] = array_merge(
				$this->config->item('email', 'inputs'),
				array(
					'class' => error_class('email', 'form-control'),
					'value' => $this->set_value('email', $this->user->email)
				)
			);

			// new password
			$this->data['password'] = array_merge(
				$this->config->item('password', 'inputs'),
				array(
					'class' => error_class('password', 'form-control'),
					'value' => $this->set_value('password')
				)
			);

			$this->render();
		}
		// A problem with CSRF
		elseif ($this->nonce->verify_request('edit-profile') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/profile');
		}
		// Submitted already and passed CSRF?
		else
		{
			// collect post data
			$collect = array(
				'first_name',
				'last_name',
				'company',
				'phone',
				'address',
				'city',
				'zipcode',
				'state',
				'country',
				'language',
				'timezone',
				'email',
				'password',
				'two_factor_auth'
			);

			// Let's do some sanity checks:
			foreach ($post_data = $this->input->post($collect, true) as $key => $value)
			{
				// Format two-factor value.
				if ($key === 'two_factor_auth')
				{
					$post_data['two_factor_auth'] = ($value === '1');
				}
				// Remove anything that did not change or is empty.
				elseif ($value === $this->user->$key OR empty($value))
				{
					unset($post_data[$key]);
				}
				// Password check, remove it if it is the same.
				elseif ($key === 'password')
				{
					isset($this->hash) OR $this->load->library('hash');
					if ($this->hash->check_password($value, $this->user->password))
					{
						unset($post_data[$key]);
					}
				}
			}

			// Change language.
			isset($post_data['language']) && $this->i18n->change($post_data['language']);

			// Are we chaning email address?
			if (isset($post_data['email']))
			{
				if ($this->users->prep_email_code($this->user, $post_data['email']))
				{
					$this->theme->set_alert($this->users->message, 'success');
				}
				else
				{
					$this->theme->set_alert($this->users->message, 'error');
				}

				unset($post_data['email']);
			}

			// Are we left with no data or updated successfully?
			if (empty($post_data) OR $this->user->update($post_data))
			{
				$this->theme->set_alert($this->lang->line('profile_update_success'), 'success');
			}
			// Something went wrong?
			else
			{
				$this->theme->set_alert($this->lang->line('profile_update_error'), 'error');
			}

			// Generic redirection.
			redirect('admin/profile');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_profile()
	{
		echo html_tag('button', array(
			'class' => 'btn btn-primary btn-sm',
			'data-submit' => '#profile'
		), $this->lang->line('save_changes')),

		// Info
		html_tag(
			'span',
			'class="d-none d-lg-inline ms-2 lh-lg"',
			fa_icon('info-circle text-primary me-1', $this->lang->line('personal_info_notice'))
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the right side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_right_profile()
	{
		// Disable account
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0)',
			$this->lang->line('account_disable'),
			'warning btn-sm',
			'times-circle',
			array_to_attr(array(
				'role' => 'button',
				'data-form' => esc_url(nonce_url('disable-account', 'account-disable')),
				'data-fields' => 'next:admin/profile',
				'data-confirm' => $this->lang->line('account_disable_confirm')
			))
		),

		// Delete account
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0)',
			$this->lang->line('account_delete'),
			'danger btn-sm ms-1',
			'trash',
			array_to_attr(array(
				'role' => 'button',
				'data-form' => esc_url(nonce_url('delete-account', 'account-delete')),
				'data-fields' => 'next:admin/profile',
				'data-confirm' => $this->lang->line('account_delete_confirm')
			))
		);
	}

}
