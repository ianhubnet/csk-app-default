<?php
defined('BASEPATH') OR die;

/**
 * Oembed Library
 *
 * Handles available types of embeds.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.93
 */
class Oembed
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $ci;

	/**
	 * Available APIs so far.
	 * @var array
	 */
	protected $_apis = array(
		'youtube' => array(
			'url' => 'https://www.youtube.com/oembed?url=',
			'param' => '&format=json',
		),
		'vimeo' => array(
			'url' => 'https://www.vimeo.com/api/oembed.json',
			'param' => '',
		),
	);

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();

		log_message('info', 'Oembed Class Initialized');
	}

	// --------------------------------------------------------------------

	public function call($api, $url)
	{
		$site = isset($this->_apis[$api]) ? $this->_apis[$api] : false;
		if (false === $site)
		{
			show_error("Kbcore_oembed - The $api API is unsupported!");
			return false;
		}

		// Prepare URL to use with cURL.
		$url = $site['url'] . rawurlencode($url) . ($site['param'] ?? '');

		// Prepare cURL instance then execute it.
		isset($this->ci->curl) OR $this->ci->load->library('curl');

		$cURL = $this->ci->curl->get($url, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => 30
		));

		return $cURL->is_success() ? json_decode($cURL->response) : false;
	}

}
