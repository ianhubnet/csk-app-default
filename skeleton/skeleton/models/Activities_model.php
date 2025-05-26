<?php
defined('BASEPATH') OR die;

/**
 * Activities_model Class
 *
 * Handles all operations done with and to 'activities' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Activities_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'activities';

	/**
	 * Array of callbacks to use before create.
	 * @var array
	 */
	protected $before_create = array('prepare_input');

	/**
	 * Array of callbacks to use after get.
	 * @var array
	 */
	protected $after_get = array('prepare_numeric');

	/**
	 * Relationship.
	 * @var array
	 */
	protected $belongs_to = array(
		'user' => array(
			'model' => 'users_model',
			'primary_key' => 'user_id'
		)
	);

	/**
	 * Datetime format.
	 * @var string
	 */
	protected $datetime_format = 'timestamp';

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Makes sure Users_model is loaded.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		isset($this->users_model) OR $this->load->model('users_model');
	}

	// --------------------------------------------------------------------

	/**
	 * Complete data before creation.
	 *
	 * @param 	array 	$data
	 * @return 	array
	 */
	public function prepare_input($data)
	{
		isset($data['module'])     OR $data['module']     = empty($this->router->module) ? 'none' : $this->router->module;
		isset($data['controller']) OR $data['controller'] = $this->router->class;
		isset($data['method'])     OR $data['method']     = $this->router->method;
		isset($data['created_at']) OR $data['created_at'] = $this->datetime();
		isset($data['ip_address']) OR $data['ip_address'] = ip_address();

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Formats data
	 *
	 * @param 	mixed 	$row
	 * @return 	mixed
	 */
	public function format(&$row)
	{
		if (is_object($row))
		{
			isset($row->user) OR $row->user = $this->users_model->get($row->user_id);
			$this->prep_output($row, true);
		}
		elseif (is_array($row))
		{
			isset($row['user']) OR $row['user'] = $this->users_model->get($row['user_id']);
			$this->prep_output($row, false);
		}
	}

	/**
	 * The real method that prepares output.
	 *
	 * @param 	mixed 	$row
	 * @param 	bool 	$is_object
	 * @return 	mixed
	 */
	private function prep_output(&$row, $is_object = false)
	{
		if ($is_object)
		{
			if (stripos($row->activity, ':') !== false)
			{
				[$line, $arg] = explode(':', $row->activity);

				$row->output = $this->lang->sline(
					$line,
					admin_anchor('reports?user='.$row->user->id, $row->user->username),
					$param
				);
			}
			else
			{
				$row->output = $this->lang->sline(
					$row->activity,
					admin_anchor('reports?user='.$row->user->id, $row->user->username)
				);
			}

			// IP address anchor
			$row->ip_anchor = ($this->core->has_demo_access() OR (int) $row->user_id === $this->auth->user_id())
			? ip_anchor($row->ip_address, null, 'target="_blank"')
			: $this->lang->line('hidden_tag');
		}
		else
		{
			if (stripos($row['activity'], ':') !== false)
			{
				[$line, $arg] = explode(':', $row['activity']);

				$row['output'] = $this->lang->sline(
					$line,
					admin_anchor('reports?user='.$row['user']->id, $row['user']->username),
					$param
				);
			}
			else
			{
				$row['output'] = $this->lang->sline(
					$row['activity'],
					admin_anchor('reports?user='.$row['user']->id, $row['user']->username)
				);
			}

			// IP address anchor
			$row['ip_anchor'] = ($this->core->has_demo_access() OR (int) $row['user_id'] === $this->auth->user_id())
			? ip_anchor($row['ip_address'], null, 'target="_blank"')
			: $this->lang->line('hidden_tag');
		}
	}
}
