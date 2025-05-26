<?php
defined('BASEPATH') OR die;

if ( ! function_exists('sanitize'))
{
	/**
	 * sanitize
	 *
	 * Function for sanitizing a string.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @param   string  $string     The string to sanitize.
	 * @return  string  The string after being sanitized.
	 */
	function sanitize($string)
	{
		// Make sure required functions are available.
		$CI =& get_instance();
		function_exists('strip_slashes') OR $CI->load->helper('string');
		function_exists('xss_clean') OR $CI->load->helper('security');

		// Sanitize the string.
		return xss_clean(e(strip_slashes($string)));
	}
}
