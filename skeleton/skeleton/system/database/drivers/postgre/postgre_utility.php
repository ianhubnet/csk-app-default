<?php
defined('BASEPATH') OR die;

/**
 * Postgre Utility Class
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/database/
 */
class CI_DB_postgre_utility extends CI_DB_utility {

	/**
	 * List databases statement
	 *
	 * @var string
	 */
	protected $_list_databases  = 'SELECT datname FROM pg_database';

	/**
	 * OPTIMIZE TABLE statement
	 *
	 * @var string
	 */
	protected $_optimize_table  = 'REINDEX TABLE %s';

	// --------------------------------------------------------------------

	/**
	 * Export
	 *
	 * @param   array   $params Preferences
	 * @return  mixed
	 */
	protected function _backup($params = array())
	{
		// Currently unsupported
		return $this->db->display_error('db_unsupported_feature');
	}
}
