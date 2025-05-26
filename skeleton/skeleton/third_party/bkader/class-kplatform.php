<?php
defined('BASEPATH') OR die;

/**
 * Directory Separator.
 * @since 	1.0
 */
defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

/**
 * CodeIgniter Skeleton version.
 * @since 	1.0
 */
const KB_VERSION = '2.145';

/**
 * Constants useful for expressing human-readable data sizes
 * in their respective number of bytes.
 * @since   1.0
 */
const KB_IN_BYTES = 1024;
const MB_IN_BYTES = 1024 * KB_IN_BYTES;
const GB_IN_BYTES = 1024 * MB_IN_BYTES;
const TB_IN_BYTES = 1024 * GB_IN_BYTES;

/**
 * Constants for expressing human-readable intervals.
 * @since 	2.0
 */
const MINUTE_IN_SECONDS = 60;
const HOUR_IN_SECONDS   = 60 * MINUTE_IN_SECONDS;
const DAY_IN_SECONDS    = 24 * HOUR_IN_SECONDS;
const WEEK_IN_SECONDS   = 7 * DAY_IN_SECONDS;
const MONTH_IN_SECONDS  = 30 * DAY_IN_SECONDS;
const YEAR_IN_SECONDS   = 365 * DAY_IN_SECONDS;

/**
 * Array of ignored files.
 * @since 	2.18
 */
const KB_IGNORED_FILES = array('.', '..', '.github', '.gitkeep', 'index.html', '.htaccess', '__MACOSX', '.DS_Store');

/**
 * KPlatform Class
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.16
 */
final class KPlatform
{
	/**
	 * CI Skeleton name, short name, keywords and slogan.
	 * @var string
	 */
	const LABEL  = 'CI Skeleton';
	const SHORT  = 'CSK';
	const KEYWORDS = 'codeigniter, skeleton, algeria, ianhub, kader';
	const SLOGAN = 'The Power to Build, The Freedom to Create';

	/**
	 * CodeIgniter Skeleton Author and Author URL.
	 * @var string
	 */
	const AUTHOR     = 'Kader Bouyakoub';
	const AUTHOR_URL = 'http://bit.ly/KaderGhb';

	/**
	 * Ianhub Site URL.
	 * @var string
	 */
	const SITE_URL = 'https://bit.ly/IanhubDz';

	/**
	 * URL to CodeIgniter Skeleton Wiki.
	 * @var string
	 */
	const WIKI_URL = 'http://bit.ly/2o3Hk8H';

	/**
	 * Skeleton Shop URL.
	 * @var string
	 */
	const SHOP_URL = 'https://bit.ly/IanhubShop';

	/**
	 * Array of protected modules.
	 * @since   2.16
	 * @var     array
	 */
	protected static $protected_modules = array(
		'languages',
		'media',
		'modules',
		'reports',
		'themes',
		'users'
	);

	/**
	 * Array of ignored files and directories.
	 * @since 	2.71
	 * @var 	array
	 */
	public static $ignored_files = array(
		'admin',
		'Admin.php',
		'Ajax.php',
		'Auth.php',
		'Content.php',
		'Help.php',
		'Main.php',
		'Reports.php',
		'Seo.php',
		'Settings.php',
		'User.php'
	);

	/**
	 * Array of drivers to be added to `Kbcore`.
	 * @since 	2.141
	 * @var 	array
	 */
	protected static $drivers = array(
		'options',
		'jwt',
		'entities',
		'backups',
		'auth',
		'activities',
		'lang',
		'nonce',
		'files',
		'modules',
		'roles',
		'assets',
		'theme',
		'groups',
		'objects',
		'users',
		'purge'
	);

	/**
	 * Array of front-end contexts.
	 * @since   2.16
	 * @var     array
	 */
	protected static $contexts = array('ajax', 'api', 'process');

	/**
	 * Array of admin contexts.
	 * @since 	2.105
	 * @var 	array
	 */
	protected static $admin_contexts = array('content', 'help', 'reports', 'settings');

	/**
	 * Array of default config items.
	 * @since 	2.18
	 * @var 	array
	 */
	protected static $config;

	/**
	 * Cached base url.
	 * @var string
	 */
	protected static $base_url;

	/**
	 * Method for defining all initial Skeleton constants.
	 *
	 * @since   2.0
	 */
	public static function constants()
	{
		// Define a constant for the current UNIX timestamp to use throughout the application.
		defined('TIME') OR define('TIME', time());

		// Unique identifier for the current request, used for debugging and tracking purposes.
		// Combines the process ID and a unique ID to ensure uniqueness across requests,
		// and hashes the result with SHA-1 for obfuscation and a fixed-length format.
		define('KB_REQUEST_ID', sha1(uniqid(getmypid(), true)));

		// Define the HMAC algorithm to use for hashing. Fallback to 'sha1' if 'hash' function is unavailable.
		define('KB_HMAC_ALGO', function_exists('hash') ? 'sha256' : 'sha1');

		// Check if OpenSSL encryption is available and define a constant accordingly.
		define('KB_OPENSSL_ENCRYPT', function_exists('openssl_encrypt'));

		// Set the default cipher algorithm for encryption to AES-256-CBC.
		define('KB_CIPHER_ALGO', 'aes-256-cbc');

		// Check if the application is running in a command-line interface (CLI) environment.
		if (is_cli())
		{
			// Remove memory limits for CLI to allow heavy tasks to run without constraints.
			@ini_set('memory_limit', '-1');
			define('KB_MEMORY_LIMIT', '-1');

			// Disable execution time limits for CLI scripts.
			ini_set('max_execution_time', 0);

			// Disable HTML formatting for errors in CLI to ensure plain-text output.
			ini_set('html_errors', 0);

			// Remove strings prepended or appended to error messages in CLI.
			ini_set('error_prepend_string', '');
			ini_set('error_append_string', '');

			// Prevent the script from being aborted if the user disconnects (e.g., in long-running tasks).
			ignore_user_abort(true);
		}
		else
		{
			// Set memory limit for web requests to 256M (or higher for memory-intensive tasks).
			@ini_set('memory_limit', '256M');
			// Store the current memory limit in a constant for reference.
			define('KB_MEMORY_LIMIT', @ini_get('memory_limit'));

			// Allow web requests to run for up to 10 minutes (600 seconds).
			ini_set('max_execution_time', 600);

			// Enable session security features for web environments.
			// Prevent JavaScript from accessing session cookies (reduces XSS risk).
			ini_set('session.cookie_httponly', 1);
			// Disallow passing session IDs in URLs (forces use of cookies only).
			ini_set('session.use_only_cookies', 1);
			// Ensure cookies are transmitted securely over HTTPS if the request is secure.
			ini_set('session.cookie_secure', is_https());

			// Configure file upload limits for web requests.
			// Maximum size for POST data (affects file uploads as well).
			ini_set('post_max_size', '16M');
			// Maximum allowed size for a single uploaded file.
			ini_set('upload_max_filesize', '16M');
			// Maximum number of files allowed to be uploaded simultaneously.
			ini_set('max_file_uploads', 20);
		}

		// Set regular expression and input limits to handle complex patterns efficiently.
		// Allow up to 10 million characters for backtracking in regex operations.
		ini_set('pcre.backtrack_limit', 10000000);
		// Disable JIT (Just-In-Time) compilation for regex as it may cause issues in some cases.
		ini_set('pcre.jit', false);
		// Limit the maximum time (in seconds) a script spends parsing input data.
		ini_set('max_input_time', 600);
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a base url.
	 *
	 * @return 	string
	 */
	public static function generate_base_url()
	{
		if ( ! isset(self::$base_url))
		{
			self::$base_url = is_https() ? 'https://' : 'http://';
			self::$base_url .= $_SERVER['SERVER_NAME'];
			self::$base_url .= substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
		}
		return self::$base_url;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a target file from a .dist template.
	 *
	 * @param 	string 	$template 	Full path to the .dist file.
	 * @param 	string 	$target 	Full path to the generated file.
	 * @param 	mixed 	$replace 	Associative array of replacements.
	 * @param 	bool 	$php_tag 	Whether to prepend "<?php\n"
	 * @return 	bool
	 */
	public static function dist_to_file(string $template, string $target, array|null $replaces = null, bool $php_tag = true)
	{
		$handle = null;

		 try {
			// Make sure the template file exists.
			if ( ! is_file($template))
			{
				throw new RuntimeException("Template file not found: {$template}");
			}

			// Get template file content and make sure it's valid.
			$content = file_get_contents($template);
			if ($content === false)
			{
				throw new RuntimeException("Failed to read template: {$template}");
			}

			// Make sure the target directory exists and is writable.
			$target_dir = dirname($target);
			if ( ! is_dir($target_dir) && ! mkdir($target_dir, 0755, true) && ! is_dir($target_dir))
			{
				throw new RuntimeException("Failed to create directory: {$target_dir}");
			}

			// Normalize line endings.
			$content = str_replace(array("\r\n", "\r"), "\n", $content);

			// Replace placeholders.
			if (is_array($replaces) && ! empty($replaces))
			{
				$content = str_replace(array_keys($replaces), array_values($replaces), $content);
			}

			if ($php_tag)
			{
				$content = "<?php\n" . $content;
			}

			// Open target file for writing.
			$handle = fopen($target, 'wb');
			if ( ! $handle)
			{
				throw new RuntimeException("Unable to open target file for writing: {$target}");
			}

			if (fwrite($handle, $content) === false)
			{
				throw new RuntimeException("Failed to write to file: {$target}");
			}

			return true;
		} catch (Throwable $e) {
			show_error($e->getMessage());
			return false;
		} finally {
			if (is_resource($handle))
			{
				fclose($handle);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a random string.
	 * @since 	2.50
	 * @return 	string
	 */
	public static function random_string($length = 8)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 1, $length);
	}

	// --------------------------------------------------------------------

	/**
	 * Instead of letting the Kbcore_options library do the job for us,
	 * we directly assign configuration here.
	 * @since   2.0
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public static function setup_options(array $assign_to_config = array())
	{
		isset(self::$config) OR self::$config = array();

		// Load defaults first.
		foreach(array(KBPATH, APPPATH) as $location)
		{
			// Load defaults.
			if (is_file($file_path = $location.'config/defaults.php'))
			{
				require_once($file_path);
				isset($config) && self::$config = deep_array_merge(self::$config, $config);
			}
		}

		// Load database settings.
		$db =& get_db_instance();
		if ( ! empty($options = $db->get('options')->result()))
		{
			foreach ($options as $option)
			{
				self::$config[$option->name] = is_numeric($option->value)
					? $option->value + 0
					: from_bool_or_serialize($option->value);
			}
		}

		// Set application timezone.
		define('TIMEZONE', self::$config['time_reference']);
		date_default_timezone_set(TIMEZONE);

		// Merge all config items and return them so KB_Config uses it.
		return array_merge($assign_to_config, self::$config);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for returning the array of protected modules names.
	 *
	 * @since   2.0
	 *
	 * @return  array
	 */
	public static function protected_modules()
	{
		return self::$protected_modules;
	}

	/**
	 * Checks whether the given module is protected or not.
	 *
	 * @since   2.16
	 *
	 * @param   string  $module     the module to check.
	 * @return  boolean true if protected, else false.
	 */
	public static function is_protected_module($module = null)
	{
		return ( ! empty($module) && in_array($module, self::$protected_modules));
	}

	/**
	 * Adds a module name to the protected modules.
	 *
	 * @since   2.16
	 *
	 * @param   mixed
	 * @return  boolean
	 */
	public static function add_protected_module()
	{
		if ( ! empty($args = func_get_args()))
		{
			self::$protected_modules = array_clean(array_merge_unique(self::$protected_modules, $args));
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for returning the array of admin contexts.
	 *
	 * @since   2.105
	 *
	 * @return  array
	 */
	public static function admin_contexts()
	{
		return self::$admin_contexts;
	}

	/**
	 * Adds a module name to the admin contexts.
	 *
	 * @since   2.105
	 *
	 * @param   mixed
	 * @return  boolean
	 */
	public static function add_admin_context()
	{
		if ( ! empty($args = func_get_args()))
		{
			self::$admin_contexts = deep_array_merge(self::$admin_contexts, $args);
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for returning the array of front-end contexts.
	 *
	 * @since   2.16
	 *
	 * @return  array
	 */
	public static function contexts()
	{
		return self::$contexts;
	}

	/**
	 * Adds a module name to the front-end modules.
	 *
	 * @since   2.16
	 *
	 * @param   mixed
	 * @return  boolean
	 */
	public static function add_context()
	{
		if ( ! empty($args = func_get_args()))
		{
			self::$contexts = deep_array_merge(self::$contexts, $args);
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	public static function drivers()
	{
		return self::$drivers;
	}

	public static function add_drivers()
	{
		if ( ! empty($args = func_get_args()))
		{
			self::$drivers = deep_array_merge(self::$drivers, $args);
			return true;
		}

		return false;
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('get_db_instance')) {
	/**
	 * Creates and returns an instance of DB.
	 * @since 	2.108
	 *
	 * @param 	string 	$group 	The connection group.
	 * @return 	mixed 	The connection if established, else exists.
	 */
	function &get_db_instance($group = null)
	{
		static $conns = array();
		static $paths = array(APPPATH, KBPATH, BASEPATH);

		empty($group = (string) $group) && $group = 'default';

		// Already cached?
		if (isset($conns[$group])) {
			return $conns[$group];
		}

		// We set it to null to prevent useless check.
		$conns[$group] = null;

		// Load 'DB.php'.
		if ( ! function_exists('DB')) {
			foreach ($paths as $path) {
				if (is_file($db_file = $path.'database/DB.php')) {
					require_once($db_file);
					$conns[$group] =& DB($group); // Cache the connection group.
					break;
				}
			}
		}

		/**
		 * We break the execution when establishing database connection
		 * fails and prevents infinite looping too.
		 */
		if (empty($conns[$group]) OR ! is_object($conns[$group])) {
			exit(EXIT_DATABASE);
		}

		return $conns[$group];
	}
}

/*
|--------------------------------------------------------------------------
| Base Controller File.
|--------------------------------------------------------------------------
|
| This automatically creates a "base_controller.php" file that is needed
| to set "default_controller" route.
|
*/
if ( ! is_file(APPPATH.'/config/base_controller.php'))
{
	KPlatform::dist_to_file(KBPATH.'dist/base_controller.dist', APPPATH.'config/base_controller.php');
}

/*
|--------------------------------------------------------------------------
| Language File
|--------------------------------------------------------------------------
|
| This automatically creates a "language.php" file that is needed
| to set "language" and "languages".
|
*/
if ( ! is_file(APPPATH.'config/language.php')) {
	KPlatform::dist_to_file(
		KBPATH.'dist/language.dist',
		APPPATH.'config/language.php',
		array('{language}' => 'english')
	);
}

/*
|--------------------------------------------------------------------------
| Trusted Hosts File
|--------------------------------------------------------------------------
|
| This automatically creates a "trusted_hosts.php" file that is needed
| to define a list of hostnames or IP addresses allowed to access the site.
|
*/
if ( ! is_file(APPPATH.'config/trusted_hosts.php')) {
	KPlatform::dist_to_file(
		KBPATH.'dist/trusted_hosts.dist',
		APPPATH.'config/trusted_hosts.php',
		array('{trusted_host}' => detect_host())
	);
}

/*
|--------------------------------------------------------------------------
| Application Config file.
|--------------------------------------------------------------------------
|
| This automatically creates a "config.php" file that is needed for the
| entire application to run.
|
*/
if ( ! is_file(APPPATH.'config/config.php')) {
	$base_url = KPlatform::generate_base_url();

	KPlatform::dist_to_file(
		KBPATH.'dist/config.dist',
		APPPATH.'config/config.php',
		array(
			'{base_url}'           => $base_url,
			'{encryption_key}'     => bin2hex(random_bytes(16)),
			'{encryption_key_256}' => bin2hex(random_bytes(32)),
			'{domain_url}'         => parse_url($base_url, PHP_URL_HOST),
			'{cookie_prefix}'      => substr(sha1($base_url), 0, 6).'_'
		)
	);

	/**
	 * Since the `config.php` file was missing, this means that this is
	 * the first time the application was accessed, therefore, we make
	 * sure to create the default `Welcome.php` controller as well as
	 * it view file so that we don't have an empty application.
	 */
	KPlatform::dist_to_file(KBPATH.'dist/welcome_controller.dist', APPPATH.'controllers/Welcome.php');
	KPlatform::dist_to_file(KBPATH.'dist/welcome_view.dist', APPPATH.'views/welcome.php', null, false);

}
