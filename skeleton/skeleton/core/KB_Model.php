<?php
defined('BASEPATH') OR die;

/**
 * KB_Model Class
 *
 * This is a base model that you can use for all other models.
 * It comes with lots of useful methods and even accepts events
 * or triggers.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	1.0
 */
class KB_Model extends CI_Model
{
	/* --------------------------------------------------------------
	 * VARIABLES
	 * ------------------------------------------------------------ */

	/**
	 * This model's default database table. Automatically
	 * guessed by pluralising the model name.
	 */
	protected $table;

	/**
	 * The database connection object. Will be set to the default
	 * connection. This allows individual models to use different DBs
	 * without overwriting CI's global $this->db connection.
	 */
	public $database;

	/**
	 * Array of cached table fields.
	 * @var array
	 */
	protected $fields;

	/**
	 * This model's default primary key or unique identifier.
	 * Used by the get(), update() and delete() functions.
	 */
	protected $primary_key = 'id';

	/**
	 * Support for soft deletes and this model's 'deleted' key
	 */
	protected $soft_delete = false;
	protected $soft_delete_key = 'deleted';
	protected $temporary_with_deleted = false;
	protected $temporary_only_deleted = false;

	/**
	 * The various callbacks available to the model. Each are
	 * simple lists of method names (methods will be run on $this).
	 */
	protected $before_create = array();
	protected $after_create = array();
	protected $before_update = array();
	protected $after_update = array();
	protected $before_get = array();
	protected $after_get = array();
	protected $before_delete = array();
	protected $after_delete = array();

	protected $callback_parameters = array();

	/**
	 * Protected, non-modifiable attributes
	 */
	protected $protected_attributes = array();

	/**
	 * Relationship arrays. Use flat strings for defaults or string
	 * => array to customise the class name and primary key
	 */
	protected $belongs_to = array();
	protected $has_many = array();
	protected $with = array();

	/**
	 * An array of validation rules. This needs to be the same format
	 * as validation rules passed to the Form_validation library.
	 */
	protected $validate = array();

	/**
	 * Optionally skip the validation. Used in conjunction with
	 * skip_validation() to skip data validation for any future calls.
	 */
	protected $skip_validation = false;

	/**
	 * By default we return our results as objects. If we need to override
	 * this, we can, or, we could use the `as_array()` and `as_object()` scopes.
	 */
	protected $return_type = 'object';
	protected $temporary_return_type = null;

	/**
	 * Added by Kader Bouyakoub.
	 * Whether to use unix_timestamp or datatime.
	 * Set to 'timestamp' or 'Y-m-d H:i:s'
	 */
	protected $datetime_format = 'Y-m-d H:i:s';

	/* --------------------------------------------------------------
	 * GENERIC METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Initialise the model, tie into the CodeIgniter superobject and
	 * try our best to guess the table name.
	 */
	public function __construct()
	{
		parent::__construct();

		function_exists('singular') OR $this->load->helper('inflector');

		$this->fetch_table();
		$this->fetch_primary_key();

		$this->database = $this->db;

		array_unshift($this->before_create, 'protect_attributes');
		array_unshift($this->before_update, 'protect_attributes');

		if ($this->soft_delete)
		{
			array_unshift($this->before_delete, 'deleted_at');
		}

		// array_unshift($this->after_get, 'prepare_numeric', 'cached_column');

		$this->temporary_return_type = $this->return_type;
	}

	/* --------------------------------------------------------------
	 * CRUD INTERFACE
	 * ------------------------------------------------------------ */

	/**
	 * Fetch a single record based on the primary key. Returns an object.
	 */
	public function get($primary_value = null)
	{
		if ( ! empty($primary_value))
		{
			return $this->get_by($this->primary_key, $primary_value);
		}
		elseif ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, (bool) $this->temporary_with_deleted);
		}

		$this->trigger('before_get');

		$row = $this->database->get($this->table)->{$this->return_type()}();

		$this->temporary_return_type = $this->return_type;

		$row = $this->trigger('after_get', $row);

		$this->with = array();

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a single record based on an arbitrary WHERE call. Can be
	 * any valid value to $this->database->where().
	 */
	public function get_by()
	{
		$where = func_get_args();

		if ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, (bool)$this->temporary_only_deleted);
		}

		$this->set_where($where);

		$this->trigger('before_get');

		$row = $this->database->get($this->table)->{$this->return_type()}();

		$this->temporary_return_type = $this->return_type;

		$row = $this->trigger('after_get', $row);

		$this->with = array();

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an array of records based on an array of primary values.
	 */
	public function get_many()
	{
		$ids = func_get_args();

		(isset($ids[0]) && is_array($ids[0])) && $ids = $ids[0];

		$this->database->where_in($this->primary_key, $ids);

		return $this->get_all();
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an array of records based on an arbitrary WHERE call.
	 */
	public function get_many_by()
	{
		$where = func_get_args();

		$this->set_where($where);

		return $this->get_all();
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch all the records in the table. Can be used as a generic call
	 * to $this->database->get() with scoped methods.
	 */
	public function get_all()
	{
		$this->trigger('before_get');

		if ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, (bool)$this->temporary_only_deleted);
		}

		$result = $this->database->get($this->table)->{$this->return_type(true)}();

		$this->temporary_return_type = $this->return_type;

		foreach ($result as $key => &$row)
		{
			$row = $this->trigger('after_get', $row, ($key == count($result) - 1));
		}

		$this->with = array();

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert a new row into the table. $data should be an associative array
	 * of data to be inserted. Returns newly created ID.
	 */
	public function insert($data, $skip_validation = false)
	{
		$skip_validation OR $data = $this->validate($data);

		if ( ! $data)
		{
			return false;
		}

		$data = $this->trigger('before_create', $data);

		$this->database->insert($this->table, $data);
		$insert_id = $this->database->insert_id();

		$this->trigger('after_create', $insert_id);

		return $insert_id;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert multiple rows into the table. Returns an array of multiple IDs.
	 */
	public function insert_many($data, $skip_validation = false)
	{
		$ids = array();

		foreach ($data as $key => $row)
		{
			$ids[] = $this->insert($row, $skip_validation, ($key == count($data) - 1));
		}

		return $ids;
	}

	// --------------------------------------------------------------------

	/**
	 * Updated a record based on the primary value.
	 */
	public function update($primary_value, $data, $skip_validation = false)
	{
		$data = $this->trigger('before_update', $data);

		$skip_validation OR $data = $this->validate($data);

		if ( ! $data)
		{
			return false;
		}

		$result = $this->database
			->where($this->primary_key, $primary_value)
			->set($data)
			->update($this->table);

		$this->trigger('after_update', array($data, $result));

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Update many records, based on an array of primary values.
	 */
	public function update_many($primary_values, $data, $skip_validation = false)
	{
		$data = $this->trigger('before_update', $data);

		$skip_validation OR $data = $this->validate($data);

		if ( ! $data)
		{
			return false;
		}

		$result = $this->database
			->where_in($this->primary_key, $primary_values)
			->set($data)
			->update($this->table);

		$this->trigger('after_update', array($data, $result));

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Updated a record based on an arbitrary WHERE clause.
	 */
	public function update_by()
	{
		$args = func_get_args();
		$data = array_pop($args);

		$data = $this->trigger('before_update', $data);

		if ( ! ($data = $this->validate($data)))
		{
			return false;
		}

		$this->set_where($args);

		$result = $this->database
			->set($data)
			->update($this->table);

		$this->trigger('after_update', array($data, $result));

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Update all records
	 */
	public function update_all($data)
	{
		$data = $this->trigger('before_update', $data);

		$result = $this->database
			->set($data)
			->update($this->table);

		$this->trigger('after_update', array($data, $result));

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a row from the table by the primary value
	 */
	public function delete($id)
	{
		$this->trigger('before_delete', $id);

		if (is_numeric($id) && ($id = (int) $id) > 0)
		{
			$this->database->where($this->primary_key, $id);

			$result = $this->soft_delete
				? $this->database->update($this->table, array($this->soft_delete_key => true))
				: $this->database->delete($this->table);
		}
		else
		{
			$result = $this->database->delete($this->table);
		}

		$this->trigger('after_delete', $result);

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a row from the database table by an arbitrary WHERE clause
	 */
	public function delete_by()
	{
		$where = $this->trigger('before_delete', func_get_args());

		$this->set_where($where);

		$result = $this->soft_delete
			? $this->database->update($this->table, array($this->soft_delete_key => true))
			: $this->database->delete($this->table);

		$this->trigger('after_delete', $result);

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete many rows from the database table by multiple primary values
	 */
	public function delete_many($primary_values)
	{
		$primary_values = $this->trigger('before_delete', $primary_values);

		$this->database->where_in($this->primary_key, $primary_values);

		$result = $this->soft_delete
			? $this->database->update($this->table, array($this->soft_delete_key => true))
			: $this->database->delete($this->table);

		$this->trigger('after_delete', $result);

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Permanently delete a row from the table by the primary value
	 */
	public function remove($id)
	{
		$this->soft_delete = false;

		return $this->delete($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Permanently delete a row from the database table by an arbitrary WHERE clause
	 */
	public function remove_by()
	{
		$this->soft_delete = false;

		return call_user_func_array(array($this, 'delete_by'), func_get_args());
	}

	// --------------------------------------------------------------------

	/**
	 * Permanently delete many rows from the database table by multiple primary values
	 */
	public function remove_many($primary_values)
	{
		$this->soft_delete = false;

		return $this->delete_many($primary_values);
	}

	// --------------------------------------------------------------------

	/**
	 * Truncates the table
	 */
	public function truncate()
	{
		$result = $this->database->truncate($this->table);

		return $result;
	}

	/* --------------------------------------------------------------
	 * RELATIONSHIPS
	 * ------------------------------------------------------------ */

	public function with($relationship)
	{
		$this->with[] = $relationship;

		in_array('relate', $this->after_get) OR $this->after_get[] = 'relate';

		return $this;
	}

	// --------------------------------------------------------------------

	public function relate($row)
	{
		if (empty($row))
		{
			return $row;
		}

		foreach ($this->belongs_to as $key => $value)
		{
			if (is_string($value))
			{
				$relationship = $value;
				$options = array( 'primary_key' => $value . '_id', 'model' => $value . '_model' );
			}
			else
			{
				$relationship = $key;
				$options = $value;
			}

			empty($options['model']) && $options['model'] = $key . '_model';

			if (in_array($relationship, $this->with))
			{
				$this->load->model($options['model'], $relationship . '_model');

				if (is_object($row))
				{
					$row->{$relationship} = $this->{$relationship . '_model'}->get($row->{$options['primary_key']});
				}
				else
				{
					$row[$relationship] = $this->{$relationship . '_model'}->get($row[$options['primary_key']]);
				}
			}
		}

		foreach ($this->has_many as $key => $value)
		{
			if (is_string($value))
			{
				$relationship = $value;
				$options = array( 'primary_key' => singular($this->table) . '_id', 'model' => singular($value) . '_model' );
			}
			else
			{
				$relationship = $key;
				$options = $value;
			}

			empty($options['model']) && $options['model'] = $key . '_model';

			if (in_array($relationship, $this->with))
			{
				$this->load->model($options['model'], $relationship . '_model');

				if (is_object($row))
				{
					$row->{$relationship} = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row->{$this->primary_key});
				}
				else
				{
					$row[$relationship] = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row[$this->primary_key]);
				}
			}
		}

		return $row;
	}


	/* --------------------------------------------------------------
	 * UTILITY METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Retrieve and generate a form_dropdown friendly array
	 */
	public function dropdown()
	{
		$args = func_get_args();

		if (count($args) == 2)
		{
			[$key, $value] = $args;
		} else
		{
			$key = $this->primary_key;
			$value = $args[0];
		}

		$this->trigger('before_dropdown', array( $key, $value ));

		if ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, false);
		}

		$result = $this->database
			->select(array($key, $value))
			->get($this->table)
			->result();

		$options = array();

		foreach ($result as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		$options = $this->trigger('after_dropdown', $options);

		return $options;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a count of rows based on an arbitrary WHERE call.
	 */
	public function count_by()
	{
		if ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, (bool)$this->temporary_only_deleted);
		}

		$this->set_where(func_get_args());

		return $this->database->count_all_results($this->table);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a total count of rows, disregarding any previous conditions
	 */
	public function count_all()
	{
		if ($this->soft_delete && ! $this->temporary_with_deleted)
		{
			$this->database->where($this->soft_delete_key, (bool)$this->temporary_only_deleted);
		}

		return $this->database->count_all($this->table);
	}

	// --------------------------------------------------------------------

	/**
	 * Direct use of ActiveRecord join
	 */
	public function join($table, $condition, $type = '', $escape = null)
	{
		$this->database->join($table, $condition, $type, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates the SELECT portion of the query
	 */
	public function select($select = '*', $escape = null)
	{
		$this->database->select($select, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates the WHERE portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function where($key, $value = null, $escape = null)
	{
		$this->database->where($key, $value, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a WHERE field IN('item', 'item') SQL query,
	 * joined with 'AND' if appropriate.
	 */
	public function where_in($key = null, $values = null, $escape = null)
	{
		$this->database->where_in($key, $values, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates the WHERE portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_where($key, $value = null, $escape = null)
	{
		$this->database->or_where($key, $value, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a WHERE field IN('item', 'item') SQL query,
	 * joined with 'OR' if appropriate.
	 */
	public function or_where_in($key = null, $values = null, $escape = null)
	{
		$this->database->or_where_in($key, $values, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a WHERE field NOT IN('item', 'item') SQL query,
	 * joined with 'AND' if appropriate.
	 */
	public function where_not_in($key = null, $values = null, $escape = null)
	{
		$this->database->where_not_in($key, $values, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a WHERE field NOT IN('item', 'item') SQL query,
	 * joined with 'OR' if appropriate.
	 */
	public function or_where_not_in($key = null, $values = null, $escape = null)
	{
		$this->database->or_where_not_in($key, $values, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a %LIKE% portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function like($field, $match = '', $side = 'both', $escape = null)
	{
		$this->database->like($field, $match, $side, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a NOT LIKE portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function not_like($field, $match = '', $side = 'both', $escape = null)
	{
		$this->database->not_like($field, $match, $side, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a %LIKE% portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_like($field, $match = '', $side = 'both', $escape = null)
	{
		$this->database->or_like($field, $match, $side, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a NOT LIKE portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_not_like($field, $match = '', $side = 'both', $escape = null)
	{
		$this->database->or_not_like($field, $match, $side, $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Tell the class to skip the insert validation
	 */
	public function skip_validation()
	{
		$this->skip_validation = true;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the skip validation status
	 */
	public function get_skip_validation()
	{
		return $this->skip_validation;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the next auto increment of the table. Only tested on MySQL.
	 */
	public function get_next_id()
	{
		return (int) $this->database
			->select('AUTO_INCREMENT')
			->from('information_schema.TABLES')
			->where('TABLE_NAME', $this->table)
			->where('TABLE_SCHEMA', $this->database->database)->get()->row()->AUTO_INCREMENT;
	}

	// --------------------------------------------------------------------

	/**
	 * Getter for the table name
	 */
	public function table()
	{
		return $this->table;
	}

	/* --------------------------------------------------------------
	 * GLOBAL SCOPES
	 * ------------------------------------------------------------ */

	/**
	 * Return the next call as an array rather than an object
	 */
	public function as_array()
	{
		$this->temporary_return_type = 'array';

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the next call as an object rather than an array
	 */
	public function as_object()
	{
		$this->temporary_return_type = 'object';

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the next as json encoded.
	 */
	public function as_json()
	{
		$this->temporary_return_type = 'json';

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Don't care about soft deleted rows on the next call
	 */
	public function with_deleted()
	{
		$this->temporary_with_deleted = true;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Only get deleted rows on the next call
	 */
	public function only_deleted()
	{
		$this->temporary_only_deleted = true;

		return $this;
	}

	/* --------------------------------------------------------------
	 * OBSERVERS
	 * ------------------------------------------------------------ */

	/**
	 * MySQL DATETIME created_at
	 */
	public function created_at($row)
	{
		if (is_object($row))
		{
			$row->created_at = $this->datetime();
		}
		elseif (is_array($row))
		{
			$row['created_at'] = $this->datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * MySQL DATETIME updated_at
	 */
	public function updated_at($row)
	{
		if (is_object($row))
		{
			$row->updated_at = $this->datetime();
		}
		elseif (is_array($row))
		{
			$row['updated_at'] = $this->datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * MySQL DATETIME deleted_at
	 */
	public function deleted_at($row)
	{
		if (is_object($row))
		{
			$row->deleted_at = $this->datetime();
		}
		elseif (is_array($row))
		{
			$row['deleted_at'] = $this->datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns unix timestamp or a date in a given format.
	 * @return string date or unix_timestamp
	 */
	private function datetime()
	{
		return ($this->datetime_format == 'timestamp') ? TIME : date($this->datetime_format, TIME);
	}

	// --------------------------------------------------------------------

	/**
	 * Serialises data for you automatically, allowing you to pass
	 * through objects and let it handle the serialisation in the background
	 */
	public function serialize($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			$row[$column] = serialize($row[$column]);
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Unserializes data.
	 */
	public function unserialize($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row))
			{
				$row[$column] = unserialize($row[$column]);
			}
			else
			{
				$row->$column = unserialize($row->$column);
			}
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares data before input.
	 */
	public function prepare_input($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row) && isset($row[$column]))
			{
				$row[$column] = to_bool_or_serialize($row[$column]);
			}
			elseif (isset($row->{$column}))
			{
				$row->{$column} = to_bool_or_serialize($row->{$column});
			}
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares data before output.
	 */
	public function prepare_output($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row) && isset($row[$column]))
			{
				$row[$column] = from_bool_or_serialize($row[$column]);
			}
			elseif (isset($row->{$column}))
			{
				$row->{$column} = from_bool_or_serialize($row->{$column});
			}
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Prepare data numeric values.
	 */
	public function prepare_numeric($row)
	{
		if (empty($row))
		{
			return $row;
		}

		foreach ($row as $key => $val)
		{
			if (is_numeric($val))
			{
				if (is_array($row))
				{
					$row[$key] = (int) $val;
				}
				else
				{
					$row->{$key} = (int) $val;
				}
			}
		}

		return $row;
	}

	// --------------------------------------------------------------------

	public function cached_column($row)
	{
		if (is_object($row))
		{
			$row->cached = false;
		}
		elseif (is_array($row))
		{
			$row['cached'] = false;
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Protect attributes by removing them from $row array
	 */
	public function protect_attributes($row)
	{
		foreach ($this->protected_attributes as $attr)
		{
			if (is_object($row))
			{
				unset($row->$attr);
			}
			else
			{
				unset($row[$attr]);
			}
		}

		return $row;
	}

	/* --------------------------------------------------------------
	 * QUERY BUILDER DIRECT ACCESS METHODS
	 * ------------------------------------------------------------ */

	/**
	 * A wrapper to $this->database->order_by()
	 */
	public function order_by($criteria, $order = 'ASC')
	{
		if (is_array($criteria))
		{
			foreach ($criteria as $key => $value)
			{
				$this->database->order_by($key, $value);
			}

			return $this;
		}

		$this->database->order_by($criteria, $order);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * A wrapper to $this->database->limit()
	 */
	public function limit($limit, $offset = 0)
	{
		$this->database->limit($limit, $offset);

		return $this;
	}

	/* --------------------------------------------------------------
	 * INTERNAL METHODS
	 * ------------------------------------------------------------ */

	/**
	 * Trigger an event and call its observers. Pass through the event name
	 * (which looks for an instance variable $this->event_name), an array of
	 * parameters to pass through and an optional 'last in interation' boolean
	 */
	public function trigger($event, $data = false, $last = true)
	{
		if (isset($this->$event) && is_array($this->$event))
		{
			foreach ($this->$event as $method)
			{
				if ( ! method_exists($this, $method))
				{
					continue;
				}
				elseif (strpos($method, '('))
				{
					preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);

					$method = $matches[1];
					$this->callback_parameters = explode(',', $matches[3]);
				}

				$data = call_user_func_array(array($this, $method), array($data, $last));
			}
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Run validation on the passed data
	 */
	public function validate($data)
	{
		if ($this->skip_validation)
		{
			return $data;
		}

		if ( ! empty($this->validate))
		{
			foreach($data as $key => $val)
			{
				$_POST[$key] = $val;
			}

			$this->load->library('form_validation');

			if (is_array($this->validate))
			{
				$this->form_validation->set_rules($this->validate);

				if ($this->form_validation->run() === true)
				{
					return $data;
				}

				return false;
			}
			else
			{
				if ($this->form_validation->run($this->validate) === true)
				{
					return $data;
				}

				return false;
			}
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * List table's fields.
	 */
	public function list_fields($table = null)
	{
		if ( ! isset($this->fields))
		{
			empty($table) && $table = $this->table;
			$this->database->table_exists($table) && $this->fields = $this->database->list_fields($table);
		}

		return $this->fields;
	}

	// --------------------------------------------------------------------

	/**
	 * Determine if a particular field exits.
	 * @param   string  $field  the field's name
	 * @param   string  $table  the table's name.
	 * @return  bool    true if exists, else false.
	 */
	public function field_exists($field, $table = null)
	{
		$table OR $table = $this->table;

		// Make sure first that the table exists.
		return $this->database->table_exists($table) ? $this->database->field_exists($field, $table) : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Guess the table name by pluralising the model name
	 */
	private function fetch_table()
	{
		if ( ! isset($this->table))
		{
			function_exists('plural') OR $this->load->helper('inflector');

			$this->table = plural(preg_replace('/(_m|_mod|_model)?$/', '', strtolower(get_class($this))));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Guess the primary key for current table
	 */
	private function fetch_primary_key()
	{
		if ($this->primary_key === null)
		{
			$this->primary_key = $this->database->query("SHOW KEYS FROM `".$this->table."` WHERE Key_name = 'PRIMARY'")->row()->Column_name;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set WHERE parameters, cleverly
	 */
	protected function set_where($params)
	{
		if (count($params) == 1 && is_array($params[0]))
		{
			foreach ($params[0] as $field => $filter)
			{
				if (is_array($filter))
				{
					$this->database->where_in($field, $filter);
				}
				else
				{
					if (is_int($field))
					{
						$this->database->where($filter);
					}
					else
					{
						$this->database->where($field, $filter);
					}
				}
			}
		}
		elseif (count($params) == 1)
		{
			$this->database->where($params[0]);
		}
		elseif (count($params) == 2)
		{
			if (is_array($params[1]))
			{
				$this->database->where_in($params[0], $params[1]);
			}
			else
			{
				$this->database->where($params[0], $params[1]);
			}
		}
		elseif (count($params) == 3)
		{
			$this->database->where($params[0], $params[1], $params[2]);
		}
		else
		{
			if (is_array($params[1]))
			{
				$this->database->where_in($params[0], $params[1]);
			}
			else
			{
				$this->database->where($params[0], $params[1]);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Return the method name for the current return type
	 */
	protected function return_type($multi = false)
	{
		$method = ($multi) ? 'result' : 'row';

		('object' !== $this->temporary_return_type) && $method .= '_' .$this->temporary_return_type;

		return $method;
	}
}
