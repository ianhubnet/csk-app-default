<?php
defined('BASEPATH') OR die;

/**
 * Objects_model Class
 *
 * Handles all operations done with and to 'objects' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Objects_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'objects';

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
		$this->db->join('entities', 'entities.id = objects.guid');
	}

	// --------------------------------------------------------------------

	/**
	 * Makes sure $row is an instance of KB_Object
	 *
	 * @uses 	KB_Object
	 * @param 	object 	$row
	 * @return 	KB_Object
	 */
	public function prepare_object($row)
	{
		// Empty? nothing to do...
		if ( ! $row)
		{
			return $row;
		}

		// Instantiation depends on the subtype.
		switch ($row->subtype)
		{
			case 'file':
				return ($row instanceof KB_File) ? $row : new KB_File($row);

			case 'menu':
				return ($row instanceof KB_Menu) ? $row : new KB_Menu($row);

			case 'menu_item':
				return ($row instanceof KB_Menu_item) ? $row : new KB_Menu_item($row);

			default:
				return ($row instanceof KB_Object) ? $row : new KB_Object($row);
		}
	}

}
