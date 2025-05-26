<?php
defined('BASEPATH') OR die;

/**
 * Users_model Class
 *
 * Handles all operations done with and to 'users' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Users_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Table's primary key.
	 * @var bool
	 */
	protected $primary_key = 'guid';

	/**
	 * Array of callbacks to user before get.
	 * @var array
	 */
	protected $before_get = array('join_entity');

	/**
	 * Array of callbacks to use after get.
	 * @var array
	 */
	protected $after_get = array('prepare_numeric', 'prepare_object');

	// --------------------------------------------------------------------

	/**
	 * Called before the user is retrieved from database.
	 *
	 * @return 	void
	 */
	protected function join_entity()
	{
		$this->db->join('entities', 'entities.id = users.guid');
	}

	// --------------------------------------------------------------------

	/**
	 * Makes sure $row is an instance of KB_User
	 *
	 * @uses 	KB_User
	 * @param 	object 	$row
	 * @return 	KB_User
	 */
	public function prepare_object($row)
	{
		return ($row && ! ($row instanceof KB_User)) ? new KB_User($row) : $row;
	}

}
