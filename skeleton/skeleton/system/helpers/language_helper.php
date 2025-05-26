<?php
defined('BASEPATH') OR die;

/**
 * CodeIgniter Language Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/helpers/language_helper.html
 */

// --------------------------------------------------------------------

if ( ! function_exists('lang'))
{
	/**
	 * Lang
	 *
	 * Fetches a language variable and optionally outputs a form label
	 *
	 * @param   string  $line       The language line
	 * @param   string  $for        The "for" value (id of the form element)
	 * @param   array   $attributes Any additional HTML attributes
	 * @return  string
	 */
	function lang($line, $for = '', $attributes = array())
	{
		$line = get_instance()->lang->line($line);

		if ($for !== '')
		{
			$line = '<label for="'.$for.'"'.array_to_attr($attributes).'>'.$line.'</label>';
		}

		return $line;
	}
}
