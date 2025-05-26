<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_options Class
 *
 * Handles all operations done on options stored in database.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_options extends KB_Driver implements CRUD_interface
{
	/**
	 * Get all autoloaded options from database and assign
	 * them to CodeIgniter config array.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function initialize()
	{
		$this->ci->config->set_item(
			'datetime_format',
			$this->ci->config->item('date_format').' '.$this->ci->config->item('time_format')
		);

		// Use admin email if contact email is missing.
		if (empty($this->ci->config->item('contact_email')))
		{
			$this->ci->config->set_item('contact_email', $this->ci->config->item('admin_email'));
		}

		/**
		 * Things to disable if enabled when the site is offline or in demo mode.
		 * 	1. Account creation.
		 */
		if ($this->ci->config->item('allow_registration'))
		{
			$this->ci->config->set_item(
				'allow_registration',
				! ($this->ci->config->item('site_offline') OR $this->ci->config->item('demo_mode'))
			);
		}

		// Disable reCAPTCHA if Site key or Private key are missing.
		if ($this->ci->config->item('use_recaptcha'))
		{
			$use_recaptcha = $this->ci->config->item('use_captcha');
			$use_recaptcha && $use_recaptcha = ! empty($this->ci->config->item('recaptcha_site_key'));
			$use_recaptcha && $use_recaptcha = ! empty($this->ci->config->item('recaptcha_private_key'));

			$this->ci->config->set_item('use_recaptcha', $use_recaptcha);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Create a new options.
	 * @access 	public
	 * @param 	array 	$data 	Array of data to insert.
	 * @return 	the new row ID if found, else false.
	 */
	public function create(array $data = array())
	{
		// If $data is empty, nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Make sure the name is set and unique.
		if ( ! isset($data['name']) OR $this->get_by('name', $data['name']))
		{
			return false;
		}

		/**
		 * Here we make sure to prepare "value" and "options" if they
		 * are set and not empty.
		 */
		if (isset($data['value']))
		{
			$data['value'] = to_bool_or_serialize($data['value']);
		}
		if (isset($data['options']))
		{
			$data['options'] = to_bool_or_serialize($data['options']);
		}

		// Insert the option into database.
		$this->ci->db->insert('options', $data);
		if ($this->ci->db->affected_rows() <= 0)
		{
			return false;
		}

		$this->ci->config->set_item($data['name'], $data['value']);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single row by it's primary ID.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Remove $single argument and use "get_by" method.
	 * 
	 * @access 	public
	 * @param 	mixed 	$name 		The primary key value.
	 * @return 	object if found, else null
	 */
	public function get($name)
	{
		// Getting by ID?
		if (is_string($name))
		{
			// See if not cached already.
			return (($value = $this->ci->config->item($name)) !== null) ? $value : $this->get_by('name', $name);
		}

		// Otherwise, let the "get_by" method handle the rest.
		return $this->get_by($name);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single option by arbitrary WHERE clause.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Remove $single argument and let parent handle WHERE clause.
	 * 
	 * @access 	public
	 * @param 	mixed 	$field 		Column name or associative array.
	 * @param 	mixed 	$match 		Comparison value.
	 * @return 	object if found, else null.
	 */
	public function get_by($field, $match = null)
	{
		// Attempt to get the option from database.
		$option = $this->_parent
			->where($field, $match, 1, 0)
			->get('options')
			->row();

		if ( ! $option)
		{
			return false;
		}

		return $this->prep_output($option);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple options by arbitrary WHERE clause.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Rewritten to let parent handle WHERE clause and create
	 *         			objects for each found option.
	 *
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to get options from database.
		$options = $this->_parent
			->where($field, $match, $limit, $offset)
			->get('options')
			->result();

		if ( ! $options)
		{
			return false;
		}

		foreach ($options as &$option)
		{
			$option = $this->prep_output($option);
		}

		return $options;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all options.
	 * @access 	public
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single options by its name.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Rewritten for better usage.
	 * 
	 * @access 	public
	 * @param 	mixed 	$name 	The option's name or array of WHERE clause.
	 * @param 	array 	$data 	Array of data to update.
	 * @return 	bool
	 */
	public function update($name, array $data = array())
	{
		// updating by name?
		if (is_string($name))
		{
			return $this->update_by(array('name' => $name), $data);
		}

		// Otherwise, let the "update_by" handle the rest.
		return $this->update_by($name, $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single, all or multiple options by arbitrary WHERE clause.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Rewritten to let the parent handle WHERE clause.
	 * @access 	public
	 * @return 	bool
	 */
	public function update_by()
	{
		// Collect function arguments and make sure there are some.
		if (empty($args = func_get_args()))
		{
			return false;
		}

		// The data is always the last array.
		$data = array_pop($args);
		if ( ! is_array($data) OR empty($data))
		{
			return false;
		}

		// Prepare the update query.
		$this->ci->db->set($this->prep_input($data));

		// All remaining arguments will be used as WHERE clause.
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$this->_parent->where($args);
		}

		// Proceed to update and return the status.
		return $this->ci->db->update('options');
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single option by its name.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Rewritten for better usage.
	 * @access 	public
	 * @param 	mixed 	$name 	The option's name or array of WHERE clause.
	 * @return 	bool
	 */
	public function delete($name)
	{
		// Deleting by name?
		if (is_string($name))
		{
			return $this->delete_by('name', $name);
		}

		// Otherwise, let the "dlete_by" handle the rest.
		return $this->delete_by($name);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single, all or multiple options by arbitrary WHERE clause.
	 *
	 * @since 	1.0
	 * @since 	1.30 	Rewritten to let the parent handle the WHERE clause, and
	 *         			add optional limit and offset.
	 *
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	bool
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Delete them.
		$this->_parent
			->where($field, $match, $limit, $offset)
			->delete('options');

		// Return true if some rows were deleted.
		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * Create a new option item.
	 * @access 	public
	 * @param 	string 	$name 		the option's name.
	 * @param 	mixed 	$value 		the option's value.
	 * @param 	string 	$tab 		Where the option should be listed.
	 * @param 	string 	$field_type What type of filed input to display
	 * @param 	mixed 	$options 	Options to display on settings page.
	 * @param 	bool 	$required 	Whether to make the field required.
	 * @return 	bool
	 */
	public function add_item(
		$name,
		$value = null,
		$tab = null,
		$field_type = 'text',
		$options = null,
		$required = true)
	{
		return $this->create(array(
			'name'       => sanitize_key($name),
			'value'      => $value,
			'tab'        => $tab,
			'field_type' => $field_type,
			'options'    => $options,
			'required'   => ($required === true) ? 1 : 0,
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Update an option item if it exists or create it if it does not.
	 * @access 	public
	 * @param 	string 	$name 		the item name.
	 * @param 	mixed 	$raw_value 	the new value.
	 * @param 	string 	$tab 		the tab to which the item belongs.
	 * @return 	bool
	 */
	public function set_item($name, $raw_value = null, $tab = null)
	{
		$new_value = to_bool_or_serialize($raw_value);
		$name = sanitize_key($name);

		$insert = array('name' => $name, 'value' => $new_value, 'tab' => $tab);
		$update = array('value' => $new_value);
		(empty($tab)) OR $update['tab'] = $tab;

		if ($this->ci->db->save('options', $insert, $update))
		{
			$this->ci->config->set_item($name, $raw_value);

			$this->ci->events->trigger('option_updated_'.$name, $raw_value);

			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Magic __set method.
	 * @param 	string 	$name 		the item name.
	 * @param 	mixed 	$raw_value 	the new value.
	 * @return 	Kbcore_options::set_item
	 */
	public function __set($name, $raw_value = null)
	{
		return $this->set_item($name, $raw_value);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single option item.
	 * @access 	public
	 * @deprecated 		Please use Config::item insteand
	 * @param 	string 	$name
	 * @param 	mixed 	$default 	the default value to use if not found.
	 * @return 	mixed 	depends on the item's value
	 */
	public function item($name, $default = null)
	{
		return $this->ci->config->item($name, null, $default);
	}

	// --------------------------------------------------------------------

	/**
	 * Get all options by tab.
	 * @access 	public
	 * @param 	string 	$tab 	default: general
	 */
	public function get_by_tab($tab = 'general')
	{
		return $this->get_many('tab', $tab);
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares an option for output.
	 *
	 * @param 	object|array 	$option
	 * @return 	object|array
	 */
	private function prep_output($option)
	{
		if (is_object($option))
		{
			if ( ! empty($option->value))
			{
				$option->value = from_bool_or_serialize($option->value);
			}
			if ( ! empty($option->options))
			{
				$option->options = from_bool_or_serialize($option->options);
			}
		}
		elseif (is_array($option))
		{
			if ( ! empty($option['value']))
			{
				$option['value'] = from_bool_or_serialize($option['value']);
			}
			if ( ! empty($option['options']))
			{
				$option['options'] = from_bool_or_serialize($option['options']);
			}
		}

		return $option;
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares an option before input into database.
	 *
	 * @param 	object|array 	$option
	 * @return 	object|array
	 */
	private function prep_input($option)
	{
		if (is_object($option))
		{
			if (isset($option->value))
			{
				$option->value = to_bool_or_serialize($option->value);
			}
			if (isset($option->options))
			{
				$option->options = to_bool_or_serialize($option->options);
			}
		}
		elseif (is_array($option))
		{
			if (isset($option['value']))
			{
				$option['value'] = to_bool_or_serialize($option['value']);
			}
			if (isset($option['options']))
			{
				$option['options'] = to_bool_or_serialize($option['options']);
			}
		}

		return $option;
	}

}
