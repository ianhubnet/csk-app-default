<?php
defined('BASEPATH') OR die;

/**
 * KB_Input Class
 *
 * This class extends CI_Input class in order to add some useful methods.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	1.33
 */
class KB_Input extends CI_Input
{
	/**
	 * Cached protocol.
	 * @var string
	 */
	protected $protocol;

	/**
	 * Stores the current request method.
	 * @var string
	 */
	public $request_method = 'get';

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Used to store current request.
	 *
	 * @uses 	parent::__construct
	 * @return 	void
	 */
	public function __construct(CI_Config &$config, CI_Security &$security)
	{
		// store the current request before calling parent's
		$this->request_method = $this->method();

		parent::__construct($config, $security);
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch an item from the FILES array
	 *
	 * @param   mixed   $index      Index for item to be fetched from $_FILES
	 * @param   bool    $xss_clean  Whether to apply XSS filtering
	 * @return  mixed
	 */
	public function file($index = null, $xss_clean = false)
	{
		return $this->_fetch_from_array($_FILES, $index, $xss_clean);
	}

	// --------------------------------------------------------------------

	/**
	 * request
	 *
	 * Method for fetching an item from the REQUEST array
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	string 	$index 		Index of the item to be fetched from $_REQUEST.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	mixed
	 */
	public function request($index, $xss_clean = false)
	{
		return $this->_fetch_from_array($_REQUEST, $index, $xss_clean);
	}

	// --------------------------------------------------------------------

	/**
	 * request_or_header
	 *
	 * Method for fetching an item from REQUEST or HTTP header.
	 *
	 * @access 	public
	 * @param 	string 	$index 		Index of the item to be fetched from $_REQUEST.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	mixed
	 */
	public function request_or_header($index, $xss_clean = false)
	{
		$value = $this->_fetch_from_array($_REQUEST, $index, $xss_clean);

		return ($value === null) ? parent::get_request_header($index, $xss_clean) : $value;
	}

	// --------------------------------------------------------------------

	/**
	 * protocol
	 *
	 * Method for returning the protocol that the request was make with.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	public function protocol()
	{
		if (isset($this->protocol))
		{
			return $this->protocol;
		}

		if ('on' == $this->server('HTTPS') OR 1 == $this->server('HTTPS') OR 443 == $this->server('SERVER_PORT'))
		{
			$this->protocol = 'https';
			return $this->protocol;
		}

		$this->protocol = 'http';
		return $this->protocol;
	}

	// --------------------------------------------------------------------

	/**
	 * referrer
	 *
	 * Method for returning the REFERRER.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	string 	$default 	What to return if no referrer is found.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering
	 * @return 	string
	 */
	public function referrer($default = '', $xss_clean = false)
	{
		$referrer = $this->server('HTTP_REFERER', $xss_clean);
		return ($referrer) ? $referrer : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * ip_address
	 *
	 * Determines and validates the visitor's IP address.
	 * @since 	2.94
	 *
	 * @access 	public
	 * @param 	string 	$default 	What to return if no referrer is found.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering
	 * @return 	string
	 */
	public function ip_address()
	{
		return (function_exists('ip_address')) ? ip_address() : parent::ip_address();
	}

	// --------------------------------------------------------------------

	/**
	 * query_string
	 *
	 * Methods for returning the QUERY_STRING from $_SERVER array.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	string 	$default 	What to return if nothing found.
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering
	 * @return 	string
	 */
	public function query_string($default = '', $xss_clean = false)
	{
		$query_string = $this->server('QUERY_STRING', $xss_clean);
		return ($query_string) ? $query_string : $default;
	}

	// --------------------------------------------------------------------

	/**
	 * is cURL request?
	 *
	 * Only works if 'HTTP_USER_AGENT' contains 'curl' or 'wget'.
	 *
	 * @return 	bool
	 */
	public function is_curl_request()
	{
		return (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/^(curl|wget)/i', $_SERVER['HTTP_USER_AGENT']));
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces parent::is_ajax_request to add an argument to check for cli
	 * request if it fails.
	 *
	 * @deprecated 	Use 'is_ajax'.
	 *
	 * @param 	bool 	$maybe_cli
	 * @return 	bool
	 */
	public function is_ajax_request($maybe_cli = false)
	{
		return is_ajax($maybe_cli);
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces parent::is_cli_request to prevent multiple function calls and
	 * remove this method from being deprecated.
	 *
	 * @deprecated 	Use 'is_cli'
	 *
	 * @since 	2.88
	 * @return 	bool
	 */
	public function is_cli_request()
	{
		return is_cli();
	}

	// --------------------------------------------------------------------

	/**
	 * Tests if the current requests is intended for API.
	 *
	 * @deprecated 	Use 'is_api'
	 *
	 * @since 	2.111
	 * @return 	bool
	 */
	public function is_api_request()
	{
		return is_api();
	}

	// --------------------------------------------------------------------

	/**
	 * is_get_request
	 *
	 * Method for making sure the request is a GET request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.33
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a GET request, else false.
	 */
	public function is_get_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'GET');
	}

	// --------------------------------------------------------------------

	/**
	 * is_post_request
	 *
	 * Method for making sure the request is a POST request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.33
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a POST request, else false.
	 */
	public function is_post_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'POST');
	}

	// --------------------------------------------------------------------

	/**
	 * is_put_request
	 *
	 * Method for making sure the request is a PUT request.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.33
	 *
	 * @access  public
	 * @param   bool    $xss_clean  Whether to apply XSS filtering.
	 * @return  bool    true if it is a PUT request, else false.
	 */
	public function is_put_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'PUT');
	}

	// --------------------------------------------------------------------

	/**
	 * is_delete_request
	 *
	 * Method for making sure the request is a DELETE request.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  public
	 * @param   bool    $xss_clean  Whether to apply XSS filtering.
	 * @return  bool    true if it is a DELETE request, else false.
	 */
	public function is_delete_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'DELETE');
	}

	// --------------------------------------------------------------------

	/**
	 * is_head_request
	 *
	 * Method for making sure the request is a HEAD request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.33
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a HEAD request, else false.
	 */
	public function is_head_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'HEAD');
	}

	// --------------------------------------------------------------------

	/**
	 * is_patch_request
	 *
	 * Method for making sure the request is a HEAD request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a HEAD request, else false.
	 */
	public function is_patch_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'PATCH');
	}

	// --------------------------------------------------------------------

	/**
	 * is_options_request
	 *
	 * Method for making sure the request is a HEAD request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	bool 	$xss_clean 	Whether to apply XSS filtering.
	 * @return 	bool 	true if it is a HEAD request, else false.
	 */
	public function is_options_request($xss_clean = false)
	{
		return ($this->server('REQUEST_METHOD', $xss_clean) === 'OPTIONS');
	}

	// --------------------------------------------------------------------

	/**
	 * is_localhost
	 *
	 * Method for detecting whether the user is on localhost.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.91
	 *
	 * @access 	public
	 * @return 	bool
	 */
	public function is_localhost()
	{
		return ('127.0.0.1' === $this->server('REMOTE_ADDR') OR '::1' === $this->server('REMOTE_ADDR'));
	}

}
