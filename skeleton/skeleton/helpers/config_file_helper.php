<?php
defined('BASEPATH') OR die;

/**
 * Configuration file helper functions.
 *
 * Functions in this file helps in reading and writting config items to
 * and from configuration files located in APPPATH.'/config/' folder.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Helpers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2022, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 * @version     1.0.0
 */

if ( ! function_exists('prep_config_array'))
{
	/**
	 * Outputs the array string which is then used in the config file.
	 * @param   array   $array      values to store in the config.
	 * @param   int     $numtabs    optional number of tabs to use in front of the array.
	 * @return  mixed   a string representing the array values, otherwise false.
	 */
	function prep_config_array($array, $numtabs = 1)
	{
		if ( ! is_array($array))
		{
			return false;
		}

		$tval = 'array(';

		// allow for two-dimensional arrays.
		$keys = array_keys($array);

		// check whether they are basic numeric keys.
		if (is_numeric($keys[0]) && $keys[0] == 0)
		{
			$tval .= "'" . implode("','", $array) . "'";
		}
		else
		{
			// non-numeric keys.
			$tabs = "";
			for ($num = 0; $num < $numTabs; $num++)
			{
				$tabs .= "\t";
			}

			foreach ($array as $key => $value)
			{
				$tval .= "\n{$tabs}'{$key}' => ";
				if (is_array($value))
				{
					$numTabs++;
					$tval .= prep_config_array($value, $numTabs);
				}
				else
				{
					$tval .= "'{$value}'";
				}

				$tval .= ',';
			}

			$tval .= "\n{$tabs}";
		}

		$tval .= ')';

		return $tval;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('read_config')) {
	/**
	 * Return an array of configuration settings from a single config file.
	 *
	 * @param   string  $file       the config file to read.
	 * @param   bool    $silent     whether to show errors or simply return false.
	 * @param   string  $module     name of the module where the config file exists.
	 * @param   bool    $moduleonly     whether to fail if config does not exist.
	 * @return  array   An array of settings, or false on failure.
	 */
	function read_config($file, $silent = true, $module = '', $moduleOnly = false)
	{
		$file = ($file == '' ? 'config' : str_replace('.php', '', $file)).'.php';

		// look in module first
		$found = false;
		if ($module && false !== ($modpath = get_instance()->router->module_path($module)))
		{
			$modfile = normalize_path($modpath . 'config/' . $file);
			if (is_file($modfile))
			{
				$file  = $modfile;
				$found = true;
			}
		}

		// Fall back to application directory
		if ( ! $found && !$moduleOnly)
		{
			$checkLocations = array();

			if (defined('ENVIRONMENT'))
			{
				$checkLocations[] = normalize_path(APPPATH . 'config/' . ENVIRONMENT . "/{$file}");
			}

			$checkLocations[] = normalize_path(APPPATH . "config/{$file}");

			foreach ($checkLocations as $location)
			{
				if (is_file($location.'.php'))
				{
					$file  = $location;
					$found = true;
					break;
				}
			}
		}

		if ( ! $found)
		{
			if ($silent === true)
			{
				return false;
			}

			show_error("The configuration file {$file} does not exist.");
		}

		include_once($file);

		if ( ! isset($config) OR !is_array($config))
		{
			if ($silent === true)
			{
				return false;
			}

			show_error("Your {$file} file does not appear to contain a valid configuration array.");
		}

		return $config;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('write_config'))
{
	/**
	 * Save the passed array settings into a single config file located in the
	 * config directory.
	 *
	 * @param   string  $file       The config file to write to.
	 * @param   array   $settings   An array of config setting name/value pairs to be
	 * written to the file.
	 * @param   string  $module     Name of the module where the config file exists.
	 * @return boolean False on error, else true.
	 */
	function write_config($file = '', $settings = null, $module = '', $apppath = APPPATH)
	{
		if (empty($file) OR !is_array($settings))
		{
			return false;
		}

		$file = ($file == '' ? 'config' : str_replace('.php', '', $file)).'.php';

		$CI =& get_instance();

		$configFile = "config/{$file}";

		// look in module first.
		$found = false;
		if ($module && false !== ($modpath = $CI->router->module_path($module)))
		{
			$modfile = $modpath . $configFile;
			if (is_file($modfile))
			{
				$configFile = normalize_path($modfile);
				$found      = true;
			}
		}

		// fall back to application directory.
		if ( ! $found)
		{
			$configFile = normalize_path("{$apppath}{$configFile}");
			$found      = is_file($configFile.'.php');
		}

		if ($found)
		{
			// Load the file and loop through the lines.
			$contents = file_get_contents($configFile);
			$empty    = false;
		}
		else
		{
			// If the file was not found, create a new file.
			$contents = '';
			$empty    = true;
		}

		foreach ($settings as $name => $val)
		{
			// Is the config setting in the file?
			$start  = strpos($contents, '$config[\'' . $name . '\']');
			$end    = strpos($contents, ';', $start);
			$search = substr($contents, $start, $end - $start + 1);

			// format the value to be written to the file.
			if (is_array($val))
			{
				// Get the array output.
				$val = prep_config_array($val);
			}
			elseif ( ! is_numeric($val))
			{
				$val = "\"$val\"";
			}

			// For a new file, just append the content. For an existing file, search
			// the file's contents and replace the config setting.

			if ($empty)
			{
				$contents .= '$config[\'' . $name . '\'] = ' . $val . ";\n";
			}
			else
			{
				$contents = str_replace($search, '$config[\'' . $name . '\'] = ' . $val . ';', $contents);
			}
		}

		// Backup the file for safety.
		$source = $configFile;
		$dest   = normalize_path(($module == '' ? "{$apppath}archives/{$file}" : $configFile) . '.bak');

		if ($empty === false)
		{
			copy($source, $dest);
		}

		// Make sure the file still has the php opening header in it...
		if (strpos($contents, '<?php') === false)
		{
			$contents = "<?php defined('BASEPATH') OR die;\n\n{$contents}";
		}

		// Write the changes out...
		function_exists('write_file') OR $CI->load->helper('file');
		$result = write_file($configFile, $contents);

		return $result !== false;
	}
}
