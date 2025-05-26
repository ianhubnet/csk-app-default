<?php
defined('BASEPATH') OR die;

/**
 * Skeleton_post_system
 *
 * Registers post system hooks.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Hooks
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.130
 */
class Skeleton_post_system
{
	/**
	 * Custom Error Logging Hook
	 *
	 * Extends error logging to capture custom data.
	 *
	 * @return 	void
	 */
	public function log_last_error()
	{
		if ( ! empty($last_error = error_get_last()))
		{
			log_message('error', 'Custom Error: '.print_r($last_error, true));
		}
	}

}
