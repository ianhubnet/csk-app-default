<?php

/**
 *---------------------------------------------------------------
 * CodeIgniter Skeleton Front Controller
 *---------------------------------------------------------------
 *
 * This is the entry point for all HTTP and CLI requests.
 * It resolves core paths, defines environment settings,
 * sets up error reporting, and loads the application bootstrap.
 *
 * @package     CodeIgniter Skeleton (CSK)
 * @author      Abdelkader Bouyakoub
 * @link        https://github.com/bkader/skeleton
 */

ob_start(); // Start output buffering to ensure headers can be sent later.

/**
 * Define the directory separator constant.
 *
 * This ensures platform-independent path building.
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * ------------------------------------------------------------------------
 * Application Bootstrapping Closure
 * ------------------------------------------------------------------------
 *
 * This IIFE (Immediately Invoked Function Expression) handles everything:
 * - Resolves directory paths
 * - Defines core constants
 * - Detects and sets the environment
 * - Configures PHP error reporting
 * - Ensures compatibility with CLI and web server
 */
(static function (): void {

	/**
	 * Normalize working directory for CLI.
	 *
	 * Ensures relative paths behave as expected.
	 */
	if (PHP_SAPI === 'cli' || defined('STDIN')) {
		chdir(dirname(__FILE__));
	}

	/**
	 * Define SELF as the name of the front controller.
	 *
	 * Used later for error messages.
	 */
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	/**
	 * Path resolver closure.
	 *
	 * @param string $path  The folder path to resolve.
	 * @param string $name  Optional name for error context (e.g., "system").
	 *
	 * @return string       Absolute path.
	 *
	 * @throws RuntimeException if the path is invalid.
	 */
	$resolve = static function (string $path, string $name = ''): string {
		// Try resolving to absolute path
		if (($real = realpath($path)) !== false) {
			return $real;
		}

		// Normalize slashes and check directory existence manually
		$real = rtrim(str_replace(['/', '\\'], DS, $path), DS);
		if (is_dir($real)) {
			return $real;
		}

		// Build a meaningful error message
		$name  = empty($name) ? basename($path) : $name;
		$error = sprintf('Your %s folder path is invalid. Please fix "%s".', $name, SELF);

		// CLI vs. Web error display
		if (PHP_SAPI === 'cli' || defined('STDIN')) {
			fwrite(STDERR, $error . PHP_EOL);
		} else {
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo $error;
		}
		exit(3); // EXIT_CONFIG
	};

	/**
	 * Define base constants used by the system.
	 *
	 * These are required by the CodeIgniter core.
	 */

	// Path to this front controller (index.php)
	define('FCPATH', __DIR__.DS);

	// Path to CI Skeleton system folder
	define('BASEPATH', $resolve('skeleton', 'system').DS);

	// Path to the application folder
	define('APPPATH', $resolve('application', 'application').DS);

	// The name of the "system" folder (used internally)
	define('SYSDIR', basename(BASEPATH));

	// --------------------------------------------------------------------
	// ENVIRONMENT SETUP
	// --------------------------------------------------------------------

	/**
	 * Environment file path (APPPATH/config/environment)
	 *
	 * This file should contain a single line: "development", "testing", or "production".
	 */
	$envfile = APPPATH.'config'.DS.'environment';

	/**
	 * Default to "development" if file does not exist.
	 * Also create the file with the default value.
	 */
	if (!is_file($envfile)) {
		$environment = 'development';
		file_put_contents($envfile, $environment, LOCK_EX);
	} else {
		$environment = trim(file_get_contents($envfile));
	}

	/**
	 * Fallback if file contents are invalid.
	 * Only the three official environments are allowed.
	 */
	if (!in_array($environment, ['development', 'testing', 'production'], true)) {
		$environment = 'development';
		file_put_contents($envfile, $environment, LOCK_EX);
	}

	/**
	 * Define the ENVIRONMENT constant.
	 */
	define('ENVIRONMENT', $environment);

	/**
	 * Set PHP error reporting levels based on ENVIRONMENT.
	 *
	 * You may customize this if you add more environments.
	 */
	switch (ENVIRONMENT) {
		case 'development':
		case 'testing':
			// In development/testing, we want to show as many errors as possible
			// to helper make sure they don't make it to production. And save
			// us hours of painful debugging.
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
			define('SHOW_DEBUG_BACKTRACE', true);
			define('CI_DEBUG', true);
			break;
		case 'production':
			// Don't show any errors in production environment. Instead, let the
			// system catch it and display a generic error message.
			error_reporting(E_ALL & ~E_DEPRECATED);
			ini_set('display_errors', '0');
			define('SHOW_DEBUG_BACKTRACE', false);
			define('CI_DEBUG', false);
			break;

		default:
			// This should never happen because we validated earlier
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo 'The application environment is not set correctly.';
			exit(1); // EXIT_ERROR
	}

})(); // <-- END of bootstrapping closure

/**
 * Finally, load the application bootstrap.
 *
 * This file initializes autoloaders, core services, and
 * kicks off CodeIgniter Skeleton.
 */
require_once BASEPATH.'bootstrap.php';
