<?php
defined('BASEPATH') OR die;

/**
 * Activities Module - Admin Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Modules\Controllers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.33
 * @version 	2.0
 */
class Reports extends Reports_Controller
{
	/**
	 * To reserved some actions.
	 * @var array
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array('_clear' => 'admin/reports');

	/**
	 * List reports.
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		// Custom $_GET appended to pagination links and WHERE clause.
		parse_str($_SERVER['QUERY_STRING'], $get);
		$_get  = null;
		$where = null;

		// Filtering by module, controller or method?
		foreach (array('module', 'controller', 'method') as $filter)
		{
			if (isset($get[$filter]))
			{
				$_get[$filter]  = $get[$filter];
				$where[$filter] = strval($this->security->xss_clean($get[$filter]));
			}
		}

		// We cannot search by method :D.
		if (isset($where['method'])
			&& ( ! isset($where['controller']) OR empty($where['controller'])))
		{
			unset($where['method']);
		}

		// Filtering by user ID?
		if (isset($get['user']))
		{
			$_get['user']     = $get['user'];
			$where['user_id'] = (int) $this->security->xss_clean($get['user']);
		}

		// Build the query appended to pagination links.
		(empty($_get)) OR $_get = '?'.http_build_query($_get);

		// Paginate reports.
		[$limit, $offset] = $this->paginate(
			$this->config->admin_url('reports'),
			$this->activities->count($where)
		);

		// Retrieve reports.
		$reports = $this->activities->get_many($where, null, $limit, $offset);

		// Loop through reports to complete data.
		if ($reports)
		{
			foreach ($reports as &$report)
			{
				// Complete data.
				$this->activities->format($report);

				// Module anchor
				$report->module_anchor = '';
				if ( ! empty($report->module))
				{
					$report->module_anchor = html_tag('a', array(
						'href' => $this->config->admin_url('reports?module='.$report->module),
						'class' => 'badge badge-default me-1',
					), $report->module);
				}

				// Controller anchor
				$report->controller_anchor = '';
				if ( ! empty($report->controller))
				{
					$controller_url = (empty($report->module))
						? $this->config->admin_url('reports?controller='.$report->controller)
						: $this->config->admin_url("reports?module={$report->module}&amp;controller={$report->controller}");
					$report->controller_anchor = html_tag('a', array(
						'href' => $controller_url,
						'class' => 'badge badge-default me-1',
					), $report->controller);
				}

				// Method anchor
				$report->method_anchor = '';
				if ( ! empty($report->method))
				{
					if ( ! empty($report->module))
					{
						$method_url = (empty($report->controller))
							? $this->config->admin_url("reports?module={$report->module}&amp;method=$report->method")
							: $this->config->admin_url("reports?module={$report->module}&amp;controller={$report->controller}&method={$report->method}");
					}
					else
					{
						$method_url = (empty($report->controller))
							? $this->config->admin_url('reports?method='.$report->method)
							: $this->config->admin_url("reports?controller={$report->controller}&method={$report->method}");
					}
					$report->method_anchor = html_tag('a', array(
						'href' => $method_url,
						'class' => 'badge badge-default',
					), $report->method);
				}
			}
		}

		// Add reports to view.
		$this->data['reports'] = $reports;

		// Set page title and render view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Clear reports
	 * @param   none
	 * @return  void
	 */
	protected function _clear()
	{
		// Security check first.
		if ($this->nonce->verify_request('reports-clear') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/reports');
		}

		$where['id >'] = 0;
		$where = array_merge_recursive($where, $this->input->post(null, true));

		if (isset($where['user']))
		{
			$where['user_id'] = (int) $where['user'];
			unset($where['user']);
		}

		// See if we could delete.
		if (false !== $this->activities->delete_by($where))
		{
			$this->activities->log($this->user->id, 'report_clear_reports');
			$this->theme->set_alert($this->lang->line('admin_reports_clear_success'), 'success');
			redirect('admin/reports');
		}

		$this->theme->set_alert($this->lang->line('admin_reports_clear_error'), 'error');
		redirect('admin/reports');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		$count = $this->activities->count();

		$args = array(
			'role'         => 'button',
			'disabled'     => 'disabled',
			'aria-label'   => $this->lang->line('admin_reports_clear'),
		);

		if (0 < $count)
		{
			unset($args['disabled']);
			$args['data-form']    = esc_url(nonce_admin_url('reports/clear', 'reports-clear'));
			$args['data-confirm'] = 'lang:reports.clear';
		}

		if ( ! empty($get = $this->input->get(null, true)))
		{
			$args['data-fields'] = _jsonify_attributes($get, true);
		}

		echo $this->theme->template('button_icon_attrs', '#', $this->lang->line('admin_reports_clear'),
			'danger btn-sm'.( ! $count ? ' disabled' : ''), 'trash', array_to_attr($args)
		);

		if ( ! empty($get))
		{
			echo $this->back_button('reports', null, 'ms-2');
		}
	}

}
