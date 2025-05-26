<?php
defined('BASEPATH') OR die;

/**
 * KB_Driver_Library Class
 *
 * Extends CI_Driver_Library Class.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.18
 */
class KB_Driver_Library extends CI_Driver_Library
{
	/**
	 * Paths where packages can be found, excluding BASEPATH.
	 * @var array
	 */
	private $valid_paths;

	/**
	 * Subclass prefix.
	 * @var string
	 */
	private $prefix;

	// --------------------------------------------------------------------

	/**
	 * Load driver
	 *
	 * Separate load_driver call to support explicit driver load by library or user
	 *
	 * @param   string  Driver name (w/o parent prefix)
	 * @return  object  Child class
	 */
	public function load_driver($child)
	{
		// See if requested child is a valid driver
		if ( ! in_array($child, $this->valid_drivers))
		{
			// The requested driver isn't valid!
			$msg = 'Invalid driver requested: '.$child;
			log_message('error', $msg);
			show_error($msg);
		}

		/**
		 * Get CodeIgniter instance and subclass prefix then
		 * get library name without any prefix.
		 */
		if ( ! isset($this->lib_name))
		{
			$this->lib_name = str_replace(
				array('CI_', isset($this->prefix) ? $this->prefix : $this->prefix = config_item('subclass_prefix')),
				'',
				get_class($this)
			);
		}

		// The child will be prefixed with the parent lib
		$child_name = $this->lib_name.'_'.$child;

		/**
		 * Get package paths and filename case variations
		 * to search except BASEPATH.
		 */
		if ( ! isset($this->valid_paths))
		{
			$this->valid_paths = array_diff(get_instance()->load->get_package_paths(true), array(BASEPATH));
		}

		// Is there an extension?
		if ( ! ($found = class_exists($class_name = $this->prefix.$child_name, false)))
		{
			// Check for subclass file
			foreach ($this->valid_paths as $path)
			{
				if (is_file($file = $path.'libraries/'.$this->lib_name.'/drivers/'.$this->prefix.$child_name.'.php'))
				{
					/**
					 * Yes - require base class from BASEPATH
					 * And this is why we excluded BASEPATH from search paths
					 * in the first page.
					 */
					if ( ! is_file($basepath = BASEPATH.'libraries/'.$this->lib_name.'/drivers/'.$child_name.'.php'))
					{
						$msg = 'Unable to load the requested class: CI_'.$child_name;
						log_message('error', $msg);
						show_error($msg);
					}

					// Include both sources and mark found
					include_once($basepath);
					include_once($file);
					$found = true;
					break;
				}
			}
		}

		// Do we need to search for the class?
		if ( ! $found)
		{
			// Use standard class name
			if ( ! class_exists($class_name = 'CI_'.$child_name, false))
			{
				// Check package paths
				foreach ($this->valid_paths as $path)
				{
					// Does the file exist?
					if (is_file($file = $path.'libraries/'.$this->lib_name.'/drivers/'.$child_name.'.php'))
					{
						// Include source
						include_once($file);
						break;
					}
				}
			}
		}

		// Did we finally find the class?
		if ( ! class_exists($class_name, false))
		{
			if (class_exists($child_name, false))
			{
				$class_name = $child_name;
			}
			else
			{
				$msg = 'Unable to load the requested driver: '.$class_name;
				log_message('error', $msg);
				show_error($msg);
			}
		}

		// Instantiate, decorate and add child
		$obj = new $class_name();
		$obj->decorate($this);
		$this->$child = $obj;
		return $this->$child;
	}
}

// --------------------------------------------------------------------

/**
 * KB_Driver Class
 *
 * Extends CI_Driver Class.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.94
 */
class KB_Driver extends CI_Driver {}
