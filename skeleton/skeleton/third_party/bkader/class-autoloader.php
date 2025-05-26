<?php
defined('BASEPATH') OR die;

/**
 * Autoloader Class
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Add-ons
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.1
 * @version 	2.1
 */
final class Autoloader
{
	/**
	 * Holds all the classes and their paths.
	 * @var array
	 */
	protected static $classes = array();

	/**
	 * Method for adding classes load path. Any class added here will not
	 * be searched for but explicitly loaded from the path.
	 *
	 * @static
	 * @access 	public
	 * @param 	string 	$class 		The class name.
	 * @param 	string 	$path 		The path to the class file.
	 * @param 	array 	$condition 	Only register class if '$condition' is met.
	 * @return 	void
	 */
	public static function add_class(string $class, string $path, bool $condition = true)
	{
		if ($condition && ! isset(self::$classes[$class]))
		{
			// Support for namespaces.
			strpos($class, '\\') && $class = str_replace('\\', '/', $class);

			self::$classes[$class] = normalize_path($path);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for adding multiple class paths to the load path {@see Autoloader::add_class}.
	 *
	 * @static
	 * @param 	array 	$classes 	Array of classes and their paths (class => path)
	 * @param 	array 	$condition 	Only register classes if '$condition' is met.
	 * @return 	void
	 */
	public static function add_classes(array $classes, bool $condition = true)
	{
		if ($condition)
		{
			foreach ($classes as $class => $path)
			{
				if ( ! isset(self::$classes[$class]))
				{
					self::$classes[$class] = normalize_path($path);
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for returning the path to the previously registered class.
	 *
	 * @since 	2.12
	 * @static
	 * @param 	string 	$class 	The class name.
	 * @return 	mixed 	The full path if found, else false.
	 */
	public static function class_path($class)
	{
		return isset(self::$classes[$class]) ? self::$classes[$class] : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Registers the autoloader to the SPL autoload stack.
	 *
	 * @static
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public static function register()
	{
		spl_autoload_register('Autoloader::load', true, true);
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a class.
	 *
	 * @static
	 * @access 	public
	 * @param 	string 	$class 	The class to load.
	 * @return 	bool 	true if the class was loaded, else false.
	 */
	public static function load($class)
	{
		if ( ! isset(self::$classes[$class]))
		{
			return false;
		}

		require_once(self::$classes[$class]);
		return true;
	}

}
