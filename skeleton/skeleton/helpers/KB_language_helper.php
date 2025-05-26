<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Helpers
 * @category 	Language Helper
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

// --------------------------------------------------------------------

if ( ! function_exists('lang'))
{
	/**
	 * Get translated local strings with arguments.
	 *
	 * Overwrites CI language_helper function to have a more useful one.
	 *
	 * @example 	lang('line_key', 'arg1', 'arg2', ...)
	 * @example 	lang('line_key', array('arg1', 'arg2', ...))
	 * @example 	lang('Hello %s! This is a %s.', 'World', 'Test')
	 *
	 * @param 	mixed
	 * @return 	string
	 */
	function lang()
	{
		if (empty($args = func_get_args()))
		{
			return false;
		}

		$CI =& get_instance();

		$line = array_shift($args);

		(isset($CI->lang->language[$line])) && $line = $CI->lang->line($line);

		if (empty($args))
		{
			return $line;
		}

		is_array($args[0]) && $args = $args[0];

		return vsprintf($line, $args);
	}
}
