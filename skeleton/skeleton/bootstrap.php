<?php
defined('BASEPATH') OR die;

/**
 * Bootstrap File.
 *
 * This file registers Skeleton classes to they can easily loaded/extended.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Autoloader
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.1
 */

/**
 * ---------------------------------------------------------------
 * Handle CORS Preflight Requests Early
 * ---------------------------------------------------------------
 * Some browsers (notably Chrome and Firefox) send a preflight request
 * using the HTTP OPTIONS method before performing cross-origin requests
 * like POST, PUT, or DELETE — especially when custom headers are involved.
 *
 * Since this request is only used to validate CORS permissions and doesn't
 * require any application logic or output, we handle it right here in
 * bootstrap.php — the earliest point of execution.
 *
 * This avoids unnecessary loading of framework files and ensures that
 * CORS policies are clearly defined and enforced before any other logic runs.
 *
 * Note: This does not affect RESTful endpoints since OPTIONS requests
 * are not meant to carry or expect content data.
 */
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
	header('Access-Control-Allow-Credentials: true');
	header('HTTP/1.1 200 OK');
	exit;
}

/**
 * Load some base functions that we added to CodeIgniter.
 * @since   2.0
 */
require(KBPATH.'third_party/bkader/class-autoloader.php');
require(KBPATH.'third_party/print_d/print_d.php');
require(KBPATH.'third_party/bkader/compat.php');
require(KBPATH.'third_party/bkader/base.php');
require(KBPATH.'third_party/bkader/formatting.php');
require(KBPATH.'third_party/bkader/class-kplatform.php');

/**
 * Import class-cs-hooks.
 * @since   2.16
 */
require(KBPATH.'third_party/bkader/class-cs-hooks.php');

/**
 * Setup Skeleton default constants.
 * @since   2.0
 */
KPlatform::constants();

/**
 * Add default Skeleton classes.
 * @since   2.1
 */
Autoloader::add_classes(array(
	// CodeIgniter 3 core files.
	'CI_Benchmark'  => BASEPATH.'core/Benchmark.php',
	'CI_Config'     => BASEPATH.'core/Config.php',
	'CI_Controller' => BASEPATH.'core/Controller.php',
	'CI_Exceptions' => BASEPATH.'core/Exceptions.php',
	'CI_Hooks'      => BASEPATH.'core/Hooks.php',
	'CI_Input'      => BASEPATH.'core/Input.php',
	'CI_Lang'       => BASEPATH.'core/Lang.php',
	'CI_Loader'     => BASEPATH.'core/Loader.php',
	'CI_Log'        => BASEPATH.'core/Log.php',
	'CI_Model'      => BASEPATH.'core/Model.php',
	'CI_Output'     => BASEPATH.'core/Output.php',
	'CI_Router'     => BASEPATH.'core/Router.php',
	'CI_Security'   => BASEPATH.'core/Security.php',
	'CI_URI'        => BASEPATH.'core/URI.php',
	'CI_Utf8'       => BASEPATH.'core/Utf8.php',

	// CodeIgniter 3 database files.
	'CI_DB_Cache'         => BASEPATH.'database/DB_Cache.php',
	'CI_DB_driver'        => BASEPATH.'database/DB_driver.php',
	'CI_DB_forge'         => BASEPATH.'database/DB_forge.php',
	'CI_DB_query_builder' => BASEPATH.'database/DB_query_builder.php',
	'CI_DB_result'        => BASEPATH.'database/DB_result.php',
	'CI_DB_utility'       => BASEPATH.'database/DB_utility.php',

	// CodeIgniter 3 database driver files (cubrid).
	'CI_DB_cubrid_driver'  => BASEPATH.'database/drivers/cubrid/cubrid_driver.php',
	'CI_DB_cubrid_forge'   => BASEPATH.'database/drivers/cubrid/cubrid_forge.php',
	'CI_DB_cubrid_result'  => BASEPATH.'database/drivers/cubrid/cubrid_result.php',
	'CI_DB_cubrid_utility' => BASEPATH.'database/drivers/cubrid/cubrid_utility.php',

	// CodeIgniter 3 database driver files (ibase).
	'CI_DB_ibase_driver'  => BASEPATH.'database/drivers/ibase/ibase_driver.php',
	'CI_DB_ibase_forge'   => BASEPATH.'database/drivers/ibase/ibase_forge.php',
	'CI_DB_ibase_result'  => BASEPATH.'database/drivers/ibase/ibase_result.php',
	'CI_DB_ibase_utility' => BASEPATH.'database/drivers/ibase/ibase_utility.php',

	// CodeIgniter 3 database driver files (mssql).
	'CI_DB_mssql_driver'  => BASEPATH.'database/drivers/mssql/mssql_driver.php',
	'CI_DB_mssql_forge'   => BASEPATH.'database/drivers/mssql/mssql_forge.php',
	'CI_DB_mssql_result'  => BASEPATH.'database/drivers/mssql/mssql_result.php',
	'CI_DB_mssql_utility' => BASEPATH.'database/drivers/mssql/mssql_utility.php',

	// CodeIgniter 3 database driver files (mysql).
	'CI_DB_mysql_driver'  => BASEPATH.'database/drivers/mysql/mysql_driver.php',
	'CI_DB_mysql_forge'   => BASEPATH.'database/drivers/mysql/mysql_forge.php',
	'CI_DB_mysql_result'  => BASEPATH.'database/drivers/mysql/mysql_result.php',
	'CI_DB_mysql_utility' => BASEPATH.'database/drivers/mysql/mysql_utility.php',

	// CodeIgniter 3 database driver files (mysqli).
	'CI_DB_mysqli_driver'  => BASEPATH.'database/drivers/mysqli/mysqli_driver.php',
	'CI_DB_mysqli_forge'   => BASEPATH.'database/drivers/mysqli/mysqli_forge.php',
	'CI_DB_mysqli_result'  => BASEPATH.'database/drivers/mysqli/mysqli_result.php',
	'CI_DB_mysqli_utility' => BASEPATH.'database/drivers/mysqli/mysqli_utility.php',

	// CodeIgniter 3 database driver files (oci8).
	'CI_DB_oci8_driver'  => BASEPATH.'database/drivers/oci8/oci8_driver.php',
	'CI_DB_oci8_forge'   => BASEPATH.'database/drivers/oci8/oci8_forge.php',
	'CI_DB_oci8_result'  => BASEPATH.'database/drivers/oci8/oci8_result.php',
	'CI_DB_oci8_utility' => BASEPATH.'database/drivers/oci8/oci8_utility.php',

	// CodeIgniter 3 database driver files (odbc).
	'CI_DB_odbc_driver'  => BASEPATH.'database/drivers/odbc/odbc_driver.php',
	'CI_DB_odbc_forge'   => BASEPATH.'database/drivers/odbc/odbc_forge.php',
	'CI_DB_odbc_result'  => BASEPATH.'database/drivers/odbc/odbc_result.php',
	'CI_DB_odbc_utility' => BASEPATH.'database/drivers/odbc/odbc_utility.php',

	// CodeIgniter 3 database driver files (pdo).
	'CI_DB_pdo_driver'  => BASEPATH.'database/drivers/pdo/pdo_driver.php',
	'CI_DB_pdo_forge'   => BASEPATH.'database/drivers/pdo/pdo_forge.php',
	'CI_DB_pdo_result'  => BASEPATH.'database/drivers/pdo/pdo_result.php',
	'CI_DB_pdo_utility' => BASEPATH.'database/drivers/pdo/pdo_utility.php',

	'CI_DB_pdo_4d_forge'        => BASEPATH.'database/drivers/pdo/subdrivers/pdo_4d_forge.php',
	'CI_DB_pdo_4d_driver'       => BASEPATH.'database/drivers/pdo/subdrivers/pdo_4d_driver.php',
	'CI_DB_pdo_cubrid_driver'   => BASEPATH.'database/drivers/pdo/subdrivers/pdo_cubrid_driver.php',
	'CI_DB_pdo_cubrid_forge'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_cubrid_forge.php',
	'CI_DB_pdo_dblib_driver'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_dblib_driver.php',
	'CI_DB_pdo_dblib_forge'     => BASEPATH.'database/drivers/pdo/subdrivers/pdo_dblib_forge.php',
	'CI_DB_pdo_firebird_driver' => BASEPATH.'database/drivers/pdo/subdrivers/pdo_firebird_driver.php',
	'CI_DB_pdo_firebird_forge'  => BASEPATH.'database/drivers/pdo/subdrivers/pdo_firebird_forge.php',
	'CI_DB_pdo_ibm_driver'      => BASEPATH.'database/drivers/pdo/subdrivers/pdo_ibm_driver.php',
	'CI_DB_pdo_ibm_forge'       => BASEPATH.'database/drivers/pdo/subdrivers/pdo_ibm_forge.php',
	'CI_DB_pdo_informix_driver' => BASEPATH.'database/drivers/pdo/subdrivers/pdo_informix_driver.php',
	'CI_DB_pdo_informix_forge'  => BASEPATH.'database/drivers/pdo/subdrivers/pdo_informix_forge.php',
	'CI_DB_pdo_mysql_driver'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_mysql_driver.php',
	'CI_DB_pdo_mysql_forge'     => BASEPATH.'database/drivers/pdo/subdrivers/pdo_mysql_forge.php',
	'CI_DB_pdo_oci_forge'       => BASEPATH.'database/drivers/pdo/subdrivers/pdo_oci_forge.php',
	'CI_DB_pdo_oci_driver'      => BASEPATH.'database/drivers/pdo/subdrivers/pdo_oci_driver.php',
	'CI_DB_pdo_odbc_forge'      => BASEPATH.'database/drivers/pdo/subdrivers/pdo_odbc_forge.php',
	'CI_DB_pdo_odbc_driver'     => BASEPATH.'database/drivers/pdo/subdrivers/pdo_odbc_driver.php',
	'CI_DB_pdo_pgsql_driver'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_pgsql_driver.php',
	'CI_DB_pdo_pgsql_forge'     => BASEPATH.'database/drivers/pdo/subdrivers/pdo_pgsql_forge.php',
	'CI_DB_pdo_sqlite_driver'   => BASEPATH.'database/drivers/pdo/subdrivers/pdo_sqlite_driver.php',
	'CI_DB_pdo_sqlite_forge'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_sqlite_forge.php',
	'CI_DB_pdo_sqlsrv_driver'   => BASEPATH.'database/drivers/pdo/subdrivers/pdo_sqlsrv_driver.php',
	'CI_DB_pdo_sqlsrv_forge'    => BASEPATH.'database/drivers/pdo/subdrivers/pdo_sqlsrv_forge.php',

	// CodeIgniter 3 database driver files (postgre).
	'CI_DB_postgre_driver'  => BASEPATH.'database/drivers/postgre/postgre_driver.php',
	'CI_DB_postgre_forge'   => BASEPATH.'database/drivers/postgre/postgre_forge.php',
	'CI_DB_postgre_result'  => BASEPATH.'database/drivers/postgre/postgre_result.php',
	'CI_DB_postgre_utility' => BASEPATH.'database/drivers/postgre/postgre_utility.php',

	// CodeIgniter 3 database driver files (sqlite).
	'CI_DB_sqlite_driver'  => BASEPATH.'database/drivers/sqlite/sqlite_driver.php',
	'CI_DB_sqlite_forge'   => BASEPATH.'database/drivers/sqlite/sqlite_forge.php',
	'CI_DB_sqlite_result'  => BASEPATH.'database/drivers/sqlite/sqlite_result.php',
	'CI_DB_sqlite_utility' => BASEPATH.'database/drivers/sqlite/sqlite_utility.php',

	// CodeIgniter 3 database driver files (sqlite3).
	'CI_DB_sqlite3_driver'  => BASEPATH.'database/drivers/sqlite3/sqlite3_driver.php',
	'CI_DB_sqlite3_forge'   => BASEPATH.'database/drivers/sqlite3/sqlite3_forge.php',
	'CI_DB_sqlite3_result'  => BASEPATH.'database/drivers/sqlite3/sqlite3_result.php',
	'CI_DB_sqlite3_utility' => BASEPATH.'database/drivers/sqlite3/sqlite3_utility.php',

	// CodeIgniter 3 database driver files (sqlsrv).
	'CI_DB_sqlsrv_driver'  => BASEPATH.'database/drivers/sqlsrv/sqlsrv_driver.php',
	'CI_DB_sqlsrv_forge'   => BASEPATH.'database/drivers/sqlsrv/sqlsrv_forge.php',
	'CI_DB_sqlsrv_result'  => BASEPATH.'database/drivers/sqlsrv/sqlsrv_result.php',
	'CI_DB_sqlsrv_utility' => BASEPATH.'database/drivers/sqlsrv/sqlsrv_utility.php',

	// CodeIgniter 3 libraries (cache).
	'CI_Cache'           => BASEPATH.'libraries/Cache/Cache.php',
	'CI_Cache_apc'       => BASEPATH.'libraries/Cache/drivers/Cache_apc.php',
	'CI_Cache_dummy'     => BASEPATH.'libraries/Cache/drivers/Cache_dummy.php',
	'CI_Cache_file'      => BASEPATH.'libraries/Cache/drivers/Cache_file.php',
	'CI_Cache_memcached' => BASEPATH.'libraries/Cache/drivers/Cache_memcached.php',
	'CI_Cache_redis'     => BASEPATH.'libraries/Cache/drivers/Cache_redis.php',
	'CI_Cache_wincache'  => BASEPATH.'libraries/Cache/drivers/Cache_wincache.php',

	// CodeIgniter 3 libraries (Javascript).
	'CI_Javascript' => BASEPATH.'libraries/Javascript.php',
	'CI_Jquery'     => BASEPATH.'libraries/Javascript/Jquery.php',

	// CodeIgniter 3 libraries (Session).
	'CI_Session'                             => BASEPATH.'libraries/Session/Session.php',
	'CI_SessionWrapper_8'                    => BASEPATH.'libraries/Session/PHP8SessionWrapper.php',
	'CI_SessionWrapper_old'                  => BASEPATH.'libraries/Session/OldSessionWrapper.php',
	'CI_Session_driver'                      => BASEPATH.'libraries/Session/Session_driver.php',
	'CI_Session_driver_interface'            => BASEPATH.'libraries/Session/CI_Session_driver_interface.php',
	'SessionUpdateTimestampHandlerInterface' => BASEPATH.'libraries/Session/SessionUpdateTimestampHandlerInterface.php',

	'CI_Session_database_driver'  => BASEPATH.'libraries/Session/drivers/Session_database_driver.php',
	'CI_Session_files_driver'     => BASEPATH.'libraries/Session/drivers/Session_files_driver.php',
	'CI_Session_memcached_driver' => BASEPATH.'libraries/Session/drivers/Session_memcached_driver.php',
	'CI_Session_redis_driver'     => BASEPATH.'libraries/Session/drivers/Session_redis_driver.php',

	// CodeIgniter 3 libraries (others).
	'CI_Calendar'        => BASEPATH.'libraries/Calendar.php',
	'CI_Cart'            => BASEPATH.'libraries/Cart.php',
	'CI_Driver'          => BASEPATH.'libraries/Driver.php',
	'CI_Driver_Library'  => BASEPATH.'libraries/Driver.php',
	'CI_Email'           => BASEPATH.'libraries/Email.php',
	'CI_Encrypt'         => BASEPATH.'libraries/Encrypt.php',
	'CI_Encryption'      => BASEPATH.'libraries/Encryption.php',
	'CI_Form_validation' => BASEPATH.'libraries/Form_validation.php',
	'CI_Ftp'             => BASEPATH.'libraries/Ftp.php',
	'CI_Image_lib'       => BASEPATH.'libraries/Image_lib.php',
	'CI_Migration'       => BASEPATH.'libraries/Migration.php',
	'CI_Pagination'      => BASEPATH.'libraries/Pagination.php',
	'CI_Parser'          => BASEPATH.'libraries/Parser.php',
	'CI_Profiler'        => BASEPATH.'libraries/Profiler.php',
	'CI_Table'           => BASEPATH.'libraries/Table.php',
	'CI_Trackback'       => BASEPATH.'libraries/Trackback.php',
	'CI_Typography'      => BASEPATH.'libraries/Typography.php',
	'CI_Unit_test'       => BASEPATH.'libraries/Unit_test.php',
	'CI_Upload'          => BASEPATH.'libraries/Upload.php',
	'CI_User_agent'      => BASEPATH.'libraries/User_agent.php',
	'CI_Xmlrpc'          => BASEPATH.'libraries/Xmlrpc.php',
	'CI_Xmlrpcs'         => BASEPATH.'libraries/Xmlrpcs.php',
	'CI_Zip'             => BASEPATH.'libraries/Zip.php',

	// CI Skeleton core files.
	'CI_Events'     => KBPATH.'core/Events.php',
	'CI_Registry'   => KBPATH.'core/Registry.php',
	'KB_Config'     => KBPATH.'core/KB_Config.php',
	'KB_Controller' => KBPATH.'core/KB_Controller.php',
	'KB_Exceptions' => KBPATH.'core/KB_Exceptions.php',
	'KB_Hooks'      => KBPATH.'core/KB_Hooks.php',
	'KB_Input'      => KBPATH.'core/KB_Input.php',
	'KB_Lang'       => KBPATH.'core/KB_Lang.php',
	'KB_Loader'     => KBPATH.'core/KB_Loader.php',
	'KB_Log'        => KBPATH.'core/KB_Log.php',
	'KB_Model'      => KBPATH.'core/KB_Model.php',
	'KB_Model'      => KBPATH.'core/KB_Model.php',
	'KB_Router'     => KBPATH.'core/KB_Router.php',
	'KB_Security'   => KBPATH.'core/KB_Security.php',
	'KB_URI'        => KBPATH.'core/KB_URI.php',

	// CI Skeleton core controllers.
	'AJAX_Controller'     => KBPATH.'core/controllers/AJAX_Controller.php',
	'API_Controller'      => KBPATH.'core/controllers/API_Controller.php',
	'Admin_Controller'    => KBPATH.'core/controllers/Admin_Controller.php',
	'CLI_Controller'      => KBPATH.'core/controllers/CLI_Controller.php',
	'Content_Controller'  => KBPATH.'core/controllers/Content_Controller.php',
	'Help_Controller'     => KBPATH.'core/controllers/Help_Controller.php',
	'Process_Controller'  => KBPATH.'core/controllers/Process_Controller.php',
	'Public_Controller'   => KBPATH.'core/controllers/Public_Controller.php',
	'Reports_Controller'  => KBPATH.'core/controllers/Reports_Controller.php',
	'Response_Controller' => KBPATH.'core/controllers/Response_Controller.php',
	'Settings_Controller' => KBPATH.'core/controllers/Settings_Controller.php',
	'User_Controller'     => KBPATH.'core/controllers/User_Controller.php',

	// CI Skeleton libraries
	'Captcha'            => KBPATH.'libraries/Captcha.php',
	'Curl'               => KBPATH.'libraries/Curl.php',
	'Datatables'         => KBPATH.'libraries/Datatables.php',
	'Format'             => KBPATH.'libraries/Format.php',
	'Google_auth'        => KBPATH.'libraries/Google_auth.php',
	'Hash'               => KBPATH.'libraries/Hash.php',
	'Jquery_validation'  => KBPATH.'libraries/Jquery_validation.php',
	'KB_Driver_Library'  => KBPATH.'libraries/KB_Driver.php',
	'KB_Email'           => KBPATH.'libraries/KB_Email.php',
	'KB_Encrypt'         => KBPATH.'libraries/KB_Encrypt.php',
	'KB_Form_validation' => KBPATH.'libraries/KB_Form_validation.php',
	'KB_Image_lib'       => KBPATH.'libraries/KB_Image_lib.php',
	'KB_Pagination'      => KBPATH.'libraries/KB_Pagination.php',
	'KB_Profiler'        => KBPATH.'libraries/KB_Profiler.php',
	'KB_Session'         => KBPATH.'libraries/KB_Session.php',
	'KB_Table'           => KBPATH.'libraries/KB_Table.php',
	'KB_Upload'          => KBPATH.'libraries/KB_Upload.php',
	'Oembed'             => KBPATH.'libraries/Oembed.php',
	'Rest'               => KBPATH.'libraries/Rest.php',

	// CI Skeleton Kbcore classes.
	'CRUD_interface' => KBPATH.'libraries/Kbcore/CRUD_interface.php',
	'KB_Entity'      => KBPATH.'libraries/Kbcore/classes/KB_Entity.php',
	'KB_File'        => KBPATH.'libraries/Kbcore/classes/KB_File.php',
	'KB_Group'       => KBPATH.'libraries/Kbcore/classes/KB_Group.php',
	'KB_Object'      => KBPATH.'libraries/Kbcore/classes/KB_Object.php',
	'KB_Role'        => KBPATH.'libraries/Kbcore/classes/KB_Role.php',
	'KB_User'        => KBPATH.'libraries/Kbcore/classes/KB_User.php',

	// Other classes.
	'Route'           => KBPATH.'third_party/bkader/class-route.php',
	'Cache_Dir'       => KBPATH.'third_party/bkader/class-cache-dir.php',
	'Beautify_Html'   => KBPATH.'third_party/bkader/class-beautify-html.php',
	'CSSMin'          => KBPATH.'third_party/bkader/class-cssmin.php',
	'JSMin'           => KBPATH.'third_party/bkader/class-jsmin.php',
	'HttpStatusCodes' => KBPATH.'third_party/bkader/class-status-codes.php',
	'PasswordHash'    => KBPATH.'third_party/phpass/PasswordHash.php'
));

// --------------------------------------------------------------------

if ( ! function_exists('load_database'))
{
	/**
	 * A quick-access to KPlatform::DB() method.
	 * @since   2.0
	 * @param   none
	 * @return  DB
	 */
	function load_database() {
		return KPlatform::DB();
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('accepted_languages'))
{
	/**
	 * Get the accepted languages
	 * @return  array
	 */
	function accepted_languages()
	{
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			return explode(',', preg_replace('/(;\s?q=[0-9\.]+)|\s/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']))));
		}

		return null;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('prefered_language'))
{
	/**
	 * Determines which language out of an available set the user prefers most
	 *
	 * @param 	array 	$available_languages 	Array of lowercased language codes.
	 * @param 	string 	$http_accept_language 	A HTTP_ACCEPT_LANGUAGE string.
	 * @return 	string
	 */
	function prefered_language($available_languages, $http_accept_language = 'auto')
	{
		if ('auto' == $http_accept_language)
		{
			$http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
		}

		preg_match_all(
			"/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
			$http_accept_language,
			$hits,
			PREG_SET_ORDER
		);

		// default language (in case of no hits) is the first in the array
		$best_code = $available_languages[0];
		$best_qval = 0;

		foreach ($hits as $arr)
		{
			$prefix = strtolower($arr[1]);

			if ( ! empty($arr[3]))
			{
				$range = strtolower($arr[3]);
				$lang  = $prefix.'-'.$range;
			}
			else
			{
				$lang = $prefix;
			}

			$qval = 1.0;

			empty($arr[5]) OR $qval = floatval($arr[5]);

			if (in_array($lang, $available_languages) && ($qval > $best_qval))
			{
				$best_code = $lang;
				$best_qval = $qval;
			}
			elseif (in_array($prefix, $available_languages) && (($qval * 0.9) > $best_qval))
			{
				$best_code = $prefix;
				$best_qval = $qval * 0.9;
			}
		}

		return $best_code;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_dir_cache'))
{
	function &get_dir_cache($path = null)
	{
		static $cache;

		if (empty($cache))
		{
			empty($path) && $path = APPPATH.'cache/paths';
			$cache = new Cache_Dir($path);
		}

		return $cache;
	}
}

// --------------------------------------------------------------------

/**
 * We now register the autoloader.
 * @since 	2.1
 */
Autoloader::register();

/**
 * We make sure to load application "bootstrap.php" file.
 * @since 	2.1
 */
require(APPPATH.'bootstrap.php');

/**
 * We now load CodeIgniter bootstrap file, and as they said:
 *
 * And away we go...
 */
require(BASEPATH.'core/CodeIgniter.php');
