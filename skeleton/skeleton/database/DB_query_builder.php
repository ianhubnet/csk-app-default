<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Database
 * @category 	KB_DB_query_builder
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

require_once(BASEPATH.'database/DB_query_builder.php');

/**
 * KB_DB_query_builder
 *
 * Small extension to CI_DB_query_builder.
 *
 * @since 2.63
 */
class KB_DB_query_builder extends CI_DB_query_builder {

	/**
	 * This method attempts to INSERT into a table and in case of
	 * DUPLICATES, it UPDATE.
	 *
	 * @since 	2.63
	 *
	 * @param 	string 	$table
	 * @param 	array 	$insert
	 * @param 	array 	$update
	 * @param   bool    $return_object
	 * @return 	parent::query
	 */
	public function save($table = '', array $insert = array(), array $update = array(), $return_object = null)
	{
		if (empty($table) OR empty($insert) OR empty($update))
		{
			return false;
		}

		// Add dbprefix if not empty.
		empty($this->dbprefix) OR $table = $this->dbprefix.$table;

		// Start query.
		$sql = 'INSERT INTO '.$table;

		// Add colums.
		$sql .= ' ('.implode(',', array_keys($insert)).') VALUES (';

		// Add placeholders for INSERT.
		$sql .= rtrim(str_repeat('?,', count($insert)), ',').')';

		// ON DUPLICATE part of the query.
		$sql .= ' ON DUPLICATE KEY UPDATE ';

		$operators = array('+', '-', '*', '/');
		foreach ($update as $key => &$val)
		{
			if (str_starts_with_any($val, $operators))
			{
				$sql .= $key.'='.$key.$val.',';
				$val = str_replace(array('+', '-', '*', '/'), '', $val);
				unset($update[$key]);
			}
			else
			{
				$sql .= $key.'=?,';
			}
		}

		return $this->query(rtrim($sql, ','), array_merge(array_values($insert), array_values($update)), $return_object);
	}

}
