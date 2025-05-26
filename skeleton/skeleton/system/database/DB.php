<?php
defined('BASEPATH') OR die;

/**
 * Initialize the database
 *
 * @category    Database
 * @author  EllisLab Dev Team
 * @link    https://codeigniter.com/userguide3/database/
 *
 * @param   string|string[] $params
 */
function &DB($params = '')
{
	static $DB;

	if (isset($DB))
	{
		return $DB;
	}

	// Load the DB config file if a DSN string wasn't passed
	if (is_string($params) && strpos($params, '://') === false)
	{
		// Is the config file in the environment folder?
		if ( ! is_file($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			&& ! is_file($file_path = APPPATH.'config/database.php'))
		{
			show_error('The configuration file database.php does not exist.');
		}

		include($file_path);

		// Make packages contain database config files,
		// given that the controller instance already exists
		if (class_exists('CI_Controller', false))
		{
			foreach (get_instance()->load->get_package_paths() as $path)
			{
				if ($path !== APPPATH)
				{
					if (is_file($file_path = $path.'config/'.ENVIRONMENT.'/database.php'))
					{
						include($file_path);
					}
					elseif (is_file($file_path = $path.'config/database.php'))
					{
						include($file_path);
					}
				}
			}
		}

		if (empty($db))
		{
			show_error('No database connection settings were found in the database config file.');
		}

		if ($params !== '')
		{
			$active_group = $params;
		}

		if ( ! isset($active_group))
		{
			show_error('You have not specified a database connection group via $active_group in your config/database.php file.');
		}
		elseif ( ! isset($db[$active_group]))
		{
			show_error('You have specified an invalid database connection group ('.$active_group.') in your config/database.php file.');
		}

		$params = $db[$active_group];
	}
	elseif (is_string($params))
	{
		/**
		 * Parse the URL from the DSN string
		 * Database settings can be passed as discreet
		 * parameters or as a data source name in the first
		 * parameter. DSNs must have this prototype:
		 * $dsn = 'driver://username:password@hostname/database';
		 */
		if (($dsn = @parse_url($params)) === false)
		{
			show_error('Invalid DB Connection String');
		}

		$params = array(
			'dbdriver'  => $dsn['scheme'],
			'hostname'  => isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
			'port'      => isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
			'username'  => isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
			'password'  => isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
			'database'  => isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
		);

		// Were additional config items set?
		if (isset($dsn['query']))
		{
			parse_str($dsn['query'], $extra);

			foreach ($extra as $key => $val)
			{
				if (is_string($val) && in_array(strtolower($val), array('true', 'false', 'null')))
				{
					$val = var_export($val, true);
				}

				$params[$key] = $val;
			}
		}
	}

	// No DB specified yet? Beat them senseless...
	if (empty($params['dbdriver']))
	{
		show_error('You have not selected a database type to connect to.');
	}

	require_once(KBPATH.'database/DB_driver.php');
	require_once(KBPATH.'database/DB_query_builder.php');
	if ( ! class_exists('CI_DB', false))
	{
		/**
		 * CI_DB
		 *
		 * Acts as an alias for both KB_DB_driver and KB_DB_query_builder.
		 *
		 * @see	KB_DB_query_builder
		 * @see	KB_DB_driver
		 */
		class CI_DB extends KB_DB_query_builder {}
	}

	// Load the DB driver
	$driver_file = BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_driver.php';
	is_file($driver_file) OR show_error('Invalid DB driver');
	require_once($driver_file);

	// Load the result classes as well
	require_once(BASEPATH.'database/DB_result.php');
	require_once(BASEPATH.'database/drivers/'.$params['dbdriver'].'/'.$params['dbdriver'].'_result.php');

	// Instantiate the DB adapter
	$driver = 'CI_DB_'.$params['dbdriver'].'_driver';
	$DB = new $driver($params);

	// Check for a subdriver
	if ( ! empty($DB->subdriver))
	{
		$driver_file = BASEPATH.'database/drivers/'.$DB->dbdriver.'/subdrivers/'.$DB->dbdriver.'_'.$DB->subdriver.'_driver.php';

		if (is_file($driver_file))
		{
			require_once($driver_file);
			$driver = 'CI_DB_'.$DB->dbdriver.'_'.$DB->subdriver.'_driver';
			$DB = new $driver($params);
		}
	}

	$DB->initialize();
	return $DB;
}
