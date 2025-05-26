<?php
defined('BASEPATH') OR die;

/**
 * MySQLi Result Class
 *
 * This class extends the parent result class: CI_DB_result
 *
 * @package     CodeIgniter
 * @subpackage  Drivers
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/database/
 */
class CI_DB_mysqli_result extends CI_DB_result {

	/**
	 * Number of rows in the result set
	 *
	 * @return  int
	 */
	public function num_rows()
	{
		return is_int($this->num_rows)
			? $this->num_rows
			: $this->num_rows = $this->result_id->num_rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Number of fields in the result set
	 *
	 * @return  int
	 */
	public function num_fields()
	{
		return $this->result_id->field_count;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Field Names
	 *
	 * Generates an array of column names
	 *
	 * @return  array
	 */
	public function list_fields()
	{
		$field_names = array();
		$this->result_id->field_seek(0);
		while ($field = $this->result_id->fetch_field())
		{
			$field_names[] = $field->name;
		}

		return $field_names;
	}

	// --------------------------------------------------------------------

	/**
	 * Field data
	 *
	 * Generates an array of objects containing field meta-data
	 *
	 * @return  array
	 */
	public function field_data()
	{
		$retval = array();
		$field_data = $this->result_id->fetch_fields();
		for ($i = 0, $c = count($field_data); $i < $c; $i++)
		{
			$retval[$i]         = new stdClass();
			$retval[$i]->name       = $field_data[$i]->name;
			$retval[$i]->type       = static::_get_field_type($field_data[$i]->type);
			$retval[$i]->max_length     = $field_data[$i]->max_length;
			$retval[$i]->primary_key    = (int) ($field_data[$i]->flags & MYSQLI_PRI_KEY_FLAG);
			$retval[$i]->default        = $field_data[$i]->def;
		}

		return $retval;
	}

	// --------------------------------------------------------------------

	/**
	 * Get field type
	 *
	 * Extracts field type info from the bitflags returned by
	 * mysqli_result::fetch_fields()
	 *
	 * @used-by CI_DB_mysqli_result::field_data()
	 * @param   int $type
	 * @return  string
	 */
	private static function _get_field_type($type)
	{
		static $map;
		isset($map) OR $map = array(
			MYSQLI_TYPE_DECIMAL     => 'decimal',
			MYSQLI_TYPE_BIT         => 'bit',
			MYSQLI_TYPE_TINY        => 'tinyint',
			MYSQLI_TYPE_SHORT       => 'smallint',
			MYSQLI_TYPE_INT24       => 'mediumint',
			MYSQLI_TYPE_LONG        => 'int',
			MYSQLI_TYPE_LONGLONG    => 'bigint',
			MYSQLI_TYPE_FLOAT       => 'float',
			MYSQLI_TYPE_DOUBLE      => 'double',
			MYSQLI_TYPE_TIMESTAMP   => 'timestamp',
			MYSQLI_TYPE_DATE        => 'date',
			MYSQLI_TYPE_TIME        => 'time',
			MYSQLI_TYPE_DATETIME    => 'datetime',
			MYSQLI_TYPE_YEAR        => 'year',
			MYSQLI_TYPE_NEWDATE     => 'date',
			MYSQLI_TYPE_INTERVAL    => 'interval',
			MYSQLI_TYPE_ENUM        => 'enum',
			MYSQLI_TYPE_SET         => 'set',
			MYSQLI_TYPE_TINY_BLOB   => 'tinyblob',
			MYSQLI_TYPE_MEDIUM_BLOB => 'mediumblob',
			MYSQLI_TYPE_BLOB        => 'blob',
			MYSQLI_TYPE_LONG_BLOB   => 'longblob',
			MYSQLI_TYPE_STRING      => 'char',
			MYSQLI_TYPE_VAR_STRING  => 'varchar',
			MYSQLI_TYPE_GEOMETRY    => 'geometry'
		);

		return isset($map[$type]) ? $map[$type] : $type;
	}

	// --------------------------------------------------------------------

	/**
	 * Free the result
	 *
	 * @return  void
	 */
	public function free_result()
	{
		if (is_object($this->result_id))
		{
			$this->result_id->free();
			$this->result_id = false;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Data Seek
	 *
	 * Moves the internal pointer to the desired offset. We call
	 * this internally before fetching results to make sure the
	 * result set starts at zero.
	 *
	 * @param   int $n
	 * @return  bool
	 */
	public function data_seek($n = 0)
	{
		return $this->result_id->data_seek($n);
	}

	// --------------------------------------------------------------------

	/**
	 * Result - associative array
	 *
	 * Returns the result set as an array
	 *
	 * @return  array
	 */
	protected function _fetch_assoc()
	{
		return $this->result_id->fetch_assoc();
	}

	// --------------------------------------------------------------------

	/**
	 * Result - object
	 *
	 * Returns the result set as an object
	 *
	 * @param   string  $class_name
	 * @return  object
	 */
	protected function _fetch_object($class_name = 'stdClass')
	{
		return $this->result_id->fetch_object($class_name);
	}

}
