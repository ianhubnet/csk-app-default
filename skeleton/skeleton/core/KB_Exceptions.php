<?php
defined('BASEPATH') OR die;

/**
 * KB_Exceptions Class
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.93
 * @version 	1.0
 */
class KB_Exceptions extends CI_Exceptions
{
	/**
	 * Extends parent's method to email exceptions.
	 *
	 * @param 	int 	$severity 	Log level
	 * @param 	string 	$message 	Error message
	 * @param 	string 	$filepath 	File path
	 * @param 	int 	$line 		Line number.
	 * @return 	void
	 */
	public function log_exception($severity, $message, $filepath, $line)
	{
		// Skip if running from CLI or not in production mode.
		if (is_cli() OR ENVIRONMENT !== 'production')
		{
			parent::log_exception($severity, $message, $filepath, $line);
			return;
		}

		// Instantiate CI Object and make sure it's valid.
		$CI =& get_instance();
		if (is_object($CI))
		{
			$CI->events->trigger('php_exception', array(
				'severity' => $severity,
				'message'  => $message,
				'filepath' => $filepath,
				'line'     => $line
			));
		}

		// Let the parent do the rest.
		parent::log_exception($severity, $message, $filepath, $line);
	}

	// --------------------------------------------------------------------

	/**
	 * General Error Page
	 *
	 * Called before parent's to append the request ID.
	 *
	 * @param 	string 	$heading 	Page heading
	 * @param 	string 	$message 	Error message
	 * @param 	string 	$template 	Template name
	 * @param 	int 	$status_code (default: 500)
	 * @return 	string 	Error page output
	 */
	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		if (defined('KB_REQUEST_ID') && is_array($message))
		{
			$message[] = 'Request ID: '.KB_REQUEST_ID;
		}
		elseif (defined('KB_REQUEST_ID'))
		{
			$message .= ' | Request ID: '.KB_REQUEST_ID;
		}

		return parent::show_error($heading, $message, $template, $status_code);
	}

	// --------------------------------------------------------------------

	/**
	 * 403 Error Handler
	 *
	 * @uses 	CI_Exceptions::show_error()
	 *
	 * @param   string  $ip_address The IP address.
	 * @param 	bool 	$log_error 	Whether to log the error.
	 * @return 	void
	 */
	public function show_403($ip_address = null, $log_error = true)
	{
		if (is_cli())
		{
			$heading = 'Access Denied';
			$template = 'error_general';
		}
		else
		{
			$heading = '403 - Access Denied';
			$template = 'error_403';
		}

		$message = 'You don\'t have permission to access this page.';

		if ($log_error)
		{
			empty($ip_address) && $ip_address = ip_address();
			log_message('critical', sprintf('%s: %s', $heading, $ip_address));
		}

		echo $this->show_error($heading, $message, $template, 403);
		exit(EXIT_UNKNOWN_FILE); // 4
	}

	// --------------------------------------------------------------------

	/**
	 * 429 Error Handler
	 *
	 * @uses 	CI_Exceptions::show_error()
	 *
	 * @param   string  $ip_address The IP address.
	 * @param 	bool 	$log_error 	Whether to log the error.
	 * @return 	void
	 */
	public function show_429($ip_address = null, $log_error = true)
	{
		if (is_cli())
		{
			$heading = 'Too Many Requests';
			$message = 'You\'ve made too many requests recently.';
			$template = 'error_general';
		}
		else
		{
			$heading = '429 - Too Many Requests';
			$message = 'You\'ve made too many requests recently. Please wait and try your request again later.';
			$template = 'error_429';
		}

		if ($log_error)
		{
			empty($ip_address) && $ip_address = ip_address();
			log_message('critical', sprintf('%s: %s', $heading, $ip_address));
		}

		echo $this->show_error($heading, $message, $template, 429);
		exit(EXIT_UNKNOWN_FILE); // 4
	}

}
