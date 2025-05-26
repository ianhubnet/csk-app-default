<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Helpers
 * @category 	Browser Helper
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

if ( ! function_exists('browser_info'))
{
	/**
	 * Returns an array of browser information.
	 *
	 * @param 	string 	$user_agent 	Browser agent.
	 * @return 	array
	 */
	function browser_info($user_agent = null)
	{
		// Declare known browsers to look for.
		static $known;
		empty($known) && $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape', 'konqueror', 'gecko');;

		/**
		 * Clean up agent and build regex that matches phrases for known
		 * browsers (i.e: "Firefox/2.0" or "MSIE 6.0") this only matches
		 * the major and minor version numbers.
		 */
		$user_agent = strtolower(empty($user_agent) ? $_SERVER['HTTP_USER_AGENT'] : $user_agent);
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

		// Find all phrases (or return empty array if none found).
		if ( ! preg_match_all($pattern, $user_agent, $matches))
		{
			return array();
		}

		/**
		 * Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
		 * Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
		 * in the UA).  That's usually the most correct.
		 */
		$i = count($matches['browser']) - 1;
		return array($matches['browser'][$i] => $matches['version'][$i]);
	}
}
