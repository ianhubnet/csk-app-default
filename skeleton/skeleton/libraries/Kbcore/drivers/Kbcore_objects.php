<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_objects Class
 *
 * Handles operations done on any thing tagged as a object.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_objects extends KB_Driver implements CRUD_interface
{
	/**
	 * Holds objects table fields.
	 * @var array
	 */
	public $fields = array(
		'guid',
		'name',
		'description',
		'content'
	);

	/**
	 * Array of database columns that can be translated.
	 * @var array
	 */
	public $i18n_fields = array(
		'name',
		'description',
		'content',
		'meta_title',
		'meta_description',
		'meta_keywords'
	);

	/**
	 * Array of meta to be used by Theme::set_meta.
	 * @var array
	 */
	public $metas = array(
		'meta_title',
		'meta_description',
		'meta_keywords'
	);

	// --------------------------------------------------------------------

	/**
	 * Create a new object.
	 *
	 * @param   array   $data
	 * @return  int     the object's ID if created, else false.
	 */
	public function create(array $data = array())
	{
		// Nothing provided? Nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Always remove unwanted fields.
		unset($data[COOK_CSRF], $data['persist']);

		// Split data.
		[$entity, $object, $meta] = $this->_parent->split_data($data, 'objects');

		// Make sure to alwayas add the entity's type.
		$entity['type'] = 'object';

		// Let's insert the entity first and make sure it's created.
		$guid = $this->_parent->entities->create($entity);
		if ( ! $guid)
		{
			return false;
		}

		// Add the id to object.
		$object['guid'] = $guid;

		// Insert the object.
		$this->ci->db->insert('objects', $object);

		// If the are any metadata, create them.
		if ( ! empty($meta))
		{
			foreach ($meta as $key => $val)
			{
				$val = to_bool_or_serialize($val);
				$this->ci->db->save(
					'metadata',
					array('guid' => $guid, 'name' => sanitize_key($key), 'value' => trim($val)), // INSERT
					array('value' => trim($val)) // UPDATE ON DUPLICATE
				);
			}
		}

		return $guid;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single object by ID or username.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance.
	 * @since 	2.20 	Only accept ID or username.
	 *
	 * @param   mixed   $id     The object's ID or username.
	 * @return  object if found, else null.
	 */
	public function get($id)
	{
		return $this->get_by(is_numeric($id) ? 'id' : 'username', $id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single object by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten the let the parent handle WHERE clause.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   bool 	$raw
	 * @return  object if found, else null.
	 */
	public function get_by($field, $match = null, $raw = false)
	{
		// Allow 'ID' as alias for 'id'.
		('ID' === $field) && $field = 'id';

		// Are we retrieving a object by ID?
		if ('id' === $field)
		{
			// Make sure provided $match is numeric.
			if ( ! is_numeric($match) OR 0 >= $match = (int) $match)
			{
				return false;
			}
		}
		elseif (is_array($field))
		{
			if (isset($field['id']) && 0 >= $field['id'])
			{
				return false;
			}
		}
		else
		{
			$match = trim($match);
		}

		if ( ! $match && ! is_array($field))
		{
			return false;
		}

		// Only accept: id or username.
		switch ($field) {
			case 'id':
			case 'guid':
			case 'entities.id':
			case 'objects.guid':
				$guid  = $match;
				$field = 'entities.id';
				break;
			case 'username':
			case 'entities.username':
				$guid  = $this->ci->registry->get($match, 'usernames');
				$field = 'entities.username';
				break;
			case is_array($field):
				(isset($field['id'])) && $guid = (int) $field['id'];
				( ! isset($guid) && isset($field['guid'])) && $guid = (int) $field['guid'];
				( ! isset($guid) && isset($field['username'])) && $guid = $this->ci->registry->get($field['username'], 'usernames');
				break;
			case is_array($field) && isset($field['username']):
				$guid = $this->ci->registry->get($field['username'], 'usernames');
				break;
			default:
				return false;
		}

		// See if the object was already cached.
		if (isset($guid) && ($object = $this->ci->registry->get($guid, 'objects')))
		{
			return $raw ? $object->data : $object;
		}

		// Get the object from database.
		$query = $this->ci->db
			->where('entities.type', 'object')
			->where($field, $match)
			->join('objects', 'entities.id = objects.guid')
			->order_by('entities.id', 'DESC')
			->limit(1, 0)
			->get('entities');

		if (1 !== $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$object = new KB_Object($query->row());
		$query->free_result();

		$this->ci->registry->add($object->id, $object, 'objects');
		$this->ci->registry->add($object->username, $object->id, 'usernames');

		return $raw ? $object->data : $object;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple objects by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle WHERE clause.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  array of objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to retrieve objects from database.
		$query = $this->_parent
			->where($field, $match, $limit, $offset)
			->where('entities.type', 'object')
			->join('objects', 'objects.guid = entities.id')
			->get('entities');

		if (0 >= $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$objects = array();
		foreach ($query->result() as $row)
		{
			$objects[] = ($object = $this->ci->registry->get($row->id, 'objects')) ? $user : new KB_Object($row);
		}

		$query->free_result();

		return $objects;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all objects with optional limit and offset.
	 *
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  array of objects.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single object.
	 *
	 * @since   1.0
	 * @since   1.30   Fixing type where we were using inexistent model.
	 *
	 * @param   int     $id
	 * @param   array   $data
	 * @return  bool
	 */
	public function update($id, array $data = array())
	{
		// Empty $data? Nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Always remove unwanted fields.
		unset($data[COOK_CSRF], $data['persist']);

		// Always add the update data.
		(isset($data['updated_at'])) OR $data['updated_at'] = TIME;

		// Split data.
		[$entity, $object, $meta] = $this->_parent->split_data($data, 'objects');

		// Update entity.
		if ( ! empty($entity) && ! $this->_parent->entities->update($id, $entity))
		{
			return false;
		}

		// Update objects table.
		if ( ! empty($object)
			&& ! $this->ci->db->update('objects', $object, array('guid' => $id)))
		{
			return false;
		}

		// If there are any metadata to update.
		if ( ! empty($meta))
		{
			foreach ($meta as $key => $val)
			{
				$val = to_bool_or_serialize($val);
				$this->ci->db->save(
					'metadata',
					array('guid' => $id, 'name' => sanitize_key($key), 'value' => trim($val)), // INSERT
					array('value' => trim($val)) // UPDATE ON DUPLICATE
				);
			}
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Update all or multiple objects by arbitrary WHERE clause.
	 *
	 * @return  bool
	 */
	public function update_by()
	{
		// Collect arguments first and make sure there are any.
		if (empty($args = func_get_args()))
		{
			return false;
		}

		// Data to update is always the last element.
		$data = array_pop($args);
		if (empty($data))
		{
			return false;
		}

		// Get objects
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$objects = $this->get_many($args);
		}
		else
		{
			$objects = $this->get_all();
		}

		// If there are any objects, proceed to update.
		if ($objects)
		{
			// Always remove unwanted fields.
			unset($data[COOK_CSRF], $data['persist']);

			foreach ($objects as $object)
			{
				$object->update($data);
			}

			return true;
		}

		// Nothing happened, return false.
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single object by ID, username or arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better usage.
	 *
	 * @param   mixed   $id     Object's ID, username or array of WHERE clause.
	 * @return  bool
	 */
	public function delete($id)
	{
		// Deleting by ID?
		if (is_numeric($id))
		{
			return $this->delete_by('id', $id, 1, 0);
		}

		// Deleting by username?
		if (is_string($id))
		{
			return $this->delete_by('username', $id, 1, 0);
		}

		// Otherwise, let the "delete_by" method handle the rest.
		return $this->delete_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete multiple objects by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code and performance and to
	 *                  add optional limit and offset.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  bool
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Let's find objects first.
		$objects = $this->get_many($field, $match, $limit, $offset);

		// If no object found, nothing to do.
		if ( ! $objects)
		{
			return false;
		}

		// Let's prepare objects IDS.
		$ids = array();
		foreach ($objects as $object)
		{
			$ids[] = $object->id;
		}

		// Double check that we have IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->delete_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Completely remove a single object by ID, username or arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "remove_by" method.
	 *
	 * @param   mixed   $id     Object's ID, username or array of WHERE clause
	 * @return  bool
	 */
	public function remove($id)
	{
		// Removing by ID?
		if (is_numeric($id))
		{
			return $this->remove_by('id', $id, 1, 0);
		}

		// Removing by username?
		if (is_string($id))
		{
			return $this->remove_by('username', $id, 1, 0);
		}

		// Otherwise, let the "remove_by" method handle the rest.
		return $this->remove_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Completely remove multiple objects by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better performance and to add optional
	 *                  limit and offset.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  bool
	 */
	public function remove_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// See if objects exist.
		$objects = $this->get_many($field, $match, $limit, $offset);

		// If not objects found, nothing to do.
		if ( ! $objects)
		{
			return false;
		}

		// Collect objects IDs.
		$ids = array();
		foreach ($objects as $object)
		{
			$ids[] = $object->id;
		}

		// Double check objects IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->remove_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore a previously soft-deleted object.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "restore_by" method.
	 *
	 * @param   mixed   $id     The object's ID, username or WHERE clause.
	 * @return  bool
	 */
	public function restore($id)
	{
		// Restoring by ID?
		if (is_numeric($id))
		{
			return $this->restore_by('id', $id, 1, 0);
		}

		// Restoring by username?
		if (is_string($id))
		{
			return $this->restore_by('username', $id, 1, 0);
		}

		// Otherwise, let the "restore_by" method handle the rest.
		return $this->restore_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore multiple or all soft-deleted objects.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better performance and to add optional
	 *                  limit and offset.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  bool
	 */
	public function restore_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Collect objects.
		$objects = $this->get_many($field, $match, $limit, $offset);

		// If not objects found, nothing to do.
		if (empty($objects))
		{
			return false;
		}

		// Collect objects IDs.
		$ids = array();
		foreach ($objects as $object)
		{
			$ids[] = $object->id;
		}

		// Double check objects IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->restore_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Count all objects.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance
	 *                  and to add optional limit and offset
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  int
	 */
	public function count($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return $this->_parent
			->where($field, $match, $limit, $offset)
			->where('entities.type', 'object')
			->join('objects', 'objects.guid = entities.id')
			->count_all_results('entities');
	}

	// --------------------------------------------------------------------

	/**
	 * Builds an array of objects used for select dropdown.
	 * @access 	public
	 * @param 	mixed
	 * @param 	mixed
	 * @param 	int
	 * @param 	int
	 * @return 	array
	 */
	public function list($field = null, $match = null, $limit = 0, $offset = 0)
	{
		if ($objects = $this->get_many($field, $match, $limit, $offset))
		{
			foreach ($objects as $object)
			{
				$list[$object->id] = $object->name;
			}

			return $list;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the object user exists.
	 *
	 * @param 	mixed 	$id 	The object's ID or username.
	 * @return 	bool 	true if the object exists, else false.
	 */
	public function exists($id)
	{
		return $this->_parent->entities->exists($id, 'object');
	}

}
