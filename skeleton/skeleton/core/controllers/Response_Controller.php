<?php
defined('BASEPATH') OR die;

/**
 * Response_Controller Class
 *
 * Common controller used for AJAX, CLI and API controllers.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.111
 */
class Response_Controller extends KB_Controller
{
	/**
	 * Array of methods that require a logged-in user and
	 * a safe URL with check.
	 * @var array
	 */
	protected $safe_methods = array();

	/**
	 * Array of methods that require a logged-in user of rank admin.
	 * @var array
	 */
	protected $admin_methods = array();

	/**
	 * Array of methods that require a logged-in user of rank admin and
	 * a safe URL check.
	 * @var array
	 */
	protected $safe_admin_methods = array();

	/**
	 * Used by AJAX methods to hold response details.
	 * @var object
	 */
	protected $response;

	// --------------------------------------------------------------------

	/**
	 * Method for catching called methods to check safety and integrity.
	 * @access  public
	 * @param   string  $method     The requested method.
	 * @param   array   $params     Arguments to pass to the method.
	 * @return  AJAX_Controller::response().
	 */
	public function _remap($method, $params = array())
	{
		// Make sure to initialize class preferences.
		$this->initialize();

		// The method does not exist?
		if ( ! method_exists($this, $method))
		{
			$this->response->header = HttpStatusCodes::HTTP_UNAUTHORIZED;
			return $this->response();
		}

		// Has permission?
		elseif ( ! $this->has_permission($method))
		{
			$this->response->header = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = $this->lang->line('permission_error_action');
			return $this->response();
		}

		/**
		 * If the method is present in both $safe_methods and
		 * $admin_methods array we make sure to automatically
		 * add it to $safe_admin_methods array.
		 */
		elseif (in_array($method, $this->safe_methods)
			&& in_array($method, $this->admin_methods)
			&& ! in_array($method, $this->safe_admin_methods))
		{
			$this->safe_admin_methods[] = $method;
		}

		/**
		 * The reason behind this is that sometime we don't need to create
		 * the referrer field. So we see if one is provided. If it is not,
		 * we simply check the nonce without referrer.
		 */
		$nonce_status = $this->nonce->verify_request();

		// Does the requested methods require a safety check?
		if (in_array($method, $this->safe_methods) && ( ! $nonce_status OR ! $this->auth->online()))
		{
			$this->response->header  = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = $this->lang->line('error_csrf');
		}

		// Does the method require an admin user?
		elseif (in_array($method, $this->admin_methods) && ! $this->auth->is_admin())
		{
			$this->response->header  = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = $this->lang->line('error_csrf');
		}

		// Does the method require an admin user AND a safety check?
		elseif (in_array($method, $this->safe_admin_methods) && ( ! $nonce_status OR ! $this->auth->is_admin()))
		{
			$this->response->header  = HttpStatusCodes::HTTP_UNAUTHORIZED;
			$this->response->message = $this->lang->line('error_csrf');
		}
		// Otherwise, call the method.
		else
		{
			call_user_func_array(array($this, $method), $params);
		}

		// Always return the final response.
		return $this->response();
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize class preferences.
	 *
	 * @return 	void
	 */
	private function initialize()
	{
		// Already initialized?
		if (isset($this->response))
		{
			return;
		}

		// Load Format library if not yet loaded.
		isset($this->format) OR $this->load->library('format');

		// Prepare $response property object.
		$this->response          = new stdClass();
		$this->response->header  = HttpStatusCodes::HTTP_BAD_REQUEST;
		$this->response->message = '';
		$this->response->scripts = array();
		$this->response->results = array();
	}

	// --------------------------------------------------------------------

	/**
	 * This method handles the rest of AJAX requests.
	 *
	 * @access  public
	 * @param   none
	 * @return  string
	 */
	protected function response()
	{
		/**
		 * Disable parsing of the {elapsed_time} and {memory_usage}
		 * pseudo-variables because we don't need them.
		 */
		$this->output->parse_exec_vars = false;

		$response['code'] = $this->response->header;
		$response['status'] = isset($this->response->status)
			? $this->response->status
			: HttpStatusCodes::$messages[$this->response->header];

		empty($this->response->message) OR $response['message'] = $this->response->message;
		empty($this->response->scripts) OR $response['scripts'] = $this->response->scripts;
		empty($this->response->results) OR $response['results'] = $this->response->results;

		// For DataTables compatibility.
		if (isset($this->response->total, $this->response->filtered))
		{
			$response['draw']            = empty($draw = $this->input->post('draw', true)) ? 1 : $draw;
			$response['recordsTotal']    = $this->response->total;
			$response['recordsFiltered'] = $this->response->filtered;
			$response['data']            = $this->response->results;
		}

		// Return the final output.
		return $this->output
			->set_content_type('application/json')
			->set_status_header($this->response->header)
			->set_output($this->format->to_json($response));
	}
}
