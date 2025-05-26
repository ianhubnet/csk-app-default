<?php
defined('BASEPATH') OR die;

/**
 * KB_Router Class
 *
 * This class extends CI_Router class in order to use HMVC structure.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.20
 */
class KB_Router extends CI_Router
{
	/**
	 * Holds current class path.
	 * @var string
	 */
	public $path = null;

	/**
	 * Holds an array of modules locations.
	 * @var array.
	 */
	protected $_locations;

	/**
	 * Caches the array of available modules.
	 * @var array
	 */
	public $modules;

	/**
	 * Array of active modules.
	 * @since   2.0
	 * @var     array
	 */
	public $active_modules;

	/**
	 * The current module's name.
	 * @var string
	 */
	public $module = null;

	/**
	 * Redirections.
	 * @since   2.16
	 */
	protected $_internal_redirects = array(
		'admin'    => KB_ADMIN,
		'login'    => KB_LOGIN,
		'logout'   => KB_LOGOUT,
		'register' => KB_REGISTER,
		KB_ADMIN   => KB_ADMIN, // backup plan.
	);

	/**
	 * User-defined redirections.
	 * @since 	2.67
	 */
	protected $_user_redirects = array();

	/**
	 * Whether we are in dashboard.
	 * @var 	bool
	 */
	protected $is_dashboard;

	/**
	 * Whether we are on homepage.
	 * @var 	bool
	 */
	protected $is_homepage;

	/**
	 * Site section.
	 * @var 	string
	 */
	protected $section;

	/**
	 * Array of paths to be added to Loader::_ci_library_paths
	 * @var 	array
	 */
	protected $_ci_module_paths;

	/**
	 * Array of cached modules contexts.
	 * @var array
	 */
	protected $_contexts = array();

	/**
	 * Class constructor.
	 * @return  void
	 */
	public function __construct(CI_Config &$config, CI_URI &$uri, $routing = null)
	{
		$this->config = $config;
		$this->uri = $uri;
		$this->is_dashboard = (defined('KB_DASHBOARD') && KB_DASHBOARD === true);

		$this->_prep_locations();

		$this->config->set_item('modules_locations', $this->_locations);

		/**
		 * Merge user-defined redirections with config.
		 * @since 2.69
		 */
		if ( ! empty($redirects = $this->config->item('redirects')) && is_array($redirects))
		{
			$this->_user_redirects = array_merge_unique($this->_user_redirects, $redirects);
		}

		// Initialize active modules.
		$this->_init_modules();

		// Let parent do the rest.
		parent::__construct($config, $uri, $routing);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns and array of modules locations.
	 * @access  public
	 * @return  array.
	 */
	public function modules_locations()
	{
		isset($this->_locations) OR $this->_prep_locations();
		return $this->_locations;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns or build the given module context.
	 * @access 	public
	 * @param 	string 	$folder 	The module folder name.
	 * @param 	string 	$path 		The module folder path.
	 * @return 	array
	 */
	public function module_contexts($folder, $path = null)
	{
		if (isset($this->_contexts[$folder]))
		{
			return $this->_contexts[$folder];
		}

		// For some reason it is invalid?
		elseif (empty($path) && empty($path = $this->module_path($folder)))
		{
			return array();
		}

		// Let's first see if the module has an admin controller.
		elseif ( ! ($contexts['admin'] = is_file($path.'controllers/Admin.php'))
			&& ! ($contexts['admin'] = is_file($path.'controllers/admin/Admin.php')))
		{
			$contexts['admin'] = is_file($path.'controllers/admin/'.ucfirst($folder).'.php');
		}

		// Now we see with other dashboard contexts.
		foreach (KPlatform::admin_contexts() as $context)
		{
			$contexts[$context] = is_file($path.'controllers/admin/'.ucfirst($context).'.php');
		}

		return $this->_contexts[$folder] = $contexts;
	}

	// --------------------------------------------------------------------

	/**
	 * Set module name.
	 * @access  public
	 * @param   string  $module
	 * @return  void
	 */
	public function set_module($module)
	{
		$this->module = $module;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetchs the current route: module, controller and method.
	 * @access 	public
	 * @return 	array
	 */
	public function fetch_route()
	{
		return array($this->module, $this->class, $this->method);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns modules paths.
	 *
	 * @access 	public
	 * @return 	KB_Router::_ci_module_paths
	 */
	public function module_paths()
	{
		return $this->_ci_module_paths;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the real path to the selected module.
	 *
	 * @since   1.0
	 * @since   1.4   Rewritten for better code.
	 * @since   2.0   Added a little check for module.
	 * @since   2.18 	Simplified code for faster execution.
	 * 
	 * @access  public
	 * @param   string  $name   Module name.
	 * @param   string  $uri 	String to append to path.
	 * @return  the full path if found, else false.
	 */
	public function module_path($name = null, $uri = '')
	{
		if (empty($name) && empty($name = $this->module))
		{
			return false;
		}
		elseif (isset($this->modules[$name]))
		{
			return normalize_path($this->modules[$name].'/'.$uri);
		}

		foreach ($this->modules_locations() as $location)
		{
			if ( ! is_dir($path = $location.$name))
			{
				continue;
			}

			$this->modules[$name] = $path;
			return normalize_path($path.'/'.$uri, true);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * List all available modules.
	 * @access  public
	 * @return  array
	 */
	public function list_modules()
	{
		if (isset($this->modules))
		{
			return $this->modules;
		}

		// Prepare an empty array of modules.
		$this->modules = array();

		// Let's go through folders and check if there are any.
		foreach ($this->modules_locations() as $location)
		{
			if ($handle = opendir($location))
			{
				while (false !== ($file = readdir($handle)))
				{
					// Ignored files?
					// Not a folder or missing "info.php" file?
					// Reserved module file?
					if (isset($this->modules[$file])
						OR in_array($file, KB_IGNORED_FILES)
						OR ! is_dir($location.$file)
						OR ! is_file($location.$file.'/info.php')
						OR KPlatform::is_protected_module($file))
					{
						continue;
					}

					$this->modules[$file] = normalize_path($location.$file.'/');
				}
			}
		}

		// Alphabetically order modules.
		empty($this->modules) OR ksort($this->modules);

		return $this->modules;
	}

	// --------------------------------------------------------------------

	/**
	 * _init_modules
	 *
	 * @access 	private
	 * @param 	none
	 * @return 	void
	 */
	protected function _init_modules()
	{
		// Make sure we have some activated modules.
		if (empty($modules = $this->active_modules(true)))
		{
			return;
		}

		foreach ($modules as $folder => $path)
		{
			// Something went wrong with path...
			// "init.php" not found? Nothing to do.
			if ( ! is_dir($path) OR ! is_file($path.'init.php'))
			{
				continue;
			}

			$this->_ci_module_paths[] = $path;
			// Import "init.php" file.
			require_once($path.'init.php');

			// Cache contexts only for dashboard.
			if ($this->is_dashboard)
			{
				$this->_contexts[$folder] = $this->module_contexts($folder, $path);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given $folder is a valid module.
	 * @access  public
	 * @param   string  $folder
	 * @return  bool
	 */
	public function valid_module($folder = null)
	{
		if ( ! empty($folder))
		{
			$modules = $this->list_modules();
			return isset($modules[$folder]);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * active_modules
	 *
	 * List all active modules previously stored in database.
	 *
	 * @since   2.1   Added details about modules.
	 * @since   2.18   Simplified code.
	 *
	 * @access  public
	 * @param 	bool 	$paths 	Whether to return an array of [name] => [path]
	 * @return  array
	 */
	public function active_modules($paths = false)
	{
		/**
		 * Because we are automatically assigning options from database
		 * to $config array, we see if we have the item
		 */
		if ( ! isset($this->active_modules)
			&& ! empty($this->active_modules = $this->config->item('active_modules', null, array())))
		{
			$this->active_modules = array_intersect_key($this->list_modules(), array_flip($this->active_modules));
		}

		return $paths ? $this->active_modules : array_keys($this->active_modules);
	}

	// --------------------------------------------------------------------

	/**
	 * module_enabled
	 *
	 * Method for checking whether the selected module is available AND active.
	 *
	 * @access  public
	 * @param   string  $name   The module's folder name.
	 * @return  bool    true if the module is active and found, else false.
	 */
	public function module_enabled($name)
	{
		return ($name && isset($this->active_modules(true)[$name]));
	}

	// --------------------------------------------------------------------

	/**
	 * is_active
	 *
	 * An alias of `module_enabled` kept for backwards compatibility.
	 *
	 * @deprecated
	 *
	 * @access 	public
	 * @param 	string 	$name 	The module's folder name.
	 * @return 	bool 	true if the module is found and active, else false.
	 */
	public function is_active($name)
	{
		return $this->module_enabled($name);
	}

	// --------------------------------------------------------------------

	/**
	 * _prep_locations
	 *
	 * Method for formatting paths to modules directories.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @access  protected
	 * @param   none
	 * @return  void
	 */
	protected function _prep_locations()
	{
		if (isset($this->_locations))
		{
			return $this->_locations;
		}

		$this->_locations = $this->config->item('modules_locations');

		if (empty($this->_locations))
		{
			$this->_locations = array(APPPATH.'modules/');
		}
		elseif ( ! in_array(APPPATH.'modules/', $this->_locations))
		{
			$this->_locations[] = APPPATH.'modules/';
		}

		foreach ($this->_locations as $i => &$location)
		{
			if (is_dir($location))
			{
				$location = normalize_path($location);
				continue;
			}

			unset($this->_locations[$i]);
		}

		return $this->_locations;
	}

	// --------------------------------------------------------------------

	/**
	 * Priority for default controllers is for application/controllers, if 
	 * none is found there, we see if a module exists or not.
	 * @access  protected
	 * @return  void
	 */
	protected function _set_default_controller()
	{
		// No default controller set? Nothing to do.
		if (empty($this->default_controller))
		{
			show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
		}

		// Is the method being specified?
		elseif (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
		{
			$method = 'index';
		}

		// Hold the controller location status.
		$controller_exists = false;
		$module_controller = false;

		// Found in application? Set it to found.
		if (is_file(APPPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			$controller_exists = true;
		}
		// Find is skeleton? Set it to found.
		elseif (is_file(KBPATH.'controllers/'.$this->directory.ucfirst($class).'.php'))
		{
			$controller_exists = true;
		}

		// If the controller was not found, try with modules.
		if ( ! $controller_exists
			&& list($module, $class, $method) = array_pad($this->locate(array($class, $class, $method)), 3, null))
		{
			if (empty($method) && 'index' === $class)
			{
				$method = $class;
				$class = $module;
			}
			elseif (empty($method))
			{
				$method = 'index';
			}

			$controller_exists = true;
			$module_controller = true;
		}

		// This will trigger 404 error.
		if ( ! $controller_exists)
		{
			return;
		}

		log_message('info', ($module_controller ? 'No URI present. Default module controller set.' : 'No URI present. Default controller set.'));

		$this->set_class($class);
		$this->set_method($method);

		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array(
			1 => $class,
			2 => $method
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Fixes some redirection problems, mainly to changed routes in constants.
	 * KB_ADMIN, KB_LOGIN, KB_LOGOUT and KB_REGISTER
	 *
	 * @since   2.16
	 *
	 * @access  protected
	 * @param   array   $segments
	 * @return  array
	 */
	protected function _rewrite_request($segments)
	{
		// Nothing to do on dashboard.
		if ($this->is_dashboard)
		{
			return array($segments, false);
		}

		$redirect = false;
		$index    = 0;

		// use a while loop to rewrite stuff.
		$uri = $this->uri->segment(1);
		while (isset($uri, $this->_internal_redirects[$uri]))
		{
			if ($uri !== $this->_internal_redirects[$uri])
			{
				$segments[$index] = $this->_internal_redirects[$uri];
				$redirect = true;
			}

			$index++;
			$uri = $this->uri->segment($index + 1);
		}

		// No redirection? Check user-defined ones.
		if ( ! $redirect && ! empty($this->_user_redirects))
		{
			// Check user-defined redirections.
			$last_segment = array_pop($segments);

			if (isset($this->_user_redirects[$last_segment]))
			{
				$segments[] = $this->_user_redirects[$last_segment];
				$redirect = true;
			}
			else
			{
				$segments[] = $last_segment;
			}
		}

		return array($segments, $redirect);
	}

	// --------------------------------------------------------------------

	/**
	 * Overrides CodeIgniter router _validate_request behavior.
	 * @access  protected
	 * @param   array   $segments
	 * @return  array
	 */
	protected function _validate_request($segments)
	{
		// If we have no segments, return as-is.
		if (0 == ($count = count($segments)))
		{
			return $segments;
		}

		// Needs an immediate redirection?
		[$segments, $redirect] = $this->_rewrite_request($segments);
		if ($redirect)
		{
			global $EXT;
			(isset($EXT)) && $EXT->call_hook('pre_redirect');

			$uri = implode('/', $segments);
			empty($_SERVER['QUERY_STRING']) OR $uri .= '?'.$_SERVER['QUERY_STRING'];
			header('Location: '.$this->config->site_url($uri), true, 301);
			exit;
		}

		// Let's now look for the controller with HMVC support.
		elseif ($located = $this->locate($segments))
		{
			// If found, return the result.
			return $located;
		}

		// Did the user specify a 404 override?
		elseif ( ! empty($this->routes['404_override']))
		{
			$segments = explode('/', $this->routes['404_override']);

			// Again, look for the controller with HMVC support.
			if ($located = $this->locate($segments))
			{
				return $located;
			}
		}

		// Let the parent handle the rest!
		return parent::_validate_request($segments);
	}

	// --------------------------------------------------------------------

	/**
	 * The only reason we are adding this method is to allow users to create
	 * a "routes.php" file inside the config folder.
	 * They can either use the "$route" array of our static Routing using
	 * Route class.
	 * @access  protected
	 * @return  void
	 */
	protected function _set_routing()
	{
		$routes  = array();

		// Modules routes.
		if ( ! empty($modules = $this->list_modules()))
		{
			foreach ($modules as $folder => $path)
			{
				if ( ! $this->module_enabled($folder))
				{
					continue;
				}
				elseif (is_file($path.'config/routes.php'))
				{
					include_once($path.'config/routes.php');

					if (isset($route) && is_array($route))
					{
						$routes = array_merge($routes, $route);
						unset($route);
					}
				}
			}
		}

		// Skeleton routes.
		if (is_file(KBPATH.'config/routes.php'))
		{
			include_once(KBPATH.'config/routes.php');

			if (isset($route) && is_array($route))
			{
				$routes = array_merge($routes, $route);
				unset($route);
			}
		}

		// Application routes.
		if (is_file(APPPATH.'config/routes.php'))
		{
			include_once(APPPATH.'config/routes.php');
		}

		if (is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
		{
			include_once(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
		}

		if (isset($route) && is_array($route))
		{
			$routes = array_merge($routes, $route);
			unset($route);
		}

		if (isset($routes) && is_array($routes))
		{
			isset($routes['default_controller']) && $this->default_controller = $routes['default_controller'];
			isset($routes['translate_uri_dashes']) && $this->translate_uri_dashes = $routes['translate_uri_dashes'];
			unset($routes['default_controller'], $routes['translate_uri_dashes']);
			$this->routes = Route::map($routes);
		}

		// Are query strings enabled in the config file? Normally CI doesn't utilize query strings
		// since URI segments are more search-engine friendly, but they can optionally be used.
		// If this feature is enabled, we will gather the directory/class/method a little differently
		if ($this->enable_query_strings)
		{
			// If the directory is set at this time, it means an override exists, so skip the checks
			if ( ! isset($this->directory))
			{
				$_d = $this->config->item('directory_trigger');
				$_d = isset($_GET[$_d]) ? trim($_GET[$_d], " \t\n\r\0\x0B/") : '';

				if ($_d !== '')
				{
					$this->uri->filter_uri($_d);
					$this->set_directory($_d);
				}
			}

			$_c = trim($this->config->item('controller_trigger'));
			if ( ! empty($_GET[$_c]))
			{
				$this->uri->filter_uri($_GET[$_c]);
				$this->set_class($_GET[$_c]);

				$_f = trim($this->config->item('function_trigger'));
				if ( ! empty($_GET[$_f]))
				{
					$this->uri->filter_uri($_GET[$_f]);
					$this->set_method($_GET[$_f]);
				}

				$this->uri->rsegments = array(
					1 => $this->class,
					2 => $this->method
				);
			}
			else
			{
				$this->_set_default_controller();
			}

			// Routing rules don't apply to query strings and we don't need to detect
			// directories, so we're done here
			return;
		}

		// Is there anything to parse?
		if ($this->uri->uri_string !== '')
		{
			$this->_parse_routes();
		}
		else
		{
			$this->_set_default_controller();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set Module Routes
	 *
	 * Sets modules routes automatically.
	 *
	 * @access  protected
	 * @param   array   $routes     Module routes stored in info.php file.
	 * @return  void
	 */
	protected function _set_module_routes($routes = array())
	{
		if (empty($routes))
		{
			return;
		}

		foreach ($routes as $route => $original)
		{
			if (1 === sscanf($route, 'resources:%s', $new_route))
			{
				Route::resources($new_route, $original);
			}
			elseif (1 === sscanf($route, 'context:%s', $new_route))
			{
				Route::context($new_route, $original);
			}
			elseif (empty($original))
			{
				Route::block($route);
			}
			elseif (1 === sscanf($route, 'any:%s', $new_route))
			{
				Route::any($new_route, $original);
			}
			elseif (1 === sscanf($route, 'get:%s', $new_route))
			{
				Route::get($new_route, $original);
			}
			elseif (1 === sscanf($route, 'post:%s', $new_route))
			{
				Route::post($new_route, $original);
			}
			elseif (1 === sscanf($route, 'put:%s', $new_route))
			{
				Route::put($new_route, $original);
			}
			elseif (1 === sscanf($route, 'delete:%s', $new_route))
			{
				Route::delete($new_route, $original);
			}
			elseif (1 === sscanf($route, 'head:%s', $new_route))
			{
				Route::head($new_route, $original);
			}
			elseif (1 === sscanf($route, 'patch:%s', $new_route))
			{
				Route::patch($new_route, $original);
			}
			elseif (1 === sscanf($route, 'options:%s', $new_route))
			{
				Route::options($new_route, $original);
			}
			else
			{
				Route::any($route, $original);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * is_dashboard
	 *
	 * Method for checking if we are on the dashboard section.
	 *
	 * @access  public
	 * @param   string 	$class 		controller to check
	 * @param   string 	$methid 	controller to check
	 * @param   string 	$module 	controller to check
	 * @return  bool
	 */
	public function is_dashboard($class = null, $method = null, $module = null)
	{
		$is_dashboard = $this->is_dashboard;
		($is_dashboard && ! empty($class)) && $is_dashboard = ($class === $this->class);
		($is_dashboard && ! empty($method)) && $is_dashboard = ($method === $this->method);
		($is_dashboard && ! empty($module)) && $is_dashboard = ($module === $this->module);

		return $is_dashboard;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the real URI/section.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function section()
	{
		if (isset($this->section))
		{
			return $this->section;
		}

		$this->section = empty($this->module) ? ($this->is_dashboard ? 'admin' : '') : $this->module;
		(empty($this->class) OR 'admin' === $this->class) OR $this->section .= '/'.$this->class;
		(empty($this->method) OR 'index' === $this->method) OR $this->section .= '/'.$this->method;

		$this->section = trim($this->section, '/');
		return $this->section;
	}

	// --------------------------------------------------------------------

	/**
	 * is_route
	 *
	 * Method for checking if we are on the given section.
	 *
	 * @access  public
	 * @param   string 	$class 		controller to check
	 * @param   string 	$methid 	controller to check
	 * @param   string 	$module 	controller to check
	 * @return  bool
	 */
	public function is_section($class = null, $method = null, $module = null)
	{
		if ( ! empty($class))
		{
			if (is_array($class) && ! in_array($this->class, $class))
			{
				return false;
			}
			elseif ($class !== $this->class)
			{
				return false;
			}
		}

		if ( ! empty($method))
		{
			if (is_array($method) && ! in_array($this->method, $method))
			{
				return false;
			}
			elseif ($method !== $this->method)
			{
				return false;
			}
		}

		if ( ! empty($module))
		{
			if (is_array($module) && ! in_array($this->module, $module))
			{
				return false;
			}
			elseif ($module !== $this->module)
			{
				return false;
			}
		}
		elseif (false === $module)
		{
			return empty($this->module);
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * is_homepage
	 *
	 * Method for checking if we are on the homepage.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function is_homepage()
	{
		if (isset($this->is_homepage))
		{
			return $this->is_homepage;
		}

		// We start with the controller.
		$uri = str_replace('/index', '', implode('/', $this->uri->rsegments));

		// Append the module if set.
		empty($this->module) OR $uri = $this->module.'/'.$uri;

		// Compare
		$this->is_homepage = ($this->default_controller === $uri);
		return $this->is_homepage;
	}

	// --------------------------------------------------------------------

	/**
	 * This method attempts to locate the controller of a module if
	 * detected in the URI.
	 * @access  public
	 * @param   array   $segments
	 * @return  array   $segments.
	 */
	public function locate($segments)
	{
		// Let's detect module's parts first.
		[$module, $directory, $controller] = array_pad($segments, 3, null);

		// Flag to see if we are in a module.
		$is_module = false;

		if (isset($this->active_modules[$module]))
		{
			$is_module = true;
			$location  = $this->active_modules[$module];
		}
		// Because of revered routes ;)
		elseif (isset($this->active_modules[$directory]))
		{
			$is_module = true;
			$location  = $this->active_modules[$directory];
			$_module   = $module;
			$module    = $directory;
			$directory = $_module;
		}

		if ($is_module && is_dir($source = $location.'controllers/'))
		{
			$this->module = $module;
			$this->directory = $location.'controllers/';

			// Changed admin route?
			(KB_ADMIN === $directory && 'admin' !== $directory) && $directory = 'admin';

			// Check within a directory first.
			if ($controller)
			{
				// In dashboard?
				if ($this->is_dashboard && is_file($source.'admin/'.ucfirst($controller).'.php'))
				{
					$this->directory = $source.'admin/';
					$this->class = $controller;
					$segments[0] = $controller;
					$segments[1] = 'admin';
					return array_slice($segments, 2);
				}

				// Public?
				elseif (is_file($source.ucfirst($controller).'.php'))
				{
					$this->class = $controller;
					$segments[0] = $module;
					$segments[1] = $controller;
					$segments[2] = 'index';
					return $segments;
				}
			}

			// Found the controller?
			if ($directory && is_file($source.ucfirst($directory).'.php'))
			{
				$this->class = $directory;
				$segments[0] = $module;
				$segments[1] = $directory;
				return array_slice($segments, 1);
			}

			// Controller in a sub-directory?
			elseif ($directory && is_dir($source.$directory.'/'))
			{
				$source = $source.$directory.'/';
				$this->directory .= $directory.'/';

				if (is_file($source.ucfirst($directory).'.php'))
				{
					return array_slice(array_reverse($segments), 1);
				}

				// Different controller's name?
				elseif ($controller && is_file($source.ucfirst($controller).'.php'))
				{
					return array_slice($segments, 2);
				}

				// Module sub-directory with default controller?
				elseif (is_file($source.ucfirst($this->default_controller).'.php'))
				{
					$segments[1] = $this->default_controller;
					return array_slice($segments, 1);
				}
			}

			// Module controller?
			if (is_file($source.ucfirst($module).'.php'))
			{
				return array_slice($segments, 1);
			}

			// Module with default controller?
			elseif (is_file($source.ucfirst($this->default_controller).'.php'))
			{
				$segments[0] = $this->default_controller;
				return $segments;
			}
		}

		// Paths where controllers may be located.
		$paths = array(APPPATH, KBPATH);
		foreach ($paths as $path)
		{
			// Changed admin route?
			(KB_ADMIN === $module && 'admin' !== $module) && $module = 'admin';

			// Priority to sub-folders.
			if ($directory && is_file($path.'controllers/'.$module.'/'.ucfirst($directory).'.php'))
			{
				$this->directory = $module.'/';
				return array_slice($segments, 1);
			}

			// Root folder controller?
			elseif (is_file($path.'controllers/'.ucfirst($module).'.php'))
			{
				return $segments;
			}

			// Sub-directory controller?
			elseif ($directory && is_file($path.'controllers/'.$module.'/'.ucfirst($directory).'.php'))
			{
				$this->directory = $module.'/';
				return array_slice($segments, 1);
			}

			// Default controller?
			elseif ($this->module &&
				is_file($path.'controllers/'.$module.'/'.ucfirst($this->default_controller).'.php'))
			{
				$segments[0] = $this->default_controller;
				return $segments;
			}
		}
	}

}

// --------------------------------------------------------------------
// Helpers.
// --------------------------------------------------------------------

if ( ! function_exists('module_path'))
{
	/**
	 * Returns the full path to the given module.
	 *
	 * @since   2.1
	 *
	 * @param   string  $name   The module's name.
	 * @param   string  $uri 	String to append to path.
	 * @return  the module's path if found, else false.
	 */
	function module_path($name = null, $uri = '')
	{
		return get_instance()->router->module_path($name, $uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('module_enabled'))
{
	/**
	 * Checks whether the given module is enabled.
	 *
	 * @since   2.1
	 *
	 * @param   string  $name   The module's name.
	 * @return  bool    true if the module is enabled, else false.
	 */
	function module_enabled($name = null)
	{
		return get_instance()->router->module_enabled($name);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_dashboard'))
{
	/**
	 * Checks whether we are on the dashboard area of the site.
	 *
	 * @param 	string 	$class
	 * @param 	string 	$method
	 * @param 	string 	$module
	 * @return 	bool
	 */
	function is_dashboard($class = null, $method = null, $module = null)
	{
		$CI =& get_instance();
		return empty($CI)
			? (defined('KB_DASHBOARD') && KB_DASHBOARD === true)
			: $CI->router->is_dashboard($class, $method, $module);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_section'))
{
	/**
	 * Checks whether we are on the given section of the site.
	 *
	 * @param 	string 	$class
	 * @param 	string 	$method
	 * @param 	string 	$module
	 * @return 	bool
	 */
	function is_section($class = null, $method = null, $module = null)
	{
		return get_instance()->router->is_section($class, $method, $module);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_homepage'))
{
	/**
	 * Checks whether we are on the homepage.
	 *
	 * @param 	none
	 * @return 	bool
	 */
	function is_homepage()
	{
		return get_instance()->router->is_homepage();
	}
}

// --------------------------------------------------------------------
// Module's helpers.
// --------------------------------------------------------------------

if ( ! function_exists('the_module'))
{
	/**
	 * Displays the current module folder's name.
	 * @param   none
	 * @return  void
	 */
	function the_module()
	{
		return get_instance()->router->module;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_module'))
{
	/**
	 * Checks if the page belongs to a given module. If no argument is passed,
	 * it checks if we areusing a module.
	 * You may pass a single string, multiple comma- separated modules or an array.
	 * @param   string|array.
	 * @return  bool    true if passed check, else false.
	 */
	function is_module($modules = null)
	{
		$module = get_instance()->router->module;

		if (null === $modules)
		{
			return ($module !== null);
		}

		if ( ! is_array($modules))
		{
			$modules = explode(',', $modules);
		}

		$modules = array_clean($modules);

		return in_array($module, $modules);
	}
}

// --------------------------------------------------------------------
// Controller's helpers.
// --------------------------------------------------------------------

if ( ! function_exists('the_controller'))
{
	/**
	 * Displays the current controller folder's name.
	 * @param   none
	 * @return  void
	 */
	function the_controller()
	{
		return get_instance()->router->class;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_controller'))
{
	/**
	 * Checks if the page belongs to a given controller.
	 * @param   mixed   $controllers
	 * @return  bool
	 */
	function is_controller($controllers = null)
	{
		$controller = get_instance()->router->class;

		if (null === $controllers)
		{
			return ($controller !== null);
		}

		if ( ! is_array($controllers))
		{
			$controllers = explode(',', $controllers);
		}

		$controllers = array_clean($controllers);

		return in_array($controller, $controllers);
	}
}

// --------------------------------------------------------------------
// Method's helpers.
// --------------------------------------------------------------------

if ( ! function_exists('the_method'))
{
	/**
	 * Returns the current method's name.
	 * @return  void
	 */
	function the_method()
	{
		return get_instance()->router->method;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_method'))
{
	/**
	 * Checks if the page belongs to a given method.
	 * @return  bool
	 */
	function is_method($methods = null)
	{
		$method = get_instance()->router->method;

		// This is silly but, let's just put it.
		if (null === $methods)
		{
			return ($method !== null);
		}

		if ( ! is_array($methods))
		{
			$methods = explode(',', $methods);
		}

		$methods = array_clean($methods);

		return (in_array($method, $methods));
	}
}
