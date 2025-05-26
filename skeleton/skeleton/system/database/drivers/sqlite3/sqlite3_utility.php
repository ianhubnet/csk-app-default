<?php
defined('BASEPATH') OR die;

/**
 * SQLite3 Utility Class
 *
 * @category    Database
 * @author  Andrey Andreev
 * @link    https://codeigniter.com/userguide3/database/
 */
class CI_DB_sqlite3_utility extends CI_DB_utility {

	/**
	 * Export
	 *
	 * @param   array   $params Preferences
	 * @return  mixed
	 */
	protected function _backup($params = array())
	{
		// Not supported
		return $this->db->display_error('db_unsupported_feature');
	}

}
