<?php
defined('BASEPATH') OR die;

/**
 * CodeIgniter Security Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/helpers/security_helper.html
 */

// --------------------------------------------------------------------

if ( ! function_exists('xss_clean'))
{
	/**
	 * XSS Filtering
	 *
	 * @param   string
	 * @param   bool    whether or not the content is an image file
	 * @return  string
	 */
	function xss_clean($str, $is_image = false)
	{
		return get_instance()->security->xss_clean($str, $is_image);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_filename'))
{
	/**
	 * Sanitize Filename
	 *
	 * @param   string
	 * @return  string
	 */
	function sanitize_filename($filename)
	{
		return get_instance()->security->sanitize_filename($filename);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('strip_image_tags'))
{
	/**
	 * Strip Image Tags
	 *
	 * @param   string
	 * @return  string
	 */
	function strip_image_tags($str)
	{
		return get_instance()->security->strip_image_tags($str);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('encode_php_tags'))
{
	/**
	 * Convert PHP tags to entities
	 *
	 * @param   string
	 * @return  string
	 */
	function encode_php_tags($str)
	{
		return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);
	}
}
