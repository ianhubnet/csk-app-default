<?php
defined('BASEPATH') OR die;

/**
 * oci8 Result Class
 *
 * This class extends the parent result class: CI_DB_result
 *
 * @category    Database
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/database/
 */
class CI_DB_oci8_result extends CI_DB_result {

	/**
	 * Limit used flag
	 *
	 * @var bool
	 */
	public $limit_used;

	/**
	 * Commit mode flag
	 *
	 * @var int
	 */
	public $commit_mode;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @param   object  &$driver_object
	 * @return  void
	 */
	public function __construct(&$driver_object)
	{
		parent::__construct($driver_object);

		$this->result_id = $driver_object->result_id;
		$this->limit_used = $driver_object->limit_used;
		$this->commit_mode =& $driver_object->commit_mode;
		$driver_object->result_id = false;
	}

	// --------------------------------------------------------------------

	/**
	 * Number of fields in the result set
	 *
	 * @return  int
	 */
	public function num_fields()
	{
		$count = oci_num_fields($this->result_id);

		// if we used a limit we subtract it
		return ($this->limit_used) ? $count - 1 : $count;
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
		for ($c = 1, $fieldCount = $this->num_fields(); $c <= $fieldCount; $c++)
		{
			$field_names[] = oci_field_name($this->result_id, $c);
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
		for ($c = 1, $fieldCount = $this->num_fields(); $c <= $fieldCount; $c++)
		{
			$F             = new stdClass();
			$F->name       = oci_field_name($this->result_id, $c);
			$F->type       = oci_field_type($this->result_id, $c);
			$F->max_length = oci_field_size($this->result_id, $c);

			$retval[] = $F;
		}

		return $retval;
	}

	// --------------------------------------------------------------------

	/**
	 * Free the result
	 *
	 * @return  void
	 */
	public function free_result()
	{
		if (is_resource($this->result_id))
		{
			oci_free_statement($this->result_id);
			$this->result_id = false;
		}
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
		return oci_fetch_assoc($this->result_id);
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
		$row = oci_fetch_object($this->result_id);

		if ($class_name === 'stdClass' OR ! $row)
		{
			return $row;
		}

		$class_name = new $class_name();
		foreach ($row as $key => $value)
		{
			$class_name->$key = $value;
		}

		return $class_name;
	}

	// --------------------------------------------------------------------

	/**
	 * Destructor
	 *
	 * Attempt to free remaining statement IDs.
	 *
	 * @see	https://github.com/bcit-ci/CodeIgniter/pull/5896
	 * @return  void
	 */
	public function __destruct()
	{
		$this->free_result();
	}
}
