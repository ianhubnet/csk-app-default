<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_roles Class
 *
 * Core library class to implement a user roles API.
 *
 * The role option is simple, the structure is organized by role name that
 * stores the name in value of the 'name' key. The capabilities are stored
 * as an array in the values of the 'capability' key.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 */
final class Kbcore_roles extends KB_Driver
{
	/**
	 * Array of roles and capabilities.
	 *
	 * @since   2.16
	 * @var     array
	 */
	public $roles;

	/**
	 * Array of role objects.
	 *
	 * @since   2.16
	 * @var     KB_Role
	 */
	public $role_objects = array();

	/**
	 * Arra of role names.
	 *
	 * @since   2.16
	 * @var     array
	 */
	public $role_names = array();

	/**
	 * List of role names.
	 *
	 * @since   2.16
	 * @var     string
	 */
	public $role_key = 'user_roles';

	/**
	 * Whether to use the database for retrieval and storage.
	 *
	 * @since   2.16
	 * @var     bool
	 */
	protected $use_db = true;

	// --------------------------------------------------------------------

	/**
	 * Initiliaze class.
	 *
	 * @param   none
	 */
	public function initialize()
	{
		if ( ! empty($this->roles = $this->get_roles()))
		{
			foreach ($this->roles as $key => $data)
			{
				$role = new KB_Role($data['name'], $data['caps']);
				$this->role_objects[$key] = $role;
				$this->role_names[$key] = $this->ci->lang->line($role->name);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds role name with capabilities to the list.
	 *
	 * Updates the list of roles, if the role doesn't already exist.
	 *
	 * The capabilities are defined in the following format `array(`read` => true);`
	 * To explicitly deny a role a capability you set the value for that
	 * capability to false.
	 *
	 * @param   string  $role   the role's name
	 * @param   string  $name   the role's display name
	 * @param   array   $caps   array of capabilities in the above format.
	 * @return  KB_Role|void    KB_Role object if added.
	 */
	public function add_role($role, $name, $caps = array())
	{
		// Invalid or exists already?
		if (empty($role) OR isset($this->roles[$role]))
		{
			return;
		}

		$this->roles[$role] = array(
			'name' => $name,
			'caps' => $caps,
		);

		// Save to database?
		$this->_save_roles();
		$this->role_objects[$role] = new KB_Role($role, $caps);
		$this->role_names[$role]   = $name;

		return $this->role_objects[$role];
	}

	// --------------------------------------------------------------------

	/**
	 * Removes a role by name.
	 *
	 * @param   string  $role   the role's name
	 */
	public function remove_role($role)
	{
		if (isset($this->role_objects[$role]))
		{
			// Unset stuff.
			unset($this->role_objects[$role], $this->role_names[$role], $this->roles[$role]);

			// Update databse.
			$this->_save_roles();

			// Make sure to update the default role.
			if ($role == $this->ci->config->item('default_role', null, false))
			{
				$this->_parent->options->set_item('default_role', 'regular');
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Add capability to role.
	 *
	 * @param   string  $role   the role's name
	 * @param   string  $cap    the capability's name
	 * @param   bool    $grant  whether to grant capability. Default: true
	 */
	public function add_cap($role, $cap, $grant = true)
	{
		if (isset($this->roles[$role]))
		{
			$this->roles[$role]['caps'][$cap] = $grant;
			$this->_save_roles();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Removes capability from role.
	 *
	 * @param   string  $role   the role's name
	 * @param   string  $cap    the capability's name
	 */
	public function remove_cap($role, $cap)
	{
		if (isset($this->roles[$role]))
		{
			unset($this->roles[$role]['caps'][$cap]);
			$this->_save_roles();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves a role object by name.
	 *
	 * @param   string  $role   the role's name
	 * @return  KB_Role|null
	 */
	public function get_role($role)
	{
		return $this->role_objects[$role] ?? null;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the list of all roles.
	 *
	 * @return  array
	 */
	public function get_roles()
	{
		if ( ! isset($this->roles))
		{
			/**
			 * If the user doesn't have defined roles saved into database,
			 * we make sure to use CI Skeleton default roles.
			 * @since 2.120
			 */
			if (empty($this->roles = $this->ci->config->item('user_roles', null, array())))
			{
				// CI Skeleton default roles.
				foreach ($this->_parent->auth->levels as $key => $level)
				{
					$this->roles[$key] = array('name' => $key, 'caps' => array());
				}

				// Saved them.
				$this->_save_roles();
			}
		}

		return $this->roles;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves a list of role names.
	 *
	 * @return  array   list of role names.
	 */
	public function get_names()
	{
		return $this->role_names;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks if the given role is currently in the list of available roles.
	 *
	 * @param   string  $role   the role's name
	 * @return  bool
	 */
	public function is_role($role)
	{
		return isset($this->role_names[$role]);
	}

	// --------------------------------------------------------------------

	/**
	 * Save roles to database if set to true.
	 */
	protected function _save_roles()
	{
		($this->use_db) && $this->_parent->options->set_item($this->role_key, $this->roles);
	}

}
