<?php
defined('BASEPATH') OR die;

/**
 * KB_DB_Driver class
 *
 * Small extension to CI_DB_Driver
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Database
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.20
 */
require_once(BASEPATH.'database/DB_driver.php');
abstract class KB_DB_driver extends CI_DB_driver
{
	/**
	 * Update statement
	 *
	 * Generates a platform-specific update string from the supplied data
	 *
	 * @param   string  the table name
	 * @param   array   the update data
	 * @return  string
	 */
	protected function _update($table, $values)
	{
		is_array($table) && $table = implode(', ', $table);

		return parent::_update($table, $values);
	}
}
