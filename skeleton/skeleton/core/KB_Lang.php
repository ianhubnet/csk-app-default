<?php
defined('BASEPATH') OR die;

/**
 * KB_Lang Class
 *
 * This class extends CI_Lang class in order en add, override or
 * enhance some of the parent's methods.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	2.16
 */
class KB_Lang extends CI_Lang
{
	/**
	 * Fall-back language.
	 * @var string
	 */
	public $fallback = 'english';

	/**
	 * Holds the current idiom so we don't re-process.
	 * @var string
	 */
	public $idiom;

	/**
	 * CodeIgniter main files used to check for extended ones.
	 * @var 	array
	 */
	private $_ci_lang_files = array(
		'calendar_lang.php',
		'date_lang.php',
		'db_lang.php',
		'email_lang.php',
		'form_validation_lang.php',
		'ftp_lang.php',
		'imglib_lang.php',
		'migration_lang.php',
		'number_lang.php',
		'pagination_lang.php',
		'profiler_lang.php',
		'unit_test_lang.php',
		'upload_lang.php'
	);

	/**
	 * Paths where language files can be found.
	 * @var 	array
	 */
	protected $_ci_lang_paths = array(APPPATH, KBPATH);

	/**
	 * A flag used to prevent error when accessing
	 * the class earlier than it should.
	 * @var bool
	 */
	protected $_loaded = false;

	/**
	 * Instance of Router object.
	 * @var object
	 */
	protected $router;

	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct(CI_Config &$config = null, CI_Router &$router = null)
	{
		parent::__construct($config, $router);

		if (isset($this->router, $this->config))
		{
			$this->_loaded = true;
			$this->fallback = $config->item('language');
		}
		else
		{
			$this->fallback = config_item('language');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Load a language file
	 *
	 * @param	mixed	$langfile	Language file name
	 * @param	string	$idiom		Language name (english, etc.)
	 * @param	bool	$return		Whether to return the loaded array of translations
	 * @param 	bool	$add_suffix	Whether to add suffix to $langfile
	 * @param 	string	$alt_path	Alternative path to look for the language file
	 *
	 * @return	void|string[]	Array containing translations, if $return is set to true
	 */
	public function load($langfile, $idiom = '', $return = false, $add_suffix = true, $alt_path = '')
	{
		// Prepare the language to use.
		$idiom = $this->prep_idiom($idiom);

		// Loading multiple language files?
		if (is_array($langfile))
		{
			foreach ($langfile as $value)
			{
				$this->load($value, $idiom, $return, $add_suffix, $alt_path);
			}

			return;
		}

		// Prepare language file.
		$langfile = $this->prep_langfile($langfile);

		// Already loaded?
		if ( ! $return && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom)
		{
			return;
		}

		// Prepare the array of language lines and
		// load the '$fallback' version first.
		$full_lang = array();

		// Load the base file, so any others found can override it
		if ( ! ($found = $this->load_file($full_lang, $langfile, $this->fallback, $alt_path)))
		{
			show_error('Unable to load the requested language file: language/'.$this->fallback.'/'.$langfile);
		}

		// Proceed only if the requested language is different from the fallback.
		($idiom !== $this->fallback) && $found_in_idiom = $this->load_file($full_lang, $langfile, $idiom, $alt_path);

		// Nothing found?
		if ( ! $found && ! $found_in_idiom)
		{
			show_error('Unable to load the requested language file: language/'.$idiom.'/'.$langfile);
		}
		// Found but empty?
		elseif (empty($full_lang))
		{
			log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);

			return $return ? array() : null;
		}
		// Should return full language array?
		elseif ($return)
		{
			return $full_lang;
		}
		else
		{
			$this->is_loaded[$langfile] = (isset($found_in_idiom) && $found_in_idiom) ? $idiom : $this->fallback;
			$this->language = deep_array_merge($this->language, $full_lang);

			log_message('info', 'Language file loaded: language/'.$idiom.'/'.$langfile);
			return true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * The real method that handles loading language files.
	 *
	 * @param 	array 	$full_lang
	 * @param 	string 	$langfile
	 * @param 	string 	$idiom
	 * @param 	string 	$alt_path
	 * @return 	boo
	 */
	private function load_file(&$full_lang, $langfile, $idiom, $alt_path)
	{
		// Load the base file, so any others found can override it
		if ($found = is_file($basepath = BASEPATH.'language/'.$idiom.'/'.$langfile))
		{
			include_once $basepath;

			if (isset($lang))
			{
				$full_lang = array_replace_recursive($full_lang, $lang);
				unset($lang);
			}
		}

		/**
		 * Allow CI Skeleton to extend some CodeIgniter language files.
		 * Priority to application-specific files.
		 */
		if (in_array($langfile, $this->_ci_lang_files))
		{
			foreach ($this->_ci_lang_paths as $path)
			{
				if (is_file($core_file_path = $path.'language/'.$idiom.'/'.$langfile))
				{
					include_once $core_file_path;

					if (isset($lang))
					{
						$full_lang = array_replace_recursive($full_lang, $lang);
						unset($lang);
					}
				}
			}
		}

		// Do we have an alternative path to look in?
		if ( ! $found && $alt_path !== '' && ($found = is_file($alt_file = $alt_path.'language/'.$idiom.'/'.$langfile)))
		{
			include_once $alt_file;

			if (isset($lang))
			{
				$full_lang = array_replace_recursive($full_lang, $lang);
				unset($lang);
			}

			return $found;
		}
		elseif ($this->_loaded)
		{
			foreach (get_instance()->load->get_package_paths(true) as $path)
			{
				if ($basepath === ($file_path = $path.'language/'.$idiom.'/'.$langfile) OR ! is_file($file_path))
				{
					continue;
				}

				$found = true;

				include_once $file_path;

				if (isset($lang))
				{
					$full_lang = array_replace_recursive($full_lang, $lang);
					unset($lang);
				}

				break; // no need to search loop further
			}

			return $found;
		}
		else
		{
			return $found;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array
	 *
	 * @param	string	$line 		Language line key
	 * @param	string	$index 		Language line index/Log error bool
	 * @param 	bool 	$log_errors Whether to log an error message if the line is not found
	 * @return	string	Translation
	 */
	public function line($line, $index = '', $log_errors = true)
	{
		// Backwards compatibility.
		if (is_bool($index))
		{
			$log_errors = $index;
			$index = '';
		}

		/**
		 * Multiple lines can be concatenated if an array is passed.
		 * Lines will be separated by space.
		 * @since 	2.113
		 */
		if (is_array($line))
		{
			$value = array();

			foreach ($line as $single)
			{
				$value[] = $this->line($single, $index, $log_errors);
			}

			return implode(' ', $value);
		}

		/**
		 * Prefix 'config:' - trying to translate a config item? we make
		 * sure to remember the the config item so that we use it if we
		 * fail to find the translation.
		 *
		 * @since 	2.16
		 */
		elseif (1 === sscanf($line, 'config:%s', $item))
		{
			$item = config_item($item);
		}

		// See with provided index first...
		if (($value = $this->_translate($line, $index)) === null)
		{
			/**
			 * If it was a config item that was requested,
			 * we simply use is as a fallback.
			 * @since 	2.16
			 */
			if (isset($item))
			{
				return $item;
			}

			// Should we log errors?
			($log_errors) && log_message('error', 'Could not find the language line "'.$line.'"');

			/**
			 * Both "inflect" and "FIXME" use were dropped. We use the $line
			 * as it is, as the line to return.
			 * @since 	2.1
			 */
			return ucwords($line);
		}

		return $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns a language line using sprintf function.
	 *
	 * @since   2.16
	 *
	 * @access  public
	 * @param   mixed
	 * @return  string  the formatted line if found.
	 */
	public function sline()
	{
		$args = func_get_args();
		array_unshift($args, $this->line(array_shift($args)));
		return call_user_func_array('sprintf', $args);
	}

	// --------------------------------------------------------------------

	/**
	 * Singular and plural language line.
	 *
	 * Fetches a single line of text from the language array depending on the
	 * given $n (number).
	 *
	 * @since 	2.0.1
	 *
	 * @param	string	$singular 	The singular form of the line.
	 * @param	string	$plural 	The plural form of the line.
	 * @param	int 	$number 	The number used for comparison.
	 * @param	string	$index 		The language line index.
	 * @return	string	Translation.
	 */
	public function nline($singular, $plural, $number, $index = '')
	{
		return $this->line(($number === 1) ? $singular : $plural, $index);
	}

	// --------------------------------------------------------------------

	/**
	 * Prepare language file.
	 *
	 * @param 	string 	$langfile
	 * @param 	bool 	$add_suffix
	 * @return 	string
	 */
	private function prep_langfile($langfile, $add_suffix = true)
	{
		$langfile = preg_replace('/\.php$/', '', $langfile);
		return ($add_suffix ? preg_replace('/_lang$/', '', $langfile).'_lang' : $langfile).'.php';
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares language idiom.
	 *
	 * @param 	string
	 * @return 	string
	 */
	private function prep_idiom($idiom)
	{
		if ( ! isset($this->idiom))
		{
			$this->idiom = ($this->_loaded && $this->router->is_dashboard('login'))
				? $this->fallback
				: (($idiom && preg_match('/^[a-z_-]+$/i', $idiom)) ? $idiom : config_item('language'));
		}

		return $this->idiom;
	}

	// --------------------------------------------------------------------

	/**
	 * _translate
	 *
	 * Meant for internal usage.
	 * Checks for 'lang:' keyword first before checking for any line.
	 *
	 * @param 	string 	$key
	 * @param 	string 	$index
	 * @return 	mixed 	the language line if found, else $key.
	 */
	public function _translate($key, $index = null)
	{
		if ( ! is_string($key))
		{
			return $key;
		}
		elseif(sscanf($key, 'lang:%s', $line) === 1)
		{
			return $this->_translate($line, $index);
		}
		elseif ( ! empty($index) && isset($this->language[$index], $this->language[$index][$key]))
		{
			return $this->language[$index][$key];
		}
		elseif (isset($this->language[$key]))
		{
			return $this->language[$key];
		}
		else
		{
			return $key;
		}
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('line'))
{
	/**
	 * Alias of KB_Lang::line with optional arguments.
	 *
	 * @since 	1.0
	 * @since 	1.34 	Added $before and $after.
	 *
	 * @param 	string 	$line 	the line the retrieve.
	 * @param 	string 	$index 	whether to look under an index.
	 * @param 	string 	$before 	Whether to put something before the line.
	 * @param 	string 	$after 		Whether to put something after the line.
	 * @return 	string
	 */
	function line($line, $index = '', $before = '', $after = '')
	{
		if (is_bool($index))
		{
			return get_instance()->lang->line($line, null, $index);
		}
		elseif (is_bool($before))
		{
			return get_instance()->lang->line($line, $index, $before);
		}
		else
		{
			// Shall we translate the before?
			(empty($before)) OR $before = _translate($before);

			// Shall we translate the after?
			(empty($after)) OR $after = _translate($after);

			return $before.get_instance()->lang->line($line, $index).$after;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Returns a language line using sprintf function.
 *
 * @since   2.16
 * @param   mixed
 * @return  string  the formatted line if found.
 */
if ( ! function_exists('sline'))
{
	function sline()
	{
		return call_user_func_array(array(get_instance()->lang, 'sline'), func_get_args());
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_translate'))
{
	/**
	 * _translate
	 *
	 * Function for translating the selected string if it contains the
	 * "lang:" keyword at the beginning.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @param   string  $key
	 * @param   string  $index
	 * @return  string
	 */
	function _translate($key, $index = null)
	{
		return ($line = get_instance()->lang->_translate($key, $index)) ? $line : $key;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_transfigure'))
{
	/**
	 * _transfigure
	 *
	 * This function's name doesn't really mean the verb "transfigure", yet,
	 * it does transfigure the string. It checks if the string contains the
	 * "config:" keyword first, then the "lang:" if the first one was not
	 * found.
	 *
	 * @param   string  $key
	 * @param   string  $index
	 * @return  string
	 */
	function _transfigure($key, $index = null)
	{
		if ( ! is_string($key))
		{
			return $key;
		}
		if (sscanf($key, 'config:%s', $item))
		{
			return _configure($item, $index);
		}
		elseif (sscanf($key, 'lang:%s', $line))
		{
			return _translate($line, $index);
		}
		else
		{
			return $key;
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('__'))
{
	/**
	 * Alias of KB_Lang::line with optional arguments.
	 *
	 * @since 	1.0
	 * @since 	1.34 	Added $before and $after.
	 * @since 	2.1 	Function ignored if using Gettext.
	 *
	 * @param 	string 	$line 		the line the retrieve.
	 * @param 	string 	$index 		whether to look under an index.
	 * @param 	string 	$before 	Whether to put something before the line.
	 * @param 	string 	$after 		Whether to put something after the line.
	 * @return 	string
	 */
	function __($line, $index = '', $before = '', $after = '')
	{
		if (is_bool($index))
		{
			return get_instance()->lang->line($line, null, $index);
		}
		elseif (is_bool($before))
		{
			return get_instance()->lang->line($line, $index, $before);
		}
		else
		{
			// Shall we translate the before?
			(empty($before)) OR $before = _translate($before);

			// Shall we translate the after?
			(empty($after)) OR $after = _translate($after);

			return $before.get_instance()->lang->line($line, $index).$after;
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_e'))
{
	/**
	 * Alias of KB_Lang::line with optional arguments.
	 *
	 * @since 	1.0
	 * @since 	1.34 	Added $before and $after.
	 * @since 	2.1 	Function ignored if using Gettext.
	 *
	 * @param 	string 	$line 		the line the retrieve.
	 * @param 	string 	$index 		whether to look under an index.
	 * @param 	string 	$before 	Whether to put something before the line.
	 * @param 	string 	$after 		Whether to put something after the line.
	 * @return 	string
	 */
	function _e($line, $index = '', $before = '', $after = '')
	{
		if (is_bool($index))
		{
			echo get_instance()->lang->line($line, null, $index);
		}
		elseif (is_bool($before))
		{
			echo get_instance()->lang->line($line, $index, $before);
		}
		else
		{
			// Shall we translate the before?
			(empty($before)) OR $before = _translate($before);

			// Shall we translate the after?
			(empty($after)) OR $after = _translate($after);

			echo $before.get_instance()->lang->line($line, $index).$after;
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nline'))
{
	/**
	 * This function is wrapper of 'KB_Lang::nline()' method.
	 * @param	string	$singular 	The singular form of the line.
	 * @param	string	$plural 	The plural form of the line.
	 * @param	int 	$number 	The number used for comparison.
	 * @param	string	$index 		The language line index.
	 * @return	string	Translation.
	 */
	function nline($singular, $plural, $number, $index = '')
	{
		return get_instance()->lang->nline($singular, $plural, $number, $index);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_n'))
{
	/**
	 * This function is wrapper of 'KB_Lang::nline()' method.
	 * 
	 * @since 	2.1 	Function ignored if using Gettext.
	 * 
	 * @param	string	$singular 	The singular form of the line.
	 * @param	string	$plural 	The plural form of the line.
	 * @param	int 	$number 	The number used for comparison.
	 * @param	string	$index 		The language line index.
	 * @return	string	Translation.
	 */
	function _n($singular, $plural, $number, $index = '')
	{
		return get_instance()->lang->nline($singular, $plural, $number, $index);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_en'))
{
	/**
	 * Alias of the "nline" function, the only different is that this 
	 * function echoes the line directly.
	 *
	 * @since 	2.0
	 * @since 	2.1 	Function ignored if using Gettext.
	 * 
	 * @param	string	$singular 	The singular form of the line.
	 * @param	string	$plural 	The plural form of the line.
	 * @param	int 	$number 	The number used for comparison.
	 * @param	string	$index 		The language line index.
	 * @return	void
	 */
	function _en($singular, $plural, $number, $index = '')
	{
		echo get_instance()->lang->nline($singular, $plural, $number, $index);
	}
}
