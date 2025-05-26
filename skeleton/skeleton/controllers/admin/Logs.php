<?php
defined('BASEPATH') OR die;

/**
 * Logs Controller
 *
 * Allows admin to view CodeIgniter log files.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 */
class Logs extends Admin_Controller
{
	/**
	 * Reserved for site owner.
	 * @var int
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array(
		'_delete' => 'admin/logs',
		'view' => 'admin/logs'
	);

	/**
	 * Holds path to logs.
	 * @var string
	 */
	protected $log_path;

	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Remember logs path.
		$this->log_path = $this->config->item('log_path');
	}

	// --------------------------------------------------------------------

	/**
	 * Stuff to do before loading our page.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		if ($method === 'index')
		{
			// Page title and icon.
			$this->page_title = $this->lang->line('admin_logs');
			$this->page_icon = 'file';
		}
		elseif ($method === 'view')
		{
			// Page title and icon
			$this->page_title = $this->uri->segment(3);
			$this->page_icon = 'magnifying-glass';
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * Prettifies log file name.
	 *
	 * @access 	private
	 * @param 	string 	$file_name
	 * @return 	string
	 */
	private function prettify_file_name($file_name)
	{
		$file_name = str_replace($this->log_path.'log-', '', $file_name);
		$file_name = preg_replace('/\.php$/', '', $file_name);
		return date($this->config->item('date_format'), strtotime($file_name));
	}

	// --------------------------------------------------------------------

	/**
	 * Lists all available log files.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		// Load log files.
		(function_exists('directory_files')) OR $this->load->helper('directory');
		$log_files = directory_files($this->log_path, 1, array('.htaccess', 'index.html', 'cron', 'archives'));
		usort($log_files, function($a, $b) {
			return filemtime($b) - filemtime($a);
		});

		// Paginate logs.
		[$limit, $offset] = $this->paginate($this->config->admin_url('logs'), count($log_files));

		// Data to be passed to view
		$this->data['pagination'] = $this->pagination->create_links();
		$this->data['log_threshold'] = $this->config->item('log_threshold');

		function_exists('byte_format') OR $this->load->helper('number');
		foreach (array_slice($log_files, $offset, $limit) as $log)
		{
			$this->data['logs'][str_replace($this->log_path, '', $log)] = array(
				'date' => $this->prettify_file_name($log),
				'size' => byte_format(filesize($log))
			);
		}

		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * View a single log file content.
	 *
	 * @param 	string 	$file_name
	 * @return 	void
	 */
	public function view($file_name = '')
	{
		// No access to view on demo.
		$this->core->redirect_demo('admin/logs');

		// Not file provided?
		if (empty($file_name))
		{
			$this->theme->set_alert($this->lang->line('admin_logs_error_empty'), 'error');
			redirect('admin/logs');
		}

		// Attempt to read file's content
		$file_path = $this->log_path.$file_name;
		is_file($file_path) && $this->data['content'] = array_reverse(file($file_path));

		// Other stuff to pass to view.
		$this->data['file_name'] = $file_name;
		$this->data['file_pretty_name'] = $this->prettify_file_name($file_name);

		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Used to delete selected log files.
	 *
	 * @access 	protected
	 * @param 	none
	 * @return 	void
	 */
	protected function _delete()
	{
		// Make sure files are selected
		if (empty($files = $this->input->post('id')))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/logs');
		}

		// Security check.
		if ($this->nonce->verify_request('logs-delete') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/logs');
		}

		// Prepare array of files to delete.
		$files = explode(',', $files);

		// Attempt delete
		function_exists('delete_files') OR $this->load->helper('file');
		foreach ($files as $file)
		{
			if (true !== @unlink($this->log_path.$file))
			{
				$this->theme->set_alert($this->lang->line('admin_logs_delete_error'), 'error');
				redirect('admin/logs');

				break;
			}
		}

		$this->theme->set_alert($this->lang->line('admin_logs_delete_success'), 'success');
		redirect('admin/logs');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (index).
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		// Info about logs
		echo html_tag(
			'span',
			'class="d-none d-md-inline lh-lg"',
			fa_icon('info-circle text-primary me-1', $this->lang->line('admin_logs_tip'))
		),

		// Delete.
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('admin_logs_delete'),
			'danger btn-sm bulk-action disabled float-end',
			'trash',
			array_to_attr(array(
				'role'         => 'button',
				'disabled'     => 'disabled',
				'data-form'    => esc_url(nonce_admin_url('logs/delete', 'logs-delete')),
				'data-confirm' => $this->lang->line('admin_logs_delete_confirm'),
			))
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (index).
	 *
	 * @return 	void
	 */
	public function _submenu_view()
	{
		echo $this->back_button('logs');
	}

}
