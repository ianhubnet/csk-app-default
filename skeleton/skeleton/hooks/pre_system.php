<?php
defined('BASEPATH') OR die;

/**
 * Skeleton_pre_system
 *
 * Register pre-system hooks.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Hooks
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.130
 */
class Skeleton_pre_system
{
	/**
	 * How many PHP scripts/requests can run at a single time.
	 * @var int
	 */
	protected $_request_limit = 20;

	/**
	 * Array of visitors IP addresses to limit requests for.
	 * @var array
	 */
	protected $_request_clients = array();

	/**
	 * Path to "blocked_ips.txt" file.
	 * @var string
	 */
	protected $blocked_ips_file = APPPATH.'config/blocked_ips.txt';

	/**
	 * Array of suspicious unwanted URIs.
	 * @var array
	 */
	protected $bad_uris = array(
		// WordPress Stuff
		'/wp-login.php',
		'/wp-admin/',
		'/xmlrpc.php',
		'/wp-content/',
		'/wp-includes/',

		// Joomla Stuff
		'/administrator/',
		'/index.php?option=com_',
		'/administrator/index.php',
		'/installation/',
		'/components/com_',

		// Drupal Stuff
		'/user/login',
		'/drupal/',
		'/sites/default/',
		'/install.php',
		'/core/',

		// Magento Stuff
		'/index.php/admin',
		'/downloader/',
		'/media/catalog/',
		'/var/',

		// PrestaShop Stuff
		'/admin-dev/',

		// Other common CMS
		'/core/',
		'/cms/',
		'/assets/',
		'/includes/',
	);

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Set application-specific request limit.
	 *
	 * @return void
	 */
	public function __construct()
	{
		defined('KB_REQUEST_LIMIT') && $this->_request_limit = KB_REQUEST_LIMIT;
	}

	// --------------------------------------------------------------------

	/**
	 * Registers PHP Whoops.
	 *
	 * @since 	2.16
	 * @return 	void
	 */
	public function whoops()
	{
		// Only register it for "development" environment.
		if (ENVIRONMENT === 'production'
			OR ! is_file($filepath = KBPATH.'third_party/Whoops/autoload.php'))
		{
			return;
		}

		require_once($filepath);

		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
		$whoops->register();
	}

	// --------------------------------------------------------------------

	/**
	 * Handles IP address request limit.
	 *
	 * @param 	string 	$ip_address
	 * @return 	void
	 */
	public function limit_request(?string $ip_address = null)
	{
		// Catch IP address if not provided.
		empty($ip_address) && $ip_address = ip_address();

		// First request?
		isset($this->_request_clients[$ip_address]) OR $this->_request_clients[$ip_address] = 0;

		// Exceeded the limit?
		if ($this->_request_clients[$ip_address] >= $this->_request_limit)
		{
			show_429();
		}

		// Increment request counter.
		$this->_request_clients[$ip_address] += 1;
	}

	// --------------------------------------------------------------------

	/**
	 * Monitors requested URIs then adds the IP address to the blocked
	 * IP addresses list if any of bad URIs is accessed.
	 *
	 * @return 	void
	 */
	public function monitor_request()
	{
		// Prepare request URI
		$script_name = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : '';
		$request_uri = safe_uri();
		if ($script_name && $request_uri !== '' && strpos($request_uri, $script_name) === 0)
		{
			$request_uri = substr($request_uri, strlen($script_name));
		}

		// Skip dashboard and language change stuff
		if (preg_match('/\/(?:'.KB_ADMIN.'|locale)(\/|$)/i', $request_uri))
		{
			return;
		}

		foreach ($this->bad_uris as $uri)
		{
			if (stripos($request_uri, $uri) !== false)
			{
				$ip = ip_address();

				// Log the blocked IP for debugging
				log_message('info', "Blocked IP: $ip");

				// Check if file exists, create it if it doesn't
				if ( ! file_exists($this->blocked_ips_file))
				{
					// Attempt to create the file and set proper permissions
					if (false === file_put_contents($this->blocked_ips_file, ""))
					{
						log_message('error', "Failed to create blocked IPs file: $this->blocked_ips_file");
						exit('Access Denied.');
					}

					// Set permissions for the newly created file (e.g., 666)
					if ( ! chmod($this->blocked_ips_file, 0666))
					{
						log_message('error', "Failed to set permissions for the blocked IPs file: $this->blocked_ips_file");
						exit('Access Denied.');
					}
				}

				// Load existing IPs
				$ips = file_exists($this->blocked_ips_file)
					? file($this->blocked_ips_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
					: [];

				// Add IP if not already in the file
				if ( ! in_array($ip, $ips))
				{
					// Ensure file is writable
					if ( ! is_writable($this->blocked_ips_file))
					{
						log_message('error', "Blocked IPs file is not writable: $this->blocked_ips_file");
						exit('Access Denied.');
					}

					// Write to the file
					elseif ( ! file_put_contents($this->blocked_ips_file, $ip.PHP_EOL, FILE_APPEND | LOCK_EX))
					{
						log_message('error', "Failed to write blocked IP: $ip to $this->blocked_ips_file");
						exit('Access Denied.');
					}
				}

				// Deny access immediately
				header('HTTP/1.0 403 Forbidden');
				header('X-Content-Type-Options: nosniff');
				header('X-XSS-Protection: 1; mode=block');
				exit('Access Denied.');
			}
		}
	}

}
