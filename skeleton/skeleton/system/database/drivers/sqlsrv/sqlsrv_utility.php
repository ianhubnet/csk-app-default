<?php
defined('BASEPATH') OR die;

/**
 * SQLSRV Utility Class
 *
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/database/
 */
class CI_DB_sqlsrv_utility extends CI_DB_utility {

	/**
	 * List databases statement
	 *
	 * @var string
	 */
	protected $_list_databases  = 'EXEC sp_helpdb'; // Can also be: EXEC sp_databases

	/**
	 * OPTIMIZE TABLE statement
	 *
	 * @var string
	 */
	protected $_optimize_table  = 'ALTER INDEX all ON %s REORGANIZE';

	// --------------------------------------------------------------------

	/**
	 * Export
	 *
	 * @param   array   $params Preferences
	 * @return  bool
	 */
	protected function _backup($params = array())
	{
		// Currently unsupported
		return $this->db->display_error('db_unsupported_feature');
	}

}
