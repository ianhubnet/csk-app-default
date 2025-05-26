<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_groups Class
 *
 * Handles operations done on any thing tagged as a group.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_groups extends KB_Driver implements CRUD_interface
{
	/**
	 * Holds groups table fields.
	 * @var array
	 */
	public $fields = array(
		'guid',
		'name',
		'description'
	);

	/**
	 * Array of database columns that can be translated.
	 * @var array
	 */
	public $i18n_fields = array(
		'name',
		'description',
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
	 * Create a new group
	 *
	 * @param   array   $data
	 * @return  int     the group's ID if created, else false.
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
		[$entity, $group, $meta] = $this->_parent->split_data($data, 'groups');

		// Make sure to alwayas add the entity's type.
		$entity['type'] = 'group';

		// Let's insert the entity first and make sure it's created.
		$guid = $this->_parent->entities->create($entity);
		if ( ! $guid)
		{
			return false;
		}

		// Add the id to group.
		$group['guid'] = $guid;

		// Insert the group.
		$this->ci->db->insert('groups', $group);

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
	 * Retrieve a single group by ID or username.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance.
	 * @since 	2.20 	Only accept ID or username.
	 *
	 * @param   mixed   $id     The group's ID or username.
	 * @return  object if found, else null.
	 */
	public function get($id)
	{
		return $this->get_by(is_numeric($id) ? 'id' : 'username', $id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single group by arbitrary WHERE clause.
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

		// Are we retrieving a group by ID?
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
			case 'groups.guid':
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
			default:
				return false;
		}

		// See if the group was already cached.
		if (isset($guid) && ($group = $this->ci->registry->get($guid, 'groups')))
		{
			return $raw ? $group->data : $group;
		}

		// Get the group from database.
		$query = $this->ci->db
			->where('entities.type', 'group')
			->where($field, $match)
			->join('groups', 'entities.id = groups.guid')
			->order_by('entities.id', 'DESC')
			->limit(1, 0)
			->get('entities');

		if (1 !== $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$group = new KB_Group($query->row());
		$query->free_result();

		$this->ci->registry->add($group->id, $group, 'groups');
		$this->ci->registry->add($group->username, $group->id, 'usernames');

		return $raw ? $group->data : $group;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple groups by arbitrary WHERE clause.
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
		// Attempt to retrieve groups from database.
		$query = $this->_parent
			->where($field, $match, $limit, $offset)
			->where('entities.type', 'group')
			->join('groups', 'groups.guid = entities.id')
			->get('entities');

		if (0 >= $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$groups = array();
		foreach ($query->result() as $row)
		{
			$groups[] = ($group = $this->ci->registry->get($row->id, 'groups')) ? $group : new KB_Group($row);
		}

		$query->free_result();

		return $groups;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all groups with optional limit and offset
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
	 * Update a single group.
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
		[$entity, $group, $meta] = $this->_parent->split_data($data, 'groups');

		// Update entity.
		if ( ! empty($entity) && ! $this->_parent->entities->update($id, $entity))
		{
			return false;
		}

		// Update groups table.
		if ( ! empty($group)
			&& ! $this->ci->db->update('groups', $group, array('guid' => $id)))
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
	 * Update all or multiple groups by arbitrary WHERE clause
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

		// Get groups
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$groups = $this->get_many($args);
		}
		else
		{
			$groups = $this->get_all();
		}

		// If there are any groups, proceed to update.
		if ($groups)
		{
			// Always remove unwanted fields.
			unset($data[COOK_CSRF], $data['persist']);

			foreach ($groups as $group)
			{
				$group->update($data);
			}

			return true;
		}

		// Nothing happened, return false.
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single group by ID, username or arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better usage.
	 *
	 * @param   mixed   $id     Group's ID, username or array of WHERE clause.
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
	 * Delete multiple groups by arbitrary WHERE clause.
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
		// Let's find group first.
		$groups = $this->get_many($field, $match, $limit, $offset);

		// If no group found, nothing to do.
		if ( ! $groups)
		{
			return false;
		}

		// Let's prepare groups IDS.
		$ids = array();
		foreach ($groups as $group)
		{
			$ids[] = $group->id;
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
	 * Completely remove a single group by ID, username or arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "remove_by" method.
	 *
	 * @param   mixed   $id     Group's ID, username or array of WHERE clause
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
	 * Completely remove multiple groups by arbitrary WHERE clause.
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
		// See if groups exist.
		$groups = $this->get_many($field, $match, $limit, $offset);

		// If not groups found, nothing to do.
		if ( ! $groups)
		{
			return false;
		}

		// Collect groups IDs.
		$ids = array();
		foreach ($groups as $group)
		{
			$ids[] = $group->id;
		}

		// Double check groups IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->remove_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore a previously soft-deleted group.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "restore_by" method.
	 *
	 * @param   mixed   $id     The group's ID, username or WHERE clause.
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
		return $this->restore_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore multiple or all soft-deleted groups.
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
		// Collect groups.
		$groups = $this->get_many($field, $match, $limit, $offset);

		// If not groups found, nothing to do.
		if (empty($groups))
		{
			return false;
		}

		// Collect groups IDs.
		$ids = array();
		foreach ($groups as $group)
		{
			$ids[] = $group->id;
		}

		// Double check groups IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->restore_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Count all groups.
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
			->where('entities.type', 'group')
			->join('groups', 'groups.guid = entities.id')
			->count_all_results('entities');
	}

	// --------------------------------------------------------------------

	/**
	 * Builds an array of groups used for select dropdown.
	 * @access 	public
	 * @param 	mixed
	 * @param 	mixed
	 * @param 	int
	 * @param 	int
	 * @return 	array
	 */
	public function list($field = null, $match = null, $limit = 0, $offset = 0)
	{
		if ($groups = $this->get_many($field, $match, $limit, $offset))
		{
			foreach ($groups as $group)
			{
				$list[$group->id] = $group->name;
			}

			return $list;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the group user exists.
	 *
	 * @param 	mixed 	$id 	The group's ID or username.
	 * @return 	bool 	true if the group exists, else false.
	 */
	public function exists($id)
	{
		return $this->_parent->entities->exists($id, 'group');
	}

}
