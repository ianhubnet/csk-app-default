<?php
defined('BASEPATH') OR die;

/**
 * KB_Entity Class
 *
 * Class used to implement methods and properties that are shared between:
 * 	1. KB_User
 * 	2. KB_Group
 * 	3. KB_Object
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries\Kbcore
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.30
 */
class KB_Entity
{
	/**
	 * Data container.
	 * @var object
	 */
	public $data;

	/**
	 * The object's ID.
	 * @var int
	 */
	public $id = 0;

	/**
	 * Array of data awaiting to be updated.
	 * @var array
	 */
	protected $queue = array();

	// --------------------------------------------------------------------

	/**
	 * Attempts to retrieve a record from metadata table.
	 *
	 * @param 	string 	$name 	The meta name.
	 * @return 	mixed 	Meta value if found, else false.
	 */
	protected function get_meta($name)
	{
		if (empty($name))
		{
			return false;
		}

		$query = get_instance()->db
			->where('guid', $this->id)
			->where('name', $name)
			->get('metadata');

		$row = $query->row();
		$query->free_result();

		return (null === $row) ? false : from_bool_or_serialize($row->value);
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method for checking the existence of a property.
	 *
	 * @param 	string 	$key 	The property's key.
	 * @return 	bool 	true if the property exists, else false.
	 */
	public function __isset($key)
	{
		// Just make it possible to use ID.
		('ID' === $key) && $key = 'id';

		if (property_exists($this, $key))
		{
			return $this->$key;
		}
		elseif (isset($this->data->$key))
		{
			return $this->data->$key;
		}
		elseif (false !== ($meta = $this->get_meta($key)))
		{
			$this->data->$key = $meta;
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method for getting a property's value.
	 *
	 * @param 	string 	$key 	The property's key to retrieve.
	 * @return 	mixed
	 */
	public function __get($key)
	{
		if (isset($this->data->$key))
		{
			return $this->data->$key;
		}
		elseif (false !== ($meta = $this->get_meta($key)))
		{
			$this->data->$key = $meta;
			return $this->data->$key;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method for setting a property's value.
	 *
	 * @param 	string 	$key 	The property's key.
	 * @param 	mixed 	$value 	The property's value.
	 * @return 	void
	 */
	public function __set($key, $value)
	{
		// Just make it possible to use ID.
		('ID' === $key) && $key = 'id';

		// If found, we make sure to set it.
		$this->data->$key = $value;

		// We enqueue it for later use.
		$this->queue[$key]  = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method for unsetting a property.
	 *
	 * @param   string  $key    The property's key.
	 */
	public function __unset($key)
	{
		// Remove it from $data object.
		if (isset($this->data->$key))
		{
			unset($this->data->$key);
		}

		// We remove it if queued.
		if (isset($this->queue[$key]))
		{
			unset($this->queue[$key]);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for checking the existence of the object in database.
	 *
	 * @param   none
	 * @return  bool    true if the object exists, else false.
	 */
	public function exists()
	{
		return ! empty($this->id);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for checking the existence of a property.
	 *
	 * @param   string  $key    The property's key.
	 * @return  bool    true if the property's exists, else false.
	 */
	public function has($key)
	{
		return $this->__isset($key);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array representation of this object data.
	 *
	 * @since   1.33
	 *
	 * @return  array
	 */
	public function to_array()
	{
		return get_object_vars($this->data);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for setting a property's value.
	 *
	 * @param   string  $key    The property's key.
	 * @param   string  $value  The property's value.
	 * @return  object  we return the object to make it chainable.
	 */
	public function set($key, $value)
	{
		$this->__set($key, $value);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for getting a property's value.
	 *
	 * @param   string  $key    The property's key.
	 * @return  mixed   Depends on the property's value.
	 */
	public function get($key)
	{
		return $this->__get($key);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for retrieving the array of data waiting to be saved.
	 *
	 * @return  array
	 */
	public function dirty()
	{
		return $this->queue;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for updating the object in database.
	 *
	 * @since 	2.20
	 *
	 * @param 	string 	$field 	The field name.
	 * @param 	mixed 	$value 	The field value.
	 * @return 	bool 	True if update, else false.
	 */
	public function update($field, $value = null)
	{
		if ( ! isset($this->driver))
		{
			return false;
		}

		$CI =& get_instance();

		if ( ! isset($CI->{$this->driver}))
		{
			return false;
		}

		$field = is_array($field) ? $field : array($field => $value);

		if (false === $CI->{$this->driver}->update($this->id, $field))
		{
			return false;
		}

		/**
		 * If the username was changed, we make sure to create a
		 * redirection for this entity.
		 * @since 	2.69
		 */
		if (isset($field['username']))
		{
			$redirects = $CI->config->item('redirects', null, array());
			$redirects[$this->data->username] = $field['username']; // Store new username
			unset($redirects[$field['username']]); // Unset old username
			$CI->options->set_item('redirects', $redirects); // Update...
		}

		foreach ($field as $key => $val)
		{
			if (isset($this->queue[$key]))
			{
				unset($this->queue[$key]);
			}

			if (isset($this->data->$key))
			{
				$this->data->$key = $val;
			}
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for saving anything that changed.
	 *
	 * @since 	2.20
	 *
	 * @return 	bool
	 */
	public function save()
	{
		if ( ! isset($this->driver) OR empty($this->queue))
		{
			return false;
		}

		$CI =& get_instance();

		if ( ! in_array($this->driver, $CI->core->valid_drivers))
		{
			return false;
		}

		if (false === $CI->{$this->driver}->update($this->id, $this->queue))
		{
			return false;
		}

		$this->queue = array();

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Creates and increments the selected metadata for the object.
	 *
	 * @since 	2.20
	 * @param 	string 	$key 	Metadata key.
	 * @param 	string 	$offset By how much.
	 * @return 	bool
	 */
	public function incr($key, $offset = 1)
	{
		if (empty($key))
		{
			return false;
		}

		return get_instance()->db->save(
			'metadata', // TABLE
			array('guid' => $this->id, 'name' => sanitize_key($key), 'value' => $offset), // INSERT
			array('value' => '+'.$offset) // UPDATE
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Creates and decrements the selected metadata for the object.
	 *
	 * @since 	2.20
	 * @param 	string 	$key 	Metadata key.
	 * @param 	string 	$offset By how much.
	 * @return 	bool
	 */
	public function decr($key, $offset = 1)
	{
		if (empty($key))
		{
			return false;
		}

		return get_instance()->db->save(
			'metadata', // TABLE
			array('guid' => $this->id, 'name' => sanitize_key($key), 'value' => 0), // INSERT
			array('value' => '-'.$offset) // UPDATE
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Allows translating the entity to the seleted language.
	 *
	 * @param 	string 	$idiom
	 * @return 	void
	 */
	public function translate(CI_Controller $CI, $idiom = null)
	{
		if (is_array($idiom))
		{
			$idiom = $idiom['folder'];
		}
		elseif (empty($idiom))
		{
			$idiom = $CI->lang->idiom;
		}

		if ($idiom === $this->data->language && ! ($this instanceof KB_User))
		{
			return;
		}

		$query = $CI->db
			->where('guid', $this->id)
			->where('idiom', $idiom)
			->get('translations');

		if (0 >= $query->num_rows())
		{
			return;
		}

		foreach ($query->result() as $row)
		{
			$this->data->{$row->name} = ('content' === $row->name OR 'description' === $row->name)
				? $row->value
				: from_bool_or_serialize(html_escape($row->value));
		}

		$query->free_result();

		method_exists($this, 'after_translate') && $this->after_translate($idiom);
	}

}
