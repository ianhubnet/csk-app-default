<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_activities Class
 *
 * Handles all operations done on site's activities and logs.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_activities extends KB_Driver implements CRUD_interface
{

	// --------------------------------------------------------------------
	// CRUD Interface.
	// --------------------------------------------------------------------

	/**
	 * Create a new activity log.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to make sure we are using HMVC.
	 *
	 * @param   array   $data   Array of data to insert.
	 * @return  int     The new activity ID if created, else false.
	 */
	public function create(array $data = array())
	{
		// Without $data, nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Multiple activities?
		if (isset($data[0]) && is_array($data[0]))
		{
			$ids = array();
			foreach ($data as $_data)
			{
				$ids[] = $this->create($_data);
			}

			return $ids;
		}

		// Let's complete some data.
		if ( ! isset($data['module']) && method_exists($this->ci->router, 'fetch_module'))
		{
			$data['module'] = $this->ci->router->module;
		}

		(isset($data['module'])) OR $data['module'] = 'none';
		(isset($data['controller'])) OR $data['controller'] = $this->ci->router->class;
		(isset($data['method']))     OR $data['method']     = $this->ci->router->method;
		(isset($data['created_at'])) OR $data['created_at'] = TIME;
		(isset($data['ip_address'])) OR $data['ip_address'] = ip_address();

		// Proceed to creation and return the ID.
		$this->ci->db->insert('activities', $data);
		return $this->ci->db->insert_id();
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single activity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "get_by" method.
	 *
	 * @param   int     $id     The activity's ID.
	 * @return  object if found, else null.
	 */
	public function get($id)
	{
		// Getting by id?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// Otherwise, let "get_by" method handle the rest.
		return $this->get_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve a single activity by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance.
	 *
	 * @param   mixed   $field  Column name or associative array.
	 * @param   mixed   $match  Comparison value, array or null.
	 * @return  object if found, else null.
	 */
	public function get_by($field, $match = null)
	{
		// Attempt to get the entity from database.
		$row = $this->_parent
			->where($field, $match, 1, 0)
			->order_by('id', 'DESC')
			->get('activities')
			->row();

		if ( ! $row)
		{
			return false;
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve multiple activities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance
	 *                  and of let the parent handle the WHERE clause.
	 *
	 * @param   mixed   $field  Column name or associative array.
	 * @param   mixed   $match  Comparison value, array or null.
	 * @param   int     $limit  Limit to use for getting records.
	 * @param   int     $offset Database offset.
	 * @return  array of objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to get activities from database.
		$result = $this->_parent
			->where($field, $match, $limit, $offset)
			->order_by('id', 'DESC')
			->get('activities')
			->result();

		if ( ! $result)
		{
			return false;
		}

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve all activities.
	 *
	 * @param   int     $limit  Limit to use for getting records.
	 * @param   int     $offset Database offset.
	 * @return  array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * This method is used in order to search activities table.
	 *
	 * @since   1.32
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  mixed   array of objects if found any, else false.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0)
	{
		// Attempt to find activities.
		$result = $this->_parent
			->find($field, $match, $limit, $offset)
			->order_by('id', 'DESC')
			->get('activities')
			->result();

		if ( ! $result)
		{
			return false;
		}

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single entity by it's ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better usage.
	 *
	 * @param   mixed   $id     The activity's ID or array of WHERE clause.
	 * @param   array   $data   Array of data to update.
	 * @return  bool
	 */
	public function update($id, array $data = array())
	{
		// Updating by ID?
		if (is_numeric($id))
		{
			return $this->update_by(array('id' => $id), $data);
		}

		// Otherwise, let "update_by" handle the rest.
		return $this->update_by($id, $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Update a single or multiple activities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten the let the parent handle WHERE clause.
	 *
	 * @return  bool
	 */
	public function update_by()
	{
		// Collect arguments first and make sure there are some.
		if (empty($args = func_get_args()))
		{
			return false;
		}

		// Data to set is always the last argument.
		$data = array_pop($args);
		if ( ! is_array($data) OR empty($data))
		{
			return false;
		}

		// Start updating/
		$this->ci->db->update($data);

		// If there are arguments left, use the as WHERE clause.
		if ( ! empty($args))
		{
			// Get rid of nasty deep array.
			is_array($args[0]) && $args = $args[0];

			// Let the parent generate the WHERE clause.
			$this->_parent->where($args);
		}

		// Proceed to update.
		return $this->ci->db->update('activities');
	}

	// --------------------------------------------------------------------

	/**
	 * Delete a single activity by its ID.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten to use "delete_by" method.
	 *
	 * @param   mixed   $id     The activity's ID or array of WHERE clause.
	 * @return  bool
	 */
	public function delete($id)
	{
		// Deleting by ID?
		if (is_numeric($id))
		{
			return $this->delete_by('id', $id, 1, 0);
		}

		// Otherwise, let "delete_by" handle the rest.
		return $this->delete_by($id, null, 1, 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete multiple activities by arbitrary WHERE clause.
	 *
	 * @since   1.0
	 * @since   1.30   Rewritten for better code readability and performance,
	 *                  add optional limit and offset and let the parent handle
	 *                  generating the WHERE clause.
	 *
	 * @param   mixed   $field  Column name or associative array.
	 * @param   mixed   $match  Comparison value, array or null.
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  bool    true if any records deleted, else false.
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Let's delete.
		$this->_parent
			->where($field, $match, $limit, $offset)
			->delete('activities');

		// See if there are affected rows.
		return (0 < $this->ci->db->affected_rows());
	}

	// --------------------------------------------------------------------

	/**
	 * Quick access to log activity.
	 *
	 * @param   int     $user_id
	 * @param   string  $activity
	 * @param   array   $method     used to override the method (ajax)
	 * @return  int     the activity id.
	 */
	public function log($user_id, $activity, $method = null)
	{
		if (true === $user_id)
		{
			$user_id = $this->_parent->auth->user_id();
		}
		elseif ( ! is_numeric($user_id) OR 0 >= ($user_id = (int) $user_id) OR empty($activity))
		{
			return false;
		}

		return $this->create(array(
			'user_id' => $user_id,
			'activity' => $activity,
			'method' => $method
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Count activities by arbitrary WHERE clause.
	 *
	 * @since   1.30
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  int
	 */
	public function count($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return $this->_parent->where($field, $match, $limit, $offset)->count_all_results('activities');
	}

	// --------------------------------------------------------------------

	/**
	 * Complete needed data for the given activity.
	 *
	 * @param 	$data 	Activity data objet by reference.
	 */
	public function format(&$data)
	{
		// For some reason, the user was deleted?
		if ( ! ($user = $this->_parent->users->get_by('id', $data->user_id)))
		{
			$this->_parent->entities->remove_by('id', $data->user_id);
			return;
		}

		// Add user
		$data->user = $this->_parent->users->get_by('id', $data->user_id);

		// Format output.
		if (stripos($data->activity, ':') !== false)
		{
			[$line, $param] = explode(':', $data->activity);

			$data->output = $this->ci->lang->sline(
				$line,
				admin_anchor('reports?user='.$data->user->id, $data->user->username),
				_translate($param)
			);
		}
		else
		{
			$data->output = $this->ci->lang->sline(
				$data->activity,
				admin_anchor('reports?user='.$data->user->id, $data->user->username)
			);
		}

		// IP address anchor
		if ($this->_parent->has_demo_access() OR (int) $data->user_id === $this->_parent->auth->user_id())
		{
			$data->ip_anchor = ip_anchor($data->ip_address, null, 'target="_blank"');
		}
		else
		{
			$data->ip_anchor = $this->ci->lang->line('hidden_tag');
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Adds extra dashboard menu for admins.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_reports()
	{
		// Action logs.
		echo admin_anchor('reports', $this->ci->lang->line('admin_reports'), 'class="dropdown-item"');
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
		// Add actions log menu.
		if ($this->_parent->auth->is_level(KB_LEVEL_ADMIN))
		{
			add_action('users_menu', array($this, '_menu_reports'), 99);

			$is_homepage && $reports = $this->get_all(10, 0);
		}
		// Not homepage?
		elseif ( ! $is_homepage)
		{
			return;
		}
		else
		{
			$reports = $this->get_many('user_id', $this->_parent->auth->user_id(), 10, 0);
		}

		// Add latest actions widget.
		if ( ! empty($reports))
		{
			// Load dashboard language file.
			$this->ci->lang->load('admin_core');

			// Format reports.
			foreach ($reports as &$report)
			{
				$this->format($report);
			}

			function_exists('date_formatter') OR $this->ci->load->helper('date');
			$this->_parent->theme->add_widget('reports/latest', array('reports' => $reports), 'latest-actions');
		}
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('log_activity'))
{
	/**
	 * Quick access to log activity.
	 *
	 * @param   int     $user_id
	 * @param   string  $activity
	 * @param   array   $method     used to override the method (ajax)
	 * @return  int     the activity id.
	 */
	function log_activity($user_id, $activity, $method = null)
	{
		return get_instance()->activities->log($user_id, $activity, $method);
	}
}
