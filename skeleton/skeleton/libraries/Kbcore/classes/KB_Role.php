<?php
defined('BASEPATH') OR die;

/**
 * KB_User Class
 *
 * Core class used to implement the KB_User object.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries\Kbcore
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.30
 */
class KB_Role
{
	/**
	 * The role's identifier.
	 * @var string
	 */
	public $id;

	/**
	 * The role's name.
	 * @var string
	 */
	public $name;

	/**
	 * List of capabilities the role has.
	 * @var array
	 */
	public $caps;

	/**
	 * Class constructor
	 *
	 * The list of capabilities, must have the key as the name of
	 * the capability and the value a boolean of whether it is
	 * granted or not.
	 *
	 * @param   string 	$role 	the role's name
	 * @param   array 	$caps 	the list of capabilities
	 */
	public function __construct($role, array $caps = array())
	{
		$this->id = $role;
		$this->name = 'role_'.$this->id;
		$this->caps = array_map('str2bool', $caps);
	}

	// --------------------------------------------------------------------

	/**
	 * Assign a role a capability
	 *
	 * @param   string  $cap   the capability's name
	 * @param   bool    $grant  whenter to grant capability. Default: true
	 */
	public function add_cap($cap, $grant = true)
	{
		$this->caps[$cap] = ($grant === true);
		get_instance()->roles->add_cap($this->name, $cap, $grant);
	}

	// --------------------------------------------------------------------

	/**
	 * Removes a capability from a role.
	 *
	 * @param   string  $cap   the capability's name
	 */
	public function remove_cap($cap)
	{
		unset($this->caps[$cap]);
		get_instance()->roles->remove_cap($this->name, $cap);
	}

	// --------------------------------------------------------------------

	/**
	 * Determines whenter the role has the given capability.
	 *
	 * @param   string  $cap   the capability's name
	 * @return  bool    true if the role has capability, else false.
	 */
	public function has_cap($cap)
	{
		return empty($this->caps[$cap]) ? false : $this->caps[$cap];
	}

}
