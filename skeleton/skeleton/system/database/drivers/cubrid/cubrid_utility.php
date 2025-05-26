<?php
defined('BASEPATH') OR die;

/**
 * CUBRID Utility Class
 *
 * @category    Database
 * @author      Esen Sagynov
 * @link        https://codeigniter.com/userguide3/database/
 */
class CI_DB_cubrid_utility extends CI_DB_utility {

	/**
	 * List databases
	 *
	 * @return  array
	 */
	public function list_databases()
	{
		if (isset($this->db->data_cache['db_names']))
		{
			return $this->db->data_cache['db_names'];
		}

		return $this->db->data_cache['db_names'] = cubrid_list_dbs($this->db->conn_id);
	}

	// --------------------------------------------------------------------

	/**
	 * CUBRID Export
	 *
	 * @param   array   Preferences
	 * @return  mixed
	 */
	protected function _backup($params = array())
	{
		// No SQL based support in CUBRID as of version 8.4.0. Database or
		// table backup can be performed using CUBRID Manager
		// database administration tool.
		return $this->db->display_error('db_unsupported_feature');
	}
}
