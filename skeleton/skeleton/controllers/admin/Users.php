<?php
defined('BASEPATH') OR die;

/**
 * Users Module - Admin Controller
 *
 * This controller allow administrators to manage users accounts.
 *
 * @package     CodeIgniter
 * @subpackage  SKeleton
 * @category    Modules\Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        https://github.com/bkader
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     1.40
 */
class Users extends Admin_Controller
{
	/**
	 * Access reserved for managers and above.
	 * @since   2.16
	 * @var     integer
	 */
	protected $access_levels = array(
		'index' => KB_LEVEL_MANAGER,
		'add'   => KB_LEVEL_MANAGER,
		'edit'  => KB_LEVEL_MANAGER,
		'mail'  => KB_LEVEL_ADMIN,
	);

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array(
		'_action'  => 'admin/users',
		'add' => 'admin/users/add'
	);

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
		$this->page_title = $this->lang->line('admin_users_manage');
		$this->page_help  = KPlatform::SITE_URL;
		$this->page_icon  = 'users';

		if ($method === 'add')
		{
			$this->assets->jquery_validate();

			$this->page_title = $this->lang->line('admin_users_add');
			$this->page_icon = 'user-plus';
		}
		elseif ($method === 'edit')
		{
			$id = $this->uri->segment(4);
			if (null === $id OR false == ($user = $this->users->get($id)))
			{
				redirect('admin/users');
			}

			$this->assets->jquery_validate();

			$this->page_title = sprintf('%s: %s', $this->lang->line('admin_users_edit'), $user->username);
			$this->page_icon = 'user-circle';
			$this->data['user'] = $user;
		}
		elseif ($method === 'mail')
		{
			$this->assets
				->garlic()
				->select2(true)
				->jquery_validate()
				->summernote(true);

			$this->page_title = $this->lang->line('admin_users_mailer');
			$this->page_icon = 'envelope';
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Display all site's users accounts.
	 *
	 * @author  Kader Bouyakoub
	 * @link    https://github.com/bkader
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function index()
	{
		// Users filter.
		$where = array();

		// Filter by role (subtype).
		if (null !== ($role = $this->input->get('role', true)))
		{
			$where['subtype'] = $role;
		}

		// Account status.
		if (null !== ($status = $this->input->get('status', true)))
		{
			switch ($status) {
				case 'deleted':
					$where['deleted !='] = 0;
					break;
				case 'active':
				case 'enabled':
					$where['enabled'] = 1;
					break;
				case 'inactive':
				case 'disabled':
					$where['enabled'] = 0;
					break;
				case 'banned':
					$where['enabled'] = -1;
					break;
				case 'locked':
					$where['enabled'] = -2;
					break;
			}
		}

		[$limit, $offset] = $this->paginate($this->config->admin_url('users'), $this->users->count($where));

		// Get all users.
		$users = $this->users->get_many($where, null, $limit, $offset);
		$this->data['users'] =& $users;

		// Attempt to translate users.
		foreach ($users as $user)
		{
			$user->translate($this, $this->lang->idiom);
		}

		// Set page title and render view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * add
	 *
	 * Method for adding a new user's account.
	 *
	 * @author  Kader Bouyakoub
	 * @link    https://github.com/bkader
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function add()
	{
		// Prepare form validation and rules.
		$this->prep_form(array(
			array(  'field' => 'first_name',
					'label' => $this->lang->line('first_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'),
			array(  'field' => 'last_name',
					'label' => $this->lang->line('last_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[last_name_min]|max_length[last_name_max]'),
			array(  'field' => 'email',
					'label' => $this->lang->line('email_address'),
					'rules' => 'trim|required|valid_email|unique_email'),
			array(  'field' => 'username',
					'label' => $this->lang->line('username'),
					'rules' => 'trim|required|alpha_numeric|min_length[username_min]|max_length[username_max]|unique_username[true]'),
			array(  'field' => 'password',
					'label' => $this->lang->line('password'),
					'rules' => 'trim|required|min_length[password_min]|max_length[password_max]'),
			array(  'field' => 'cpassword',
					'label' => $this->lang->line('confirm_password'),
					'rules' => 'trim|required|matches[password]'),
		), '#add-user');

		// Before form processing
		if ($this->form_validation->run() === false)
		{
			// Prepare form fields.
			$this->data['first_name'] = array_merge(
				$this->config->item('first_name', 'inputs'),
				array(
					'class' => error_class('first_name', 'form-control'),
					'value' => $this->set_value('first_name')
				)
			);
			$this->data['last_name'] = array_merge(
				$this->config->item('last_name', 'inputs'),
				array(
					'class' => error_class('last_name', 'form-control'),
					'value' => $this->set_value('last_name')
				)
			);
			$this->data['email'] = array_merge(
				$this->config->item('email', 'inputs'),
				array(
					'class' => error_class('email', 'form-control'),
					'value' => $this->set_value('email')
				)
			);
			$this->data['username'] = array_merge(
				$this->config->item('username', 'inputs'),
				array(
					'class' => error_class('username', 'form-control'),
					'value' => $this->set_value('username')
				)
			);
			$this->data['password'] = array_merge(
				$this->config->item('password', 'inputs'),
				array(
					'class' => error_class('password', 'form-control'),
					'value' => $this->set_value('password')
				)
			);
			$this->data['cpassword'] = array_merge(
				$this->config->item('cpassword', 'inputs'),
				array(
					'class' => error_class('cpassword', 'form-control'),
					'value' => $this->set_value('cpassword')
				)
			);
			$this->data['subtype'] = array_merge(
				$this->config->item('role', 'inputs'),
				array(
					'class' => error_class('subtype', 'form-select form-select-sm'),
					'value' => $this->set_value('subtype')
				)
			);

			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('users-new');
			$this->data['hidden']['persist'] = '1';

			$this->render();
		}
		// Failed nonce?
		elseif ($this->nonce->verify_request('users-new') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/users/add');
		}
		// Process form.
		else
		{
			// Collect data
			$data = $this->input->post(array(
				'first_name',
				'last_name',
				'email',
				'username',
				'password',
				'subtype'
			), true);

			$data['enabled'] = ($this->input->post('enabled') === '1') ? 1 : 0;

			if (false !== ($guid = $this->users->create($data)))
			{
				$this->theme->set_alert($this->users->message, 'success');
				redirect('admin/users');
			}

			$this->theme->set_alert($this->users->message, 'error');
			redirect('admin/users/add');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * edit
	 *
	 * Edit an existing user's account.
	 *
	 * @author  Kader Bouyakoub
	 * @link    https://github.com/bkader
	 * @since   1.0
	 *
	 * @access  public
	 * @param   int     $id     The user's ID.
	 * @return  void
	 */
	public function edit($id)
	{
		// Disabled in demo mode!
		$this->core->redirect_demo('admin/users');

		// Get the user from database.
		isset($this->data['user']) OR $this->data['user'] = $user = $this->users->get($id);
		if ( ! $this->data['user'])
		{
			$this->theme->set_alert($this->lang->line('account_missing_error'), 'error');
			redirect($this->agent->referrer());
		}
		elseif ( ! isset($user))
		{
			$user = $this->data['user'];
		}

		// Prepare form validation.
		$rules = array(
			array(  'field' => 'first_name',
					'label' => $this->lang->line('first_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'),
			array(  'field' => 'last_name',
					'label' => $this->lang->line('last_name'),
					'rules' => 'trim|required|alpha_spaces|min_length[last_name_min]|max_length[last_name_max]'),
			array(	'field' => 'password',
					'label' => $this->lang->line('password'),
					'rules' => 'trim|min_length[password_min]|max_length[password_max]'),
			array(	'field' => 'cpassword',
					'label' => $this->lang->line('confirm_password'),
					'rules' => 'trim|required[password]|min_length[password_min]|max_length[password_max]|matches[password]')
		);

		// Using a new email address?
		$email_rules = 'trim|required|valid_email';
		if ($this->input->post('email'))
		{
			if (strtolower($this->input->post('email')) !== strtolower($user->email))
			{
				$email_rules .= '|unique_email';
			}

			$rules[] = array(
				'field' => 'email',
				'label' => $this->lang->line('email_address'),
				'rules' => $email_rules,
			);
		}

		// Using a different username?
		$username_rules = 'trim|required|min_length[username_min]|max_length[username_max]';
		if ($this->input->post('username'))
		{
			if (strtolower($this->input->post('username')) !== strtolower($user->username))
			{
				$username_rules .= '|unique_username';
			}

			$rules[] = array(
				'field' => 'username',
				'label' => $this->lang->line('username'),
				'rules' => $username_rules,
			);
		}

		// Prepare form validation and rules.
		$this->prep_form($rules, '#edit-user');

		/**
		 * The reason behind lines you see below is to allow modules or
		 * themes to add extra fields to users profiles.
		 * As you can see, $_defaults are the fields that will always be
		 * present no matter what.
		 * Right after, we are using $defaults and send it to modules
		 * system so that modules and themes can alter it.
		 * The final result is merged than automatically generated.
		 */

		// Default user fields.

		// Allow modules to add extra fields.
		$defaults = array_merge_unique(
			$_defaults = array('first_name', 'last_name', 'email', 'username'),
			apply_filters('users_fields', $_defaults)
		);

		// Let's now generate our form fields.
		foreach ($defaults as $field)
		{
			/**
			 * We first start by getting the name of the input.
			 * NOTE: If you pass arrays as new fields make sure to
			 * ALWAYS add input names.
			 */
			$name = (is_array($field)) ? $field['name'] : $field;

			/**
			 * Now we store the default value of the field.
			 * If the fields is the $_defaults array, it means it comes
			 * from "users" table. Otherwise, it's a metadata.
			 */
			if (in_array($name, $_defaults))
			{
				$value = $user->$name;
			}
			else
			{
				$meta = $this->db
					->where('guid', $user->id)
					->where('name', $name)
					->get('metadata')
					->row();

				empty($meta) OR $value = from_bool_or_serialize($meta->value);
			}

			// In case of an array, use it as-is.
			if (is_array($field))
			{
				$inputs[$name] = array_merge($field, array(
					'class' => error_class($name, 'form-control'),
					'value' => $this->set_value($name, $value)
				));
			}
			/**
			 * In case a string is passed, we make sure it exists first,
			 * if it does, we add it. Otherwise, we set error.
			 */
			elseif ($item = $this->config->item($name, 'inputs'))
			{
				$inputs[$name] = array_merge($item, array(
					'class' => error_class($name, 'form-control'),
					'value' => $this->set_value($name, $value)
				));
			}
		}

		/**
		 * Fields below are default fields as well, so we don't give
		 * modules or themes the right to alter them.
		 */
		$inputs['password']  = array_merge(
			$this->config->item('password', 'inputs'),
			array('class' => error_class('password', 'form-control'))
		);
		$inputs['cpassword'] = array_merge(
			$this->config->item('cpassword', 'inputs'),
			array('class' => error_class('cpassword', 'form-control'))
		);

		// User role.
		if ($this->user->level >= $user->level)
		{
			$inputs['subtype'] = array_merge(
				$this->config->item('role', 'inputs'),
				array(
					'class' => error_class('subtype', 'form-select form-select-sm'),
					'selected' => $user->subtype
				)
			);

			// Remove roles above the user's level.
			foreach ($inputs['subtype']['options'] as $key => $value)
			{
				if ($this->auth->levels[$key] > $this->user->level)
				{
					unset($inputs['subtype']['options'][$key]);
				}
			}
		}

		$inputs['gender'] = array_merge(
			$this->config->item('gender', 'inputs'),
			array(
				'class' => error_class('subtype', 'form-select form-select-sm'),
				'selected' => $user->gender,
			)
		);

		// Let's now add our generated inputs to view.
		$this->data['inputs'] = $inputs;

		// Before form processing
		if ($this->form_validation->run() === false)
		{
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('edit-user_'.$id);
			// Set page title and render view.
			$this->render();
		}
		// Failed nonce
		elseif ($this->nonce->verify_request('edit-user_'.$id) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/users/edit/'.$id);
		}
		// Process form.
		else
		{
			/**
			 * Here we make sure to remove the confirm password field.
			 * Otherwise it will be used as a metadata
			 */
			unset($inputs['cpassword']);

			// Collect all user details.
			$_fields = array_keys($inputs);
			$user_data = $this->input->post($_fields, true);


			// Format "enabled" and user's "subtype".
			$user_data['enabled'] = ('1' === $this->input->post('enabled', true)) ? 1 : 0;

			/**
			 * After form submit. We make sure to remove fields that have
			 * not been changed: Username, Email address, first name, last name
			 * and user's subtype.
			 */
			foreach ($_fields as $_field)
			{
				if ($user_data[$_field] === $this->data['user']->$_field)
				{
					unset($user_data[$_field]);
				}
			}

			/**
			 * For the password, we make sure to remove it if it's empty
			 * of if it's the same as the old one.
			 */
			if (empty($user_data['password']))
			{
				unset($user_data['password']);
			}
			else
			{
				(isset($this->hash)) OR $this->load->library('hash');
				if ($this->hash->check_password($user_data['password'], $this->data['user']->password))
				{
					unset($user_data['password']);
				}
			}

			// Successful or nothing to update?
			if (empty($user_data) OR true === $this->users->update($id, $user_data))
			{
				$this->theme->set_alert($this->lang->line('admin_users_edit_success'), 'success');

				// Log the activity.
				redirect('admin/users');
			}
			// Something went wrong?
			else
			{
				$this->theme->set_alert($this->lang->line('admin_users_edit_error'), 'error');
				redirect('admin/users/edit/'.$this->data['user']->id);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Users mass mailer.
	 *
	 * @param   none
	 * @return  void
	 */
	public function mail()
	{
		/* Only allowed for admins and above. */
		$this->set_access_level(KB_LEVEL_ADMIN);

		/* Prepare the form. */
		$this->prep_form(array(
			array(  'field' => 'subject',
					'label' => $this->lang->line('subject'),
					'rules' => 'trim|required|min_length[5]|max_length[100]'),
			array(  'field' => 'message',
					'label' => $this->lang->line('message'),
					'rules' => 'trim|required|min_length[100]')
		), '#mail');

		// Build roles list.
		$roles = array('**' => $this->lang->line('admin_users_all_users'));
		foreach ($this->auth->levels as $key => $num)
		{
			$roles[$key] = $this->lang->line('role_'.$key);
		}

		/* Not yet submitted? */
		if ($this->form_validation->run() === false)
		{
			$this->data['hidden']['persist'] = '1';
			$this->data['roles'] = $roles;

			$this->data['subject'] = array(
				'type'        => 'text',
				'name'        => 'subject',
				'value'       => $this->set_value('subject'),
				'class'       => error_class('subject', 'form-control'),
				'placeholder' => $this->lang->line('subject')
			);

			$this->data['message'] = array(
				'type'        => 'textarea',
				'name'        => 'message',
				'value'       => $this->set_value('message', '', false),
				'class'       => error_class('message', 'summernote form-control form-control-sm'),
				'placeholder' => $this->lang->line('message'),
				'data-upload' => esc_url($this->config->admin_url('ajax/upload/emails'))
			);

			$this->render();
		}
		else
		{
			// Disabled in demo mode!
			$this->core->redirect_demo('admin/users/mail');

			// Collect post data.
			$post_data = array(
				'subject'  => $this->input->post('subject', true),
				'message'  => $this->input->post('message'),
				'disabled' => $this->input->post('disabled'),
				'banned'   => $this->input->post('banned'),
				'deleted'  => $this->input->post('deleted'),
				'subtype'  => $this->input->post('subtype', true)
			);

			// Build WHERE clause.
			$where = array(
				'enabled' => array(1),
				'deleted' => 0
			);

			// Send to disabled account?
			if (isset($post_data['disabled']))
			{
				if ('1' === $post_data['disabled'])
				{
					$where['enabled'][] = 0;
				}

				unset($post_data['disabled']);
			}

			// Sending to banned?
			if (isset($post_data['banned']))
			{
				if ($post_data['banned'] === '1')
				{
					$where['enabled'][] = -1;
				}

				unset($post_data['banned']);
			}

			// Sending to deleted?
			if (isset($post_data['deleted']))
			{
				unset($post_data['deleted'], $where['deleted']);
			}

			// Specific subtype
			('**' !== $post_data['subtype']) && $where['subtype'] = $post_data['subtype'];
			unset($post_data['subtype']);

			/* Problem finding users? */
			if (false === ($users = $this->users->get_many($where)))
			{
				$this->theme->set_alert($this->lang->line('message_send_error'), 'error');
				redirect('admin/users/mail');
			}

			foreach ($users as $user)
			{
				$subject = str_replace('{name}', $user->first_name, $post_data['subject']);

				if (true !== $this->core->mail_user($user, $subject, $post_data['message']))
				{
					$this->theme->set_alert($this->lang->line('message_send_error'), 'error');
					redirect('admin/users/mail');
				}
			}

			$this->theme->set_alert($this->lang->line('message_send_success'), 'success');
			redirect('admin/users/mail');
		}
	}

	// --------------------------------------------------------------------
	// POST METHODS
	// --------------------------------------------------------------------

	/**
	 * List of allowed actions.
	 * @var 	array
	 */
	private $_actions = array(
		'ban'     => KB_LEVEL_MANAGER,
		'delete'  => KB_LEVEL_MANAGER,
		'disable' => KB_LEVEL_MANAGER,
		'enable'  => KB_LEVEL_MANAGER,
		'remove'  => KB_LEVEL_ADMIN,
		'restore' => KB_LEVEL_MANAGER,
		'unban'   => KB_LEVEL_MANAGER,
	);

	/**
	 * Handles performaning POST actions.
	 * @access 	public
	 * @param 	string 	$string 	the action to perform.
	 * @return 	void
	 */
	public function _action($action = null)
	{
		// Unknown action.
		if (empty($action) OR ! isset($this->_actions[$action]))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/users');
		}

		// Unauthorized.
		elseif ( ! $this->auth->is_level($this->_actions[$action]))
		{
			$this->theme->set_alert($this->lang->line('permission_error_action'), 'error');
			redirect('admin/users');
		}

		// Security check
		elseif ($this->nonce->verify_request("users-{$action}") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/users');
		}

		// Didn't pass?
		elseif (empty($ids = $this->input->post('id', true)) OR true !== $this->users->$action($ids, true))
		{
			$this->theme->set_alert($this->lang->line("admin_users_{$action}_error"), 'error');
			redirect('admin/users');
		}

		$this->theme->set_alert($this->lang->line("admin_users_{$action}_success"), 'success');
		redirect('admin/users');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (index).
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		// Add user button.
		echo $this->theme->template(
			'button_icon', $this->config->admin_url('users/add'),
			$this->lang->line('admin_users_add'),
			'success btn-sm me-4', 'plus-circle'
		),

		'<div class="btn-group btn-group-sm me-2" role="toolbar">',

		// Activate users
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('enable'),
			'default bulk-action disabled',
			'check-circle text-success',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/enable', 'users-enable')),
				'data-confirm' => $this->lang->line('admin_users_enable_confirm'),
			))
		),

		// Deenable user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('disable'),
			'default bulk-action disabled',
			'times-circle text-danger',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/disable', 'users-disable')),
				'data-confirm' => $this->lang->line('admin_users_disable_confirm'),
			))
		),

		'</div>',
		'<div class="btn-group btn-group-sm me-2" role="toolbar">',

		// Ban user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('ban'),
			'default bulk-action disabled',
			'lock text-danger',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/ban', 'users-ban')),
				'data-confirm' => $this->lang->line('admin_users_ban_confirm'),
			))
		),

		// Unban user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('unban'),
			'default bulk-action disabled',
			'unlock text-success',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/unban', 'users-unban')),
				'data-confirm' => $this->lang->line('admin_users_unban_confirm'),
			))
		),

		'</div>',
		'<div class="btn-group btn-group-sm me-4" role="toolbar">',

		// Delete user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('delete'),
			'default bulk-action disabled',
			'times text-danger',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/delete', 'users-delete')),
				'data-confirm' => $this->lang->line('admin_users_delete_confirm'),
			))
		),

		// Restore user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('restore'),
			'default bulk-action disabled',
			'refresh text-success',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/restore', 'users-restore')),
				'data-confirm' => $this->lang->line('admin_users_restore_confirm'),
			))
		),

		'</div>',

		'<div class="btn-group btn-group-sm" role="toolbar">',

		// Remove user
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('remove'),
			'danger bulk-action disabled',
			'trash',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('users/action/remove', 'users-remove')),
				'data-confirm' => $this->lang->line('admin_users_remove_confirm'),
			))
		);
		if ($this->input->get('role') OR $this->input->get('status'))
		{
			echo $this->back_button('users');
		}

		echo '</div>';

		do_action('in_users_submenu');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (add).
	 *
	 * @return 	void
	 */public function _submenu_add()
	{
		echo $this->back_button('users');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (edit).
	 *
	 * @return 	void
	 */public function _submenu_edit()
	{
		echo $this->back_button('users');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (main).
	 *
	 * @return 	void
	 */public function _submenu_mail()
	{
		echo $this->back_button('users');
	}

}
