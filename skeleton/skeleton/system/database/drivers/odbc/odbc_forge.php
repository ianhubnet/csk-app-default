<?php
defined('BASEPATH') OR die;

/**
 * ODBC Forge Class
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/database/
 */
class CI_DB_odbc_forge extends CI_DB_forge {

	/**
	 * CREATE TABLE IF statement
	 *
	 * @var string
	 */
	protected $_create_table_if = false;

	/**
	 * DROP TABLE IF statement
	 *
	 * @var string
	 */
	protected $_drop_table_if   = false;

	/**
	 * UNSIGNED support
	 *
	 * @var bool|array
	 */
	protected $_unsigned        = false;

	// --------------------------------------------------------------------

	/**
	 * Field attribute AUTO_INCREMENT
	 *
	 * @param   array   &$attributes
	 * @param   array   &$field
	 * @return  void
	 */
	protected function _attr_auto_increment(&$attributes, &$field)
	{
		// Not supported (in most databases at least)
	}

}
