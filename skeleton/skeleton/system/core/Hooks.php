<?php
defined('BASEPATH') OR die;

/**
 * Hooks Class
 *
 * Provides a mechanism to extend the base system without hacking.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/general/hooks.html
 */
class CI_Hooks
{
	/**
	 * Determines whether hooks are enabled
	 *
	 * @var bool
	 */
	public $enabled = false;

	/**
	 * List of all hooks set in config/hooks.php
	 *
	 * @var array
	 */
	public $hooks = array();

	/**
	 * Array with class objects to use hooks methods
	 *
	 * @var array
	 */
	protected $_objects = array();

	/**
	 * In progress flag
	 *
	 * Determines whether hook is in progress, used to prevent infinte loops
	 *
	 * @var bool
	 */
	protected $_in_progress = false;

	/**
	 * Paths where hook files are located.
	 *
	 * @var array
	 */
	protected $_hook_paths = array(APPPATH);

	/**
	 * Class constructor
	 *
	 * @param	CI_Config	$config
	 * @param	CI_Events	$events
	 * @return  void
	 */
	public function __construct(CI_Config &$config, CI_Events &$events)
	{
		log_message('info', 'Hooks Class Initialized');

		// If hooks are not enabled in the config file
		// there is nothing else to do
		if ($config->item('enable_hooks') === false)
		{
			return;
		}

		$all_hooks = array();

		// Grab the "hooks" definition file.
		foreach ($this->_hook_paths as $path)
		{
			if (is_file($path.'config/hooks.php'))
			{
				include($path.'config/hooks.php');
			}

			if (is_file($path.'config/'.ENVIRONMENT.'/hooks.php'))
			{
				include($path.'config/'.ENVIRONMENT.'/hooks.php');
			}
		}

		// If there are no hooks, we're done.
		if ( ! isset($hook) OR ! is_array($hook))
		{
			return;
		}

		$this->hooks =& $hook;
		$this->enabled = true;
	}

	// --------------------------------------------------------------------

	/**
	 * Call Hook
	 *
	 * Calls a particular hook. Called by CodeIgniter.php.
	 *
	 * @uses    CI_Hooks::_run_hook()
	 *
	 * @param   string  $which  Hook name
	 * @return  bool    true on success or false on failure
	 */
	public function call_hook($which = '')
	{
		if ( ! $this->enabled OR ! isset($this->hooks[$which]))
		{
			return false;
		}
		elseif (is_array($this->hooks[$which]) && ! isset($this->hooks[$which]['function']))
		{
			foreach ($this->hooks[$which] as $val)
			{
				$this->_run_hook($val);
			}
		}
		else
		{
			$this->_run_hook($this->hooks[$which]);
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Run Hook
	 *
	 * Runs a particular hook
	 *
	 * @param   array   $data   Hook details
	 * @return  bool    true on success or false on failure
	 */
	protected function _run_hook($data)
	{
		// Closures/lambda functions and array($object, 'method') callables
		if (is_callable($data))
		{
			is_array($data)
				? $data[0]->{$data[1]}()
				: $data();

			return true;
		}
		elseif ( ! is_array($data))
		{
			return false;
		}

		// -----------------------------------
		// Safety - Prevents run-away loops
		// -----------------------------------

		// If the script being called happens to have the same
		// hook call within it a loop can happen
		elseif ($this->_in_progress === true)
		{
			return;
		}

		// -----------------------------------
		// Set file path
		// -----------------------------------

		elseif ( ! isset($data['filepath'], $data['filename']))
		{
			return false;
		}
		elseif ( ! is_file($filepath = $data['filepath'].'/'.$data['filename']))
		{
			foreach ($this->_hook_paths as $path)
			{
				if (is_file($filepath = $path.$data['filepath'].'/'.$data['filename']))
				{
					break;
				}
			}
		}

		if ( ! isset($filepath))
		{
			return false;
		}

		// Determine and class and/or function names
		$class    = empty($data['class']) ? false : $data['class'];
		$function = empty($data['function']) ? false : $data['function'];
		$params   = isset($data['params']) ? $data['params'] : '';

		if (empty($function))
		{
			return false;
		}

		// Set the _in_progress flag
		$this->_in_progress = true;

		// Call the requested class and/or function
		if ($class !== false)
		{
			// The object is stored?
			if (isset($this->_objects[$class]))
			{
				if (method_exists($this->_objects[$class], $function))
				{
					$this->_objects[$class]->$function($params);
				}
				else
				{
					return $this->_in_progress = false;
				}
			}
			else
			{
				class_exists($class, false) OR require_once($filepath);

				if ( ! class_exists($class, false) OR ! method_exists($class, $function))
				{
					return $this->_in_progress = false;
				}

				// Store the object and execute the method
				$this->_objects[$class] = new $class();
				$this->_objects[$class]->$function($params);
			}
		}
		else
		{
			function_exists($function) OR require_once($filepath);

			if ( ! function_exists($function))
			{
				return $this->_in_progress = false;
			}

			$function($params);
		}

		$this->_in_progress = false;
		return true;
	}

}
