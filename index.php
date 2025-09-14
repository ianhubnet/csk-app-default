<?php

/**
 *---------------------------------------------------------------
 * CiSkeleton Front Controller
 *---------------------------------------------------------------
 *
 * This is the entry point for all HTTP and CLI requests.
 * It resolves core paths, defines environment settings,
 * sets up error reporting, and loads the application bootstrap.
 *
 * @package    CiSkeleton
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @link       https://github.com/bkader/skeleton
 */

ob_start(); // Start output buffering to ensure headers can be sent later.

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
(function () {

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
	$resolve = function ($path, $name = '') {
		// Try resolving to absolute path
		if (($real = realpath($path)) !== false) {
			return $real;
		}

		// Normalize slashes and check directory existence manually
		$real = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
		if (is_dir($real)) {
			return $real;
		}

		// Build a meaningful error message
		$name  = empty($name) ? basename($path) : $name;
		$error = sprintf('Your %s folder path is invalid. Please fix "%s".', $name, SELF);

		// CLI vs. Web error display
		if (PHP_SAPI === 'cli' || defined('STDIN')) {
			fwrite(STDERR, $error.PHP_EOL);
		} else {
			header('HTTP/1.1 503 Service Unavailable.', true, 503);
			echo $error;
		}
		exit(3); // EXIT_CONFIG
	};

	/**
	 * Define base constants used by the system.
	 *
	 * These are required by the CiSkeleton core.
	 */

	// Path to this front controller (index.php)
	define('FCPATH', __DIR__.DIRECTORY_SEPARATOR);

	// Path to CiSkeleton system folder
	define('BASEPATH', $resolve('skeleton', 'system').DIRECTORY_SEPARATOR);

	// Path to the application folder
	define('APPPATH', $resolve('application', 'application').DIRECTORY_SEPARATOR);

	// The name of the "system" folder (used internally)
	define('SYSDIR', basename(BASEPATH));
})(); // <-- END of bootstrapping closure

/**
 * Finally, load the application bootstrap.
 *
 * This file initializes autoloaders, core services,
 * and kicks off CiSkeleton.
 */
require_once BASEPATH.'bootstrap.php';
