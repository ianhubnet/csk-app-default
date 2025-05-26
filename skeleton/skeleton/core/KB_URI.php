<?php
defined('BASEPATH') OR die;

/**
 * KB_URI
 *
 * @package 	CodeIgniter
 * @subpackage 	Subpackage
 * @category 	Category
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.1
 * @version 	2.1
 */
class KB_URI extends CI_URI
{
	/**
	 * Cache query string.
	 * @var string
	 */
	protected $query_str;

	/**
	 * Cached ruri_string.
	 * @var string
	 */
	protected $ruri_string;

	/**
	 * Flag to check if we are on the dashboard.
	 * @var bool
	 */
	public $is_dashboard = false;

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct(CI_Config &$config)
	{
		parent::__construct($config);

		// Check if we are on dashboard and set constant.
		$this->is_dashboard = ($this->segment(1) === KB_ADMIN);
		defined('KB_DASHBOARD') OR define('KB_DASHBOARD', $this->is_dashboard);
	}

	// --------------------------------------------------------------------

	/**
	 * query_string
	 *
	 * Returns the URL query string part.
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	array 	an array of query string parameters to exclude
	 * @param 	boolean whether to include posted variables in the query string
	 * @param 	boolean whether to include the question mark
	 * @return 	string
	 */
	public function query_string($exclude = array(), $inc_quest = true, $inc_post = false)
	{
		// Already cached?
		if (isset($this->query_str))
		{
			return $this->query_str;
		}

		$this->query_str = '';

		$CI =& get_instance();

		$get_array = ($inc_post) ? $CI->input->get_post(null, false) : $CI->input->get(null, false);

		if ( ! empty($get_array))
		{
			if ( ! empty($exclude))
			{
				foreach ($exclude as $e)
				{
					if (isset($get_array[$e]))
					{
						unset($get_array[$e]);
					}
				}
			}

			$this->query_str = http_build_query($get_array);
			if ( ! empty($this->query_str) && $inc_quest)
			{
				$this->query_str = '?'.$this->query_str;
			}
		}

		return $this->query_str;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the REQUEST_URI used for csrf referrer fields.
	 *
	 * @param   bool    $escape     whether to escape
	 * @return  string
	 */
	public function request($escape = false)
	{
		return (true === $escape) ? esc_attr(deep_stripslashes($_SERVER['REQUEST_URI'])) : $_SERVER['REQUEST_URI'];
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch URI string
	 *
	 * @return  string  CI_URI::$uri_string
	 */
	public function uri_string($include_get = false)
	{
		return $include_get ? $this->uri_string.$this->query_string() : $this->uri_string;
	}

	// --------------------------------------------------------------------

	/**
	 * Fetch Re-routed URI string.
	 *
	 * Uses parent::ruri_string() but caches result to reduce calls.
	 *
	 * @return 	string
	 */
	public function ruri_string()
	{
		isset($this->ruri_string) OR $this->ruri_string = parent::ruri_string();
		return $this->ruri_string;
	}

	// --------------------------------------------------------------------

	/**
	 * Replace all * wildcards in a URI by the current segment in that location.
	 *
	 * @since 	2.45
	 *
	 * @param 	string 	$url 	The url containing wildcards.
	 * @param 	bool 	$secure To force a particular HTTP scheme.
	 * @return 	string
	 */
	public function replace($url, $secure = null)
	{
		// Get the path from the url
		$parts = parse_url($url);

		// No path provided? Return the URL.
		if (empty($parts['path']))
		{
			return $url;
		}

		// Explode it in it's segments
		$segments = explode('/', trim($parts['path'], '/'));

		// Fetch any segments needed
		$wildcards = 0;
		foreach ($segments as $index => &$segment)
		{
			if (str_contains($segment, '*'))
			{
				$wildcards++;
				if (null === ($new = $this->segment($index+1)))
				{
					throw new Exception('Segment replace on "'.$url.'" failed. No segment exists for wildcard '.$wildcards.'.');
				}
				$segment = str_replace('*', $new, $segment);
			}
		}

		// Re-assemble the path
		$parts['path'] = '/'.implode('/', $segments);

		// Do we need to force a scheme?
		is_bool($secure) && $parts['scheme'] = $secure ? 'https' : 'http';

		/**
		 * And rebuild the url with the new path.
		 * true: if a relative url was given, fake a host so we can
		 * remove it after building.
		 * false: a hostname was present, just rebuild it.
		 */
		$url = empty($parts['host'])
			? substr(http_build_url('http://__removethis__/', $parts), 22)
			: http_build_url('', $parts);

		return $url;
	}

	// --------------------------------------------------------------------

	/**
	 * Creates a URL with the given uri, including base url.
	 *
	 * @since 	2.45
	 *
	 * @param 	string 	$uri 			The uri to create the URL for
	 * @param 	array 	$variables 		Some variables for the URL
	 * @param 	array 	$get_variables 	Any GET urls to append via a query string
	 * @param 	bool 	$secure 		If false, force http. If true, force https
	 * @return 	string
	 */
	public function create($uri = null, $vars = array(), $get_vars = array(), $secure = null)
	{
		$url = '';
		empty($uri) && $uri = $this->uri_string;

		// If the given uri is not a full URL
		if ( ! preg_match("#^(http|https|ftp)://#i", $uri))
		{
			$url .= $this->config->item('base_url');

			if ( ! empty($index_file = $this->config->item('index_page')))
			{
				$url .= $index_file.'/';
			}
		}
		$url .= ltrim($uri, '/');

		// Stick a url suffix onto it if defined and needed
		if ( ! empty($url_suffix = $this->config->item('url_suffix')) && substr($url, -1) != '/')
		{
			$current_suffix = strrchr($url, '.');
			if ( ! $current_suffix OR str_contains($current_suffix, '/'))
			{
				$url .= $url_suffix;
			}
		}

		// Build GET vars.
		if ( ! empty($get_vars))
		{
			$char = str_contains($url, '?') ? '&' : '?';
			$url .= $char.str_replace('%3A', ':', is_string($get_vars) ? $get_vars : http_build_query($get_vars));
		}

		array_walk($vars, function ($val, $key) use (&$url) {
			$url = str_replace(':'.$key, $val, $url);
		});

		is_bool($secure) and $url = http_build_url($url, array('scheme' => $secure ? 'https' : 'http'));

		return $url;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current URI string.
	 *
	 * @since 	2.45
	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->ruri_string();
	}

}
