<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_users Class
 *
 * Handles all operations done on users accounts.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_users extends KB_Driver
{
	/**
	 * Holds users table fields.
	 * @var array
	 */
	public $fields = array(
		'guid',
		'email',
		'password',
		'first_name',
		'last_name',
		'gender',
		'timezone',
		'online',
		'online_at',
		'check_online_at',
		'ip_address'
	);

	/**
	 * Array of database columns that can be translated.
	 * @var array
	 */
	public $i18n_fields = array(
		'first_name',
		'last_name'
	);

	/**
	 * Holds any message return by the lib.
	 * @var string
	 */
	public $message = '';

	/**
	 * Email change code variable name.
	 * @var string
	 */
	public $email_code_var_name = 'email_code';

	// --------------------------------------------------------------------

	/**
	 * Create a new user.
	 *
	 * @param   array   $data
	 * @return  int     the user's ID if created, else false.
	 */
	public function create(array $data = array())
	{
		// Nothing provided? Nothing to do.
		if (empty($data))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Always remove unwanted fields.
		unset($data[COOK_CSRF], $data['persist']);

		// Multiple users?
		if (isset($data[0]) && is_array($data[0]))
		{
			$ids = array();
			foreach ($data as $_data)
			{
				$ids[] = $this->create($_data);
			}
			return $ids;
		}

		// Always remove tokens.
		unset($data[COOK_CSRF], $data['persist']);

		// Sanitize email address.
		if (isset($data['email']) && empty($data['email'] = sanitize_email($data['email'])))
		{
			$this->message = $this->ci->lang->sline('required_field_error', $this->ci->lang->line('email_address'));
			return false;
		}

		// Split data.
		[$entity, $user, $meta] = $this->_parent->split_data($data, 'users');

		// Make sure to alwayas add the entity's type.
		$entity['type'] = 'user';

		/**
		 * Allow external defining default users roles.
		 * @since   2.12
		 */
		if ( ! isset($entity['subtype']))
		{
			$role = apply_filters('default_users_role', 'regular');
			empty($role) && $role = 'regular';
			$entity['subtype'] = $role;
		}
		// Make sure user's type is always set.

		(isset($entity['subtype'])) OR $entity['subtype'] = 'regular';

		// The user should be enabled or not?
		(isset($entity['enabled'])) OR $entity['enabled'] = 0;

		// Add the language if it's not set.
		if ( ! isset($data['language']))
		{
			$data['language'] = isset($_SESSION[SESS_LANG])
				? $_SESSION[SESS_LANG]
				: $this->_parent->lang->current('folder');
		}

		// Let's insert the entity first and make sure it's created.
		$guid = $this->_parent->entities->create($entity);
		if ( ! $guid)
		{
			$this->message = $this->ci->lang->line(array('account_create_error', 'try_again_later'));
			return false;
		}

		// Add the id to user.
		$user['guid'] = $guid;

		// Hash the password if present.
		if (isset($user['password']) && ! empty($user['password']))
		{
			(isset($this->ci->hash)) OR $this->ci->load->library('hash');
			$user['password'] = $this->ci->hash->hash_password($user['password']);
		}

		// Format first and last names.
		empty($user['email']) OR $user['email'] = strtolower($user['email']);
		empty($user['first_name']) OR $user['first_name'] = ucwords(strtolower($user['first_name']));
		empty($user['last_name']) OR $user['last_name'] = ucwords(strtolower($user['last_name']));

		// Insert the user.
		$this->ci->db->insert('users', $user);

		// Some metadata?
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

		$this->message = $this->ci->lang->line('account_create_success');
		return $guid;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single user by ID, username OR email address.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten of better readability.
	 *
	 * @param   mixed   $id     The user's ID, username or email address.
	 * @return  object if found, else null.
	 */
	public function get($id)
	{
		// Getting by ID?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// Retrieving by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			return $this->get_by('email', sanitize_email($id));
		}

		// Retrieve by username.
		if (is_string($id))
		{
			return $this->get_by('username', sanitize_username($id, true));
		}

		// Fall-back to get_by method.
		return $this->get_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single user by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to let the parent handle WHERE clause.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   bool 	$raw
	 * @return  object if found, else null.
	 */
	public function get_by($field, $match, $raw = false)
	{
		// Allow 'ID' as alias for 'id'.
		('ID' === $field) && $field = 'id';

		// Are we retrieving a user by ID?
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

		// Only accept: id, username or email.
		switch ($field) {
			case 'id':
			case 'guid':
			case 'entities.id':
			case 'users.guid':
				$guid  = $match;
				$field = 'entities.id';
				break;
			case 'username':
			case 'entities.username':
				$guid  = $this->ci->registry->get($match, 'usernames');
				$field = 'entities.username';
				break;
			case 'email':
			case 'users.email':
				$guid  = $this->ci->registry->get($match, 'emails');
				$field = 'users.email';
				break;
			case is_array($field):
				(isset($field['id'])) && $guid = (int) $field['id'];
				( ! isset($guid) && isset($field['guid'])) && $guid = (int) $field['guid'];
				( ! isset($guid) && isset($field['username'])) && $guid = $this->ci->registry->get($field['username'], 'usernames');
				break;
			default:
				return false;
		}

		// See if the user was already cached.
		if (isset($guid) && ($user = $this->ci->registry->get($guid, 'users')))
		{
			return $raw ? $user->data : $user;
		}

		// Get the user from database.
		$query = $this->ci->db
			->where('entities.type', 'user')
			->where($field, $match)
			->join('users', 'entities.id = users.guid')
			->get('entities');

		if (1 !== $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$user = new KB_User($query->row());
		$query->free_result();

		$this->ci->registry->add($user->id, $user, 'users');
		$this->ci->registry->add($user->username, $user->id, 'usernames');
		empty($user->email) OR $this->ci->registry->add($user->email, $user->id, 'emails');

		return $raw ? $user->data : $user;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple users by arbitrary WHERE clause.
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
		// Attempt to retrieve users from database.
		$query = $this->_parent
			->where($field, $match, $limit, $offset)
			->where('entities.type', 'user')
			->join('users', 'users.guid = entities.id')
			->get('entities');

		if (0 >= $query->num_rows())
		{
			$query->free_result();
			return false;
		}

		$users = array();
		foreach ($query->result() as $row)
		{
			$users[] = ($user = $this->ci->registry->get($row->id, 'users')) ? $user : new KB_User($row);
		}

		$query->free_result();

		return $users;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all users with optional limit and offset.
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
	 * Update a single user.
	 *
	 * @param   int     $user_id
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
		[$entity, $user, $meta] = $this->_parent->split_data($data, 'users');

		// Update entity.
		if ( ! empty($entity) && ! $this->_parent->entities->update($id, $entity))
		{
			return false;
		}

		// Are there any changes to do to "users" table?
		if ( ! empty($user))
		{
			/**
			 * Filters to apply on user's field before proceeding.
			 * @since   2.0
			 */
			foreach ($user as $key => &$val) {
				// Global fields.
				if (has_filter("edit_{$key}")) {
					$var = apply_filters("edit_{$key}", $val, $key);
				}

				// "users" table related fields.
				if (has_filter("edit_user_{$key}")) {
					$var = apply_filters("edit_user_{$key}", $val, $key);
				}

				// Format stuff
				if ('email' === $key) {
					$val = strtolower($val);
				} elseif ('first_name' === $key OR 'last_name' === $key) {
					$val = ucwords(strtolower($val));
				}
			}

			// Hash the password if present.
			if (isset($user['password']) && ! empty($user['password']))
			{
				(isset($this->ci->hash)) OR $this->ci->load->library('hash');
				$user['password'] = $this->ci->hash->hash_password($user['password']);
			}

			if ( ! $this->ci->db->update('users', $user, array('guid' => $id)))
			{
				return false;
			}
		}

		// Are there any metadata to update?
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
	 * Update all or multiple users by arbitrary WHERE clause.
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

		// Get users
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$users = $this->get_many($args);
		}
		else
		{
			$users = $this->get_all();
		}

		// If there are any users, proceed to update.
		if ($users)
		{
			// Always remove unwanted fields.
			unset($data[COOK_CSRF], $data['persist']);

			foreach ($users as $user)
			{
				$user->update($data);
			}

			return true;
		}

		// Nothing happened, return false.
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single user by ID, username or email address.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better usage.
	 * @since 	2.15 	Added bulk delete.
	 *
	 *  @param  mixed   $id     User's ID, username, email address or array of WHERE clause.
	 * @return  bool
	 */
	public function delete($id, $bulk = false)
	{
		// Bulk action?
		if (true === $bulk)
		{
			is_array($id) OR $id = explode(',', $id);

			if (in_array($user_id = $this->_parent->auth->user_id(), $id))
			{
				unset($id[array_search($user_id, $id)], $user_id);
			}

			if (empty($id))
			{
				return false;
			}

			$this->ci->db
				->where('type', 'user')
				->where_in('id', $id)
				->set('deleted',  -1)
				->set('deleted_at', TIME)
				->update('entities');

			return ($this->ci->db->affected_rows() > 0);
		}

		// Deleting by ID?
		if (is_numeric($id))
		{
			return ($id === $this->_parent->auth->user_id()) ? false : $this->delete_by('id', $id, 1, 0);
		}

		// Deleting by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->email)
			{
				unset($user);
				return false;
			}

			return $this->delete_by('email', $id, 1, 0);
		}

		// Deleting by username?
		if (is_string($id))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->username)
			{
				unset($user);
				return false;
			}

			return $this->delete_by('username', $id, 1, 0);
		}

		// Otherwise, let the "delete_by" method handle the rest.
		return $this->delete_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete multiple users by arbitrary WHERE clause.
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
		// Let's find users first.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If no user found, nothing to do.
		if ( ! $users)
		{
			return false;
		}

		// Let's prepare users IDS.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
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
	 * Completely remove a single user by ID, username or email address.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "remove_by" method.
	 * @since   2.16 	Added bulk remove.
	 *
	 * @param   mixed   $id     User's ID, username, email address or array of WHERE clause
	 * @return  bool
	 */
	public function remove($id, $bulk = false)
	{
		// Bulk remove?
		if (true === $bulk)
		{
			is_array($id) OR $id = explode(',', $id);

			if (in_array($user_id = $this->_parent->auth->user_id(), $id))
			{
				unset($id[array_search($user_id, $id)], $user_id);
			}

			return empty($id) ? false : $this->remove_by('id', $id);
		}

		// Removing by ID?
		if (is_numeric($id))
		{
			return ($id === $this->_parent->auth->user_id()) ? false : $this->remove_by('id', $id, 1, 0);
		}

		// Removing by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->email)
			{
				unset($user);
				return false;
			}

			return $this->remove_by('email', $id, 1, 0);
		}

		// Removing by username?
		if (is_string($id))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->username)
			{
				unset($user);
				return false;
			}

			return $this->remove_by('username', $id, 1, 0);
		}

		// Otherwise, let the "remove_by" method handle the rest.
		return $this->remove_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Completely remove multiple users by arbitrary WHERE clause.
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
		// See if users exist.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If not users found, nothing to do.
		if ( ! $users)
		{
			return false;
		}

		// Collect users IDs.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check users IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->remove_by('id', $ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore a previously soft-deleted user.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "restore_by" method.
	 * @since   2.16 	Added bulk restore.
	 *
	 * @param   mixed   $id     The user's ID, username, email address or WHERE clause.
	 * @return  bool
	 */
	public function restore($id, $bulk = false)
	{
		// Bulk restore?
		if (true === $bulk)
		{
			is_array($id) OR $id = explode(',', $id);

			if (in_array($user_id = $this->_parent->auth->user_id(), $id))
			{
				unset($id[array_search($user_id, $id)], $user_id);
			}

			return empty($id) ? false : $this->restore_by('id', $id);
		}

		// Restoring by ID?
		if (is_numeric($id))
		{
			return ($id === $this->_parent->auth->user_id()) ? false : $this->restore_by('id', $id, 1, 0);
		}

		// Restoring by email address?
		if (false !== filter_var($id, FILTER_VALIDATE_EMAIL))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->email)
			{
				unset($user);
				return false;
			}

			return $this->restore_by('email', $id, 1, 0);
		}

		// Restoring by username?
		if (is_string($id))
		{
			if (($user = $this->_parent->auth->user()) && $id === $user->username)
			{
				unset($user);
				return false;
			}

			return $this->restore_by('username', $id, 1, 0);
		}

		// Otherwise, let the "restore_by" method handle the rest.
		return $this->restore_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Restore multiple or all soft-deleted users.
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
		// Collect users.
		$users = $this->get_many($field, $match, $limit, $offset);

		// If not users found, nothing to do.
		if (empty($users))
		{
			return false;
		}

		// Collect users IDs.
		$ids = array();
		foreach ($users as $user)
		{
			$ids[] = $user->id;
		}

		// Double check users IDs.
		if (empty($ids))
		{
			return false;
		}

		return $this->_parent->entities->restore_by('id', $ids);
	}

	// --------------------------------------------------------------------
	// Front-end methods.
	// --------------------------------------------------------------------

	/**
	 * prep_email_code
	 *
	 * Method for preparing account for email change.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.71
	 *
	 * @param   int
	 * @param   string
	 * @return  bool
	 */
	public function prep_email_code($user_id = 0, $email = null)
	{
		// $email is empty?
		if (empty($email))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Get user from database and make sure (s)he exists.
		if ($user_id instanceof KB_User)
		{
			$user = $user_id;
			$user_id = $user->id;
		}
		elseif (false === ($user = $this->get_by('id', $user_id)))
		{
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}

		// Make sure the account is not banned.
		if ($user->enabled < 0)
		{
			$this->message = $this->ci->lang->line('account_banned_error');
			return false;
		}

		function_exists('random_string') OR $this->ci->load->helper('string');
		$code = random_string('alnum', 40);

		$status = $this->ci->db->save(
			'variables',
			array(
				'guid' => $user_id,
				'name' => $this->email_code_var_name,
				'value' => $code,
				'params' => $email,
				'created_at' => TIME,
				'updated_at' => TIME
			),
			array(
				'value' => $code,
				'params' => $email,
				'updated_at' => TIME
			)
		);

		if ($status)
		{
			// TODO: log the activity.

			$this->message = $this->ci->lang->line('email_change_ready');

			// Email user
			$this->_parent->mail_user(
				$user,
				$this->ci->lang->line('mail_email_change_request'),
				'view:emails/users/email_prep',
				array(
					'link' => anchor('process-change-email/'.$code),
					'ip_address' => ip_address()
				)
			);

			return true;
		}

		$this->message = $this->ci->lang->line('email_change_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * process_email_code
	 *
	 * Method for checking the provided email change code.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.71
	 *
	 * @param   string  $code   The email change reset code.
	 * @return  mixed   Return the user's ID if found, else false.
	 */
	public function process_email_code($code = null)
	{
		if (empty($code) OR 40 !== strlen($code))
		{
			return false;
		}

		// Attempt to gte the variable from database.
		$query = $this->ci->db
			->where('name', $this->email_code_var_name)
			->where('BINARY(value)', $code)
			->get('variables');

		if (1 !== $query->num_rows())
		{
			return false;
		}

		$var = $query->row();
		$query->free_result();


		// Expired? Delete it first.
		if ($var->updated_at < TIME - (DAY_IN_SECONDS * 2))
		{
			$this->ci->db->delete('variables', array('id' => $var->id));
			return false;
		}

		// Attempt to get the user.
		if (false === ($user = $this->get_by('id', $var->guid)) OR true !== $user->update('email', $var->params))
		{
			return false;
		}

		// TODO: log the activity.

		// Delete email change code
		$this->ci->db->delete('variables', array('id' => $var->id));

		// Email user
		$this->_parent->mail_user(
			$user,
			$this->ci->lang->line('mail_email_changed'),
			'view:emails/users/email',
			array('ip_address' => ip_address())
		);

		return true;
	}

	// --------------------------------------------------------------------
	// Utilities.
	// --------------------------------------------------------------------

	/**
	 * Count all users.
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
			->where('entities.type', 'user')
			->join('users', 'users.guid = entities.id')
			->count_all_results('entities');
	}

	// --------------------------------------------------------------------

	/**
	 * Activates user(s)
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	boool
	 */
	public function enable($id, $bulk = false)
	{
		if (is_numeric($id) && (int) $id === $this->_parent->auth->user_id())
		{
			return false;
		}

		// Prepare WHERE clause.
		$where = array('id' => $id, 'enabled !=' => 1);

		// Bulk ban?
		if (true === $bulk)
		{
			is_array($where['id']) OR $where['id'] = explode(',', $where['id']);

			// Remove user's own ID.
			if (in_array($user_id = $this->_parent->auth->user_id(), $where['id']))
			{
				unset($where['id'][array_search($user_id, $where['id'])], $user_id);
			}
		}

		return $this->update_by($where, array('enabled' => 1));
	}

	// --------------------------------------------------------------------

	/**
	 * Deactivates user(s)
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	boool
	 */
	public function disable($id, $bulk = false)
	{
		if (is_numeric($id) && (int) $id === $this->_parent->auth->user_id())
		{
			return false;
		}

		// Prepare WHERE clause.
		$where = array('id' => $id, 'enabled !=' => 0);

		// Bulk ban?
		if (true === $bulk)
		{
			is_array($where['id']) OR $where['id'] = explode(',', $where['id']);

			// Remove user's own ID.
			if (in_array($user_id = $this->_parent->auth->user_id(), $where['id']))
			{
				unset($where['id'][array_search($user_id, $where['id'])], $user_id);
			}
		}

		return $this->update_by($where, array('enabled' => 0));
	}

	// --------------------------------------------------------------------

	/**
	 * Bans user(s)
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	boool
	 */
	public function ban($id, $bulk = false)
	{
		if (is_numeric($id) && (int) $id === $this->_parent->auth->user_id())
		{
			return false;
		}

		// Prepare WHERE clause.
		$where = array('id' => $id, 'enabled !=' => -1);

		// Bulk ban?
		if (true === $bulk)
		{
			is_array($where['id']) OR $where['id'] = explode(',', $where['id']);

			// Remove user's own ID.
			if (in_array($user_id = $this->_parent->auth->user_id(), $where['id']))
			{
				unset($where['id'][array_search($user_id, $where['id'])]);

			}
		}

		return $this->update_by($where, array('enabled' => -1));
	}

	// --------------------------------------------------------------------

	/**
	 * Unbans user(s)
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	boool
	 */
	public function unban($id, $bulk = false)
	{
		if (is_numeric($id) && (int) $id === $this->_parent->auth->user_id())
		{
			return false;
		}

		// Prepare WHERE clause.
		$where = array('id' => $id, 'enabled' => -1);

		// Bulk unban?
		if (true === $bulk)
		{
			is_array($where['id']) OR $where['id'] = explode(',', $where['id']);

			// Remove user's own ID.
			if (in_array($user_id = $this->_parent->auth->user_id(), $where['id']))
			{
				unset($where['id'][array_search($user_id, $where['id'])], $user_id);
			}
		}

		return $this->update_by($where, array('enabled' => 0));
	}

	// --------------------------------------------------------------------

	/**
	 * Builds an array of users used for select dropdown.
	 * @access 	public
	 * @param 	mixed
	 * @param 	mixed
	 * @param 	int
	 * @param 	int
	 * @return 	array
	 */
	public function list($field = null, $match = null, $limit = 0, $offset = 0, $line = null)
	{
		if ($users = $this->get_many($field, $match, $limit, $offset))
		{
			$list[0] = empty($line) ? $this->ci->lang->line('select_user') : $line;

			foreach ($users as $user)
			{
				$list[$user->id] = $user->full_name;
			}

			return $list;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds users dashboard menu for managers.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_users()
	{
		// Manage users.
		echo admin_anchor('users', $this->ci->lang->line('admin_users_manage'), 'class="dropdown-item"');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds extra dashboard menu for admins.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_mailer()
	{
		// Mass mailer.
		echo admin_anchor('users/mail', $this->ci->lang->line('admin_users_mailer'), 'class="dropdown-item"');
	}

	// --------------------------------------------------------------------

	/**
	 * Counts users and displays the info box on the dashboard index.
	 * @since 	2.54
	 *
	 * @return 	void
	 */
	public function _stats_admin()
	{
		$count = $this->ci->db
			->where('entities.type', 'user')
			->join('users', 'entities.id = users.guid')
			->count_all_results('entities');

		echo info_box(
			$count, $this->ci->lang->line('admin_users'),
			'users', $this->ci->config->admin_url('users'),
			'green', 'div', 'class="col"'
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Displays menus and other stuff on dashboard.
	 * @since 	2.53
	 *
	 * @param 	bool 	$is_homepage 	Whether we are on dashboard index.
	 * @return 	void
	 */
	public function for_dashboard($is_homepage = false)
	{
		// Menu item displayed for managers+.
		if ($this->_parent->auth->is_level(KB_LEVEL_MANAGER))
		{
			add_action('users_menu', array($this, '_menu_users'), 98);

			if ($is_homepage && ! empty($logged_in = $this->get_many('online', 1)))
			{
				$this->_parent->theme->add_widget('users/online', array('users' => $logged_in), 'logged-in');
			}
		}
		else
		{
			return; // no need to go further.
		}

		// Menus items and stats displayed for admins+.
		if ($this->_parent->auth->is_level(KB_LEVEL_ADMIN))
		{
			add_action('users_menu', array($this, '_menu_mailer'), 100);
			$is_homepage && add_action('admin_index_stats', array($this, '_stats_admin'), 0);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given user exists.
	 *
	 * @param 	mixed 	$id 	The user's ID, username or email.
	 * @return 	bool 	true if the user exists, else false.
	 */
	public function exists($id)
	{
		return $this->_parent->entities->exists($id, 'user');
	}

}
