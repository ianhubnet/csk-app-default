<?php
defined('BASEPATH') OR die;

/**
 * CRON Purge Controller
 *
 * Handles purging database and other stuff.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.91
 */
class Purge extends CLI_Controller
{
	/**
	 * Main function that's is called by CRON job.
	 *
	 * @return 	void
	 */
	public function index()
	{
		$this->purge->run();
	}

}
