<?php
defined('BASEPATH') OR die;

/**
 * KB_url_helper
 *
 * Extending and overriding some of CodeIgniter url function.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Helpers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 *
 * @since       1.0
 * @since       2.16   Deprecated functions and simplified others.
 *
 * @version     2.16
 */

// --------------------------------------------------------------------
// Some CodeIgniter and PHP override to use Hooks.
// --------------------------------------------------------------------

if ( ! function_exists('url_title'))
{
	/**
	 * url_title
	 *
	 * Takes a string as input and created a human friendly URL string with
	 * a "separator" string as the word separator.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$str
	 * @param 	string 	$sep
	 * @param 	bool 	$low
	 * @param 	bool 	$accents
	 * @return 	string
	 */
	function url_title($str, $sep = '-', $low = false, $accents = true)
	{
		$q_sep = preg_quote($sep, '#');

		// convert all accents if requested.
		($accents === true) && $str = convert_accents($str);

		// strip tags.
		$str = strip_tags($str);

		// some replaces.
		$trans = array(
			'&.+?;' => '',
			'[^\w\d\pL\pM _-]'  => '',
			'\s+'               => $sep,
			'('.$q_sep.')+' => $sep
		);
		foreach ($trans as $key => $val)
		{
			$str = preg_replace('#'.$key.'#iu', $val, $str);
		}

		// convert to lowercase if requested.
		($low === true) && $str = mb_strtolower($str);

		return trim(trim($str, $sep));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('mb_url_title'))
{
	/**
	 * mb_url_title
	 *
	 * Creates URL title that takes into account accented characters.
	 *
	 * @since 	2.93
	 *
	 * @param 	string 	$str 	input string
	 * @param 	string 	$sep 	word separator (usually '-' or '_')
	 * @param 	bool 	$low 	whether to lowercase the output string.
	 * @return 	string
	 */
	function mb_url_title($str, $sep = '-', $low = false)
	{
		return url_title($str, $sep, $low, true);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('url_is'))
{
	/**
	 * url_is
	 *
	 * Determines if current URL contans the given string. It may contain
	 * a wildcard (*) which will allow any valid character.
	 *
	 * @since 	2.93
	 *
	 * @param 	string 	$str
	 * @return 	bool
	 */

	function url_is($str) {
		// Setup our regex to allow wildcards
		$str = '/'.trim(str_replace('*', '(\S)*', $str), '/ ');
		$current_str = '/'.trim(uri_string(), '/ ');

		return (bool) preg_match("|^{$str}$|", $current_str, $matches);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_http_build_query'))
{
	/**
	 * From php.net
	 * @param 	mixed 		$data 		An array or object of data. Converted to array.
	 * @param 	string 		$prefix 	Numeric index If set, start param numbering with it
	 * @param 	string 		$sep 		Argument separator.
	 * @param 	string 		$key 		Used to prefix key name
	 * @param 	boolean 	$urlencode  Whether to use urlencode() in the result.
	 * @return string 		The query string.
	 */
	function _http_build_query($data, $prefix = null, $sep = null, $key = '', $urlencode = true)
	{
		$ret = array();

		foreach ((array) $data as $k => $v )
		{
			if ($urlencode)
			{
				$k = urlencode($k);
			}
			if (is_int($k) && null != $prefix)
			{
				$k = $prefix.$k;
			}
			if ( ! empty($key))
			{
				$k = $key.'%5B'.$k.'%5D';
			}
			if (null === $v)
			{
				continue;
			}
			elseif (false === $v)
			{
				$v = '0';
			}

			if (is_array($v) || is_object($v))
			{
				array_push($ret, _http_build_query($v, '', $sep, $k, $urlencode));
			}
			elseif ($urlencode)
			{
				array_push($ret, $k.'='.urlencode($v));
			}
			else
			{
				array_push($ret, $k.'='.$v);
			}
		}

		if (null === $sep)
		{
			$sep = ini_get('arg_separator.output');
		}

		return implode($sep, $ret);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('build_query'))
{
	/**
	 * Builds URL query based on an associative and/or indexed array.
	 * @param 	array 	$data 	URL-encoded key/value pairs.
	 * @return 	string 	URL-encoded string.
	 */
	function build_query($data)
	{
		return _http_build_query($data, '', '&', '', false);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('add_query_arg'))
{
	/**
	 * Retrieves a modified URL query string.
	 *
	 * You can rebuild the URL and append query variables to the URL query by using this function.
	 * There are two ways to use this function; either a single key and value, or an associative array.
	 *
	 * Important: The return value of add_query_arg() is not escaped by default. Output should be
	 * late-escaped with esc_url() or similar to help prevent vulnerability to cross-site scripting
	 * (XSS) attacks.
	 *
	 * @param 	mixed 	$key   Either a query variable key, or an associative array of query variables.
	 * @param 	string 	      $value Optional. Either a query variable value, or a URL to act upon.
	 * @param 	string 	      $url   Optional. A URL to act upon.
	 * @return 	string 	New URL query string (unescaped).
	 */
	function add_query_arg(... $args)
	{
		if (is_array($args[0]))
		{
			if (count($args) < 2 || false === $args[1])
			{
				$uri = uri_string(true);
			}
			else
			{
				$uri = $args[1];
			}
		}
		else
		{
			if (count($args) < 3 || false === $args[2])
			{
				$uri = uri_string(true);
			}
			else
			{
				$uri = $args[2];
			}
		}

		$frag = strstr($uri, '#');
		if ($frag)
		{
			$uri = substr($uri, 0, -strlen($frag));
		}
		else
		{
			$frag = '';
		}

		if (0 === stripos($uri, 'http://'))
		{
			$uri = substr($uri, 7);
			$protocol = 'http://';
		}
		elseif (0 === stripos($uri, 'https://'))
		{
			$uri = substr($uri, 8);
			$protocol = 'https://';
		}
		else
		{
			$protocol = '';
		}

		if (strpos($uri, '?') !== false)
		{
			[$base, $query] = explode('?', $uri, 2);
			$base           .= '?';
		}
		elseif ($protocol || strpos($uri, '=') === false)
		{
			$base  = $uri . '?';
			$query = '';
		}
		else
		{
			$base  = '';
			$query = $uri;
		}

		parse_str($query, $qs);
		$qs = deep_urlencode($qs); // This re-URL-encodes things that were already in the query string.
		if (is_array($args[0]))
		{
			foreach ($args[0] as $k => $v)
			{
				$qs[ $k ] = $v;
			}
		}
		else
		{
			$qs[ $args[0] ] = $args[1];
		}

		foreach ($qs as $k => $v)
		{
			if (false === $v)
			{
				unset($qs[ $k ]);
			}
		}

		$ret = build_query($qs);
		$ret = trim($ret, '?');
		$ret = preg_replace('#=(&|$)#', '$1', $ret);
		$ret = $protocol . $base . $ret . $frag;
		$ret = rtrim($ret, '?');
		return $ret;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('remove_query_arg'))
{
	/**
	 * Removes an item or items from a query string.
	 * @param string|string[] $key   Query key or keys to remove.
	 * @param false|string    $query Optional. When false uses the current URL. Default false.
	 * @return string New URL query string.
	 */
	function remove_query_arg($key, $query = false)
	{
		if (is_array($key)) // Removing multiple keys.
		{
			foreach ($key as $k)
			{
				$query = add_query_arg($k, false, $query);
			}
			return $query;
		}

		return add_query_arg($key, false, $query);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('validate_url'))
{
	/**
	 * Validates a give URL after encoding all non-ascii chars.
	 *
	 * @since   2.16
	 *
	 * @param   string  $url    the url to validate.
	 * @return  bool    true if a valid url, false otherwise
	 */
	function validate_url($url)
	{
		static $pattern;

		if (is_string($url) && ! empty($url))
		{
			empty($pattern) && $pattern = ';(?:https?://)?(?:[a-zA-Z0-9.-]+?\.(?:com|net|org|gov|edu|mil)|\d+\.\d+\.\d+\.\d+);';
			return preg_match($pattern, $url) ? true : false;
		}

		return false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('verify_url'))
{
	/**
	 * Checks whether the given URL is accessible.
	 *
	 * @since 	2.93
	 *
	 * @param 	string 	$url
	 * @return 	true if url is reachable, else false.
	 */
	function verify_url($url)
	{
		if (function_exists('curl_init'))
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, prep_url($url));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);

			$response = @curl_exec($curl);
			curl_close($curl);

			return (false !== $response);
		}

		return (false !== @fopen($url, 'r'));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_site_url'))
{
	/**
	 * Checks whether the given URL is an internal one (accepts empty URL)
	 *
	 * @param 	string
	 * @return 	bool
	 */
	function is_site_url($url = '')
	{
		static $domain;

		if ( ! isset($domain))
		{
			$CI =& get_instance();

			$domain = $CI->config->base_url('', '');
		}

		return (empty($url) OR ! path_is_url($url)) ? true : (false !== strpos($url, $domain));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('validate_email'))
{
	/**
	 * Verifies that an email is valid.
	 * Does not grok i18n domains. Not RFC compliant.
	 *
	 * @since   2.16
	 *
	 * @param   string  $email  email address to verify.
	 * @return  boolean     true if a valid email address, else false.
	 */
	function validate_email($email)
	{
		// Doesn't have the required length?
		if (strlen($email) < 6)
		{
			return false;
		}

		// Doesn't have the @ character?
		if (strpos($email, '@', 1) === false)
		{
			return false;
		}

		// Split to local and domain.
		[$local, $domain] = explode('@', $email, 2);

		// Check local for invalid characters.
		if ( ! preg_match('/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local))
		{
			return false;
		}

		// Check domain for sequences of periods.
		if (preg_match('/\.{2,}/', $domain))
		{
			return false;
		}

		// Check for leading and trailing periods and whitespace.
		if (trim($domain, " \t\n\r\0\x0B.") !== $domain)
		{
			return false;
		}

		// Split the domain into subs.
		$subs = explode('.', $domain);

		// Assume the domain will have at elast 2 subs.
		if (2 > count($subs))
		{
			return false;
		}

		// Now we loop through subs and check them.
		foreach ($subs as $sub)
		{
			// Check for leading and trailing hyphens and whitespace.
			if (trim($sub, "\t\n\r\0\x0B-") !== $sub)
			{
				return false;
			}

			// Check for invalid characters.
			if ( ! preg_match('/^[a-z0-9-]+$/i', $sub))
			{
				return false;
			}
		}

		// Congrats, your email made it!
		return true;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_http_path'))
{
	/**
	 * Helper function to determine if it is a local path
	 *
	 * @since   2.16
	 *
	 * @param   string  URL
	 * @return  string
	 */
	function is_http_path($path)
	{
		return (preg_match('!^\w+://! i', $path));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_mailto'))
{
	/**
	 * Replaces CodeIgniter function in order to use the `antispambot`
	 * function instead of generate all those silly html stuff.
	 *
	 * @since   2.16
	 *
	 * @uses    antispambot
	 * @see     third_party/bkader/formatting.php:1023
	 *
	 * @param   string  the email address
	 * @param   string  the link title
	 * @param   mixed   any attributes
	 * @return  string
	 */
	function safe_mailto($email, $title = '', $attrs = '')
	{
		validate_email($title) && $title = antispambot($title);
		return mailto(antispambot($email), $title, $attrs);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('anchor'))
{
	/**
	 * Anchor Link
	 *
	 * Creates an anchor based on the local URL.
	 *
	 * @param   string  the URL
	 * @param   string  the link title. null to make it empty
	 * @param   mixed   any attributes
	 * @param   string
	 * @return  string
	 */
	function anchor($uri = '', $title = '', $attrs = '', $protocol = null)
	{
		static $template; // remember it

		(isset($template)) OR $template = '<a href="%s"%s>%s</a>';

		if ($uri === '#')
		{
			$site_url = 'javascript:void(0);';
		}
		elseif (str_starts_with($uri, 'tel:') OR preg_match('#^(\w+:)?//#i', $uri) OR strpos($uri, 'javascript:') === 0)
		{
			$site_url = $uri;
		}
		else
		{
			$site_url = site_url($uri, $protocol);
		}

		if ($title !== null && ($title = (string) trim($title)) === '')
		{
			$title = $site_url;
		}
		/**
		 * @todo check if using '_translate' is still valuable.
		 */
		// elseif ($title !== null)
		// {
		// 	$title = _translate($title);
		// }

		if ($attrs !== '')
		{
			$attrs = array_to_attr($attrs);
		}

		return sprintf($template, $site_url, $attrs, $title);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('ip_anchor'))
{
	/**
	 * Generates an anchor used to trace IP address using 'whatismyipaddress'.
	 *
	 * @param 	string 	$ip_address
	 * @param 	string 	$title
	 * @param 	string 	$attrs
	 * @return 	string
	 */
	function ip_anchor($ip_address, $title = '', $attrs = '')
	{
		static $provider = 'https://whatismyipaddress.com/ip/';

		empty($title) && $title = $ip_address;

		return anchor($provider.$ip_address, $title, $attrs);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('anchor_popup'))
{
	/**
	 * Anchor Link - Pop-up version
	 *
	 * Creates an anchor based on the local URL. The link
	 * opens a new window based on the attributes specified.
	 *
	 * @param   string  the URL
	 * @param   string  the link title. null to make it empty
	 * @param   mixed   any attributes
	 * @return  string
	 */
	function anchor_popup($uri = '', $title = '', $attrs = false)
	{
		(null !== $title) && $title = (string) $title;
		$site_url = preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri);

		if ($title === '')
		{
			$title = $site_url;
		}
		else
		{
			$title = _translate($title);
		}

		if ($attrs === false)
		{
			return '<a href="'.$site_url.'" onclick="window.open(\''.$site_url."', '_blank'); return false;\">".$title.'</a>';
		}

		if ( ! is_array($attrs))
		{
			$attrs = array($attrs);

			// Ref: http://www.w3schools.com/jsref/met_win_open.asp
			$window_name = '_blank';
		}
		elseif ( ! empty($attrs['window_name']))
		{
			$window_name = $attrs['window_name'];
			unset($attrs['window_name']);
		}
		else
		{
			$window_name = '_blank';
		}

		foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'menubar' => 'no', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0') as $key => $val)
		{
			$atts[$key] = isset($attrs[$key]) ? $attrs[$key] : $val;
			unset($attrs[$key]);
		}

		$attrs = array_to_attr($attrs);

		return '<a href="'.$site_url
			.'" onclick="window.open(\''.$site_url."', '".$window_name."', '".array_to_attr($atts, true)."'); return false;\""
			.$attrs.'>'.$title.'</a>';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('build_url'))
{
	/**
	 * Builds URL using site url and query args.
	 *
	 * @param 	array 	$query_data
	 * @param 	string 	$protocol
	 * @return 	string
	 */
	function build_url($uri = '', array $query_data = array(), $protocol = null)
	{
		return get_instance()->config->build_url($uri, $query_data, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('lang_url'))
{
	/**
	 * Language URL
	 *
	 * Returns the full URL that can be used to switch site language.
	 *
	 * @param 	string 	$idiom 	The language to switch to.
	 * @return 	string
	 */
	function lang_url(string $idiom, $protocol = null)
	{
		return get_instance()->config->lang_url($idiom, $protocol);
	}
}
// --------------------------------------------------------------------

if ( ! function_exists('current_url'))
{
	/**
	 * Current URL
	 *
	 * Returns the full URL (including segments) of the page where this
	 * function is placed
	 *
	 * @param   bool    $query_string   Whether to add QUERY STRING.
	 * @return  string
	 */
	function current_url($query_string = true, $protocol = null)
	{
		return get_instance()->config->current_url($query_string);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('previous_url'))
{
	/**
	 * Returns the last page the user visited.
	 *
	 * @param   string  $default    Default value if no lat page exists.
	 * @param   bool    $uri_only   Whether to return only the URI.
	 * @return  bool
	 */
	function previous_url($default = null, $uri_only = false)
	{
		static $previous_uri; // remember it.

		$CI =& get_instance();

		if ( ! isset($previous_uri))
		{
			(isset($CI->session)) OR $CI->load->library('session');

			$previous_uri = $CI->session->flashdata(SESS_PREV_URI);

			(empty($previous_uri)) && $previous_uri = '';
		}

		if (validate_url($uri = $previous_uri))
		{
			return $previous_uri;
		}
		elseif (empty($uri) OR $uri === $CI->uri->uri_string())
		{
			$uri = $default;
		}

		return ($uri_only) ? $uri : $CI->config->site_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('next_url'))
{
	/**
	 * Returns the next page the user should visit.
	 *
	 * @param   string  $default    Default value if no lat page exists.
	 * @param   bool    $uri_only   Whether to return only the URI.
	 * @return  bool
	 */
	function next_url($default = null, $uri_only = false)
	{
		static $next_uri; // remember it.

		$CI =& get_instance();

		if ( ! isset($next_uri))
		{
			(isset($CI->session)) OR $CI->load->library('session');

			$next_uri = $CI->session->flashdata(SESS_NEXT_URI);

			(empty($next_uri)) && $next_uri = '';
		}

		if (validate_url($uri = $next_uri))
		{
			return $next_uri;
		}
		elseif (empty($uri) OR $uri === $CI->uri->uri_string())
		{
			$uri = $default;
		}

		return ($uri_only) ? $uri : $CI->config->site_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('referrer_url'))
{
	/**
	 * Returns referrer URL.
	 * @return 	string
	 */
	function referrer_url()
	{
		static $referrer_url; // remember it.

		if ( ! isset($referrer_url))
		{
			$CI =& get_instance();

			isset($CI->agent) OR $CI->load->library('user_agent');

			return $referrer_url = $CI->agent->referrer();
		}

		return $referrer_url;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('uri_string'))
{
	/**
	 * URL String
	 *
	 * Overrides CodeIgniter default function in order to optionally
	 * include GET parameters.
	 *
	 * @since   2.11
	 *
	 * @param   bool    $include_get    Whether to include GET parameters.
	 * @return  string
	 */
	function uri_string($include_get = false)
	{
		return get_instance()->uri->uri_string($include_get);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('uri_path'))
{
	/**
	 * Returns the uri path normalized
	 *
	 * @param	boolean	use the rerouted URI string?
	 * @param	int		the start index to build the uri path
	 * @return	string
	 */
	function uri_path($rerouted = true, $start_index = 0)
	{
		$CI =& get_instance();

		$segments = ($rerouted) ? $CI->uri->rsegment_array() : $CI->uri->segment_array();

		if ( ! empty($segments) && $segments[count($segments)] === 'index')
		{
			array_pop($segments);
		}

		if ( ! empty($start_index))
		{
			$segments = array_slice($segments, $start_index);
		}

		return implode('/', $segments);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('query_string'))
{
	/**
	 * query_string
	 *
	 * Returns the URL query string part.
	 *
	 * @since   2.16
	 *
	 * @param   array   an array of query string parameters to exclude
	 * @param   boolean whether to include the question mark
	 * @param   boolean whether to include posted variables in the query string
	 * @return  string
	 */
	function query_string($inc_quest = true, $inc_post = false)
	{
		return get_instance()->uri->query_string($inc_quest, $inc_post);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('static_url'))
{
	/**
	 * Builds and returns URL to the static cookies-free domain used as
	 * content delivery site.
	 *
	 * @param   string|string[] $uri    URI string or an array of segments
	 * @return  string
	 */
	function static_url($uri = '')
	{
		return get_instance()->config->static_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('trace_url'))
{
	/**
	 * Adds a $_GET tracker to URL
	 *
	 * @param   string  $uri    uri to build
	 * @param   string  $trace  click track position
	 * @param   string  $$protocol
	 * @return  string
	 */
	function trace_url($uri = '', $trace = '', $protocol = null)
	{
		return get_instance()->config->trace_url($uri, $trace, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('trace_anchor'))
{
	/**
	 * Returns an anchor tag with optional track & attributes
	 * @param   string  $uri    the URI to append
	 * @param   string  $title  The title to use for our anchor
	 * @param   string  $trace  the track argument to append to URL
	 * @param   mixed   $attrs  string or array of attributes
	 * @param   string  $protocol
	 * @return  string  html anchor tag
	 */
	function trace_anchor($uri = '', $title = '', $trace = '', $attrs = '', $protocol = null)
	{
		return anchor(get_instance()->config->trace_url($uri, $trace, $protocol), $title, $attrs);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_url'))
{
	/**
	 * nonce_url
	 *
	 * Function for generating site URLs with appended security nonce.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri        The URI used to generate the URL.
	 * @param   mixed   $action     Action to attach to the URL.
	 * @return  string
	 */
	function nonce_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url($uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_anchor'))
{
	/**
	 * nonce_anchor
	 *
	 * Function for generating anchor using CodeIgniter built-in anchor
	 * function but using our custom "nonce_url" function generate a full
	 * URL with security nonce.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 *  @since  1.0
	 *  @since  1.40   Rewritten because the "nonce_url" was rewritten as well.
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   string  $action The action to attach to the URL.
	 * @param   string  $title  The anchor text.
	 * @param   mixed   $attrs  Links attributes.
	 * @param   string  $protocol
	 * @return  string  The full anchor tag.
	 */
	function nonce_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		(null !== $title) && $title = (string) $title;

		$nonce_url = is_array($uri)
			? nonce_url($uri, $action, $protocol)
			: (preg_match('#^(\w+:)?//#i', $uri) ? $uri : nonce_url($uri, $action, $protocol));

		if ($title === '')
		{
			$title = $nonce_url;
		}
		else
		{
			$title = _translate($title);
		}

		if ($attrs !== '')
		{
			$attrs = array_to_attr($attrs);
		}

		return '<a href="'.$nonce_url.'"'.$attrs.'>'.$title.'</a>';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('admin_url'))
{
	/**
	 * Admin URL
	 *
	 * Returns the full URL to admin sections of the site.
	 *
	 * @param   string  $uri
	 * @param   string  $protocol
	 * @return  string
	 */
	function admin_url($uri = '', $protocol = null)
	{
		return get_instance()->config->admin_url($uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('admin_anchor'))
{
	/**
	 * Admin Anchor
	 *
	 * Creates an anchor that links to an admin section.
	 *
	 * @param  string   $uri    the section to link to.
	 * @param  string   $title  the string to display.
	 * @param  string   $attrs  attribites to add to anchor.
	 * @param  string   $protocol
	 * @return string
	 */
	function admin_anchor($uri = '', $title = '', $attrs = '', $protocol = null)
	{
		('#' !== $uri && false !== $uri) && $uri = KB_ADMIN.'/'.$uri;
		return anchor($uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_admin_url'))
{
	/**
	 * nonce_admin_url
	 *
	 * Function for creating nonce URLs for the dashboard area.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 * @since   2.0.1   Added automatic context guess.
	 *
	 * @param   string  $uri        The URI used to generate the URL.
	 * @param   string  $action     The action used to create nonce.
	 * @return  string  $protocol
	 */
	function nonce_admin_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url(KB_ADMIN.'/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_admin_anchor'))
{
	/**
	 * nonce_admin_anchor
	 *
	 * Function for creating secured anchor tags for the dashboard area.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 * @since   2.0.1   Added automatic context guess.
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action attached to the URL.
	 * @param   string  $title  The anchor text.
	 * @param   mixed   $attrs  Anchor html attributes.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_admin_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor(KB_ADMIN.'/'.$uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('process_url'))
{
	/**
	 * Process URL
	 *
	 * Returns the full URL to process sections of the site.
	 *
	 * @param   string  $uri
	 * @param   string  $protocol
	 * @return  string
	 */
	function process_url($uri = '', $protocol = null)
	{
		return get_instance()->config->site_url('process/'.$uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('process_anchor'))
{
	/**
	 * Process Anchor
	 *
	 * Creates and anchor that links to an process section.
	 *
	 * @param  string   $uri    the section to link to.
	 * @param  string   $title  the string to display.
	 * @param  string   $attrs  attribites to add to anchor.
	 * @param  string   $protocol
	 * @return string
	 */
	function process_anchor($uri = '', $title = '', $attrs = '', $protocol = null)
	{
		return anchor('process/'.$uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_process_url'))
{
	/**
	 * nonce_process_url
	 *
	 * Function for creating nonce URLs for the process context.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_process_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url('process/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_process_anchor'))
{
	/**
	 * nonce_process_anchor
	 *
	 * Function for create nonce process anchor.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   string  $title  The anchor text.
	 * @param   mixed   $attrs  The anchor attributes.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_process_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor('process/'.$uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('ajax_url'))
{
	/**
	 * AJAX URL
	 *
	 * Returns the full URL to ajax sections of the site.
	 *
	 * @param   string  $uri
	 * @param   string  $protocol
	 * @return  string
	 */
	function ajax_url($uri = '', $protocol = null)
	{
		return get_instance()->config->site_url('ajax/'.$uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('ajax_anchor'))
{
	/**
	 * AJAX Anchor
	 *
	 * Creates and anchor that links to an ajax section.
	 *
	 * @param  string   $uri    the section to link to.
	 * @param  string   $title  the string to display.
	 * @param  string   $attrs  attribites to add to anchor.
	 * @param  string   $protocol
	 * @return string
	 */
	function ajax_anchor($uri = '', $title = '', $attrs = '', $protocol = null)
	{
		return anchor('ajax/'.$uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_ajax_url'))
{
	/**
	 * nonce_ajax_anchor
	 *
	 * Function for creating nonce URLs for Ajax context.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_ajax_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url('ajax/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_ajax_anchor'))
{
	/**
	 * nonce_ajax_anchor
	 *
	 * Function for creating nonce anchors for the AJAX context.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   string  $title  The anchor text.
	 * @param   mixed   $attrs  The anchor attributes.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_ajax_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor('ajax/'.$uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('api_url'))
{
	/**
	 * API URL
	 *
	 * Returns the full URL to api sections of the site.
	 *
	 * @param   string  $uri
	 * @param   string  $protocol
	 * @return  string
	 */
	function api_url($uri = '', $protocol = null)
	{
		return get_instance()->config->site_url('api/'.$uri, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('api_anchor'))
{
	/**
	 * API Anchor
	 *
	 * Creates and anchor that links to an api section.
	 *
	 * @param  string   $uri    the section to link to.
	 * @param  string   $title  the string to display.
	 * @param  string   $attrs  attribites to add to anchor.
	 * @param  string   $protocol
	 * @return string
	 */
	function api_anchor($uri = '', $title = '', $attrs = '', $protocol = null)
	{
		return anchor('api/'.$uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_api_url'))
{
	/**
	 * nonce_api_anchor
	 *
	 * Function for creating nonce URLs for API context.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_api_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url('api/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('nonce_api_anchor'))
{
	/**
	 * nonce_api_anchor
	 *
	 * Function for creating nonce anchors for the API context.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $uri    The URI used to generate the URL.
	 * @param   mixed   $action The action to attach to the URL.
	 * @param   string  $title  The anchor text.
	 * @param   mixed   $attrs  The anchor attributes.
	 * @param   mixed   $protocol
	 * @return  string
	 */
	function nonce_api_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor('api/'.$uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('check_nonce_url'))
{
	/**
	 * check_nonce_url
	 *
	 * Function for checking the selected URL noncety.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 *
	 * @since   1.5.0
	 *
	 * @param   string  $action
	 * @param   string  $url
	 * @return  bool
	 */
	function check_nonce_url($action = -1, $url = null)
	{
		// If no URL provided, we use the current one, then format it.
		(null === $url) && $url = current_url();
		$url = str_replace('&amp;', '&', $url);

		$args = parse_url($url, PHP_URL_QUERY);
		parse_str($args, $query);

		return (isset($token)) ? get_instance()->nonce->verify($query[COOK_CSRF], $action) : false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('share_url'))
{
	/**
	 * Generates a social share URL.
	 *
	 * @since   2.16
	 *
	 * @param   string  $network    the network to generate share for
	 * @param   string  $uri        the URL to use. Default: site's URL
	 * @param   string  $title      the post title. Default: site's name
	 * @param   string  $excerpt    the excerpt. Default: site's description
	 * @return  string
	 */
	function share_url($network = '', $uri = '', $title = '', $excerpt = '')
	{
		// Grab the patterns first.
		$patterns = config_item('share_links');

		// If none found, we return an empty string.
		if ( ! isset($patterns))
		{
			return '';
		}

		// If no network is provided, we fall back to Twitter.
		if (empty($network))
		{
			$network = 'twitter';
		}
		// Not a valid network? We return an empty string.
		elseif ( ! isset($patterns[$network]))
		{
			return '';
		}

		// set some default is missing.
		$site_name = config_item('site_name');
		empty($uri) && $uri = site_url(); // fallback to site's URL
		empty($title) && $title = $site_name; // fallback to site's name
		empty($excerpt) && $excerpt = config_item('site_description'); // fallback to site's descriptioN.

		return sprintf(
			$patterns[$network],
			rawurlencode($uri),
			urlencode($title),
			urlencode($excerpt),
			urlencode($site_name)
		);
	}
}

/*
|-------------------------------------------------------------------------
| Deprecated Functions
|-------------------------------------------------------------------------
|
| The functions below are deprecated and only kept for backwards
| compatibility. You are better off using them and use their
| functions listed here:
|
|   safe_url            = nonce_url
|   safe_anchor         = nonce_anchor
|   safe_admin_url      = nonce_admin_url
|   safe_admin_anchor   = nonce_admin_anchor
|   safe_process_url    = nonce_process_url
|   safe_process_anchor = nonce_process_anchor
|   safe_ajax_url       = nonce_ajax_url
|   safe_ajax_anchor    = nonce_ajax_anchor
|
*/

if ( ! function_exists('safe_url'))
{
	/**
	 * Function for generating site URLs with appended security nonce.
	 * @deprecated 1.5.0    Kept for backward compatibility.
	 * @return  string
	 */
	function safe_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url($uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_anchor'))
{
	/**
	 * Function for generating anchor using CodeIgniter built-in anchor
	 * function but using our custom "safe_url" function generate a full
	 * URL with security nonce.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string  The full anchor tag.
	 */
	function safe_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor($uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_admin_url'))
{
	/**
	 * Function for creating safe URLs for the dashboard area.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string  $action The action to attach to the URL.
	 */
	function safe_admin_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url(KB_ADMIN.'/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_admin_anchor'))
{
	/**
	 * Function for creating secured anchor tags for the dashboard area.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string
	 */
	function safe_admin_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol =null)
	{
		return nonce_anchor(KB_ADMIN.'/'.$uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_process_url'))
{
	/**
	 * Function for creating safe URLs for the process context.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string
	 */
	function safe_process_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url('process/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_process_anchor'))
{
	/**
	 * Function for create safe process anchor.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string
	 */
	function safe_process_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor('process/'.$uri, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_ajax_url'))
{
	/**
	 * Function for creating safe URLs for Ajax context.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string
	 */
	function safe_ajax_url($uri = '', $action = -1, $protocol = null)
	{
		return get_instance()->config->nonce_url('ajax/'.$uri, $action, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safe_ajax_anchor'))
{
	/**
	 * Function for creating safe anchors for the AJAX context.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  string
	 */
	function safe_ajax_anchor($uri = '', $action = -1, $title = '', $attrs = '', $protocol = null)
	{
		return nonce_anchor('ajax/'.$uri, $action, $title, $attrs, $protocol);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('ip_anchor'))
{
	/**
	 * Generates an anchor to 'www.iplocation.net'.
	 *
	 * @param 	string 	$ip_address
	 * @param 	mixed 	$attrs
	 * @param 	string 	$title
	 * @return 	string
	 */
	function ip_anchor($ip_address = '', $attrs = '', $title = '')
	{
		empty($ip_address) && $ip_address = ip_address();
		empty($title) && $title = $ip_address;

		return anchor('https://www.iplocation.net/search?ie=UTF-8&q='.$ip_address, $title, $attrs);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('check_safe_url'))
{
	/**
	 * Function for checking the selected URL safety.
	 * @deprecated  1.5.0   Kept for backward compatibility.
	 * @return  bool
	 */
	function check_safe_url($action = -1, $url = null)
	{
		return check_nonce_url($url, $action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('refresh'))
{
	/**
	 * Header Refresh
	 *
	 * Refreshes the current page.
	 *
	 * @return 	void
	 */
	function refresh()
	{
		header('Refresh:0');
		exit;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect_alert'))
{
	/**
	 * Redirects to a specific URL with a temporary flash message.
	 * @param 	string 	$uri 	The URI to redirect to.
	 * @param 	string 	$text 	The alert text.
	 * @param 	string 	$type 	The alert type.
	 * @return 	void
	 */
	function redirect_alert($uri = '', $text = '', $type = 'info')
	{
		empty($text) OR get_instance()->theme->set_alert($text, $type);

		if ($uri === 'prev' OR $uri === 'back')
		{
			redirect_back($uri);
		}
		elseif ($uri === 'next')
		{
			redirect_next($uri);
		}
		else
		{
			redirect($uri);
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_secure_redirect_uri'))
{
	/**
	 * Returns secured redirect URI by only allowing URIs from same host.
	 *
	 * @param 	string 	$session_key
	 * @param 	string 	$request_key
	 * @return 	mixed 	URI if found, else null
	 */
	function _secure_redirect_uri(string $session_key, string $request_key = 'next')
	{
		$CI =& get_instance();

		if (empty($uri = $CI->input->get_post($request_key)) OR ! is_internal_url($uri))
		{
			isset($CI->session) OR $CI->load->library('session');

			return $CI->session->flashdata($session_key);
		}

		return $uri;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect_back'))
{
	/**
	 * Just an alias used to redirect the user to the previous url
	 * @param 	string 	$default 	URL
	 * @param 	string 	$method 	Redirect method 'auto', 'location' or 'refresh'
	 * @param 	int 	$code 		HTTP Response status code
	 * @return  void
	 */
	function redirect_back($default = '', $method = 'auto', $code = null)
	{
		redirect((empty($uri = _secure_redirect_uri(SESS_PREV_URI))) ? $default : $uri, $method, $code);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect_prev'))
{
	/**
	 * An alias to 'redirect_back' kept only for compatibility purposes.
	 * @deprecated
	 * @return 	redirect_back
	 */
	function redirect_prev($default = '', $method = 'auto', $code = null)
	{
		return redirect_back($default, $method, $code);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect_next'))
{
	/**
	 * Just an alias used to redirect the user to the next url
	 * @param 	string 	$default 	URL
	 * @param 	string 	$method 	Redirect method 'auto', 'location' or 'refresh'
	 * @param 	int 	$code 		HTTP Response status code
	 * @return  void
	 */
	function redirect_next($default = '', $method = 'auto', $code = null)
	{
		redirect((empty($uri = _secure_redirect_uri(SESS_NEXT_URI))) ? $default : $uri, $method, $code);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect_referrer'))
{
	/**
	 * Used to redirect back to referrer page.
	 * @param 	string 	$method 	Redirect method.
	 * @param 	int 	$code 		HTTP Response status code
	 * @return 	void
	 */
	function redirect_referrer($method = 'auto', $code = null)
	{
		redirect(referrer_url(), $method, $code);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('content_url'))
{
	/**
	 * Returns the URL to the content folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function content_url($uri = '')
	{
		return get_instance()->config->content_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('common_url'))
{
	/**
	 * Returns the URL to the common folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function common_url($uri = '')
	{
		return get_instance()->config->common_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('uploads_url'))
{
	/**
	 * Returns the URL to the uploads folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function uploads_url($uri = '')
	{
		return get_instance()->config->uploads_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('themes_url'))
{
	/**
	 * Returns the URL to the themes folder.
	 * @param   string  $uri
	 * @return  string
	 */
	function themes_url($uri = '')
	{
		return get_instance()->config->themes_url($uri);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('theme_url'))
{
	/**
	 * Returns the URL to the currently active theme.
	 * @param   string  $uri
	 * @return  string
	 */
	function theme_url($uri = '')
	{
		return get_instance()->config->theme_url($uri);
	}
}
