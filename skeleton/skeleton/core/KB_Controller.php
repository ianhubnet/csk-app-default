<?php
defined('BASEPATH') OR die;

/**
 * KB_Controller Class
 *
 * All controllers should extend this class if you want to use all skeleton
 * features OR you can create your own MY_Controller inside application/core
 * and make it extend this class. Then, all your controllers may extend your
 * custom class, MY_Controller.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.16
 */
class KB_Controller extends CI_Controller
{
	/**
	 * Array of items to load prior to constructor.
	 * @since   2.16
	 * @var     array
	 */
	protected $autoload = array();

	/**
	 * Holds the current user's object.
	 * @var object
	 */
	public $user;

	/**
	 * Holds the previous user's id.
	 * @var int|null
	 */
	public $prev_user_id;

	/**
	 * Holds the current URI string.
	 * @var string
	 */
	public $uri_string = '';

	/**
	 * Holds the current module
	 * @since   1.4
	 * @var     string
	 */
	protected $module;

	/**
	 * Array of data to pass to views.
	 * @var array
	 */
	protected $data = array();

	/**
	 * Array of previously sent $_POST data.
	 * @var array
	 */
	protected $_post_data;

	/**
	 * Array of fields to ignore when keeping post data.
	 * @var     array
	 */
	protected $_ignored_fields = array(
		'captcha',
		COOK_CSRF,
		'password',
		'persist',
	);

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array();

	/**
	 * Protected methods that should only be accessed via AJAX/POST request.
	 * @var array
	 */
	protected $ajax_methods = array('create', 'update', 'delete');

	/**
	 * Holds current page title.
	 * @var 	string
	 */
	public $page_title;

	/**
	 * Class constructor
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		// Is the current IP address blacklisted?
		if ($this->core->is_blocked_ip())
		{
			// Make sure to always redirect to homepage.
			$this->router->is_homepage() OR redirect('');
			show_403(ip_address()); // show error.
		}

		/**
		 * Autoload any items before calling continuing.
		 * @since   2.16
		 */
		empty($this->autoload) OR $this->load->autoload($this->autoload);

		/**
		 * We make sure to always store $_POST data upon submission. This
		 * is useful if we want to re-fill form inputs.
		 * @since   2.1
		 */
		if ($this->input->is_post_request() && $this->input->post('persist', true) === '1')
		{
			function_exists('array_except') OR $this->load->helper('array');
			$this->session->set_flashdata(SESS_POSTDATA, array_except(
				$this->input->post(null, true),
				$this->_ignored_fields
			));
		}

		/**
		 * Store the previously sent $_POST data to be used in any way.
		 * @since   2.16
		 */
		elseif ( ! empty($post_data = $this->session->flashdata(SESS_POSTDATA)))
		{
			$this->_post_data = $post_data;
			unset($post_data);
		}

		// Store current URI string.
		$this->uri_string = $this->uri->uri_string(true);

		if ( ! is_ajax(true))
		{
			// Set user & Check access
			$this->user = $this->auth->user();
			$this->check_access();

			/**
			 * Previous user's ID for 'login as' feature
			 * @since 2.93
			 */
			$this->prev_user_id = $this->session->userdata(SESS_PREV_USER_ID);

			/**
			 * If the "info.php" file is missing or badly formatted,
			 * $module will be set to "false". So we have two options:
			 * 1. Redirect the user to homepage.
			 * 2. Show error, this is in case the module is used as the
			 * default controller.
			 */
			if ( ! empty($this->module = $this->router->module) && ! $this->router->module_enabled($this->module))
			{
				/**
				 * Redirect to dashboard if the module isn't enabled
				 * and user is accessing a dashboard section.
				 */
				if ($this->uri->is_dashboard)
				{
					$this->theme->set_alert($this->lang->line('error_component_disabled'), 'error');
					redirect('admin');
				}

				/**
				 * If the controller is set as the homepage, we make sure
				 * to alert admins about it and display 404 error to others.
				 */
				elseif (str_starts_with($this->router->default_controller, $this->module))
				{
					show_error(
						$this->lang->line($this->auth->is_admin() ? 'error_component_disabled' : 'error_404_body'),
						501,
						$this->lang->line('error_404')
					);
				}

				/**
				 * If we are accessing the module but its disabled, we make sure
				 * to alert admins about it and redirect user to homepage.
				 */
				else
				{
					if ($this->auth->is_admin())
					{
						$this->theme->set_alert($this->lang->line('error_component_disabled'), 'error');
					}

					redirect('');
				}
			}

			/**
			 * Profiler if enabled and admin.
			 * @since 	2.16
			 */
			$this->output->enable_profiler($this->config->item('enable_profiler') && $this->auth->is_admin());

			// Modernizr.
			$this->scripts['modernizr'] = $this->core->is_live
				? '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js'
				: 'vendor/modernizr.min.js';

			// jQuery
			$this->scripts['jquery'] = $this->core->is_live
				? '//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js'
				: 'vendor/jquery.min.js';
		}
		else
		{
			$this->output->enable_profiler(false);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * We are remapping things just so we can handle methods that are
	 * http accessed and methods that require AJAX requests only.
	 *
	 * @since   1.0.0
	 * @since   1.33   Added logged-in user check for safe AJAX methods.
	 *
	 * @access  public
	 * @param   string  $method     The method's name.
	 * @param   array   $params     Arguments to pass to the method.
	 * @return  mixed   Depends on the called method.
	 */
	public function _remap($method, $params = array())
	{
		// The method is not found? Nothing to do.
		if ( ! $this->input->is_get_request())
		{
			/**
			 * Hack for HTTP requests.
			 * @since 	2.16
			 */
			if ( ! method_exists($this, $method)
				&& ! method_exists($this, $method = '_'.$method))
			{
				show_404();
			}

			// Check protected demo methods.
			$this->check_demo_access($method, $this->input->request_method);

			// Call the function otherwise.
			return call_user_func_array(array($this, $method), $params);
		}

		// Check protected demo methods.
		$this->check_demo_access($method, $this->input->request_method);

		// The method doesn't exist?
		if ( ! method_exists($this, $method))
		{
			show_404();
		}

		// Skip everything else for ajax requests.
		elseif (is_ajax(true))
		{
			return call_user_func_array(array($this, $method), $params);
		}

		/**
		 * Protected methods are only accessible via AJAX.
		 * @since   2.16
		 */
		elseif (in_array($method, $this->ajax_methods))
		{
			show_404();
		}

		/**
		 * Access levels can be assigned per method instead of
		 * being global to the whole controller.
		 * @since   2.16
		 */
		elseif ( ! $this->has_permission($method))
		{
			$this->theme->set_alert($this->lang->line($this->input->is_post_request() ? 'permission_error_access' : 'permission_error_access'), 'warning');
			redirect_back('');
		}

		// append our skeleton app main js file.
		elseif ( ! $this->uri->is_dashboard)
		{
			$this->assets->js($this->core->is_live ? 'main.min.js' : 'main.js', 'main');
		}

		// Call the method.
		return call_user_func_array(array($this, $method), $params);
	}

	// --------------------------------------------------------------------

	/**
	 * This shortcut was created in order to access previously sent $_POST data
	 * and grab any field if found, otherwise it uses the default value.
	 *
	 * Example: skeleton\controllers\admin\Login.php:216
	 *
	 * @since   2.16
	 * @param   string  $field        the field to find in $_POST data.
	 * @param   mixed   $default    default value to use when it fails.
	 * @return  mixed
	 */
	protected function post_data($field, $default = null)
	{
		return isset($this->_post_data, $this->_post_data[$field]) ? $this->_post_data[$field] : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to set form input value of a persistent form if found.
	 *
	 * @access 	protected
	 * @param   string  $field      Field name
	 * @param   string  $default    Default value
	 * @param   bool    $html_escape    Whether to escape HTML special characters or not
	 * @return  string
	 */
	protected function set_value($field, $default = '', $html_escape = true)
	{
		$post_default = $this->post_data($field, $default);
		return set_value($field, empty($post_default) ? $default : $post_default, $html_escape);
	}

	// --------------------------------------------------------------------

	/**
	 * This method can be used to block direct access to routed controller.
	 *
	 * @example		$this->_block('main', '')
	 * Any direct access to '/main/method' will redirect to '/method'.
	 *
	 * @param 	string 	$search
	 * @param 	string 	$replace
	 * @return 	void
	 */
	protected function _block(string $search, string $replace = '')
	{
		if ($this->uri->segment(1) === ($search = rtrim($search, '/')))
		{
			redirect(str_replace("{$search}/", $replace, $this->uri_string), false, 301);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the user has demo access to given method.
	 *
	 * @param 	string 	$method
	 * @param 	string 	$request_method
	 * @return 	void
	 */
	private function check_demo_access($method, $request_method)
	{
		// User already has full demo access?
		if ($this->core->has_demo_access() OR $request_method === 'get')
		{
			return;
		}
		// Protected this specified method?
		elseif (isset($this->demo_protected[$method]))
		{
			$this->theme->set_alert($this->lang->line('demo_mode_error'), 'warning');
			redirect($this->demo_protected[$method]);
		}
		elseif (isset($this->demo_protected['*']))
		{
			$this->theme->set_alert($this->lang->line('demo_mode_error'), 'warning');
			redirect($this->demo_protected['*']);
		}
	}

	// --------------------------------------------------------------------

		/**
	 * Checks whether the current user has the required access level.
	 *
	 * @since   2.16
	 *
	 * @param   string  $method
	 * @return  bool
	 */
	protected function has_permission($method)
	{
		(empty($method)) && $method = $this->router->method;

		/**
		 * Access levels can be assigned per method instead of
		 * being global to the whole controller.
		 * @since   2.16
		 */
		if (isset($this->access_levels, $method)
			&& is_array($this->access_levels)
			&& isset($this->access_levels[$method])
			&& ! $this->auth->is_level($this->access_levels[$method]))
		{
			return false;
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * prep_form
	 *
	 * Method for preparing form validation library with optional rules to
	 * apply and whether to use jQuery.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.0.0
	 * @since   1.5.0   Added jQuery validation plugin.
	 *
	 * @access  protected
	 * @param   array
	 * @param   string  $form       jQuery selector.
	 * @param   string  $filter     String appended to filtered parameters.
	 * @param   string  $captcha    Whether to use captcha or not.
	 * @return  void
	 */
	public function prep_form($rules = array(), $form = null, $captcha = null, $filter = null)
	{
		// Load form validation library if not loaded.
		(isset($this->form_validation)) OR $this->load->library('form_validation');

		// Load inputs config file.
		if ( ! $this->config->item('inputs'))
		{
			$this->load->config('inputs', true, true);
		}

		if ($this->module)
		{
			$this->load->config($this->module.'/inputs', true);
		}

		if (is_string($rules))
		{
			empty($this->config->item('rules')) && $this->load->config('rules', true);
			$rules = empty($item = $this->config->item($rules, 'rules')) ? array() : $item;

			if ($captcha && $this->config->item('use_captcha'))
			{
				$rules[] = array(
					'field' => $this->config->item('use_recaptcha') ? 'g-recaptcha-response' : 'captcha',
					'label' => $this->lang->line('captcha'),
					'rules' => 'trim|required|callback__check_captcha'
				);
			}
		}

		// Are there any rules to apply?
		if (is_array($rules) && ! empty($rules))
		{
			// Set CI validation rules first.
			$this->form_validation->set_rules($rules);

			// Use jQuery validation?
			if ( ! empty($form))
			{
				(isset($this->jquery_validation)) OR $this->load->library('jquery_validation');
				$this->jquery_validation->set_rules($rules);

				// we build the final jQuery validation output.
				$this->assets->jquery_validate();
				$this->assets->inline_js($this->jquery_validation->run($form, $filter));
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Callback used for validating Captcha/reCAPTCHA.
	 *
	 * @param 	string 	$str
	 * @return 	bool
	 */
	public function _check_captcha($str)
	{
		$str = $this->config->item('use_recaptcha') ? $this->input->post('g-recaptcha-response') : $str;
		return $this->form_validation->check_captcha($str);
	}

	// --------------------------------------------------------------------

	/**
	 * Creates pagination.
	 *
	 * @access 	protected
	 * @param 	string 	$base_url 		Base URL and First Link URL.
	 * @param 	int 	$total_rows 	The number of rows used to paginate.
	 * @param 	array 	$params 		Array of parameters to add to config, or limit.
	 * @return 	array 	Returns an array of limit and offset.
	 */
	protected function paginate($base_url, $total_rows, $params = array())
	{
		// Load Pagination library if not loaded.
		(isset($this->pagination)) OR $this->load->library('pagination');

		// Prepare basic config items.
		$config['per_page'] = $limit = $this->config->item('per_page');
		$config['base_url'] = $config['first_link'] = $base_url;
		$config['total_rows'] = $total_rows;
		$config['reuse_query_string'] = true;

		// Allow used to do whatever they want.
		empty($params) OR $config = array_merge($config, $params);

		// Calculate the offset that should be returned.
		$offset = empty($this->input->get('page')) ? 0 : $config['per_page'] * ($this->input->get('page') - 1);

		// Initialize pagination.
		$this->pagination->initialize($config);

		// Pass pagination to views.
		$this->data['pagination'] = $this->pagination->create_links();

		// Return the array of limit and offset that can be used
		// to retrieve and splice data from database.
		return array($limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Renders theme view file.
	 *
	 * @param 	string 	$title 		The page title.
	 * @param 	array 	$options	Array of options to apply before rendering.
	 * @return 	void
	 */
	protected function render($title = null, $options = array())
	{
		empty($title) && $title = isset($this->page_title) ? $this->page_title : null;

		$this->theme->render($this->data, $title, $options);
	}

	// --------------------------------------------------------------------

	/**
	 * check_access
	 *
	 * Used to make sure the user has the required level to access the page.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  private
	 * @param   string  $type   The type of alert: page or action.
	 * @return  void
	 */
	private function check_access($type = 'page')
	{
		/**
		 * If the user is logged in and the controller has the $access_level property
		 * we make sure to compare it to their level and direct them to the homepage
		 * if they don't access permission to access.
		 */
		if (isset($this->access_level) && ! $this->auth->is_level($this->access_level))
		{
			switch ($type)
			{
				case 'action':
					$this->theme->set_alert($this->lang->line('permission_error_action'), 'warning');
					break;
				case 'page':
				default:
					$this->theme->set_alert($this->lang->line('permission_error_access'), 'warning');
					break;
			}

			if ( ! $this->auth->online())
			{
				$uri = $this->uri->is_dashboard ? 'admin-login' : 'login';
				if ($this->uri_string !== '')
				{
					$uri .= '?next='.rawurlencode($this->uri_string);
				}
				redirect($uri);
			}

			redirect($this->auth->is_level(KB_LEVEL_ACP) ? 'admin' : '');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * set_access_level
	 *
	 * Used to dynamically set the access level to a controller or its methods.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  protected
	 * @param   int     $level  The access level to use.
	 * @param   string  $type   The type of alert: page or action.
	 * @return  void
	 */
	protected function set_access_level($level = 0, $type = 'page')
	{
		$this->access_level = $level;
		$this->check_access($type);
	}

}
