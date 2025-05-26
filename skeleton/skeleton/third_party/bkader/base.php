<?php
defined('BASEPATH') OR die;

if ( ! function_exists('detect_host')) {

    /**
     * detect_host
     *
     * Detects the current host (domain or IP) of the request.
     *
     * This function is useful for routing, multi-domain support,
     * or environment detection (e.g., dev vs. production).
     *
     * It attempts to determine the most accurate host in this order:
     *   1. HTTP_HOST         - Most reliable (user-facing host).
     *   2. SERVER_NAME       - Server's configured name (may differ from HTTP_HOST).
     *   3. HOSTNAME          - Set by some servers or CLI environments.
     *   4. SERVER_ADDR       - IP address of the server (IPv6 supported).
     *   5. Fallback to 'localhost'.
     *
     * @since 	2.108
     * @return 	string 	The detected host, always lowercased.
     */
	function detect_host()
	{
		static $host = null;

		// Use cached result if available.
		if ($host !== null) {
			return $host;
		} elseif (isset($_SERVER['HTTP_HOST'])) {
			$host = strtolower($_SERVER['HTTP_HOST']);
		} elseif (isset($_SERVER['SERVER_NAME'])) {
			$host = strtolower($_SERVER['SERVER_NAME']);
		} elseif (isset($_SERVER['HOSTNAME'])) {
			$host = strtolower($_SERVER['HOSTNAME']);
		} elseif (isset($_SERVER['SERVER_ADDR'])) {
			$host = strtolower((strpos($_SERVER['SERVER_ADDR'], '::') === false) ? $_SERVER['SERVER_ADDR'] : '['.$_SERVER['SERVER_ADDR'].']');
		} else {
			$host = 'localhost';
		}

		return $host;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_https')) {
	/**
	 * Detects if the current request is made over HTTPS.
	 *
	 * This function checks common server variables and headers that
	 * indicate HTTPS usage, including setups behind proxies or load
	 * balancers that set forwarded headers.
	 * The result is cached statically for performance on multiple
	 * calls within the same request.
	 *
	 * @since 	2.18
	 * @return 	bool 	True if HTTPS is detected, false otherwise.
	 */
	function is_https()
	{
		static $is_https = null;

		// Early return if the value was set already.
		if ($is_https !== null) {
			return $is_https;
		}

		$is_https = (
			// Check if the "HTTPS" server variable is set and is not explicitly turned off.
			// This is the most common way to detect HTTPS on standard setups.
			(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')

			// Check if the "X-Forwarded-Proto" header is set to "https".
			// This is typically used by reverse proxies or load balancers to indicate the original protocol.
			OR (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')

			// Check if the "X-Forwarded-Ssl" header is set to "on".
			// Some proxies use this header to indicate that the request was made over HTTPS.
			OR (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')

			// Check if the "Front-End-Https" header is set and is not "off".
			// This header is often set by specific load balancers (e.g., Microsoft's Front-End servers).
			OR (isset($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')

			// As a final fallback, check if the server port is set to "443".
			// Port 443 is the default port for HTTPS connections.
			OR (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
		);

		return $is_https;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_cli')) {
	/**
	 * Is CLI?
	 * Test to see if a request was made from the command line.
	 * @return  bool
	 */
	function is_cli()
	{
		static $is_cli = null;

		($is_cli === null) && $is_cli = (defined('KB_REST_REQUEST') && KB_REST_REQUEST === 'cli');

		return $is_cli;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_ajax')) {
	/**
	 * Is AJAX?
	 * Test to see if a request was made via AJAX, or maybe CLI.
	 *
	 * @param   bool   $maybe_cli
	 * @return  bool
	 */
	function is_ajax(bool $maybe_cli = false)
	{
		static $is_ajax = null;

		($is_ajax === null) && $is_ajax = (defined('KB_REST_REQUEST') && KB_REST_REQUEST === 'ajax');

		return ($is_ajax) ? true : ($maybe_cli ? is_cli() : false);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_api'))
{
	/**
	 * Is API?
	 *
	 * Test to see if a request was intended for API
	 *
	 * @return  bool
	 */
	function is_api()
	{
		static $is_api = null;

		($is_api === null) && $is_api = (defined('KB_REST_REQUEST') && KB_REST_REQUEST === 'api');

		return $is_api;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_uri')) {
	/**
	 * Get the safe, decoded, UTF-8 normalized request URI.
	 *
	 * @return string
	 */
	function safe_uri()
	{
		// Decode URL-encoded raw request URI.
		$uri = urldecode(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

		// Ensure UTF-8 internal encoding
		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding('UTF-8');
		}

		return $uri;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('normalize_url')) {
	/**
	 * Normalizes a URL.
	 *
	 * @since 	2.18
	 *
	 * @param 	string 	$url 	URL to normalize.
	 * @param 	bool 	$remove_protocol 	Whether to remove http/https protocol.
	 * @return 	string 	Normalized URL.
	 */
	function normalize_url($url, $remove_protocol = false)
	{
		static $regex_double_slash = '/([^:])(\/{2,})/';
		static $regex_protocol = '/^https?:/';

		$url = preg_replace($regex_double_slash, '$1/', $url);
		return $remove_protocol ? preg_replace($regex_protocol, '', $url) : $url;
	}
}

// --------------------------------------------------------------------
// Paths functions.
// --------------------------------------------------------------------

if ( ! function_exists('normalize_path')) {
	// Define directory separator.
	defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

	/**
	 * Normalizes a filesystem path.
	 *
	 * @since 	2.0.1
	 * @since 	2.18 	Added $realpath arg
	 *
	 * @param 	string 	$path 		Path to normalize.
	 * @param 	string 	$realpath 	Whether to use realpath.
	 * @return 	string 	Normalized path.
	 */
	function normalize_path($path, $realpath = false)
	{
		$path = preg_replace('/[\\\\\/]+/', DS, $path);

		// Uppercase drive letter on Windows systems.
		if (':' === substr($path, 1, 1) && ! ctype_upper($path[0])) {
			$path = ucfirst($path);
		}

		return $realpath ? ((is_file($path) OR is_dir($path)) ? $path : false) : $path;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('normalize_file')) {
	/**
	 * Simply addes ".php" extension to the given file name.
	 *
	 * 	'test' => 'Test.php'
	 *
	 * @param 	string 	$filename 	The file name to format.
	 * @param 	string 	$ucfirst 	Whether to use ucfirst
	 * @return 	string 	The formatted file name.
	 */
	function normalize_file($filename, $ucfirst = true)
	{
		static $regex_php_ext = '/\.php$/i';

		$filename = preg_replace($regex_php_ext, '', trim($filename)).'.php';
		return $ucfirst ? ucfirst($filename) : $filename;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('path_is_url')) {
	/**
	 * Tests if the given path is a URL with or without protocol.
	 *
	 * @since 	2.11
	 *
	 * @param 	string 	$path 	The resource path or URL.
	 * @return 	bool 	true if the path is a valid URL, else false.
	 */
	function path_is_url($path)
	{
		// Use static regex for improved performance on repeated calls
		static $regex = '/^(https?:)?\/\/[a-zA-Z0-9.-]+(\.[a-zA-Z]{2,}|:\d+)?(\/.*)?$/i';
		return preg_match($regex, $path) ? true : false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_internal_url')) {
	/**
	 * Checks whether the given URL is an internal one.
	 *
	 * @since 	2.122
	 *
	 * @param 	string
	 * @return 	bool
	 */
	function is_internal_url(string $uri = '')
	{
		static $host;

		if (empty($uri) OR ! path_is_url($uri)) {
			return true;
		} elseif ($host === null) {
			$host = detect_host();
		}

		return (parse_url($uri, PHP_URL_HOST) === $host);
	}
}

// --------------------------------------------------------------------
// Files importers.
// --------------------------------------------------------------------

if ( ! function_exists('import')) {
	// Define directory separator.
	defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

	/**
	 * Function for loading files.
	 *
	 * @since 	2.0
	 *
	 * @param 	string 	$path 	The path to the file.
	 * @param 	string 	$folder 	The folder where the file should be.
	 * @return 	void
	 */
	function import($path, $folder = 'core')
	{
		$path = normalize_file($path, false);

		foreach (array(APPPATH, KBPATH, BASEPATH) as $basepath) {
			if (is_file($file = $basepath.$folder.DS.$path)) {
				require_once($file);
				break; // Stop searching once the file is found.
			}
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('import_with_vars'))
{
	/**
	 * Including files with optional variables.
	 * @param 	string 	$filepath 	The file to include.
	 * @param 	array 	$vars 		Variables to pass.
	 * @param 	bool 	$print 		Whether to print th output.
	 * @return 	mixed
	 */
	function import_with_vars(string $filepath, array $vars = array(), bool $print = false) {
		if ( ! is_file($filepath)) {
			return null;
		} elseif ( ! empty($vars) && is_array($vars)) {
			extract($vars, EXTR_SKIP);
		}

		ob_start();
		include_once($filepath);
		$output = ob_get_clean();

		if ( ! $print) {
			return $output;
		}

		print $output;
	}
}

// --------------------------------------------------------------------
// Array Helpers.
// --------------------------------------------------------------------

if ( ! function_exists('array_clean')) {
	/**
	 * This function make sure to clean the given array by first removing
	 * white-spaces from array values, then removing empty elements and
	 * final keep unique values.
	 *
	 * @since 	1.40
	 *
	 * @param 	array
	 * @return 	array
	 */
	function array_clean(array $array)
	{
		$array = array_map('trim', array_filter($array));
		return array_unique(array_filter($array));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_clean_keys')) {
	/**
	 * Similar to the above function, except that it's a bit smarter and
	 * does not delete different keys with similar values.
	 *
	 * @since 	2.105
	 *
	 * @param 	array 	$array
	 * @return 	array
	 */
	function array_clean_keys(array $array)
	{
		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				$value = array_clean_keys($value);
			} elseif ((is_string($value) && empty($value = trim($value))) OR $value === null) {
				unset($array[$key]);
			}
		}
		return $array;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_merge_unique')) {
	/**
	 * Merges multiple arrays and only keeps unique values.
	 *
	 * @since 	2.18
	 * @param 	mixed
	 * @return 	array
	 */
	function array_merge_unique()
	{
		$array = array();
		$args = func_get_args();

		foreach ($args as $arg) {
			$array = array_merge($array, array_filter($arg));
		}

		return array_unique($array);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_array_merge')) {
	/**
	 * Merges multiple arrays, recursively, and returns the merged array.
	 *
	 * This function is similar to PHP's array_merge_recursive() function,
	 * but it handles non-array values differently. When merging values
	 * that are not both arrays, the latter value replaces the former
	 * rather than merging with it.
	 *
	 * @param 	array 	$array1
	 * @param 	array 	$array2
	 * @return 	array
	 */
	function deep_array_merge(array $array1, array $array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => $value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = deep_array_merge($merged[$key], $value);
			} elseif (is_numeric($key)) {
				in_array($value, $merged) OR $merged[] = $value;
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_merge_exists'))
{
	/**
	 * Merges elements from '$array2' into '$array1' only if key exists.
	 *
	 * @param 	array 	$array1
	 * @param 	array 	$array2
	 * @return 	array 	$array1;
	 */
	function array_merge_exists($array1, $array2)
	{
		foreach ($array2 as $key => $value)
		{
			array_key_exists($key, $array1) && $array1[$key] = $value;
		}

		return $array1;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_truncate'))
{
	/**
	 * Limits the number of elements in an array to the specified limit.
	 *
	 * This function trims the array to keep either the first or last elements,
	 * based on the specified mode ('first' or 'last').
	 *
	 * @param 	array 	$haystack	The array to be limited.
	 * @param 	int 	$limit 		The maximum number of elements to keep.
	 * @param 	string 	$mode		Optional. Determines which elements to keep.
	 * @return 	array 	The trimmed array with the specified number of elements.
	 */
	function array_truncate(array &$haystack, int $limit, string $mode = 'first')
	{
		if (count($haystack) > $limit) {
			if ($mode === 'last') {
				// Keep the last $limit elements
				$haystack = array_slice($haystack, -$limit);
			} else {
				// Default: Keep the first $limit elements
				$haystack = array_slice($haystack, 0, $limit);
			}
		}

		return $haystack;
	}
}

// --------------------------------------------------------------------
// JSON files functions.
// --------------------------------------------------------------------

if ( ! function_exists('json_read_file')) {
	/**
	 * json_read_file
	 *
	 * Function for reading JSON encoded files content.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$path 	The path to the file to read.
	 * @return 	mixed 	Array if the file is found and valid, else false.
	 */
	function json_read_file($path)
	{
		// Make sure function remember read files.
		static $cached = array();

		// No already cached?
		if ( ! isset($cached[$path])) {
			// Make sure the file exists.
			if (true !== is_file($path)) {
				return false;
			}

			// Get the content of the file and cache it if found.
			$content = @file_get_contents($path);
			$content = json_decode($content, true);
			is_array($content) && $cached[$path] = $content;
		}

		return isset($cached[$path]) ? $cached[$path] : false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('json_write_file')) {
	/**
	 * json_write_file
	 *
	 * Function writing Arrays/Objects into a json file.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$path 		The path to the file.
	 * @param 	mixed 	$data 		The data to write to file.
	 * @param 	bool 	$override 	Whether to override all file content.
	 * @return 	bool 	true if the file was written, else false.
	 */
	function json_write_file($path, $data = array(), $override = false)
	{
		// We shall not override content?
		if (false === $override && false !== ($old_data = json_read_file($path))) {
			$data = array_replace_recursive($old_data, (array) $data);
		}

		function_exists('write_file') OR import('file_helper', 'helpers');
		return write_file($path, json_encode($data, JSON_PRETTY_PRINT));
	}
}

// --------------------------------------------------------------------
// Usernames functions.
// --------------------------------------------------------------------

if ( ! function_exists('forbidden_usernames')) {
	/**
	 * This function returns an array of all possible forbidden usernames.
	 *
	 * @since 	2.0
	 *
	 * @return 	array
	 */
	function forbidden_usernames()
	{
		static $usernames;

		if (empty($usernames)) {
			$usernames =  is_file($filepath = KBPATH.'third_party/bkader/inc/usernames.php')
				? require_once($filepath)
				: array();
		}

		return $usernames;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_forbidden_username')) {
	/**
	 * Checks whether the given name is forbidden.
	 *
	 * @since 	2.0
	 *
	 * @param 	string 	$username
	 * @return 	bool 	true if the username is forbidden, else false.
	 */
	function is_forbidden_username($username)
	{
		return (in_array($username, forbidden_usernames()));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('traceback')) {
	/**
	 * Function that can be used to trace back which function/method
	 * called the one this is used on.
	 *
	 * @since 	2.18
	 *
	 * @param 	string 	$key
	 * @return 	mixed
	 */
	function traceback($key = null)
	{
		$info = debug_backtrace()[1];
		return isset($key, $info[$key]) ? $info[$key] : $info;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('http_build_url')) {
	defined('HTTP_URL_REPLACE') 		OR define('HTTP_URL_REPLACE', 1);
	defined('HTTP_URL_JOIN_PATH')		OR define('HTTP_URL_JOIN_PATH', 2);
	defined('HTTP_URL_JOIN_QUERY') 		OR define('HTTP_URL_JOIN_QUERY', 4);
	defined('HTTP_URL_STRIP_USER') 		OR define('HTTP_URL_STRIP_USER', 8);
	defined('HTTP_URL_STRIP_PASS') 		OR define('HTTP_URL_STRIP_PASS', 16);
	defined('HTTP_URL_STRIP_AUTH') 		OR define('HTTP_URL_STRIP_AUTH', 32);
	defined('HTTP_URL_STRIP_PORT') 		OR define('HTTP_URL_STRIP_PORT', 64);
	defined('HTTP_URL_STRIP_PATH') 		OR define('HTTP_URL_STRIP_PATH', 128);
	defined('HTTP_URL_STRIP_QUERY') 	OR define('HTTP_URL_STRIP_QUERY', 256);
	defined('HTTP_URL_STRIP_FRAGMENT')	OR define('HTTP_URL_STRIP_FRAGMENT', 512);
	defined('HTTP_URL_STRIP_ALL')		OR define('HTTP_URL_STRIP_ALL', 1024);

	/**
	 * Build a URL.
	 *
	 * The parts of the second URL will be merged into the first according
	 * to the flags argument.
	 *
	 * @param 	mixed 	$url 		part(s) of) a URL in form of a string or
	 *                       		associative array like parse_url() returns.
	 * @param 	mixed 	$parts 		same as the first argument.
	 * @param 	int 	$flags 		a bitmask of binary or'ed HTTP_URL constants
	 *                      		HTTP_URL_REPLACE is the default.
	 * @param 	array 	$new_url 	if set, it will be filled with the parts
	 *                          	of the composed url like parse_url() would return.
	 * @return 	Returns the new URL as string on success or false on failure.
	 */
	function http_build_url($url, $parts = array(), $flags = HTTP_URL_REPLACE, &$new_url = array())
	{
		static $keys;

		empty($keys) && $keys = array('user', 'pass', 'port', 'path', 'query', 'fragment');

		is_array($url) OR $url = parse_url($url);
		is_array($parts) OR $parts = parse_url($parts);

		isset($url['query']) && is_string($url['query']) OR $url['query'] = null;
		isset($parts['query']) && is_string($parts['query']) OR $parts['query'] = null;


		// HTTP_URL_STRIP_ALL and HTTP_URL_STRIP_AUTH cover several other flags.
		if ($flags & HTTP_URL_STRIP_ALL) {
			$flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS | HTTP_URL_STRIP_PORT | HTTP_URL_STRIP_PATH | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT;
		} elseif ($flags & HTTP_URL_STRIP_AUTH) {
			$flags |= HTTP_URL_STRIP_USER | HTTP_URL_STRIP_PASS;
		}

		// Schema and host are alwasy replaced
		foreach (array('scheme', 'host') as $part) {
			isset($parts[$part]) && $url[$part] = $parts[$part];
		}

		if ($flags & HTTP_URL_REPLACE) {
			foreach ($keys as $key) {
				isset($parts[$key]) && $url[$key] = $parts[$key];
			}
		} else {
			if (isset($parts['path']) && ($flags & HTTP_URL_JOIN_PATH)) {
				if (isset($url['path']) && substr($parts['path'], 0, 1) !== '/') {
					// Workaround for trailing slashes
					$url['path'] .= 'a';
					$url['path'] = rtrim(str_replace(basename($url['path']), '', $url['path']), '/' ).'/'.ltrim($parts['path'], '/');
				} else {
					$url['path'] = $parts['path'];
				}
			}

			if (isset($parts['query']) && ($flags & HTTP_URL_JOIN_QUERY)) {
				if (isset($url['query'])) {
					parse_str($url['query'], $url_query);
					parse_str($parts['query'], $parts_query);

					$url['query'] = http_build_query(array_replace_recursive($url_query, $parts_query));
				} else {
					$url['query'] = $parts['query'];
				}
			}
		}

		if (isset($url['path']) && $url['path'] !== '' && substr($url['path'], 0, 1) !== '/') {
			$url['path'] = '/'.$url['path'];
		}

		foreach ($keys as $key) {
			$strip = 'HTTP_URL_STRIP_'.strtoupper($key);
			if ($flags & constant($strip)) {
				unset($url[$key]);
			}
		}

		$parsed_string = '';

		empty($url['scheme']) OR $parsed_string .= $url['scheme'].'://';

		if ( ! empty($url['user'])) {
			$parsed_string .= $url['user'];

			isset($url['pass']) && $parsed_string .= ':'.$url['pass'];

			$parsed_string .= '@';
		}

		empty($url['host']) OR $parsed_string .= $url['host'];

		empty($url['port']) OR $parsed_string .= ':'.$url['port'];

		empty($url['path']) OR $parsed_string .= $url['path'];

		empty($url['query']) OR $parsed_string .= '?'.$url['query'];

		empty($url['fragment']) OR $parsed_string .= '#'.$url['fragment'];

		$new_url = $url;

		return $parsed_string;
	}
}
