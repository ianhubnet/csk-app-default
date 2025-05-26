<?php
defined('BASEPATH') OR die;

/**
 * KB_Config Class
 *
 * This file extending CI_Config class in order to add, alter
 * or enhance some of the parent's methods.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	2.11
 */
class KB_Config extends CI_Config
{
	/**
	 * Instance of CI
	 * @var object
	 */
	protected $CI;

	/**
	 * Path where content is localed.
	 * @var string
	 */
	protected $content_path = FCPATH.'content/';

	/**
	 * Cached static URL.
	 * @var string
	 */
	protected $static_url;

	/**
	 * Holds currently active theme.
	 * @var string
	 */
	protected $theme;

	/**
	 * Cache for slashed items.
	 * @var array
	 */
	protected $slashed_items = array();

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @param 	array $assign_to_config
	 * @return 	void
	 */
	public function __construct(array $assign_to_config = array())
	{
		// Load options from database.
		$assign_to_config = KPlatform::setup_options($assign_to_config);

		// Our our custom config path.
		array_unshift($this->_config_paths, KBPATH);

		// Now we call parent's constructor.
		parent::__construct($assign_to_config);

		// Import config items.
		empty($assign_to_config) OR $this->config = deep_array_merge($this->config, $assign_to_config);
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
		if (isset($this->slashed_items[$item]))
		{
			return $this->slashed_items[$item];
		}

		$this->slashed_items[$item] = parent::slash_item($item);
		return $this->slashed_items[$item];
	}

	// --------------------------------------------------------------------

	/**
	 * Magic __get method for getting config item.
	 *
	 * @param 	string 	$item 	Config item name.
	 * @return 	mixed 	Config item value if found, else false.
	 */
	public function __get($item)
	{
		return isset($item, $this->config[$item]) ? $this->config[$item] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetchs a config file item.
	 * @param 	string 	$item 	 Config item name.
	 * @param 	string 	$index 	 Index name.
	 * @param 	mixed 	$default Fallback value if item is not found.
	 * @return 	mixed
	 */
	public function item($item, $index = null, $default = null)
	{
		if (empty($index))
		{
			return isset($this->config[$item]) ? $this->config[$item] : $default;
		}

		return isset($this->config[$index], $this->config[$index][$item]) ? $this->config[$index][$item] : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * Add the possibility to set an item with an index.
	 * @access 	public
	 * @param 	string 	$item 	The key of the item.
	 * @param 	mixed 	$value 	The value of the item.
	 * @param 	mixed 	$index 	The index of the item.
	 */
	public function set_item($item, $value = null, $index = '')
	{
		if (empty($index))
		{
			$this->config[$item] = $value;
		}
		else
		{
			$this->config[$index][$item] = $value;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Magic __set method for getting config item.
	 *
	 * @param 	string 	$item 	Config item name.
	 * @param 	mixed	$value 	Config item value.
	 */
	public function __set($item, $value = null)
	{
		$this->config[$item] = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Builds a URI string.
	 *
	 * This method is called before parent's in order to use our named routes
	 * system.
	 *
	 * @access 	protected
	 * @param 	mixed 	$uri 	URI string or an array of segments.
	 * @return 	string
	 */
	protected function _uri_string($uri)
	{
		if (class_exists('Route', false) && ! empty($uri))
		{
			$uri = is_array($uri)
				? array_map(array('Route', 'named'), $uri)
				: Route::named($uri);
		}

		return parent::_uri_string((empty($uri)) ? '' : $uri);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the path to the content folder.
	 * @param   string  $uri
	 * @param   string  $prefix
	 * @return  string
	 */
	public function content_path($uri = '', $prefix = null)
	{
		return normalize_path($this->content_path.(empty($prefix) ? $uri : $prefix.'/'.$uri));
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the path to the common folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function common_path($uri = '')
	{
		return $this->content_path($uri, 'common');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the path to the uploads folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function uploads_path($uri = '')
	{
		return $this->content_path($uri, 'uploads');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the path to the themes folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function themes_path($uri = '')
	{
		return $this->content_path($uri, 'themes');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the path to the current theme's folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function theme_path($uri = '')
	{
		isset($this->theme) OR $this->theme = $this->item('theme');
		return $this->content_path($uri, 'themes/'.$this->theme);
	}

	// --------------------------------------------------------------------

	/**
	 * Builds URL using site url and query args.
	 *
	 * @param 	array 	$query_data
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function build_url($uri = '', array $query_data = array(), $protocol = null)
	{
		empty($query_data) OR $uri .= '?'.http_build_query($query_data);

		return parent::site_url($uri, $protocol);
	}

	// --------------------------------------------------------------------

	/**
	 * Site URL
	 *
	 * Returns 'javascript:void(0);' if $uri is set to '#' or false, otherwise
	 * lets the parent handle the rest.
	 *
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function site_url($uri = '', $protocol = null)
	{
		return ('#' === $uri OR false === $uri)
			? 'javascript:void(0);'
			: normalize_url(parent::site_url($uri, $protocol));
	}

	// --------------------------------------------------------------------

	/**
	 * Base URL
	 *
	 * Returns 'javascript:void(0);' if $uri is set to '#' or false, otherwise
	 * lets the parent handle the rest.
	 *
	 * @param 	string 	$uri
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	public function base_url($uri = '', $protocol = null)
	{
		return ('#' === $uri OR false === $uri)
			? 'javascript:void(0);'
			: normalize_url(parent::base_url($uri, $protocol));
	}

	// --------------------------------------------------------------------

	/**
	 * Language URL
	 *
	 * Returns the full URL that can be used to switch site language.
	 *
	 * @param 	string 	$idiom 	The language to switch to.
	 * @return 	string
	 */
	public function lang_url(string $idiom, $protocol = null)
	{
		if (empty($idiom) OR ! in_array($idiom, $this->config['languages']))
		{
			return 'javascript:void();';
		}

		empty($next = get_instance()->uri->uri_string(true)) OR $next = '?next='.urlencode($next);

		return parent::site_url("switch-language/{$idiom}{$next}", $protocol);
	}

	// --------------------------------------------------------------------

	/**
	 * Current URL
	 *
	 * Returns the full URL (including segments) of the current page.
	 *
	 * @param 	bool 	$query_string
	 * @return 	string
	 */
	public function current_url($query_string = false, $protocol = null)
	{
		return parent::site_url(get_instance()->uri->uri_string(!$query_string), $protocol);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a tracking $_GET paramter that can be used to track user clicks
	 * on the website.
	 *
	 * @param   string  $uri    uri to build
	 * @param   string  $trace  click track position
	 * @param   string  $$protocol
	 * @return  string
	 */
	public function trace_url($uri = '', $trace = '', $protocol = null)
	{
		if ('#' === $uri OR false === $uri)
		{
			return 'javascript:void(0);';
		}

		$trace_url = $this->site_url($uri);

		if ( ! empty($key = $this->item('trace_url_key')) && ! empty($trace))
		{
			$uri .= str_contains($uri, '?') ? "&amp;$key=$trace" : "?$key=$trace";
		}

		return normalize_url(parent::site_url($uri, $protocol));
	}

	// --------------------------------------------------------------------

	/**
	 * Just an aliase used to automatically generate URLs to admin panel.
	 *
	 * @since 	2.16
	 */
	public function admin_url($uri = '', $protocol = null)
	{
		return ('#' === $uri OR false === $uri)
			? 'javascript:void(0);'
			: normalize_url(parent::site_url(KB_ADMIN.'/'.$uri, $protocol));
	}

	// --------------------------------------------------------------------

	/**
	 * Builds and returns URL to the static cookies-free domain used as
	 * content delivery site.
	 *
	 * @param   string|string[] $uri    URI string or an array of segments
	 * @param   string|null 	$prefix A prefix to prepend to URI.
	 * @return  string
	 */
	public function static_url($uri = '', $prefix = null)
	{
		if (preg_match('#^(\w+:)?//#i', $uri))
		{
			return $uri;
		}

		// Static URL not yet cached?
		if ( ! isset($this->static_url))
		{
			$this->static_url = $this->slash_item('static_url');
			empty($this->static_url) && $this->static_url = $this->slash_item('base_url');
		}

		empty($prefix) OR $uri = $prefix.'/'.$uri;
		($this->static_url === $this->slash_item('base_url')) && $uri = 'content/'.$uri;

		return normalize_url($this->static_url.'/'.$uri, true);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the URL to the content folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function content_url($uri = '')
	{
		return $this->static_url($uri);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the URL to the common folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function common_url($uri = '')
	{
		return $this->static_url($uri, 'common');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the URL to the uploads folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function uploads_url($uri = '')
	{
		return $this->static_url($uri, 'uploads');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the URL to the themes folder.
	 * @param   string  $uri
	 * @return  string
	 */
	public function themes_url($uri = '')
	{
		return $this->static_url($uri, 'themes');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the URL to the currently active theme.
	 * @param   string  $uri
	 * @return  string
	 */
	public function theme_url($uri = '')
	{
		isset($this->theme) OR $this->theme = $this->item('theme');
		return $this->static_url($uri, 'themes/'.$this->theme);
	}

	// --------------------------------------------------------------------

	/**
	 * nonce_url
	 *
	 * Function for generating site URLs with appended security token.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 *
	 * @since 	1.5.0
	 *
	 * @param 	string 	$uri 		The URI used to generate the URL.
	 * @param 	string 	$action 	Action to attach to the URL.
	 * @param	string	$protocol
	 * @return 	string
	 */
	public function nonce_url($uri = '', $action = -1, $protocol = null)
	{
		if ('#' === $uri OR false === $uri)
		{
			return 'javascript:void(0);';
		}

		$nonce = get_instance()->nonce->create($action);
		$uri .= str_contains($uri, '?') ? "&amp;nonce=$nonce" : "?nonce=$nonce";

		return normalize_url(parent::site_url($uri, $protocol));
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to read and return a config array found inside the given file.
	 *
	 * @access 	public
	 * @param   string  $file       the config file to read.
	 * @param   bool    $silent     whether to show errors or simply return false.
	 * @param   string  $module     name of the module where the config file exists.
	 * @param   bool    $moduleonly     whether to fail if config does not exist.
	 * @return  array   An array of settings, or false on failure.
	 */
	public function read($file, $silent = true, $module = '', $moduleOnly = false)
	{
		$CI =& get_instance();

		$file = ($file == '' ? 'config' : str_replace('.php', '', $file)).'.php';

		// look in module first
		$found = false;
		if ($module && false !== ($modpath = $CI->router->module_path($module)))
		{
			$modfile = normalize_path($modpath.'config/'.$file);
			if (is_file($modfile))
			{
				$file  = $modfile;
				$found = true;
			}
		}

		// Fall back to application directory
		if ( ! $found && ! $moduleOnly)
		{
			$checkLocations = array();

			if (defined('ENVIRONMENT'))
			{
				$checkLocations[] = normalize_path(APPPATH.'config/'.ENVIRONMENT."/{$file}");
			}

			$checkLocations[] = normalize_path(APPPATH."config/{$file}");

			foreach ($checkLocations as $location)
			{
				if (is_file($location))
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

		include($file);

		if ( ! isset($config) OR ! is_array($config))
		{
			if ($silent === true)
			{
				return false;
			}

			show_error("Your {$file} file does not appear to contain a valid configuration array.");
		}

		return $config;
	}

	// --------------------------------------------------------------------

	/**
	 * Save the passed array settings into a single config file located in the
	 * config directory.
	 *
	 * @param   string  $file 		the config file to write to.
	 * @param   array   $array 		An array of config to be written to the file.
	 * @param   string  $module 	Name of the module where the config file exists.
	 * @return 	bool 	true if file written, otherwise false.
	 */
	public function write($file = '', $array = null, $module = '', $apppath = APPPATH)
	{
		if (empty($file) OR ! is_array($array))
		{
			return false;
		}

		$file = ($file == '' ? 'config' : str_replace('.php', '', $file)).'.php';

		// Set CI instance if not already set.
		$CI =& get_instance();

		$configFile = "config/{$file}";

		// look in module first.
		$found = false;
		if ($module && false !== ($modpath = $CI->router->module_path($module)))
		{
			$modfile = $modpath.$configFile;
			if (file_exists($modfile))
			{
				$configFile = normalize_path($modfile);
				$found      = true;
			}
		}

		// fall back to application directory.
		if ( ! $found)
		{
			$configFile = normalize_path("{$apppath}{$configFile}");
			$found      = is_file($configFile);
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

		foreach ($array as $name => $val)
		{
			// Is the config setting in the file?
			$start  = strpos($contents, '$config[\''.$name.'\']');
			$end    = strpos($contents, ';', $start);
			$search = substr($contents, $start, $end - $start + 1);

			// format the value to be written to the file.
			if (is_array($val))
			{
				// Get the array output.
				$val = $this->prep_config($val);
			}
			elseif (is_bool($val))
			{
				$val = (true === $val) ? 'true' : 'false';
			}
			elseif ( ! is_numeric($val))
			{
				$val = "'{$val}'";
			}

			// For a new file, just append the content. For an existing file, search
			// the file's contents and replace the config setting.

			if ($empty)
			{
				$contents .= '$config[\''.$name.'\'] = '.$val.";\n";
			}
			else
			{
				$contents = str_replace($search, '$config[\''.$name.'\'] = '.$val.';', $contents);
			}
		}

		// Backup the file for safety.
		$source = $configFile;
		$dest   = normalize_path(($module == '' ? APPPATH.'backups/config/'.$file : $configFile).'.bak');

		if ($empty === false)
		{
			@copy($source, $dest);
		}

		// Make sure the file still has the php opening header in it...
		if (strpos($contents, '<?php') === false)
		{
			$contents = '<?php'.PHP_EOL."defined('BASEPATH') OR die;\n\n".$contents;
		}

		// Write the changes out...
		function_exists('write_file') OR $CI->load->helper('file');
		$result = write_file($configFile, $contents);

		return $result !== false;
	}

	// Alias of the function above.
	public function save($file = '', $array = null, $module = '', $apppath = APPPATH)
	{
		return $this->write($file, $array, $module, $apppath);
	}

	// --------------------------------------------------------------------

	/**
	 * Converts an array into its string representation which is then used
	 * to be written into a config file.
	 *
	 * @access 	private
	 * @param   array   $array      values to store in the config.
	 * @param   int     $numtabs    optional number of tabs to use in front of the array.
	 * @return  mixed   a string representing the array values, otherwise false.
	 */
	private function prep_config($array, $numtabs = 1)
	{
		if ( ! is_array($array))
		{
			return false;
		}

		$tval = 'array(';

		// allow for two-dimensional arrays.
		$keys = array_keys($array);

		// check whether they are basic numeric keys.
		if (isset($keys[0]) && is_numeric($keys[0]) && $keys[0] == 0)
		{
			$tval .= "'".implode("', '", $array)."'";
		}
		else
		{
			// non-numeric keys.
			$tabs = "";
			for ($num = 0; $num < $numtabs; $num++)
			{
				$tabs .= "\t";
			}

			foreach ($array as $key => $value)
			{
				$tval .= "\n{$tabs}'{$key}' => ";
				if (is_array($value))
				{
					$numtabs++;
					$tval .= $this->prep_config($value, $numtabs);
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

	// --------------------------------------------------------------------

	/**
	 * _configure
	 *
	 * Meant for internal usage.
	 * Checks for 'config:' keyword before checking for any item.
	 *
	 * @param 	string 	$key
	 * @param 	string 	$index
	 * @return 	mixed 	the config item if found, else null.
	 */
	public function _configure($key, $index = null)
	{
		if ( ! is_string($key))
		{
			return $key;
		}
		elseif (sscanf($key, 'config:%s', $key) === 1)
		{
			return $this->_configure($key, $index);
		}
		elseif ( ! empty($index) && isset($this->config[$index], $this->config[$index][$key]))
		{
			return $this->config[$index][$key];
		}
		elseif (isset($this->config[$key]))
		{
			return $this->config[$key];
		}
		else
		{
			return null;
		}
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('_configure'))
{
	/**
	 * _configure
	 *
	 * Function for getting the config value of the selected string if
	 * it contains the "config:" keyword at the beginning.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @param   string  $key
	 * @param   string  $index
	 * @return  string
	 */
	function _configure($key, $index = null)
	{
		return ($item = get_instance()->config->_configure($key, $index)) ? $item : $key;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('content_path'))
{
	/**
	 * Returns the path to the content folder.
	 * @param   string  $uri
	 * @param   string  $prefix
	 * @return  string
	 */
	function content_path($uri = '', $prefix = null)
	{
		return get_instance()->config->content_path($uri, $prefix);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('common_path'))
{
	/**
	 * Returns the path to the common folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function common_path($uri = '')
	{
		return get_instance()->config->common_path($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('uploads_path'))
{
	/**
	 * Returns the path to the uploads folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function uploads_path($uri = '')
	{
		return get_instance()->config->uploads_path($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('themes_path'))
{
	/**
	 * Returns the path to the themes folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function themes_path($uri = '')
	{
		return get_instance()->config->themes_path($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('theme_path'))
{
	/**
	 * Returns the path to the currently active theme.
	 * @param   string  $uri
	 * @return  string
	 */
	function theme_path($uri = '')
	{
		return get_instance()->config->theme_path($uri);
	}
}
