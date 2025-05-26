<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Helpers
 * @category 	Scraper Helper
 * @since 		2.94
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

// --------------------------------------------------------------------

if ( ! function_exists('scrape_html'))
{
	/**
	 * Uses Curl library to scrape the content of the given URL.
	 *
	 * @uses 	Curl
	 * @param 	string 	$url 		The URL to scrape.
	 * @param 	array 	$data 		Array used to build GET query.
	 * @param 	arrau 	$options 	Options to pass to cURL.
	 */
	function scrape_html(string $url, array $data = array(), array $options = array())
	{
		$CI =& get_instance();

		isset($CI->curl) OR $CI->load->library('curl');

		empty($options) OR $CI->curl->set_options($options);

		$cURL = $CI->curl->get($url, $data);

		if ($cURL->is_success())
		{
			return $cURL->get_response();
		}
		else
		{
			return array(
				'error_code'    => $cURL->error_code,
				'error_string' => $cURL->error_message
			);
		}
	}
}
