<?php
defined('BASEPATH') OR die;

/**
 * KB_Loader Class
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
 * @version     1.0
 */
class KB_Loader extends CI_Loader
{
	/**
	 * Holds an array of loaded modules.
	 * @var array
	 */
	protected $_ci_modules = array();

	/**
	 * Holds an array of loaded modules controllers.
	 * @var array
	 */
	protected $_ci_controllers = array();

	/**
	 * Array of already loaded packages.
	 * @var array
	 */
	protected $_loaded_packages = array();

	/**
	 * Holds the array of files to be autoloaded.
	 * @var array
	 */
	public $autoload = array();

	/**
	 * Holds the current module.
	 * @since   2.16
	 * @var     string
	 */
	protected $module;

	/**
	 * Class constructor.
	 * @return  void
	 */
	public function __construct(CI_Controller &$CI)
	{
		// Let's add our path.
		$this->_ci_library_paths = array(APPPATH, KBPATH, BASEPATH);
		$this->_ci_model_paths[] = KBPATH;
		$this->_ci_view_paths[normalize_path(KBPATH.'views/')] = true;

		// Now we call parent's constructor.
		parent::__construct($CI);

		// Make sure to add the module as a package.
		empty($this->CI->router->module) OR $this->add_module($this->CI->router->module);

		// Add modules paths to _ci_library_paths
		if ( ! empty($module_paths = $this->CI->router->module_paths()))
		{
			$this->_ci_library_paths = array_merge_unique($this->_ci_library_paths, $module_paths);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Config Loader
	 *
	 * This method overrides CodeIgniter config loaded to allow HMVC support.
	 *
	 * @uses    CI_Config::load()
	 * @param   string  $file           Configuration file name
	 * @param   bool    $use_sections       Whether configuration values should be loaded into their own section
	 * @param   bool    $fail_gracefully    Whether to just return false or display an error message
	 * @return  bool    true if the file was loaded correctly or false on failure
	 */
	public function config($file, $use_sections = false, $fail_gracefully = false)
	{
		// Let's see if we are loading form a module.
		if (list($module, $class) = $this->_detect_module($file))
		{
			// Make sure the module is enabled.
			if ($module && ! $this->CI->router->module_enabled($module))
			{
				return;
			}
			// Already loaded?
			elseif (in_array($module, $this->_ci_modules))
			{
				return $this->CI->config->load($class, $use_sections, $fail_gracefully);
			}

			// Not loaded? Load it and catch the result.
			$this->add_module($module);
			$result = $this->CI->config->load($class, $use_sections, $fail_gracefully);
			$this->remove_module();

			return $result;
		}

		return $this->CI->config->load($file, $use_sections, ($module !== null) OR $fail_gracefully);
	}

	// --------------------------------------------------------------------

	/**
	 * Add Package Path
	 *
	 * This method was added so we can load "bootstrap.php" files from added
	 * packages and do actions if found.
	 *
	 * @access  public
	 * @param   string  $path   The path to add.
	 * @param   bool    $view_cascade
	 * @return  object
	 */
	public function add_package_path($path, $view_cascade = true)
	{
		// Already loaded? Nothing to do...
		if (isset($this->_loaded_packages[$path]))
		{
			return $this;
		}

		// Normalize path and use folder name for action.
		$path = normalize_path($path);
		$basename = basename($path);

		parent::add_package_path($path, $view_cascade);

		// Possibility to add a bootstrap.php file.
		if (is_file($path.'/bootstrap.php'))
		{
			require_once($path.'/bootstrap.php');

			/**
			 * If your package bootstrap file contains a class named like
			 * "Folder_bootstrap" and having an "init" method, the class
			 * is automatically initialized and the method called.
			 */
			$class = ucfirst($basename).'_bootstrap';

			if (class_exists($class, false))
			{
				$class = new $class();
				is_callable(array($class, 'init')) && $class->init();
			}

			do_action('package_added_'.$basename);
		}

		$this->_loaded_packages[$path] = $basename;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Remove Package Path
	 *
	 * Simply uses parent's method and fires the "package_removed_" action
	 * if it exists.
	 *
	 * @access  public
	 * @param   string  $path   The path to remove.
	 * @return  object
	 */
	public function remove_package_path($path = '')
	{
		empty($path) OR do_action('package_removed_'.basename($path));
		return parent::remove_package_path($path);
	}

	// --------------------------------------------------------------------

	/**
	 * Override CodeIgniter helper loader to allow passing arguments
	 * as array or comma-separated arguments.
	 * @access  public
	 * @param   mixed   $helpers    string(s) or array.
	 * @return  object
	 */
	public function helper($helpers = array())
	{
		// Catch method arguments first. None? Nothing to do.
		if (empty($args = func_get_args()))
		{
			return $this;
		}

		// Get rid of nasty array.
		is_array($args[0]) && $args = $args[0];

		foreach ($args as &$helper)
		{
			$filename = basename($helper);
			$filepath = ($filename === $helper) ? '' : substr($helper, 0, strlen($helper) - strlen($filename));
			$filename = strtolower(preg_replace('#(_helper)?(\.php)?$#i', '', $filename)).'_helper';
			$helper   = $filepath.$filename;

			if (isset($this->_ci_helpers[$helper]))
			{
				continue;
			}

			// Is this a helper extension request?
			$ext_helper = config_item('subclass_prefix').$filename;
			$ext_loaded = false;
			foreach ($this->_ci_helper_paths as $path)
			{
				if (is_file($path.'helpers/'.$ext_helper.'.php'))
				{
					include_once($path.'helpers/'.$ext_helper.'.php');
					$ext_loaded = true;
				}
			}

			// Look for out custom helpers.
			if (is_file(KBPATH.'helpers/KB_'.$helper.'.php'))
			{
				include_once(KBPATH.'helpers/KB_'.$helper.'.php');
				$ext_loaded = true;
			}

			// If we have loaded extensions - check if the base one is here
			if ($ext_loaded === true)
			{
				$base_helper = BASEPATH.'helpers/'.$helper.'.php';
				if ( ! is_file($base_helper))
				{
					show_error('Unable to load the requested file: helpers/'.$helper.'.php');
				}

				include_once($base_helper);
				$this->_ci_helpers[$helper] = true;
				log_message('info', 'Helper loaded: '.$helper);
				continue;
			}

			// No extensions found ... try loading regular helpers and/or overrides
			foreach ($this->_ci_helper_paths as $path)
			{
				if (is_file($path.'helpers/'.$helper.'.php'))
				{
					include_once($path.'helpers/'.$helper.'.php');

					$this->_ci_helpers[$helper] = true;
					log_message('info', 'Helper loaded: '.$helper);
					break;
				}
			}

			// Not loaded? Try in module.
			if ( ! isset($this->_ci_helpers[$helper])
				&& list($module, $class) = $this->_detect_module($helper))
			{
				// Make sure the module is enabled.
				if ($module && ! $this->CI->router->module_enabled($module))
				{
					return;
				}

				// Module already loaded?
				elseif (in_array($module, $this->_ci_modules)
					&& is_file($file_path = $this->CI->router->module_path($module, 'helpers/'.$filename.'.php')))
				{
					include_once($file_path);
					$this->_ci_helpers[$helper] = true;
				}
				// Not loaded? Try to loaded and look for the file.
				else
				{
					$this->add_module($module);
					if (is_file($file_path = $this->CI->router->module_path($module, 'helpers/'.$filename.'.php')))
					{
						include_once($file_path);
						$this->_ci_helpers[$helper] = true;
					}
					$this->remove_module();
				}
			}

			// unable to load the helper
			if ( ! isset($this->_ci_helpers[$helper]))
			{
				show_error('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Load multiple helpers. This method was added only to override parent's.
	 * @access  public
	 * @param   mixed
	 * @return  object.
	 */
	public function helpers($helpers = array())
	{
		return call_user_func_array(array($this, 'helper'), func_get_args());
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the list of loaded helpers.
	 * @access 	public
	 * @return 	array
	 */
	public function get_helpers()
	{
		return $this->_ci_helpers;
	}

	// --------------------------------------------------------------------

	/**
	 * Language loader.
	 * @access  public
	 * @param   string|array    $files  Files to load.
	 * @param   string          $lang   Language name.
	 * @return  object.
	 */
	public function language($files, $lang = '')
	{
		if (is_array($files))
		{
			foreach ($files as $file)
			{
				$this->language($file, $lang);
			}

			return true;
		}

		// Detect the module first. Priority if to modules first.
		elseif (list($module, $class) = $this->_detect_module($files))
		{
			// Make sure the module is enabled.
			if ($module && ! $this->CI->router->module_enabled($module))
			{
				return;
			}

			// Module already loaded?
			if (in_array($module, $this->_ci_modules))
			{
				return parent::language($class, $lang);
			}

			/// Here we add the module, catch the result, remove it and return.
			$this->add_module($module);
			$result = parent::language($class, $lang);
			$this->remove_module();

			return $result;
		}
		// No module? Nothing to do.
		else
		{
			return parent::language($files, $lang);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Library Loader.
	 * @access  public
	 * @param   mixed   $class          String or array.
	 * @param   array   $params         Library configuration.
	 * @param   string  $object_name    Whether to rename the library.
	 * @return  void
	 */
	public function library($class, $params = null, $object_name = null)
	{
		// In case of multiple libraries.
		if (is_array($class))
		{
			foreach ($class as $key => $val)
			{
				if (is_int($key))
				{
					$this->library($val, $params);
				}
				else
				{
					$this->library($key, $params, $val);
				}
			}

			return $this;
		}

		// Ignore Kbcore driver.
		elseif ('Kbcore/kbcore' === $class)
		{
			return parent::library($class, $params, $object_name);
		}
		// Priority is to modules.
		elseif (list($module, $_class) = $this->_detect_module($class))
		{
			// Make sure the module is enabled.
			if ($module && ! $this->CI->router->module_enabled($module))
			{
				return;
			}

			// Already loaded?
			if (in_array($module, $this->_ci_modules))
			{
				return parent::library($_class, $params, $object_name);
			}

			// Not loaded? Load it.
			$this->add_module($module);
			$result = parent::library($_class, $params, $object_name);
			$this->remove_module();

			return $result;
		}
		// Not in module? Go the default way.
		else
		{
			return parent::library($class, $params, $object_name);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Models Loader.
	 * @access  public
	 * @param   mixed   $model      Single or multiple models
	 * @param   string  $$name      Whether to rename the model.
	 * @param   bool    $db_conn    An optional database connection configuration to initialize
	 * @return  object
	 */
	public function model($model, $name = '', $db_conn = false)
	{
		if (empty($model))
		{
			return $this;
		}
		elseif (is_array($model))
		{
			foreach ($model as $key => $value)
			{
				is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
			}

			return $this;
		}

		$path = '';

		// Used by HMVC.
		$original_name = $model;

		// Is the model in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($model, '/')) !== false)
		{
			// The path is in front of the last slash
			$path = substr($model, 0, ++$last_slash);

			// And the model name behind it
			$model = substr($model, $last_slash);
		}

		if (empty($name))
		{
			$name = $model;
		}

		if (in_array($name, $this->_ci_models, true))
		{
			return $this;
		}

		elseif (isset($this->CI->$name))
		{
			throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: '.$name);
		}

		elseif ($db_conn !== false && ! class_exists('CI_DB', false))
		{
			if ($db_conn === true)
			{
				$db_conn = '';
			}

			$this->database($db_conn, false, true);
		}

		/**
		 * Note: All of the code under this condition used to be just:
		 *
		 * 	load_class('Model', 'core');
		 *
		 * 	However, load_class() instantiates classes to cache them for
		 * 	later use and that prevents MY_Model from being an abstract class
		 * 	and is sub-optimal otherwise anyway.
		 */
		if ( ! class_exists('CI_Model', false))
		{
			$app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
			if (is_file($app_path.'Model.php'))
			{
				require_once($app_path.'Model.php');
				if ( ! class_exists('CI_Model', false))
				{
					throw new RuntimeException($app_path."Model.php exists, but doesn't declare class CI_Model");
				}
			}
			elseif ( ! class_exists('CI_Model', false))
			{
				require_once(BASEPATH.'core'.DIRECTORY_SEPARATOR.'Model.php');
			}

			$class = config_item('subclass_prefix').'Model';
			if (is_file($app_path.$class.'.php'))
			{
				require_once($app_path.$class.'.php');
				if ( ! class_exists($class, false))
				{
					throw new RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
				}
			}
		}

		$model = ucfirst($model);
		if ( ! class_exists($model, false))
		{
			// Use by HMVC
			$model_exists = false;

			foreach ($this->_ci_model_paths as $mod_path)
			{
				if ( ! is_file($mod_path.'models/'.$path.$model.'.php'))
				{
					continue;
				}

				require_once($mod_path.'models/'.$path.$model.'.php');
				if ( ! class_exists($model, false))
				{
					throw new RuntimeException($mod_path."models/".$path.$model.".php exists, but doesn't declare class ".$model);
				}

				$model_exists = true;
				break;
			}

			// The model was not found? Try in modules.
			if ( ! $model_exists && list($module, $_class) = $this->_detect_module($original_name))
			{
				// Make sure the module is enabled.
				if ($module && ! $this->CI->router->module_enabled($module))
				{
					return;
				}

				// Load the module if not loaded.
				(in_array($module, $this->_ci_modules)) OR $this->add_module($module);
				$file_path = $this->CI->router->module_path($module, 'models/'.$model.'.php');
				if (is_file($file_path))
				{
					require_once($file_path);
				}
			}

			if ( ! class_exists($model, false))
			{
				throw new RuntimeException('Unable to locate the model you have specified: '.$model);
			}
		}
		elseif ( ! is_subclass_of($model, 'CI_Model'))
		{
			throw new RuntimeException("Class ".$model." already exists and doesn't extend CI_Model");
		}

		$this->_ci_models[] = $name;
		$this->CI->$name = new $model();
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the list of loaded models.
	 * @access 	public
	 * @return 	array
	 */
	public function get_models()
	{
		return $this->_ci_models;
	}

	// --------------------------------------------------------------------

	/**
	 * This method adds a module exactly the same way we add packages.
	 * @access  public
	 * @param   string  $module     Module's name.
	 * @param   bool    $view_cascade.
	 * @return  void
	 */
	public function add_module($module, $view_cascade = true)
	{
		// Make sure the module exists first.
		if ( ! ($path = $this->CI->router->module_path($module)))
		{
			return;
		}

		// Mark the module as loaded first.
		array_unshift($this->_ci_modules, $module);

		array_unshift($this->_ci_library_paths, $path);
		array_unshift($this->_ci_model_paths, $path);

		$this->_ci_view_paths = array($path.'views/' => $view_cascade) + $this->_ci_view_paths;

		// Add config file path
		$config =& $this->_ci_get_component('config');
		$config->_config_paths[] = $path;
	}

	// --------------------------------------------------------------------

	/**
	 * This method does what is says, it removes the module the same way
	 * we remove packages paths with CodeIgniter.
	 * @access  public
	 * @param   string  $module     Module's name.
	 * @param   bool    $config     Whether to remove config file.
	 * @return  void
	 *
	 */
	public function remove_module($module = null, $remove_config = true)
	{
		// Empty module's name?
		if (empty($module))
		{
			// Remove the first element of loaded modules array.
			array_shift($this->_ci_modules);

			// Now we remove the package.
			$this->remove_package_path('', $remove_config);
		}
		// Search the module and remove it if found.
		elseif (($key = array_search($module, $this->_ci_modules)))
		{
			$module_path = $this->CI->router->module_path($module);

			// Found? Remove it.
			if ($module_path)
			{
				// Make the module as not loaded.
				unset($this->_ci_modules[$key]);

				// Remove the module as we remove package.
				$this->remove_package_path($module_path, $remove_config);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * This method attempts to detect the module form the passed string.
	 * @access  protected
	 * @param   string  $class  The string used to detect the module.
	 * @return  mixed   array of module and class if found, else false.
	 */
	protected function _detect_module($class)
	{
		// First, we remove the extension and trim slashes.
		$class = preg_replace('/\.php$/', '', trim($class, '/'));

		// Catch the position of the first slash.
		$first_slash = strpos($class, '/');

		// If there is a slash, proceed.
		// Make sure the module exits before returning the result.
		return ($first_slash && $this->CI->router->module_path($module = substr($class, 0, $first_slash)))
			? array($module, substr($class, $first_slash + 1))
			: false;
	}

	// --------------------------------------------------------------------

	/**
	 * Internal CI Data Loader
	 * @access  protected
	 * @param   array   $_ci_data   Data to load
	 * @return  void
	 */
	protected function _ci_load($_ci_data)
	{
		/**
		 * Here we loop through available views paths and see if the
		 * view exists. Priority here is for application/views/ and
		 * skeleton/views/ folders first.
		 */
		if (isset($_ci_data['_ci_view']))
		{
			foreach ($this->_ci_view_paths as $path => $cascade)
			{
				if (is_file($path.$_ci_data['_ci_view'].'.php'))
				{
					return parent::_ci_load($_ci_data);
				}
			}
		}

		// See if it's inside a module!
		$_ci_file = $_ci_data['_ci_path'] ?? $_ci_data['_ci_view'] ?? false;
		if ($_ci_file && ! is_file($_ci_file) && list($module, $class) = $this->_detect_module($_ci_file))
		{
			// Make sure the module is enabled.
			if ($module && ! $this->CI->router->module_enabled($module))
			{
				return;
			}

			// Module already loaded?
			elseif ( ! in_array($module, $this->_ci_modules))
			{
				$this->add_module($module);
			}

			// $_ci_data['_ci_view'] = $class;
			$_ci_data['_ci_path'] = $this->CI->router->module_path($module, 'views/'.$class.'.php');

			return parent::_ci_load($_ci_data);
		}

		return parent::_ci_load($_ci_data);
	}

	// --------------------------------------------------------------------

	/**
	 * Internal CI Stock Library Loader
	 *
	 * A little tweak so we can load our customized libraries.
	 *
	 * @param   string  $library_name   Library name to load
	 * @param   string  $file_path  Path to the library filename, relative to libraries/
	 * @param   mixed   $params     Optional parameters to pass to the class constructor
	 * @param   string  $object_name    Optional object name to assign to
	 * @return  void
	 */
	protected function _ci_load_stock_library($library_name, $file_path, $params, $object_name)
	{
		// We load our custom libraries if found.
		if (is_file(BASEPATH.'libraries/'.$file_path.$library_name.'.php')
			&& is_file(KBPATH.'libraries/KB_'.$library_name.'.php'))
		{
			// Has already been loaded.
			if (class_exists('KB_'.$library_name, false))
			{
				return;
			}

			// Load stock library first.
			include_once(BASEPATH.'libraries/'.$file_path.$library_name.'.php');

			// Now we load our custom library.
			include_once(KBPATH.'libraries/KB_'.$library_name.'.php');

			// Let the parent do the job!
			return parent::_ci_init_library($library_name, 'KB_', $params, $object_name);
		}

		return parent::_ci_load_stock_library($library_name, $file_path, $params, $object_name);
	}

	// --------------------------------------------------------------------

	/**
	 * CI Autoloader
	 *
	 * Overrides CodeIgniter default method to use our custom file.
	 *
	 * Loads component listed in the config/autoload.php file.
	 *
	 * @used-by     CI_Loader::initialize()
	 * @return  void
	 */
	protected function _ci_autoloader()
	{
		// Default paths.
		$autoload_paths = array(KBPATH, APPPATH);

		// Module's path.
		if (null !== $this->module)
		{
			array_push($autoload_paths, $this->CI->router->module_path($this->module));
		}

		// Attempt to load files.
		foreach ($autoload_paths as $path)
		{
			// Environment-free "autoload.php".
			if (is_file($file = $path.'config/autoload.php'))
			{
				require_once($file);

				if (isset($autoload))
				{
					$this->autoload = deep_array_merge($this->autoload, $autoload);
					unset($autoload);
				}
			}

			// Environment-specific "autoload.php".
			if (is_file($file = $path.'config/'.ENVIRONMENT.'/autoload.php'))
			{
				require_once($file);

				if (isset($autoload))
				{
					$this->autoload = deep_array_merge($this->autoload, $autoload);
					unset($autoload);
				}
			}
		}

		if (empty($this->autoload = apply_filters('autoload', $this->autoload)))
		{
			return;
		}

		// Autoload packages
		elseif (isset($this->autoload['packages']))
		{
			foreach ($this->autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}

		// Load any custom config file
		if (isset($this->autoload['config']) && count($this->autoload['config']) > 0)
		{
			foreach ($this->autoload['config'] as $val)
			{
				$this->config($val);
			}
		}

		// Autoload helpers and languages
		foreach (array('helper', 'language') as $type)
		{
			if (isset($this->autoload[$type]) && count($this->autoload[$type]) > 0)
			{
				$this->$type($this->autoload[$type]);
			}
		}

		// Autoload drivers
		if (isset($this->autoload['drivers']))
		{
			$this->driver($this->autoload['drivers']);
		}

		// Load libraries
		if (isset($this->autoload['libraries']) && count($this->autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $this->autoload['libraries']))
			{
				$this->database();
				$this->autoload['libraries'] = array_diff($this->autoload['libraries'], array('database'));
			}

			// Load all other libraries
			$this->library($this->autoload['libraries']);
		}

		// Autoload models
		if (isset($this->autoload['model']))
		{
			$this->model($this->autoload['model']);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initializer
	 *
	 * @uses    CI_Loader::_ci_autoloader()
	 * @return  void
	 */
	public function initialize()
	{
		$this->module = $this->CI->router->module;
		parent::initialize();
	}

	// --------------------------------------------------------------------

	/**
	 * Method for quickly load items.
	 *
	 * @since   2.16
	 *
	 * @param   array   $autoload   the array of items to load.
	 * @return  void
	 */
	public function autoload($autoload = array())
	{
		// Nothing provided?
		if (empty($autoload))
		{
			return;
		}

		// Autoload packages
		elseif (isset($autoload['packages']))
		{
			foreach ($autoload['packages'] as $package_path)
			{
				$this->add_package_path($package_path);
			}
		}

		// Load any custom config file
		if (isset($autoload['config']) && count($autoload['config']) > 0)
		{
			foreach ($autoload['config'] as $val)
			{
				$this->config($val);
			}
		}

		// Autoload helpers and languages
		foreach (array('helper', 'language') as $type)
		{
			if (isset($autoload[$type]) && count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}
		}

		// Autoload drivers
		if (isset($autoload['drivers']))
		{
			$this->driver($autoload['drivers']);
		}

		// Load libraries
		if (isset($autoload['libraries']) && count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load all other libraries
			$this->library($autoload['libraries']);
		}

		// Autoload models
		if (isset($autoload['model']))
		{
			$this->model($autoload['model']);
		}
	}

}
