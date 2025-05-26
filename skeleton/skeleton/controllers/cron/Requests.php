<?php
defined('BASEPATH') OR die;

/**
 * CRON Bad requests handler.
 *
 * Updates `.htaccess` by adding/removing blocked IP address.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.130
 */
class Requests extends CLI_Controller
{
	/**
	 * Path to "blocked_ips.txt" file.
	 * @var string
	 */
	protected $blocked_ips_file = APPPATH.'config/blocked_ips.txt';

	/**
	 * Path to ".htaccess" file.
	 * @var string
	 */
	protected $htaccess_file = FCPATH.'/.htaccess';

	// --------------------------------------------------------------------

	/**
	 * Main method that handles things.
	 *
	 * @return 	void
	 */
	public function index()
	{
		// Check if there are any IPs
		if ( ! file_exists($this->blocked_ips_file))
		{
			exit('No blocked IPs to add.'.PHP_EOL);
		}

		// Check if .htaccess file is writable
		elseif ( ! is_writable($this->htaccess_file))
		{
			exit("Error: .htaccess file is not writable.".PHP_EOL);
		}

		$ips = file($this->blocked_ips_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		// Prepare deny rules
		$deny_rules = "";
		foreach ($ips as $ip)
		{
			$deny_rules .= "Deny from $ip".PHP_EOL;
		}

		// Prepare new block
		$start_marker = "# BEGIN Blocked IPs";
		$end_marker = "# END Blocked IPs";

		$new_block = "$start_marker".PHP_EOL.
			"<IfModule mod_authz_host.c>".PHP_EOL.
			$deny_rules.
			"</IfModule>".PHP_EOL.
			"$end_marker";

		// Read current .htaccess
		$htaccess = file_get_contents($this->htaccess_file);

		// Check if deny rules already exist in .htaccess
		if (strpos($htaccess, $deny_rules) === false)
		{
			// No matching rules, proceed to append or replace
			if (strpos($htaccess, $start_marker) !== false && strpos($htaccess, $end_marker) !== false)
			{
				// Replace existing block
				$htaccess = preg_replace("/$start_marker(.*?)$end_marker/s", $new_block, $htaccess);
			}
			else
			{
				// Append new block
				$htaccess .= PHP_EOL.$new_block;
			}

			// Save changes to .htaccess
			if ( ! file_put_contents($this->htaccess_file, $htaccess))
			{
				exit("Error: Failed to update .htaccess.".PHP_EOL);
			}

			// Log the update
			log_message('info', ".htaccess updated with new blocked IPs.");
			exit("✅ .htaccess updated successfully.".PHP_EOL);
		}

		exit("No new IPs to block.".PHP_EOL);
	}
}
