<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Core
 * @category 	Common Functions
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

/**
 * Common Functions
 *
 * @since 2.93
 */

// --------------------------------------------------------------------

if ( ! function_exists('html_escape'))
{
	/**
	 * Returns HTML escaped variable.
	 *
	 * @param   mixed   $var        The input string or array of strings to be escaped.
	 * @param   bool    $double_encode  $double_encode set to false prevents escaping twice.
	 * @return  mixed           The escaped string or array of strings as a result.
	 */
	function html_escape($var, $double_encode = true)
	{
		static $charset = null;

		($charset === null) && $charset = config_item('charset') ?: 'UTF-8';

		// Handle strings directly.
		if (is_string($var))
		{
			return htmlspecialchars($var, ENT_QUOTES, $charset, $double_encode);
		}

		// Handle arrays recursively
		elseif (is_array($var))
		{
			foreach ($var as $key => $value)
			{
				$var[$key] = html_escape($value, $double_encode);
			}
			return $var;
		}

		// Handle objects with public properties.
		elseif (is_object($var))
		{
			foreach (get_object_vars($var) as $key => $value)
			{
				$var->$key = html_escape($value, $double_encode);
			}
			return $var;
		}

		// Return the variable unchanged for other types.
		return $var;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('html_unescape'))
{
    /**
     * Reverses the effects of html_escape, decoding HTML entities back to their original characters.
     *
     * @param 	mixed 	var 	The input string, array of strings, or object to decode.
     * @return 	mixed 			The unescaped string, array of strings, or object as a result.
     */
	function html_unescape($var)
	{
		static $charset = null;

		($charset === null) && $charset = config_item('charset') ?: 'UTF-8';

		// Handle strings directly.
		if (is_string($var))
		{
			return htmlspecialchars_decode($var, ENT_QUOTES);
		}

		// Handle arrays recursively
		elseif (is_array($var))
		{
			foreach ($var as $key => $value)
			{
				$var[$key] = html_unescape($value, $double_encode);
			}
			return $var;
		}

		// Handle objects with public properties.
		elseif (is_object($var))
		{
			foreach (get_object_vars($var) as $key => $value)
			{
				$var->$key = html_unescape($value, $double_encode);
			}
			return $var;
		}

		// Return the variable unchanged for other types.
		return $var;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('html_purify'))
{
	/**
	 * Purifies HTML content to remove potentially harmful elements
	 * and attributes.
	 *
	 * This function uses HTMLPurifier to sanitize input HTML, ensuring
	 * it adheres to defined rules and configurations.
	 * It initializes the HTMLPurifier instance once and reuses it for
	 * subsequent calls for better performance.
	 *
	 * @param 	string 	$dirty_html 	The raw HTML string to be purified.
	 * @param 	array 	$options 		Optional array of HTMLPurifier configuration settings.
	 *
	 * @return string The purified HTML string.
	 *
	 * @throws Exception Logs an error message if the cache directory cannot be created.
	 *
	 * @example
	 * // Basic usage
	 * $clean_html = html_purify($dirty_html);
	 *
	 * // Custom configuration
	 * $clean_html = html_purify($dirty_html, ['HTML.Allowed' => 'p,b,strong']);
	 */
	function html_purify(string $dirty_html, array $options = array()): string
	{
		static $base_config = null, $cache_path = null;

		// Define cache path.
		if ($cache_path === null)
		{
			$cache_path = APPPATH.'cache/HTMLPurifier';
		}

		// Create cache folder if it doesn't exist.
		if ( ! is_dir($cache_path) && ! @mkdir($cache_path, 0777, true))
		{
			// Handle error if the directory cannot be created.
			log_message('error', 'Failed to create HTMLPurifier cache directory.');
			return html_escape($dirty_html);
		}

		if ($base_config === null)
		{
			if ( ! class_exists('HTMLPurifier_Config', false))
			{
				require_once KBPATH.'third_party/HTMLPurifier/HTMLPurifier.auto.php';
			}

			// Initialize config.
			$base_config = HTMLPurifier_Config::createDefault();

			// General configuration.
			$base_config->set('Core.Encoding', config_item('charset'));
			$base_config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$base_config->set('Cache.SerializerPath', $cache_path);
			$base_config->set('AutoFormat.RemoveEmpty', true);
		}

		// Clone config for this call
		$config = clone $base_config;

		// Apply custom config options if provided.
		foreach ($options as $key => $value)
		{
			$config->set($key, $value);
		}

		$purifier = new HTMLPurifier($config);
		return $purifier->purify($dirty_html);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('show_403'))
{
	/**
	 * 403 Page Handler
	 *
	 * Thos function is similar to the show_error() function. However, instead
	 * of the standard error template, it displays 403 error.
	 *
	 * @param 	string
	 * @param 	bool
	 * @return 	void
	 */
	function show_403($ip_address = null, $log_error = true)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_403($ip_address, $log_error);
		exit(EXIT_ERROR); // 1
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('show_429'))
{
	/**
	 * 429 Page Handler
	 *
	 * Thos function is similar to the show_error() function. However, instead
	 * of the standard error template, it displays 429 error.
	 *
	 * @param 	string
	 * @param 	bool
	 * @return 	void
	 */
	function show_429($ip_address = null, $log_error = true)
	{
		$_error =& load_class('Exceptions', 'core');
		$_error->show_429($ip_address, $log_error);
		exit(EXIT_ERROR); // 1
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('ip_address'))
{
	/**
	 * Fetch the IP Address
	 *
	 * This function was borrowed from Input::ip_address in order to be
	 * available and used earlier.
	 *
	 * @since 	2.94
	 * @return  string
	 */
	function ip_address()
	{
		static $ip_address = null;

		if ($ip_address !== null)
		{
			return $ip_address;
		}

		if ( ! empty($proxy_ips = config_item('proxy_ips')) && ! is_array($proxy_ips))
		{
			$proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
		}

		$ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;

		if ($proxy_ips)
		{
			foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header)
			{
				$spoof = isset($_SERVER[$header]) ? $_SERVER[$header] : null;

				if ($spoof !== null)
				{
					// Some proxies typically list the whole chain of IP
					// addresses through which the client has reached us.
					// e.g. client_ip, proxy_ip1, proxy_ip2, etc.
					sscanf($spoof, '%[^,]', $spoof);

					if ( ! $this->valid_ip($spoof))
					{
						$spoof = null;
					}
					else
					{
						break;
					}
				}
			}

			if ($spoof)
			{
				for ($i = 0, $c = count($proxy_ips); $i < $c; $i++)
				{
					// Check if we have an IP address or a subnet
					if (strpos($proxy_ips[$i], '/') === false)
					{
						// An IP address (and not a subnet) is specified.
						// We can compare right away.
						if ($proxy_ips[$i] === $ip_address)
						{
							$ip_address = $spoof;
							break;
						}

						continue;
					}

					// We have a subnet ... now the heavy lifting begins
					isset($separator) OR $separator = $this->valid_ip($ip_address, 'ipv6') ? ':' : '.';

					// If the proxy entry doesn't match the IP protocol - skip it
					if (strpos($proxy_ips[$i], $separator) === false)
					{
						continue;
					}

					// Convert the REMOTE_ADDR IP address to binary, if needed
					if ( ! isset($ip, $sprintf))
					{
						if ($separator === ':')
						{
							// Make sure we're have the "full" IPv6 format
							$ip = explode(':',
								str_replace('::',
									str_repeat(':', 9 - substr_count($ip_address, ':')),
									$ip_address
								)
							);

							for ($j = 0; $j < 8; $j++)
							{
								$ip[$j] = intval($ip[$j], 16);
							}

							$sprintf = '%016b%016b%016b%016b%016b%016b%016b%016b';
						}
						else
						{
							$ip = explode('.', $ip_address);
							$sprintf = '%08b%08b%08b%08b';
						}

						$ip = vsprintf($sprintf, $ip);
					}

					// Split the netmask length off the network address
					sscanf($proxy_ips[$i], '%[^/]/%d', $netaddr, $masklen);

					// Again, an IPv6 address is most likely in a compressed form
					if ($separator === ':')
					{
						$netaddr = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($netaddr, ':')), $netaddr));
						for ($j = 0; $j < 8; $j++)
						{
							$netaddr[$j] = intval($netaddr[$j], 16);
						}
					}
					else
					{
						$netaddr = explode('.', $netaddr);
					}

					// Convert to binary and finally compare
					if (strncmp($ip, vsprintf($sprintf, $netaddr), $masklen) === 0)
					{
						$ip_address = $spoof;
						break;
					}
				}
			}
		}

		if ( ! filter_var($ip_address, FILTER_VALIDATE_IP, 0))
		{
			$ip_address = '0.0.0.0';
		}

		return $ip_address;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('log_critical'))
{
	/**
	 * Log Critical Messages.
	 *
	 * @param 	string 	$message
	 * @return 	void
	 */
	function log_critical($message)
	{
		log_message('critical', $message, true);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('log_error'))
{
	/**
	 * Log Error Messages.
	 *
	 * @param 	string 	$message
	 * @return 	void
	 */
	function log_error($message)
	{
		log_message('error', $message, true);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('log_debug'))
{
	/**
	 * Log Debug Messages.
	 *
	 * @param 	string 	$message
	 * @return 	void
	 */
	function log_debug($message)
	{
		log_message('debug', $message);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('log_info'))
{
	/**
	 * Log Info Messages.
	 *
	 * @param 	string 	$message
	 * @return 	void
	 */
	function log_info($message)
	{
		log_message('info', $message);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('create_static_index'))
{
	/**
	 * create_static_index
	 *
	 * Creates an 'index.html' file inside the given path if needed.
	 *
	 * @param 	string 	$path
	 * @return 	bool 	true if exists or created, else false.
	 */
	function create_static_index(string $path)
	{
		static $CI, $template;

		if (empty($template))
		{
			$template = KBPATH.'dist/index_static.dist';
			$CI =& get_instance();
		}

		return KPlatform::dist_to_file(
			$template,
			$path.'/index.html',
			array(
				'{static_url}' => trim($CI->config->static_url(), '//'),
				'{site_url}'   => $CI->config->site_url(),
				'{site_name}'  => $CI->config->item('site_name')
			),
			false
		);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('create_access_index'))
{
	/**
	 * create_access_index
	 *
	 * Like the function above, except that the 'index.html' used is for access.
	 *
	 * @param 	string 	$path
	 * @return 	bool 	true if exists or created, else false.
	 */
	function create_access_index(string $path)
	{
		static $template = KBPATH.'dist/index_access.dist';

		return KPlatform::dist_to_file($template, $path.'/index.html', null, false);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('create_redirect_index'))
{
	/**
	 * create_redirect_index
	 *
	 * Creates an 'index.php' file that should redirect to Site URL.
	 *
	 * @param 	string 	$path
	 * @return 	bool 	true if exists or created, else false.
	 */
	function create_redirect_index(string $path)
	{
		static $template = KBPATH.'dist/index_redirect.dist';

		return KPlatform::dist_to_file(
			$template,
			$path.'/index.php',
			array('{site_url}' => get_instance()->config->site_url())
		);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('create_htaccess'))
{
	/**
	 * create_htaccess
	 *
	 * Makes sure the '.htaccess' file to deny direct access to the given
	 * path is exists or creates it if it doesn't.
	 *
	 * @param 	string 	$path
	 * @return 	bool 	true if exists or created, else false.
	 */
	function create_htaccess(string $path)
	{
		static $template = KBPATH.'dist/htaccess.dist';

		return KPlatform::dist_to_file($template, $path.'/.htaccess', null, false);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('encrypt_data'))
{
	/**
	 * encrypt_data
	 *
	 * Takes a string, encrypts it and returns the encrypted value.
	 *
	 * @param 	string 	$data
	 * @param 	string 	$cipher_algo
	 * @return 	string
	 */
	function encrypt_data(string $data, string $cipher_algo = KB_CIPHER_ALGO)
	{
		if (empty($key = config_item('encryption_key_256')))
		{
			return $data;
		}
		elseif (KB_OPENSSL_ENCRYPT)
		{
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_algo));
			$encrypted = openssl_encrypt($data, $cipher_algo, $key, 0, $iv);
			return base64_encode($iv.'::'.$encrypted);
		}
		else
		{
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
			$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv);
			return base64_encode($iv.'::'.$encrypted);
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('decrypt_data'))
{
	/**
	 * decrypt_data
	 *
	 * Takes a string, decrypts it and return the decrypted value.
	 *
	 * @param 	string 	$data
	 * @param 	string 	$cipher_algo
	 * @return 	mixed 	returns a string if decrypted, else false.
	 */
	function decrypt_data(string $data, string $cipher_algo = KB_CIPHER_ALGO)
	{
		if (empty($key = config_item('encryption_key_256')))
		{
			return $data;
		}
		elseif (KB_OPENSSL_ENCRYPT)
		{
			[$iv, $encrypted] = explode('::', base64_decode($data), 2);
			return openssl_decrypt($encrypted, $cipher_algo, $key, 0, $iv);
		}
		else
		{
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
			$iv = substr($data, 0, $iv_size);
			$encrypted = substr($data, $iv_size);
			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encrypted, MCRYPT_MODE_CBC, $iv), "\0");
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('standard_timezone'))
{
	/**
	 * Converts CodeIgniter's timezone code to PHP timezone.
	 *
	 * @deprecated 	This function is not used anywhere.
	 * @param 	string 	$timezone 	CodeIgniter timezone code.
	 * @return 	string 	PHP timezone
	 */
	function standard_timezone($timezone)
	{
		static $mapping = array(
			'UM12'   => 'Pacific/Kwajalein',
			'UM11'   => 'Pacific/Midway',
			'UM10'   => 'Pacific/Honolulu',
			'UM95'   => 'Pacific/Marquesas',
			'UM9'    => 'Pacific/Gambier',
			'UM8'    => 'America/Los_Angeles',
			'UM7'    => 'America/Boise',
			'UM6'    => 'America/Chicago',
			'UM5'    => 'America/New_York',
			'UM45'   => 'America/Caracas',
			'UM4'    => 'America/Sao_Paulo',
			'UM35'   => 'America/St_Johns',
			'UM3'    => 'America/Buenos_Aires',
			'UM2'    => 'Atlantic/St_Helena',
			'UM1'    => 'Atlantic/Azores',
			'UTC'    => 'UTC',
			'UP1'    => 'Europe/Berlin',
			'UP2'    => 'Europe/Kaliningrad',
			'UP3'    => 'Asia/Baghdad',
			'UP35'   => 'Asia/Tehran',
			'UP4'    => 'Asia/Baku',
			'UP45'   => 'Asia/Kabul',
			'UP5'    => 'Asia/Karachi',
			'UP55'   => 'Asia/Calcutta',
			'UP575'  => 'Asia/Kathmandu',
			'UP6'    => 'Asia/Almaty',
			'UP65'   => 'Asia/Rangoon',
			'UP7'    => 'Asia/Bangkok',
			'UP8'    => 'Asia/Hong_Kong',
			'UP875'  => 'Australia/Eucla',
			'UP9'    => 'Asia/Tokyo',
			'UP95'   => 'Australia/Darwin',
			'UP10'   => 'Australia/Melbourne',
			'UP105'  => 'Australia/LHI',
			'UP11'   => 'Asia/Magadan',
			'UP115'  => 'Pacific/Norfolk',
			'UP12'   => 'Pacific/Fiji',
			'UP1275' => 'Pacific/Chatham',
			'UP13'   => 'Pacific/Samoa',
			'UP14'   => 'Pacific/Kiritimati',
		);

		return isset($mapping[$timezone]) ? $mapping[$timezone] : 'UTC';
	}
}
