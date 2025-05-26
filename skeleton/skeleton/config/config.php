<?php
defined('BASEPATH') OR die;

/**
 * Config
 *
 * Main configuration file.
 *
 * @since 2.41
 */

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See https://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol'] = 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language'] = 'english';

/*
|--------------------------------------------------------------------------
| Available Site Languages
|--------------------------------------------------------------------------
|
| This defines the list of languages that should be enabled by default.
| Note that this setting is overriden once config stored in database
| is loaded.
|
*/
$config['languages'] = array('english');

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to true (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = true;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: true or false (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['enable_query_strings'] = false;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging FILENAME
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| 'log-'.date('Y-m-d').'.php'. No DIRECTORY_SEPARATOR(s), just the filename.
|
*/
$config['log_filename'] = 'log-'.date('Y-m-d', TIME).'.php';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = KBPATH.'views/errors/';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = APPPATH.'cache/';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Whether to take the URL query string into consideration when generating
| output cache files. Valid options are:
|
|   false      = Disabled
|   true       = Enabled, take all query parameters into account.
|                Please be aware that this may result in numerous cache
|                files generated for the same page over and over again.
|   array('q') = Enabled, but only take into account the specified list
|                of query parameters.
|
*/
$config['cache_query_string'] = true;

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_samesite'
|
|   Session cookie SameSite attribute: Lax (default), Strict or None
|
| 'sess_match_ip'
|
|   Whether to match the user's IP address when reading the session data.
|
|   WARNING: If you're using the database driver, don't forget to update
|            your session table's PRIMARY KEY when changing this setting.
|
| 'sess_regenerate_destroy'
|
|   Whether to destroy session data associated with the old session ID
|   when auto-regenerating the session ID. When set to FALSE, the data
|   will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_samesite']           = 'Strict';
$config['sess_match_ip']           = false;
$config['sess_regenerate_destroy'] = false;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
| 'cookie_samesite' = Cookie's samesite attribute (Lax, Strict or None)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_domain']   = '';
$config['cookie_path']     = '/';
$config['cookie_secure']   = is_https();
$config['cookie_httponly'] = ('development' !== ENVIRONMENT);
$config['cookie_samesite'] = 'Strict';

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to true, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_regenerate' = Regenerate token on every submission
*/
$config['csrf_token_name']   = COOK_CSRF;
$config['csrf_cookie_name']  = COOK_CSRF;
$config['csrf_regenerate']   = true;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = (ENVIRONMENT === 'production');

/*
|--------------------------------------------------------------------------
| Modules locations
|--------------------------------------------------------------------------
|
| These are the folders where your modules are located. You may define an
| absolute path to the location or a relative path starting from the root
| directory.
|
*/
$config['modules_locations'] = array(
	/**
	 * There reason we are putting modules within the public
	 * folder is to make it possible to load their own assets.
	 * It is up to you to secure them by using usual CodeIgniter
	 * check:
	 * defined('BASEPATH') OR die;
	 */
	FCPATH.'content/modules/',

	/**
	 * Other locations are here for backup only. In case modules cannot be
	 * found in the public area, we will look for them inside the following
	 * folders.
	 * NOTE: Modules' assets cannot be loaded from within those directories.
	 */
	APPPATH.'modules/',

	/**
	 * Skeleton-specific modules. This modules cannot be deleted.
	 */
	KBPATH.'modules/',
);

