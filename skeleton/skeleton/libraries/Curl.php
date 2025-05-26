<?php
defined('BASEPATH') OR die;

/**
 * Curl Class
 *
 * Handles all cURL operations.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.18
 */

/**
 * Define some constants just in case.
 * @since 	2.105
 */
defined('CURLAUTH_BASIC') OR define('CURLAUTH_BASIC', 1);
defined('CURLAUTH_DIGEST') OR define('CURLAUTH_DIGEST', 2);
defined('CURLAUTH_GSSNEGOTIATE') OR define('CURLAUTH_GSSNEGOTIATE', 4);
defined('CURLAUTH_NTLM') OR define('CURLAUTH_NTLM', 8);
defined('CURLAUTH_ANYSAFE') OR define('CURLAUTH_ANYSAFE', 4294967278);
defined('CURLAUTH_ANY') OR define('CURLAUTH_ANY', 4294967279);

class Curl
{
	// --------------------------------------------------------------------
	// HTTP Authentication Methods.
	// --------------------------------------------------------------------

	/**
	 * HTTP Basic authentication.
	 * This is the default choice, and the only method that is in wide-spread
	 * use and supported virtually everywhere. This sends the username and
	 * password over the network in plain text, easily captured by others.
	 * @var int
	 */
	const AUTH_BASIC = CURLAUTH_BASIC;

	/**
	 * HTTP Digest authentication.
	 * Digest authentication is defined in RFC 2617 and is a more secure way
	 * to do authentication over public networks than the regular old-fashioned
	 * Basic method.
	 * @var int
	 */
	const AUTH_DIGEST = CURLAUTH_DIGEST;

	/**
	 * @Type AUTH_GSSNEGOTIATE
	 * @var int
	 */
	const AUTH_GSSNEGOTIATE = CURLAUTH_GSSNEGOTIATE;

	/**
	 * HTTP NTLM authentication.
	 * A proprietary protocol invented and used by Microsoft. It uses a challenge-response
	 * and hash concept similar to Digest, to prevent the password from being eavesdropped.
	 * You need to build libcurl with either OpenSSL or GnuTLS support for this option
	 * to work, or build libcurl on Windows with SSPI support.
	 * @var int
	 */
	const AUTH_NTLM = CURLAUTH_NTLM;

	/**
	 * This is a convenience macro that sets all bits except Basic and thus makes libcurl
	 * pick any it finds suitable. libcurl automatically selects the one it finds most secure.
	 * @var int
	 */
	const AUTH_ANYSAFE = CURLAUTH_ANYSAFE;

	/**
	 * This is a convenience macro that sets all bits and thus makes libcurl pick any it
	 * finds suitable. libcurl automatically selects the one it finds most secure.
	 * @var int
	 */
	const AUTH_ANY = CURLAUTH_ANY;

	/**
	 * The user agent name which is set when making a request.
	 * @var string
	 */
	private $user_agent;

	/**
	 * Array of curl cookies.
	 * @var array
	 */
	private $_cookies = array();

	/**
	 * Array of curl headers.
	 * @var array
	 */
	private $_headers = array();

	/**
	 * Contains the curl resource created by `curl_init()` function.
	 * @var resource
	 */
	public $curl;

	/**
	 * Whether an error occurred or not.
	 * @var bool
	 */
	public $error = false;

	/**
	 * Contains the error code of the current request, 0 means no error happened.
	 * @var int
	 */
	public $error_code = 0;

	/**
	 * If the curl request failed, the error message is contained.
	 * @var string
	 */
	public $error_message = null;

	/**
	 * Whether an error occurred or not.
	 * @var bool
	 */
	public $curl_error = false;

	/**
	 * Contains the error code of the current request, 0 means no error happened.
	 * @var int
	 */
	public $curl_error_code = 0;

	/**
	 * If the curl request failed, the error message is contained.
	 * @var string
	 */
	public $curl_error_message = null;

	/**
	 * Whether an error occurred or not
	 * @var bool
	 */
	public $http_error = false;

	/**
	 * Contains the status code of the current processed request.
	 * @var int
	 */
	public $http_status_code = 0;

	/**
	 * If the curl request failed, the error message is contained.
	 * @var string
	 */
	public $http_error_message = null;

	/**
	 * Contains the request header information.
	 * @var string|arrayTBD (ensure type)
	 */
	public $request_headers = null;

	/**
	 * Contains the response header information.
	 * @var string|array TBD (ensure type)
	 */
	public $response_headers = array();

	/**
	 * Contains the response from the curl request.
	 * @var string|false|null
	 */
	public $response = null;

	/**
	 * Whether the current section of response headers is after 'HTTP/1.1 100 Continue'.
	 * @var bool
	 */
	protected $response_header_continue = false;

	// --------------------------------------------------------------------

	/**
	 * Constructor ensures the available curl extension is loaded.
	 *
	 * @throws 	RuntimeException
	 * @return 	void
	 */
	public function __construct()
	{
		if ( ! extension_loaded('curl'))
		{
			throw new RuntimeException('The cURL extensions is not loaded, make sure you have installed the cURL extension: https://php.net/manual/curl.setup.php');
		}

		$this->init();
	}

	// --------------------------------------------------------------------
	// Private Methods.
	// --------------------------------------------------------------------

	/**
	 * Initialization of the curl resource.
	 * Is called by the __construct() of the class or when the curl request is reset.
	 *
	 * @return 	self
	 */
	private function init()
	{
		isset($this->user_agent) OR $this->user_agent = 'curl/'.curl_version()['version'];

		$this->curl = curl_init();
		$this->set_user_agent($this->user_agent);
		$this->set_option(CURLINFO_HEADER_OUT, true);
		$this->set_option(CURLOPT_HEADER, false);
		$this->set_option(CURLOPT_RETURNTRANSFER, true);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Closing the current open curl resource.
	 * Is called after each request.
	 *
	 * @return 	self
	 */
	private function close()
	{
		if (is_resource($this->curl))
		{
			curl_close($this->curl);
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Handle writing the response headers
	 *
	 * @param 	resource 	$curl 			The current curl resource
	 * @param 	string 		$header_line 	A line from the list of response headers
	 *
	 * @return 	int 	The length of the $header_line
	 */
	private function add_response_header_line($curl, $header_line)
	{
		$trimmed_header = trim($header_line, "\r\n");

		if ($trimmed_header === '')
		{
			$this->response_header_continue = false;
		}
		elseif (strtolower($trimmed_header) === 'http/1.1 100 continue')
		{
			$this->response_header_continue = true;
		}
		elseif ( ! $this->response_header_continue)
		{
			$this->response_headers[] = $trimmed_header;
		}

		return strlen($header_line);
	}

	// --------------------------------------------------------------------
	// Protected Methods
	// --------------------------------------------------------------------

	/**
	 * Execute the curl request based on the respective settings.
	 *
	 * @return 	int 	The error code for the current curl request
	 */
	protected function exec()
	{
		$this->set_option(CURLOPT_HEADERFUNCTION, array($this, 'add_response_header_line'));
		$this->response_headers = array();
		$this->response = curl_exec($this->curl);
		$this->curl_error_code = curl_errno($this->curl);
		$this->curl_error_message = curl_error($this->curl);
		$this->curl_error = ! ($this->curl_error_code === 0);
		$this->http_status_code = intval(curl_getinfo($this->curl, CURLINFO_HTTP_CODE));
		$this->http_error = $this->is_error();
		$this->error = $this->curl_error OR $this->http_error;
		$this->error_code = $this->error ? ($this->curl_error ? $this->curl_error_code : $this->http_status_code) : 0;
		$this->request_headers = preg_split('/\r\n/', curl_getinfo($this->curl, CURLINFO_HEADER_OUT), -1, PREG_SPLIT_NO_EMPTY);
		$this->http_error_message = $this->error ? (isset($this->response_headers['0']) ? $this->response_headers['0'] : '') : '';
		$this->error_message = $this->curl_error ? $this->curl_error_message : $this->http_error_message;

		$this->set_option(CURLOPT_HEADERFUNCTION, null);

		return $this->error_code;
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares payload
	 *
	 * @param 	mixed 	$data
	 *
	 * @return 	self::set_option
	 */
	protected function prep_payload($data)
	{
		$this->set_option(CURLOPT_POST, true);

		if (is_array($data) OR is_object($data))
		{
			$skip = false;

			foreach ($data as $key => $value)
			{
				/**
				 * If a value is an instance of CurlFile skip the http_build_query
				 * @see issue https://github.com/php-mod/curl/issues/46
				 * suggestion from: https://stackoverflow.com/a/36603038/4611030
				 */
				if ($value instanceof CurlFile)
				{
					$skip = true;
				}
			}

			$skip OR $data = http_build_query($data);
		}

		$this->set_option(CURLOPT_POSTFIELDS, $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Set the JSON payload informations to the postfield curl option.
	 *
	 * @param 	array 	$data The data to be sent.
	 *
	 * @return 	void
	 */
	protected function prep_json_payload(array $data)
	{
		$this->set_option(CURLOPT_POST, true);
		$this->set_option(CURLOPT_POSTFIELDS, json_encode($data));
	}

	// --------------------------------------------------------------------

	/**
	 * Set auth options for the current request.
	 *
	 * Available auth types are:
	 *
	 * self::AUTH_BASIC
	 * self::AUTH_DIGEST
	 * self::AUTH_GSSNEGOTIATE
	 * self::AUTH_NTLM
	 * self::AUTH_ANYSAFE
	 * self::AUTH_ANY
	 *
	 * @param 	int 	$httpauth 	The type of authentication
	 */
	protected function set_http_auth($httpauth)
	{
		$this->set_option(CURLOPT_HTTPAUTH, $httpauth);
	}

	// --------------------------------------------------------------------
	// Public Methods
	// --------------------------------------------------------------------

	/**
	 * Make a get request with optional data.
	 *
	 * The get request has no body data, the data will be correctly added
	 * to the $url with the http_build_query() method.
	 *
	 * @param 	string 	$url 	The URL to make the get request for
	 * @param 	array 	$data 	Optional arguments who are part of the URL
	 *
	 * @return 	self
	 */
	public function get($url, $data = array())
	{
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'GET');

		$this->set_option(CURLOPT_URL, empty($data) ? $url : $url.'?'.http_build_query($data));
		$this->set_option(CURLOPT_HTTPGET, true);

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Purge Request
	 *
	 * A very common scenario to send a purge request is within the use of varnish,
	 * therefore the optional hostname can be defined.
	 *
	 * @param 	string 	$url 		The URL to make the purge request
	 * @param 	string 	$hostName 	An optional hostname which will be sent as HTTP host header
	 *
	 * @return 	self
	 */
	public function purge($url, $hostName = null)
	{
		$this->set_option(CURLOPT_URL, $url);
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'PURGE');

		empty($hostName) OR $this->set_header('Host', $hostName);

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Make a post request with optional post data.
	 *
	 * @param 	string 		$url 		The URL to make the post request
	 * @param 	mixed 		$data 		Post data to pass to the URL
	 * @param 	boolean 	$as_json 	Whether the data should be passed as JSON or not.
	 *
	 * @return 	self
	 */
	public function post($url, $data = array(), $as_json = false)
	{
		$this->set_option(CURLOPT_URL, $url);
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'POST');

		if ($as_json)
		{
			$this->prep_json_payload($data);
		}
		else
		{
			$this->prep_payload($data);
		}

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Make a put request with optional data.
	 *
	 * The put request data can be either sent via payload or as get parameters of the string.
	 *
	 * @param 	string 		$url		The URL to make the put request
	 * @param 	array 		$data 		Optional data to pass to the $url
	 * @param 	bool 		$payload 	Whether the data should be transmitted trough
	 *                          		payload or as get parameters of the string
	 * @param 	boolean 	$as_json 	Whether the data should be passed as JSON or not.
	 *
	 * @return 	self
	 */
	public function put($url, $data = array(), $payload = false, $as_json = false)
	{
		if ( ! empty($data))
		{
			if ($payload === false)
			{
				$url .= '?'.http_build_query($data);
			}
			elseif ($as_json)
			{
				$this->prep_json_payload($data);
			}
			else
			{
				$this->prep_payload($data);
			}
		}

		$this->set_option(CURLOPT_URL, $url);
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'PUT');

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Make a patch request with optional data.
	 *
	 * The patch request data can be either sent via payload or as get parameters of the string.
	 *
	 * @param 	string 		$url 		The URL to make the patch request
	 * @param 	array 		$data 		Optional data to pass to the $url
	 * @param 	bool 		$payload	Whether the data should be transmitted trough
	 *                         			payload or as get parameters of the string
	 * @param 	boolean 	$as_json	Whether the data should be passed as JSON or not.
	 *
	 * @return 	self
	 */
	public function patch($url, $data = array(), $payload = false, $as_json = false)
	{
		if ( ! empty($data))
		{
			if ($payload === false)
			{
				$url .= '?'.http_build_query($data);
			}
			elseif ($as_json)
			{
				$this->prep_json_payload($data);
			}
			else
			{
				$this->prep_payload($data);
			}
		}

		$this->set_option(CURLOPT_URL, $url);
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'PATCH');

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Make a delete request with optional data.
	 *
	 * @param 	string 	$url 		The URL to make the delete request
	 * @param 	array 	$data 		Optional data to pass to the $url
	 * @param 	bool 	$payload 	Whether the data should be transmitted trough
	 *                         		payload or as get parameters of the string
	 *
	 * @return 	self
	 */
	public function delete($url, $data = array(), $payload = false)
	{
		if ( ! empty($data))
		{
			if ($payload === false)
			{
				$url .= '?'.http_build_query($data);
			}
			else
			{
				$this->prep_payload($data);
			}
		}

		$this->set_option(CURLOPT_URL, $url);
		$this->set_option(CURLOPT_CUSTOMREQUEST, 'DELETE');

		$this->exec();

		return $this;
	}

	// --------------------------------------------------------------------
	// Setters
	// --------------------------------------------------------------------

	/**
	 * Send as a fake AJAX request?
	 *
	 * @return 	self
	 */
	public function as_ajax()
	{
		$this->set_header('X-Requested-With', 'XMLHttpRequest');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Pass basic auth data.
	 *
	 * If the the requested URL is secured by an htaccess basic auth mechanism
	 * you can use this method to provided the auth data.
	 *
	 * @example
	 * 	$this->curl
	 * 		->set_basic_auth('username', 'password')
	 * 		->get('http://www.yourwebsite.com/secure.php');
	 *
	 * @param 	string 	$username 	The username for the authentication
	 * @param 	string 	$password 	The password for the given username for the authentication
	 *
	 * @return 	self
	 */
	public function set_basic_auth($username, $password)
	{
		$this->set_http_auth(self::AUTH_BASIC);
		$this->set_option(CURLOPT_USERPWD, $username.':'.$password);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Provide optional header information.
	 *
	 * @example
	 * 	In order to pass optional headers by key value pairing:
	 * 	$this->curl
	 * 		->set_header('X-Requested-With', 'XMLHttpRequest')
	 * 		->get('http://www.yourwebsite.com/request.php');
	 *
	 * @param 	string 	$key 	The header key
	 * @param 	string 	$value 	The value for the given header key
	 *
	 * @return 	self
	 */
	public function set_header($key, $value)
	{
		$this->_headers[$key] = $key.': '.$value;
		$this->set_option(CURLOPT_HTTPHEADER, array_values($this->_headers));

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets multiple headers at once.
	 *
	 * @param 	array 	$headers 	Array of strings.
	 *
	 * @return 	self
	 */
	public function set_headers(array $headers)
	{
		foreach ($headers as $key => $value)
		{
			$this->_headers[$key] = $key.': '.$value;
		}

		$this->set_option(CURLOPT_HTTPHEADER, array_values($this->_headers));
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Provide a User Agent.
	 *
	 * In order to provide you customized user agent name you can use this method.
	 *
	 * @example
	 * 	$this->curl
	 * 		->set_user_agent('My John Doe Agent 1.0')
	 * 		->get('http://www.yourwebsite.com/request.php');
	 *
	 * @param 	string 	$useragent 	The name of the user agent to set for the current request
	 *
	 * @return 	self
	 */
	public function set_user_agent($useragent)
	{
		$this->set_option(CURLOPT_USERAGENT, $useragent);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set the HTTP referer header.
	 *
	 * The $referer Information can help identify the requested client where the requested was made.
	 *
	 * @param 	string 	$referer 	An URL to pass and will be set as referer header.
	 *
	 * @return 	self
	 */
	public function set_referer($referer)
	{
		$this->set_option(CURLOPT_REFERER, $referer);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set contents of HTTP Cookie header.
	 *
	 * @param 	string 	$key 	The name of the cookie
	 * @param 	string 	$value 	The value for the provided cookie name
	 *
	 * @return 	self
	 */
	public function set_cookie($key, $value)
	{
		$this->_cookies[$key] = $value;
		$this->set_option(CURLOPT_COOKIE, http_build_query($this->_cookies, '', '; '));

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set customized curl options.
	 *
	 * @see 	https://php.net/curl_setopt
	 *
	 * @param 	int 	$option The curl option constant e.g. `CURLOPT_AUTOREFERER`
	 * @param 	mixed 	$value 	The value to pass for the given $option
	 *
	 * @return 	bool
	 */
	public function set_option($option, $value)
	{
		return curl_setopt($this->curl, $option, $value);
	}

	// --------------------------------------------------------------------

	/**
	 * Get curl option for a certain name
	 *
	 * @see 	https://php.net/curl_getinfo
	 *
	 * @param 	int 	$option 	The curl option constant e.g. `CURLOPT_AUTOREFERER`
	 *
	 * @return 	mixed
	 */
	public function get_option($option)
	{
		return curl_getinfo($this->curl, $option);
	}

	// --------------------------------------------------------------------

	/**
	 * Set multiple curl options.
	 *
	 * @see 	https://php.net/curl_setopt
	 *
	 * @param 	array 	$options 	Array of options to set.
	 *
	 * @return 	bool
	 */
	public function set_options(array $options)
	{
		foreach ($options as $option => $value)
		{
			if ( ! curl_setopt($this->curl, $option, $value))
			{
				return false;
			}
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the all options for current curl ressource
	 *
	 * @see 	https://php.net/curl_getinfo
	 *
	 * @return 	array
	 */
	public function get_options()
	{
		return curl_getinfo($this->curl);
	}

	// --------------------------------------------------------------------

	/**
	* Return the endpoint set for curl
	*
	* @see 	https://php.net/curl_getinfo
	*
	* @return 	string 	The endpoint
	*/
	public function get_endpoint()
	{
		return $this->get_option(CURLINFO_EFFECTIVE_URL);
	}

	// --------------------------------------------------------------------

	/**
	 * Enable verbosity.
	 *
	 * @param 	bool 	$enable
	 *
	 * @return 	self
	 */
	public function set_verbose($enable = true)
	{
		$this->set_option(CURLOPT_VERBOSE, $enable);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Reset all curl options.
	 *
	 * In order to make multiple requests with the same curl object all settings
	 * requires to be reset.
	 *
	 * @return 	self
	 */
	public function reset()
	{
		$this->close();
		$this->_cookies = array();
		$this->_headers = array();
		$this->error = false;
		$this->error_code = 0;
		$this->error_message = null;
		$this->curl_error = false;
		$this->curl_error_code = 0;
		$this->curl_error_message = null;
		$this->http_error = false;
		$this->http_status_code = 0;
		$this->http_error_message = null;
		$this->request_headers = null;
		$this->response_headers = array();
		$this->response = false;
		$this->init();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Close the connection when the Curl object will be destroyed.
	 *
	 * @return 	void
	 */
	public function __destruct()
	{
		$this->close();
	}

	// --------------------------------------------------------------------
	// Checkers
	// --------------------------------------------------------------------

	/**
	 * Was an 'info' header returned.
	 *
	 * @return 	bool
	 */
	public function is_info()
	{
		return $this->http_status_code >= 100 && $this->http_status_code < 200;
	}

	// --------------------------------------------------------------------

	/**
	 * Was an 'OK' response returned.
	 *
	 * @return 	bool
	 */
	public function is_success()
	{
		return $this->http_status_code >= 200 && $this->http_status_code < 300;
	}

	// --------------------------------------------------------------------

	/**
	 * Was a 'redirect' returned.
	 *
	 * @return 	bool
	 */
	public function is_redirect()
	{
		return $this->http_status_code >= 300 && $this->http_status_code < 400;
	}

	// --------------------------------------------------------------------

	/**
	 * Was an 'error' returned (client error or server error).
	 *
	 * @return 	bool
	 */
	public function is_error()
	{
		return $this->http_status_code >= 400 && $this->http_status_code < 600;
	}

	// --------------------------------------------------------------------

	/**
	 * Was a 'client error' returned.
	 *
	 * @return 	bool
	 */
	public function is_client_error()
	{
		return $this->http_status_code >= 400 && $this->http_status_code < 500;
	}

	// --------------------------------------------------------------------

	/**
	 * Was a 'server error' returned.
	 *
	 * @return 	bool
	 */
	public function is_server_error()
	{
		return $this->http_status_code >= 500 && $this->http_status_code < 600;
	}

	// --------------------------------------------------------------------

	/**
	 * Get a specific response header key or all values from the response headers array.
	 *
	 * @example 	Basic usage:
	 *
	 * 	$curl = $this->curl->get('http://www.yourwebsite.com');
	 * 	echo $curl->get_response_headers('Content-Type');
	 *
	 * @example 	Dump all keys with the given values use:
	 *
	 * 	$curl = $this->curl->get('http://www.yourwebsite.com');
	 * 	var_dump($curl->get_response_headers());
	 *
	 * @param 	string 	$headerKey 	Optional key to get from the array.
	 *
	 * @return 	mixed 	A boolean, a string or an array.
	 */
	public function get_response_headers($headerKey = null)
	{
		$headers = array();

		($headerKey === null) OR $headerKey = strtolower($headerKey);

		foreach ($this->response_headers as $header)
		{
			$parts = explode(':', $header, 2);

			$key = isset($parts[0]) ? $parts[0] : '';
			$value = isset($parts[1]) ? $parts[1] : '';

			$headers[trim(strtolower($key))] = trim($value);
		}

		return ($headerKey) ? (isset($headers[$headerKey]) ? $headers[$headerKey] : false) : $headers;
	}

	// --------------------------------------------------------------------

	/**
	 * Get response from the curl request
	 *
	 * @return 	mixed 	String if passed, else false
	 */
	public function get_response()
	{
		return $this->response;
	}

	// --------------------------------------------------------------------

	/**
	 * Get curl error code
	 *
	 * @return 	int
	 */
	public function get_error_code()
	{
		return $this->curl_error_code;
	}

	// --------------------------------------------------------------------

	/**
	 * Get curl error message
	 *
	 * @return 	string
	 */
	public function get_error_message()
	{
		return $this->curl_error_message;
	}

	// --------------------------------------------------------------------

	/**
	 * Get HTTP status code from the curl request
	 *
	 * @return 	int
	 */
	public function get_http_status()
	{
		return $this->http_status_code;
	}

}
