<?php
defined('BASEPATH') OR die;

/**
 * System Initialization File
 *
 * Loads the base classes and executes the request.
 *
 * @package     CodeIgniter
 * @subpackage  CodeIgniter
 * @category    Front-controller
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/
 */

/**
 * CodeIgniter Version
 *
 * @var string
 *
 */
	const CI_VERSION = '3.1.13';

/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
	if (is_file(KBPATH.'config/constants.php'))
	{
		require_once(KBPATH.'config/constants.php');
	}

	if (is_file(APPPATH.'config/'.ENVIRONMENT.'/constants.php'))
	{
		require_once(APPPATH.'config/'.ENVIRONMENT.'/constants.php');
	}

	if (is_file(APPPATH.'config/constants.php'))
	{
		require_once(APPPATH.'config/constants.php');
	}

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
	if (is_file(APPPATH.'core/Common.php'))
	{
		require_once(APPPATH.'core/Common.php');
	}

	if (is_file(KBPATH.'core/Common.php'))
	{
		require_once(KBPATH.'core/Common.php');
	}

	if (is_file(APPPATH.'config/'.ENVIRONMENT.'/constants.php'))
	{
		require_once(APPPATH.'config/'.ENVIRONMENT.'/constants.php');
	}

	require_once(BASEPATH.'core/Common.php');

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
	set_error_handler('_error_handler');
	set_exception_handler('_exception_handler');
	register_shutdown_function('_shutdown_handler');

/*
 * ------------------------------------------------------
 *  Set the subclass_prefix
 * ------------------------------------------------------
 *
 * Normally the "subclass_prefix" is set in the config file.
 * The subclass prefix allows CI to know if a core class is
 * being extended via a library in the local application
 * "libraries" folder. Since CI allows config items to be
 * overridden via data set in the main index.php file,
 * before proceeding we need to know if a subclass_prefix
 * override exists. If so, we will set this value now,
 * before any classes are loaded
 * Note: Since the config file data is cached it doesn't
 * hurt to load it here.
 */
	if ( ! empty($assign_to_config['subclass_prefix']))
	{
		get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
	}

/*
 * ------------------------------------------------------
 *  Should we use a Composer autoloader?
 * ------------------------------------------------------
 */
	if ($composer_autoload = config_item('composer_autoload'))
	{
		if ($composer_autoload === true)
		{
			is_file(APPPATH.'vendor/autoload.php')
				? require_once(APPPATH.'vendor/autoload.php')
				: log_message('error', '$config[\'composer_autoload\'] is set to true but '.APPPATH.'vendor/autoload.php was not found.');
		}
		elseif (is_file($composer_autoload))
		{
			require_once($composer_autoload);
		}
		else
		{
			log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
		}
	}

/*
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */
	$BM =& load_class('Benchmark', 'core');
	$BM->mark('total_execution_time_start');
	$BM->mark('loading_time:_base_classes_start');

/*
 * ------------------------------------------------------
 *  Instantiate the config class
 * ------------------------------------------------------
 *
 * Note: It is important that Config is loaded first as
 * most other classes depend on it either directly or by
 * depending on another class that uses it.
 *
 */
	$CFG =& load_class('Config', 'core', isset($assign_to_config) ? $assign_to_config : array());

/*
 * ------------------------------------------------------
 *  Instantiate the object cache class
 * ------------------------------------------------------
 */
	$EVT =& load_class('Events', 'core');

	/**
	 * Reference to the CI_Events method.
	 *
	 * Returns current CI_Events instance object
	 *
	 * @return CI_Events
	 */
	function &events_instance()
	{
		return CI_Events::get_instance();
	}

/*
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */
	$EXT =& load_class('Hooks', 'core', $CFG, $EVT);

/*
 * ------------------------------------------------------
 *  Is there a "pre_system" hook?
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  Instantiate the object cache class
 * ------------------------------------------------------
 */
	$REG =& load_class('Registry', 'core');

/**
 * ------------------------------------------------------
 * Trusted Host Stuff.
 * ------------------------------------------------------
 *
 * Note: This security measure is really crucial for
 * production environment. So please make sure to set
 * 'trusted_hosts_only' to true and define 'trusted_hosts'.
 */
	if ($CFG->item('trusted_hosts_only') && ! (PHP_SAPI === 'cli' OR defined('STDIN')))
	{
		$detected_host = detect_host();
		$trusted_hosts = $CFG->item('trusted_hosts', null, array('localhost'));

		$is_trusted = false;

		foreach ($trusted_hosts as $trusted_host) {
			if ( ! is_string($trusted_host)) {
				continue;
			} elseif ($trusted_host === $detected_host OR preg_match("#^$trusted_host$#i", $detected_host)) {
				$is_trusted = true;
				break;
			}
		}

		if ( ! $is_trusted) {
			$_error =& load_class('Exceptions', 'core');
			echo $_error->show_error(
				'Access through untrusted host',
				'Please contact your administrator. If you are an administrator, edit the "trusted_hosts" setting in config/defaults.php.',
				'error_403',
				403
			);
			exit(EXIT_ERROR);
		}
	}

/*
 * ------------------------------------------------------
 * Important charset-related stuff
 * ------------------------------------------------------
 *
 * Configure mbstring and/or iconv if they are enabled
 * and set MB_ENABLED and ICONV_ENABLED constants, so
 * that we don't repeatedly do extension_loaded() or
 * function_exists() calls.
 *
 * Note: UTF-8 class depends on this. It used to be done
 * in it's constructor, but it's _not_ class-specific.
 *
 */
	$charset = strtoupper(config_item('charset'));
	ini_set('default_charset', $charset);

	define('MB_ENABLED', extension_loaded('mbstring'));

	if (MB_ENABLED)
	{
		mb_internal_encoding($charset);
		mb_substitute_character('none');
	}

	// There's an ICONV_IMPL constant, but the PHP manual says that using
	// iconv's predefined constants is "strongly discouraged".
	define('ICONV_ENABLED', extension_loaded('iconv'));

/*
 * ------------------------------------------------------
 *  Load compatibility features
 * ------------------------------------------------------
 */

	require_once(BASEPATH.'core/compat/mbstring.php');
	require_once(BASEPATH.'core/compat/hash.php');
	require_once(BASEPATH.'core/compat/password.php');
	require_once(BASEPATH.'core/compat/standard.php');

/*
 * ------------------------------------------------------
 *  Instantiate the UTF-8 class
 * ------------------------------------------------------
 */
	$UNI =& load_class('Utf8', 'core', $charset);

/*
 * ------------------------------------------------------
 *  Instantiate the URI class
 * ------------------------------------------------------
 */
	$URI =& load_class('URI', 'core', $CFG);

/*
 * ------------------------------------------------------
 *  Instantiate the routing class and set the routing
 * ------------------------------------------------------
 */
	$RTR =& load_class('Router', 'core', $CFG, $URI, isset($routing) ? $routing : null);

/*
 * ------------------------------------------------------
 *  Instantiate the output class
 * ------------------------------------------------------
 */
	$OUT =& load_class('Output', 'core', $CFG, $URI, $BM);

/*
 * ------------------------------------------------------
 *  Is there a valid cache file? If so, we're done...
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('cache_override') === false && $OUT->_display_cache() === true)
	{
		exit;
	}

/*
 * -----------------------------------------------------
 * Load the security class for xss and csrf support
 * -----------------------------------------------------
 */
	$SEC =& load_class('Security', 'core', $CFG, $RTR, $charset);

/*
 * ------------------------------------------------------
 *  Load the Input class and sanitize globals
 * ------------------------------------------------------
 */
	$IN =& load_class('Input', 'core', $CFG, $SEC);
	$IN->is_localhost() && define('KB_LOCALHOST', true);

/*
 * ------------------------------------------------------
 *  Load the Language class
 * ------------------------------------------------------
 */
	$LANG =& load_class('Lang', 'core', $CFG, $RTR);

/*
 * ------------------------------------------------------
 *  Define 'KB_REST_REQUEST' if possible
 * ------------------------------------------------------
 *
 */
	// Okay, first things first: is this code running from
	// CLI like a true nerd? Maybe it's a cron job? A deploy
	// script? A lonely dev typing in the dark?
	if (PHP_SAPI === 'cli' OR defined('STDIN'))
	{
		// Yup, it's a CLI. Let's label it so everything
		// downstream knows it came from the abyss.
		define('KB_REST_REQUEST', 'cli');
	}
	// Oh no! Could it be... an AJAX request?
	elseif (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
	{
		// A valid AJAX appears! Let's give it a name before
		// it uses jQuery magic on us.
		define('KB_REST_REQUEST', 'ajax');
	}
	// Wait for it... do we smell JSON?
	elseif (isset($_SERVER['CONTENT_TYPE'])
		&& stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
	{
		// Ding ding! JSON detected. This must be a serious
		// API call. Probably from a real app.
		define('KB_REST_REQUEST', 'api');
	}
	// Still not sure? Maybe the Accept header is whispering
	// sweet JSON nothings?
	elseif (isset($_SERVER['HTTP_ACCEPT'])
		&& stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
	{
		// Another clue! They want JSON back.
		// We're clearly dealing with API royalty here.
		define('KB_REST_REQUEST', 'api');
	}
	// Fine. We give up. It's a boring ol' web request.

/*
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 *
 */
	// Load the base controller class
	require_once BASEPATH.'core/Controller.php';

	/**
	 * Reference to the CI_Controller method.
	 *
	 * Returns current CI instance object
	 *
	 * @return CI_Controller
	 */
	function &get_instance()
	{
		return CI_Controller::get_instance();
	}

	if (is_file(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php'))
	{
		require_once APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
	}

	// Set a mark point for benchmarking
	$BM->mark('loading_time:_base_classes_end');

/*
 * ------------------------------------------------------
 *  Sanity checks
 * ------------------------------------------------------
 *
 *  The Router class has already validated the request,
 *  leaving us with 3 options here:
 *
 *  1) an empty class name, if we reached the default
 *     controller, but it didn't exist;
 *  2) a query string which doesn't go through a
 *     is_file() check
 *  3) a regular request for a non-existing page
 *
 *  We handle all of these as a 404 error.
 *
 *  Furthermore, none of the methods in the app controller
 *  or the loader class can be called via the URI, nor can
 *  controller methods that begin with an underscore.
 */

	$e404 = false;
	$class = ucfirst($RTR->class);
	$method = $RTR->method;

	// if (empty($class) OR ! is_file(APPPATH.'controllers/'.$RTR->directory.$class.'.php'))
	if (empty($class))
	{
		$e404 = true;
	}
	else
	{
		/**
		 * We make sure to load module's 'boot.php' file if present.
		 * This is useful if we want to allow modules to register classes
		 * or do something else before controller is called.
		 * @since 2.105
		 */
		if (is_file($bootstrap = $RTR->module_path($RTR->module, 'boot.php')))
		{
			require_once($bootstrap);
		}

		if (is_file($class_path = $RTR->directory.$class.'.php'))
		{
			$RTR->path = realpath($class_path);
			require_once($class_path);
		}
		elseif (is_file($class_path = APPPATH.'controllers/'.$RTR->directory.$class.'.php'))
		{
			$RTR->path = realpath($class_path);
			require_once($class_path);
		}
		elseif (is_file($class_path = KBPATH.'controllers/'.$RTR->directory.$class.'.php'))
		{
			$RTR->path = realpath($class_path);
			require_once($class_path);
		}
		else
		{
			$e404 = true;
		}

		if ( ! class_exists($class, false) OR $method[0] === '_' OR method_exists('CI_Controller', $method))
		{
			$e404 = true;
		}
		elseif (method_exists($class, '_remap'))
		{
			$params = array($method, array_slice($URI->rsegments, 2));
			$method = '_remap';
		}
		elseif ( ! method_exists($class, $method))
		{
			$e404 = true;
		}
		/**
		 * DO NOT CHANGE THIS, NOTHING ELSE WORKS!
		 *
		 * - method_exists() returns true for non-public methods, which passes the previous elseif
		 * - is_callable() returns false for PHP 4-style constructors, even if there's a __construct()
		 * - method_exists($class, '__construct') won't work because CI_Controller::__construct() is inherited
		 * - People will only complain if this doesn't work, even though it is documented that it shouldn't.
		 *
		 * ReflectionMethod::isConstructor() is the ONLY reliable check,
		 * knowing which method will be executed as a constructor.
		 */
		else
		{
			$reflection = new ReflectionMethod($class, $method);
			if ( ! $reflection->isPublic() OR $reflection->isConstructor())
			{
				$e404 = true;
			}
		}
	}

	if ($e404)
	{
		if ( ! empty($RTR->routes['404_override']))
		{
			if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2)
			{
				$error_method = 'index';
			}

			$error_class = ucfirst($error_class);

			if ( ! class_exists($error_class, false))
			{
				if (is_file($e404_path = APPPATH.'controllers/'.$RTR->directory.$error_class.'.php'))
				{
					require_once($e404_path);
					$e404 = ! class_exists($error_class, false);
				}
				// Were we in a directory? If so, check for a global override
				elseif ( ! empty($RTR->directory) && is_file($e404_path = APPPATH.'controllers/'.$error_class.'.php'))
				{
					require_once($e404_path);
					if (($e404 = ! class_exists($error_class, false)) === false)
					{
						$RTR->directory = '';
					}
				}
			}
			else
			{
				$e404 = false;
			}
		}

		// Did we reset the $e404 flag? If so, set the rsegments, starting from index 1
		if ( ! $e404)
		{
			$class = $error_class;
			$method = $error_method;

			$URI->rsegments = array(
				1 => $class,
				2 => $method
			);
		}
		else
		{
			show_404($RTR->directory.$class.'/'.$method);
		}
	}

	if ($method !== '_remap')
	{
		$params = array_slice($URI->rsegments, 2);
	}

/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
	$EXT->call_hook('pre_controller');

/*
 * ------------------------------------------------------
 *  Instantiate the requested controller
 * ------------------------------------------------------
 */
	// Mark a start point so we can benchmark the controller
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

	$CI = new $class();

/*
 * ------------------------------------------------------
 *  Is there a "post_controller_constructor" hook?
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller_constructor');

/*
 * ------------------------------------------------------
 *  Call the requested method
 * ------------------------------------------------------
 */
	call_user_func_array(array(&$CI, $method), $params);

	// Mark a benchmark end point
	$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
	if ($EXT->call_hook('display_override') === false)
	{
		$OUT->_display();
	}

/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
	$EXT->call_hook('post_system');
