<?php
defined('BASEPATH') OR die;

/**
 * Rest Class
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.18
 */
class Rest
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $ci;

	/**
	 * Array of supported formats.
	 * @var array
	 */
	protected $supported_formats = array(
		'xml'               => 'application/xml',
		'json'              => 'application/json',
		'serialize'         => 'application/vnd.php.serialized',
		'php'               => 'text/plain',
		'csv'               => 'text/csv'
	);

	/**
	 * Array of formats used for auto-detection.
	 * @var array
	 */
	protected $auto_detect_formats = array(
		'application/xml'   => 'xml',
		'text/xml'          => 'xml',
		'application/json'  => 'json',
		'text/json'         => 'json',
		'text/csv'          => 'csv',
		'application/csv'   => 'csv',
		'application/vnd.php.serialized' => 'serialize'
	);

	/**
	 * REST server URL.
	 * @var string
	 */
	protected $rest_server;

	/**
	 * Return format.
	 * @var string
	 */
	protected $format;

	/**
	 * Mime Types
	 * @var string
	 */
	protected $mime_type;

	/**
	 * HTTP Authentication.
	 * @var string
	 */
	protected $http_auth = null;

	/**
	 * HTTP Auth Username.
	 * @var string
	 */
	protected $http_user = null;

	/**
	 * HTTP Auth Password.
	 * @var string
	 */
	protected $http_pass = null;

	/**
	 * HTTP API Name.
	 * @var string
	 */
	protected $api_name  = 'X-API-KEY';

	/**
	 * HTTP API key.
	 * @var string
	 */
	protected $api_key   = null;

	/**
	 * Whether to verify peer.
	 * @var bool
	 */
	protected $ssl_verify_peer  = null;
	protected $ssl_cainfo       = null;

	/**
	 * Whether to send cookies.
	 * @var bool
	 */
	protected $send_cookies = null;

	/**
	 * HTTP response string.
	 * @var string
	 */
	protected $response_string;

	/**
	 * Class contructor.
	 *
	 * @param 	array 	$config
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		$this->ci =& get_instance();
		(isset($this->ci->curl)) OR $this->ci->load->library('curl');
		$this->initialize($config);
	}

	/**
	 * Class desctructor.
	 *
	 * @param 	none
	 * @return 	void
	 */
	public function __destruct()
	{
		$this->ci->curl->set_defaults();
	}

	/**
	 * Initialize.
	 *
	 * @param 	array 	$config
	 * @return 	void
	 */
	public function initialize(array $config = array())
	{
		$this->rest_server = @$config['server'];
		if (isset($this->rest_server) && substr($this->rest_server, -1, 1) != '/')
		{
			$this->rest_server .= '/';
		}

		isset($config['send_cookies']) && $this->send_cookies = $config['send_cookies'];

		isset($config['api_name']) && $this->api_name = $config['api_name'];
		isset($config['api_key']) && $this->api_key = $config['api_key'];

		isset($config['http_auth']) && $this->http_auth = $config['http_auth'];
		isset($config['http_user']) && $this->http_user = $config['http_user'];
		isset($config['http_pass']) && $this->http_pass = $config['http_pass'];

		isset($config['ssl_verify_peer']) && $this->ssl_verify_peer = $config['ssl_verify_peer'];
		isset($config['ssl_cainfo']) && $this->ssl_cainfo = $config['ssl_cainfo'];
	}

	// --------------------------------------------------------------------
	// PUBLIC METHODS
	// --------------------------------------------------------------------

	/**
	 * get
	 *
	 * @param 	string 	$uri
	 * @param 	array 	@params
	 * @param 	string 	$format
	 * @return 	Kbcore_rest::_call
	 */
	public function get($uri, $params = array(), $format = null)
	{
		empty($params) OR $uri .= '?'.(is_array($params) ? http_build_query($params) : $params);

		return $this->_call('get', $uri, null, $format);
	}

	/**
	 * post
	 *
	 * @param 	string 	$uri
	 * @param 	array 	@params
	 * @param 	string 	$format
	 * @return 	Kbcore_rest::_call
	 */
	public function post($uri, $params = array(), $format = null)
	{
		return $this->_call('post', $uri, $params, $format);
	}

	/**
	 * put
	 *
	 * @param 	string 	$uri
	 * @param 	array 	@params
	 * @param 	string 	$format
	 * @return 	Kbcore_rest::_call
	 */
	public function put($uri, $params = array(), $format = null)
	{
		return $this->_call('put', $uri, $params, $format);
	}

	/**
	 * patch
	 *
	 * @param 	string 	$uri
	 * @param 	array 	@params
	 * @param 	string 	$format
	 * @return 	Kbcore_rest::_call
	 */
	public function patch($uri, $params = array(), $format = null)
	{
		return $this->_call('patch', $uri, $params, $format);
	}

	/**
	 * delete
	 *
	 * @param 	string 	$uri
	 * @param 	array 	@params
	 * @param 	string 	$format
	 * @return 	Kbcore_rest::_call
	 */
	public function delete($uri, $params = array(), $format = null)
	{
		return $this->_call('delete', $uri, $params, $format);
	}

	/**
	 * api_key
	 *
	 * @param 	string 	$key
	 * @param 	mixed 	$value
	 * @return 	void
	 */
	public function api_key($key, $name = false)
	{
		$this->api_key  = $key;

		if ($name !== false)
		{
			$this->api_name = $name;
		}
	}

	/**
	 * language
	 *
	 * @param 	mixed 	$lang
	 * @return 	void
	 */
	public function language($lang)
	{
		is_array($lang) && $lang = implode(', ', $lang);

		$this->ci->curl->http_header('Accept-Language', $lang);
	}

	/**
	 * header
	 *
	 * @param 	string 	$header
	 * @return 	void
	 */
	public function header($header)
	{
		$this->ci->curl->http_header($header);
	}

	/**
	 * format
	 *
	 * If a type is passed in that is not supported, use it as a mime type
	 *
	 * @param 	string 	$format
	 * @return 	void
	 */
	public function format($format)
	{
		if (array_key_exists($format, $this->supported_formats))
		{
			$this->format = $format;
			$this->mime_type = $this->supported_formats[$format];
		}
		else
		{
			$this->mime_type = $format;
		}

		return $this;
	}

	/**
	 * debug
	 *
	 * @param 	none
	 * @return 	void
	 */
	public function debug()
	{
		$request = $this->ci->curl->debug_request();

		echo "=============================================<br/>\n";
		echo "<h2>REST Test</h2>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Request</h3>\n";
		echo $request['url']."<br/>\n";
		echo "=============================================<br/>\n";
		echo "<h3>Response</h3>\n";

		if ($this->response_string)
		{
			echo "<code>".nl2br(htmlentities($this->response_string))."</code><br/>\n\n";
		}

		else
		{
			echo "No response<br/>\n\n";
		}

		echo "=============================================<br/>\n";

		if ($this->ci->curl->error_string)
		{
			echo "<h3>Errors</h3>";
			echo "<strong>Code:</strong> ".$this->ci->curl->error_code."<br/>\n";
			echo "<strong>Message:</strong> ".$this->ci->curl->error_string."<br/>\n";
			echo "=============================================<br/>\n";
		}

		echo "<h3>Call details</h3>";
		echo "<pre>";
		print_r($this->ci->curl->info);
		echo "</pre>";

	}

	/**
	 * status
	 *
	 * Return HTTP status code.
	 *
	 * @param 	none
	 * @return 	Kbcore_rest::info
	 */
	public function status()
	{
		return $this->info('http_code');
	}

	/**
	 * info
	 *
	 * Return curl info by specified key, or whole array.
	 *
	 * @param 	string 	$key
	 * @return 	mixed
	 */
	public function info($key = null)
	{
		if (null === $key)
		{
			return $this->ci->curl->info;
		}

		return @$this->ci->curl->info[$key];
	}

	/**
	 * option
	 *
	 * Set custom CURL options.
	 *
	 * @param 	string 	$code
	 * @param 	mixed 	$value
	 * @return 	void
	 */
	public function option($code, $value)
	{
		$this->ci->curl->option($code, $value);
	}

	/**
	 * http_header
	 *
	 * @param 	string 	$header
	 * @param 	mixed 	$content
	 * @return 	void
	 */
	public function http_header($header, $content = null)
	{
		// Did they use a single argument or two?
		$params = $content ? array($header, $content) : array($header);

		// Pass these attributes on to the curl library
		call_user_func_array(array($this->ci->curl, 'http_header'), $params);
	}

	// --------------------------------------------------------------------
	// PRIVATE METHODS
	// --------------------------------------------------------------------

	/**
	 * _call
	 *
	 * @param 	string 	$method
	 * @param 	string 	$uri
	 * @param 	array 	$params
	 * @param 	string 	$format
	 * @return 	mixed
	 */
	protected function _call($method, $uri, $params = array(), $format = null)
	{
		if ($format !== null)
		{
			$this->format($format);
		}

		$this->http_header('Accept', $this->mime_type);

		// Initialize cURL session
		$this->ci->curl->create($this->rest_server.$uri);

		if ($this->ssl_verify_peer === false)
		{
			$this->ci->curl->ssl(false);
		}
		elseif ($this->ssl_verify_peer === true)
		{
			$this->ssl_cainfo = getcwd() . $this->ssl_cainfo;
			$this->ci->curl->ssl(true, 2, $this->ssl_cainfo);
		}

		// If authentication is enabled use it
		if ($this->http_auth != '' && $this->http_user != '')
		{
			$this->ci->curl->http_login($this->http_user, $this->http_pass, $this->http_auth);
		}

		// If we have an API Key, then use it
		if ($this->api_key != '')
		{
			$this->ci->curl->http_header($this->api_name, $this->api_key);
		}

		// Send cookies with curl
		if ($this->send_cookies != '')
		{
			$this->ci->curl->set_cookies($_COOKIE);
		}

		// Set the Content-Type (contributed by https://github.com/eriklharper)
		$this->http_header('Content-Type', $this->mime_type);

		// We still want the response even if there is an error code over 400
		$this->ci->curl->option('failonerror', false);

		// Call the correct method with parameters
		$this->ci->curl->{$method}($params);

		// Execute and return the response from the REST server
		$response = $this->ci->curl->execute();

		// Format and return
		return $this->_format_response($response);
	}

	/**
	 * _format_response
	 *
	 * @param 	mixed 	$response
	 * @return 	mixed 	$response
	 */
	protected function _format_response($response)
	{
		$this->response_string =& $response;

		// It is a supported format, so just run its formatting method
		if (array_key_exists($this->format, $this->supported_formats))
		{
			return $this->{"_".$this->format}($response);
		}

		// Find out what format the data was returned in
		if ( ! empty($returned_mime = @$this->ci->curl->info['content_type']))
		{
			// If they sent through more than just mime, strip it off
			if (strpos($returned_mime = trim($returned_mime), ';'))
			{
				$returned_mime = explode(';', $returned_mime)[0];
			}

			if (array_key_exists($returned_mime, $this->auto_detect_formats))
			{
				return $this->{'_'.$this->auto_detect_formats[$returned_mime]}($response);
			}
		}

		return $response;
	}

	/**
	 * _xml
	 *
	 * Format XML for output.
	 *
	 * @param 	mixed 	$string
	 * @return 	mixed
	 */
	protected function _xml($string)
	{
		return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}

	/**
	 * _csv
	 *
	 * Format HTML for output.  This function is DODGY! Not perfect CSV support but works
	 * with my REST_Controller (https://github.com/philsturgeon/codeigniter-restserver).
	 *
	 * @param 	mixed 	$string
	 * @return 	array
	 */
	protected function _csv($string)
	{
		$data = array();

		// Splits
		$rows = explode("\n", trim($string));
		$headings = explode(',', array_shift($rows));
		foreach($rows as $row)
		{
			// The substr removes " from start and end
			$data_fields = explode('","', trim(substr($row, 1, -1)));

			if (count($data_fields) === count($headings))
			{
				$data[] = array_combine($headings, $data_fields);
			}

		}

		return $data;
	}

	/**
	 * _json
	 *
	 * Encode as JSON.
	 *
	 * @param 	mixed 	$string
	 * @return 	string
	 */
	protected function _json($string)
	{
		return json_decode(trim($string));
	}

	/**
	 * _serialize
	 *
	 * Encode as Serialized array.
	 *
	 * @param 	mixed 	$string
	 * @return 	array
	 */
	protected function _serialize($string)
	{
		return unserialize(trim($string));
	}

	/**
	 * _php
	 *
	 * Encode raw PHP.
	 *
	 * @param 	mixed 	$string
	 * @return 	evaludated code.
	 */
	protected function _php($string)
	{
		$string = trim($string);
		$populated = array();
		eval("\$populated = \"$string\";");
		return $populated;
	}

}
