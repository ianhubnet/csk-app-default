<?php
defined('BASEPATH') OR die;

/**
 * Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/libraries/config.html
 */
class CI_Config
{
	/**
	 * List of all loaded config values
	 *
	 * @var array
	 */
	public $config = array();

	/**
	 * List of all loaded config files
	 *
	 * @var array
	 */
	public $is_loaded = array();

	/**
	 * List of paths to search when trying to load a config file.
	 *
	 * @used-by CI_Loader
	 * @var     array
	 */
	public $_config_paths = array(APPPATH);

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable.
	 *
	 * @param   array  $assign_to_config
	 * @return  void
	 */
	public function __construct(array $assign_to_config = array())
	{
		$this->config =& get_config();

		// Set the base_url automatically if none was provided
		if (empty($this->config['base_url']))
		{
			if (isset($_SERVER['SERVER_ADDR']))
			{
				if (strpos($_SERVER['SERVER_ADDR'], ':') !== false)
				{
					$server_addr = '['.$_SERVER['SERVER_ADDR'].']';
				}
				else
				{
					$server_addr = $_SERVER['SERVER_ADDR'];
				}

				$base_url = (is_https() ? 'https' : 'http').'://'.$server_addr
					.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
			}
			else
			{
				$base_url = 'http://localhost/';
			}

			$this->set_item('base_url', $base_url);
		}

		log_message('info', 'Config Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Load Config File
	 *
	 * @param   string  $file           Configuration file name
	 * @param   bool    $use_sections       Whether configuration values should be loaded into their own section
	 * @param   bool    $fail_gracefully    Whether to just return false or display an error message
	 * @return  bool    true if the file was loaded correctly or false on failure
	 */
	public function load($file = '', $use_sections = false, $fail_gracefully = false)
	{
		$file = ($file === '') ? 'config' : str_replace('.php', '', $file);
		$loaded = false;

		foreach ($this->_config_paths as $path)
		{
			foreach (array($file, ENVIRONMENT.DIRECTORY_SEPARATOR.$file) as $location)
			{
				$file_path = $path.'config/'.$location.'.php';
				if (in_array($file_path, $this->is_loaded, true))
				{
					return true;
				}

				if ( ! is_file($file_path))
				{
					continue;
				}

				include($file_path);

				if ( ! isset($config) OR ! is_array($config))
				{
					if ($fail_gracefully === true OR $loaded === true)
					{
						return false;
					}

					show_error('Your '.$file_path.' file does not appear to contain a valid configuration array.');
				}

				if ($use_sections === true)
				{
					$this->config[$file] = isset($this->config[$file])
						? array_merge($this->config[$file], $config)
						: $config;
				}
				else
				{
					$this->config = array_merge($this->config, $config);
				}

				$this->is_loaded[] = $file_path;
				$config = null;
				$loaded = true;
				log_message('info', 'Config file loaded: '.$file_path);
			}
		}

		if ($loaded === true)
		{
			return true;
		}
		elseif ($fail_gracefully === true)
		{
			return false;
		}

		show_error('The configuration file '.$file.'.php does not exist.');
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item
	 *
	 * @param   string  $item   Config item name
	 * @param   string  $index  Index name
	 * @return  string|null The configuration item or null if the item doesn't exist
	 */
	public function item($item, $index = '')
	{
		if ($index == '')
		{
			return isset($this->config[$item]) ? $this->config[$item] : null;
		}

		return isset($this->config[$index], $this->config[$index][$item]) ? $this->config[$index][$item] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item with slash appended (if not empty)
	 *
	 * @param   string      $item   Config item name
	 * @return  string|null The configuration item or null if the item doesn't exist
	 */
	public function slash_item($item)
	{
		if ( ! isset($this->config[$item]))
		{
			return null;
		}
		elseif (trim($this->config[$item]) === '')
		{
			return '';
		}

		return rtrim($this->config[$item], '/').'/';
	}

	// --------------------------------------------------------------------

	/**
	 * Site URL
	 *
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @uses    CI_Config::_uri_string()
	 *
	 * @param   string|string[] $uri    URI string or an array of segments
	 * @param   string  $protocol
	 * @return  string
	 */
	public function site_url($uri = '', $protocol = null)
	{
		$base_url = $this->slash_item('base_url');

		if (isset($protocol))
		{
			// For protocol-relative links
			if ($protocol === '')
			{
				$base_url = substr($base_url, strpos($base_url, '//'));
			}
			else
			{
				$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
			}
		}

		if (empty($uri))
		{
			return $base_url.$this->item('index_page');
		}

		$uri = $this->_uri_string($uri);

		if ($this->item('enable_query_strings') === false)
		{
			$suffix = isset($this->config['url_suffix']) ? $this->config['url_suffix'] : '';

			if ($suffix !== '')
			{
				if (($offset = strpos($uri, '?')) !== false)
				{
					$uri = substr($uri, 0, $offset).$suffix.substr($uri, $offset);
				}
				else
				{
					$uri .= $suffix;
				}
			}

			return $base_url.$this->slash_item('index_page').$uri;
		}
		elseif (strpos($uri, '?') === false)
		{
			$uri = '?'.$uri;
		}

		return $base_url.$this->item('index_page').$uri;
	}

	// -------------------------------------------------------------

	/**
	 * Base URL
	 *
	 * Returns base_url [. uri_string]
	 *
	 * @uses    CI_Config::_uri_string()
	 *
	 * @param   string|string[] $uri    URI string or an array of segments
	 * @param   string  $protocol
	 * @return  string
	 */
	public function base_url($uri = '', $protocol = null)
	{
		$base_url = $this->slash_item('base_url');

		if (isset($protocol))
		{
			// For protocol-relative links
			if ($protocol === '')
			{
				$base_url = substr($base_url, strpos($base_url, '//'));
			}
			else
			{
				$base_url = $protocol.substr($base_url, strpos($base_url, '://'));
			}
		}

		return $base_url.$this->_uri_string($uri);
	}

	// -------------------------------------------------------------

	/**
	 * Build URI string
	 *
	 * @used-by CI_Config::site_url()
	 * @used-by CI_Config::base_url()
	 *
	 * @param   string|string[] $uri    URI string or an array of segments
	 * @return  string
	 */
	protected function _uri_string($uri)
	{
		if ($this->item('enable_query_strings') === false)
		{
			is_array($uri) && $uri = implode('/', $uri);
			return ltrim($uri, '/');
		}
		elseif (is_array($uri))
		{
			return http_build_query($uri);
		}

		return $uri;
	}

	// --------------------------------------------------------------------

	/**
	 * Set a config file item
	 *
	 * @param   string  $item   Config item key
	 * @param   string  $value  Config item value
	 * @return  void
	 */
	public function set_item($item, $value)
	{
		$this->config[$item] = $value;
	}

}
