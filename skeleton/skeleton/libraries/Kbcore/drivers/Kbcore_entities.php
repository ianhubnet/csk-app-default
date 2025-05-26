<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_entities Class
 *
 * Handles all operations done on entities which are users, groups and objects.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_entities extends KB_Driver
{
	/**
	 * Holds entities table fields.
	 * @var array
	 */
	public $fields = array(
		'id',
		'parent_id',
		'owner_id',
		'type',
		'subtype',
		'username',
		'language',
		'privacy',
		'enabled',
		'deleted',
		'created_at',
		'updated_at',
		'deleted_at'
	);

	/**
	 * Valid entity types.
	 * @var array
	 */
	private $entity_types = array('user', 'group', 'object');

	// --------------------------------------------------------------------
	// Create Entities.
	// --------------------------------------------------------------------

	/**
	 * Create a new entity.
	 * @access  public
	 * @param   array   $data   Array of data to insert.
	 * @return  int     The entity's ID.
	 */
	public function create(array $data = array())
	{
		/**
		 * There are several things we need to check:
		 * 1. $data is never empty.
		 * 2. Entity's type is one of the allowed types.
		 * 3. Entity's subtype is always provided.
		 * 4. The entity username is available.
		 */
		if (empty($data) OR // There are $data
			! isset($data['type']) OR // The type is set
			! in_array($data['type'], $this->entity_types) OR // type is valid.
			! isset($data['subtype'])) // Subtype is set.
		{
			return false;
		}

		// Make sure to always have a language.
		isset($data['language']) OR $data['language'] = $this->ci->lang->fallback;

		// Sanitize and check for duplicates.
		isset($data['username']) && $data['username'] = $this->_check_slug($data['username'], $data['type']);

		// Add date of creation.
		if ( ! isset($data['created_at']))
		{
			$data['created_at'] = isset($data['updated_at']) ? $data['updated_at'] : TIME;
		}

		// Update date is the same as the creation date.
		isset($data['updated_at']) OR $data['updated_at'] = $data['created_at'];

		// Proceed to insert.
		$this->ci->db->insert('entities', $data);
		return $this->ci->db->insert_id();
	}

	// --------------------------------------------------------------------

	/**
	 * Checks the existing of the given slug and increments if needed.
	 *
	 * @param 	string 	$slug 	the given slug (or username).
	 * @param 	string 	$type 	the entity type (user, group, object)
	 * @param 	int 	$count
	 * @return 	string
	 */
	private function _check_slug($slug, $type, $count = 0)
	{
		// First time? URL title the slug.
		if ($count <= 0)
		{
			$slug = url_title(sanitize_username($slug, true), '-', true);
			$new_slug = $slug;
		}
		else
		{
			$new_slug = ($type === 'user') ? $slug.$count : $slug.'-'.$count;
		}

		$this->ci->db->select('username')->where('username', $new_slug);

		return ($this->ci->db->count_all_results('entities') > 0)
			? $this->_check_slug($slug, $type, ++$count)
			: $new_slug;
	}

	// --------------------------------------------------------------------
	// Getters.
	// --------------------------------------------------------------------

	/**
	 * Retrieve a single entity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Method rewriting for larger use.
	 *
	 * @access  public
	 * @param   mixed   $id     The entity's ID or username.
	 * @return  object if found, else null.
	 */
	public function get($id)
	{
		// If getting the entity by ID.
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// In case of retrieving it with username.
		if (is_string($id))
		{
			return $this->get_by('username', $id);
		}

		// Otherwise, let the "get_by" method handle the rest
		return $this->get_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single entity by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   The method was rewritten to let the parent handle
	 *                  the WHERE clause and return an database object.
	 *
	 * @access  public
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @return  object if a single row if found, else null.
	 */
	public function get_by($field, $match = null)
	{
		// Attempt to get the entity from database.
		$entity = $this->_parent
			->where($field, $match, 1, 0)
			->order_by('id', 'DESC')
			->get('entities')
			->row();

		return $entity ? $entity : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple entities by arbitrary WHERE clause.
	 * @access  public
	 * @param   mixed   $field  Column name or array.
	 * @param   mixed   $match  Comparison value or null.
	 * @param   int     $limit  Limit of rows to retrieve.
	 * @param   int     $offset MySQL offset.
	 * @return  array of objects if found, else null
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to get entities from database.
		$entities = $this->_parent
			->where($field, $match, $limit, $offset)
			->get('entities')
			->result();

		return $entities ? $entities : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all entities from table.
	 * @access  public
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  array of objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * This method is used in order to search entities table.
	 *
	 * @since   1.32
	 *
	 * @access  public
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  mixed   array of objects if found any, else false.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to find entities.
		$entities = $this->_parent
			->find($field, $match, $limit, $offset)
			->get('entities')
			->result();

		return $entities ? $entities : false;
	}

	// --------------------------------------------------------------------
	// Update Entities.
	// --------------------------------------------------------------------

	/**
	 * Update a single entity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten.
	 * @access  public
	 * @param   mixed   $id     The entity's ID (or username).
	 * @param   array   $data   The array of data to update.
	 * @return  boolean
	 */
	public function update($id, array $data = array())
	{
		// Updating by id?
		if (is_numeric($id)) {
			return $this->update_by(array('id' => $id), $data);
		}

		// Updating by username?
		if (is_string($id)) {
			return $this->update_by(array('username' => $id), $data);
		}

		// Otherwise, let the "get_by" handle it.
		return $this->update_by($id, $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single, all or multiple entities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle WHERE clause.
	 *
	 * @access  public
	 * @return  boolean
	 */
	public function update_by()
	{
		// Collect arguments and make sure there are some.
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

		// Always add the update data.
		(isset($data['updated_at'])) OR $data['updated_at'] = TIME;

		/**
		 * We make sure the username is always URL-titled and proceed
		 * only if it is not taken.
		 * @since   1.5.0
		 */
		if (isset($data['username']))
		{
			$data['username'] = url_title(sanitize_username($data['username'], true), '-', true);
			if ($this->get_by('username', $data['username']))
			{
				return false;
			}
		}

		// Prepare out update statement.
		$this->ci->db->set($data);

		// Are there where conditions?
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];

			/**
			 * We let the parent generate the WHERE clause.
			 *
			 * @since   1.30
			 */
			$this->_parent->where($args);
		}

		// Proceed to update.
		return $this->ci->db->update('entities');
	}

	// --------------------------------------------------------------------
	// Delete Entities.
	// --------------------------------------------------------------------

	/**
	 * Soft delete a single entity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten.
	 * @access  public
	 * @param   mixed   $id     The entity's ID or username.
	 * @return  boolean
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

		// Otherwise, we let the "delete_by" handle the rest.
		return $this->delete_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single, all or multiple entities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle the WHERE clause and
	 *                  we added the optional limit and offset.
	 *
	 * @access  public
	 * @param   mixed   $field  This is required to avoid deleting all.
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  boolean
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Did we provide a limit?
		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		// Let's prepare the WHERE clause.
		(is_array($field)) OR $field = array($field => $match);

		// We make sure to target only undeleted entities.
		$field['deleted'] = 0;

		// Now we use the "update_by" method.
		return $this->update_by($field, array(
			'deleted'    => 1,
			'deleted_at' => TIME,
		));
	}

	// --------------------------------------------------------------------
	// Remove Entities.
	// --------------------------------------------------------------------

	/**
	 * Completely remove a single entity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "remove_by" method.
	 *
	 * @access  public
	 * @param   mixed   $id     The entity's ID or username.
	 * @return  boolean
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

		// Otherwise, we let the "remove_by" method do the rest.
		return $this->remove_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Complete remove a single, all or multiple entities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle the WHERE clause and
	 *                  we added optional limit and offset.
	 *
	 * @access  public
	 * @param   mixed   $field  This is required to avoid deleting all.
	 * @param   mixed   $match
	 * @param   int 	$limit
	 * @param   int 	$offset
	 * @return  boolean
	 */
	public function remove_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Let's first find entities.
		$entities = $this->get_many($field, $match, $limit, $offset);

		// If no entity is found, nothing to do.
		if ( ! $entities)
		{
			return false;
		}

		// Let's delete them one by one.
		foreach ($entities as $e)
		{
			/**
			 * To avoid deleting things if a row is not deleted, we track
			 * the delete status and continue if it's successful.
			 */
			$status = false;

			/**
			 * NOTE:
			 * Because users, groups and objects have their delete methods
			 * calling this class method, if we call them, they will get
			 * soft deleted only. To override this, we simply use CodeIgniter
			 * to delete them from database.
			 */
			switch ($e->type)
			{
				/**
				 * In case of deleting a user, we make sure to remove data
				 * from "users" table and all user's "activities" table data.
				 */
				case 'user':
					// Now we remove the user from table.
					$status = $this->ci->db->where('guid', $e->id)->delete('users');
					break;

				/**
				 * In case of deleting a group or an object, we simply delete
				 * them from their respective tables, unless, you implement
				 * activities for groups or objects.
				 */
				case 'group':
					$status = $this->ci->db->where('guid', $e->id)->delete('groups');
					break;

				case 'object':
					// A file?
					if ($e->subtype === 'file')
					{
						$info = $this->ci->db->get_where('metadata', array(
							'guid' => $e->id,
							'name' => 'metadata'
						), 1)->row();;

						empty($info) OR $info = from_bool_or_serialize($info->value);

						if ( ! empty($info) && isset($info['file']))
						{
							$info = pathinfo($info['file']);
							$filename = $this->ci->config->uploads_path($info['dirname'].'/'.$info['filename']);
						}
					}

					$status = $this->ci->db->where('guid', $e->id)->delete('objects');
					break;
			}

			/**
			 * Now we check if the process was successful. If it was, we proceed
			 * with deleting everything related to the entity.
			 */
			if ($status)
			{
				// Deleted a file?
				if (isset($filename))
				{
					@array_map('unlink', glob($filename.'*.*'));
					unset($filename); // free memory
				}

				// We delete entities having this one as parent or owner.
				$this->remove_by('parent_id', $e->id);
				$this->remove_by('owner_id', $e->id);

				// We remove all entity's metadata and variables.
				$this->ci->db->where('user_id', $e->id)->delete('activities');
				$this->ci->db->where('guid', $e->id)->delete('metadata');
				$this->ci->db->where('guid', $e->id)->delete('variables');

				// And finally, we remove the entity from "entities" table.
				$this->ci->db->where('id', $e->id)->delete('entities');

				// Remove redirections.
				if ( ! empty($e->username)
					&& ! empty($redirects = $this->ci->config->item('redirects'))
					&& ($key = array_search($e->username, $redirects)))
				{
					unset($redirects[$key]);
					$this->_parent->options->set_item('redirects', $redirects);
				}

				// Now we remove all entity's relations.
				$this->ci->db
					->where('guid_from', $e->id)
					->or_where('guid_to', $e->id)
					->delete('relations');
			}
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Restore a previously soft deleted entity by ID or username.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "restore_by" method.
	 *
	 * @access  public
	 * @param   mixed   $id     The entity's ID or username.
	 * @return  boolean
	 */
	public function restore($id)
	{
		// Restoring by ID?
		if (is_numeric($id))
		{
			return $this->restore_by('id', $id);
		}

		// Restoring by username?
		if (is_string($id))
		{
			return $this->restore_by('username', $id);
		}

		// Otherwise, let the restore by handle the WHERE clause.
		return $this->restore_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore multiple entities previously soft deleted by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle the WHERE clause and
	 *                  we added optional limit and offset.
	 *
	 * @access  public
	 * @param   mixed   $field
	 * @param   mixed   $param
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  boolean
	 */
	public function restore_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Use the limit if provided.
		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		// Let's prepare the WHERE clause.
		(is_array($field)) OR $field = array($field => $match);

		// We make sure to target only deleted entities.
		$field['deleted >'] = 0;

		// Let's now update entities.
		return $this->update_by($field, array(
			'deleted'    => 0,
			'deleted_at' => 0,
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of stored entities.
	 *
	 * @since   1.30
	 *
	 * @access  public
	 * @param   mixed   $field
	 * @param   mixed   $param
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  array
	 */
	public function get_ids($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Let's retrieve entities from database.
		$this->ci->db->select('id');
		$entities = $this->get_many($field, $match, $limit, $offset);

		// If we found any, we fill $ids array.
		if ($entities)
		{
			$ids = array();

			foreach ($entities as $e)
			{
				$ids[] = (int) $e->id;
			}

			return $ids;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given entity exists, given its ID, username or email
	 * address in case of a user.
	 *
	 * @param 	mixed 	$id 	The entity's ID, username or user's email.
	 * @param 	string 	$type 	The entity's type.
	 * @return 	bool 	True if the entity exists, else false.
	 */
	public function exists($id, $type = 'user')
	{
		// Undefined type?
		if (empty($type = strtolower($type)) OR ! in_array($type, $this->entity_types))
		{
			return false;
		}
		elseif (is_numeric($id))
		{
			if (0 >= $id = (int) $id)
			{
				return false;
			}

			$query = $this->ci->db->get('entities', array('id' => $id, 'type' => $type), 1, 0);

			return ($query->num_rows() === 1);
		}

		// Try with username.
		$query = $this->ci->db->get('entities', array('type' => $type, 'username' => $id), 1, 0);
		if ($query->num_rows() === 1)
		{
			$query->free_result();
			return true;
		}

		if ('user' !== $type)
		{
			return false;
		}

		$query->free_result();
		$query = $this->ci->db
			->where('entities.type', 'user')
			->where('users.email', $id)
			->join('users', 'entities.id = users.guid')
			->limit(1, 0)
			->get('entities');

		return ($query->num_rows()) === 1;
	}

}
