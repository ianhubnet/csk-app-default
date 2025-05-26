<?php
defined('BASEPATH') OR die;

/**
 * KB_string_helper
 *
 * Extending and overriding some of CodeIgniter string function.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Helpers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0.0
 * @version 	1.0.0
 */

if ( ! function_exists('readable_random_string'))
{
	/**
	 * Generates a human readable random string
	 *
	 * @param   int
	 * @param   boolean
	 * @return  string
	 */
	function readable_random_string($length = 6, $camelize = false)
	{
		static $conso, $value;

		empty($conso) && $conso = array("b","c","d","f","g","h","j","k","l","m","n","p","r","s","t","v","w","x","y","z");
		empty($vocal) && $vocal = array("a","e","i","o","u");

		$string = "";

		srand ((double)microtime()*1000000);

		$max = $length / 2;
		for($i = 1; $i <= $max; $i++)
		{
			$string .=$conso[rand(0,19)];
			$string .=$vocal[rand(0,4)];
		}
		return ($camelize) ? ucwords($string) : $string;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('mask_string'))
{
	/**
	 * Masks a string with a give char (takes in )
	 *
	 * @param   string 	$str 	The string to mask.
	 * @param   int 	$start 	Where to start masking.
	 * @param   int 	$stop 	When to stop masking.
	 * @param   string 	$mask 	The mask character to use.
	 * @return  string
	 */
	function mask_string($str, $start = 3, $stop = 3, $mask = '*')
	{
		// Prepare the length of the string
		$length = strlen($str);

		// Clamp $start and $stop values.
		($start >= $length) && $start = 0;
		($stop >= $length) && $stop = floor($length * 0.5);

		// We then prepare the array that will holds all of chars
		$chars = array();

		foreach(str_split($str) as $index => $char)
		{
			$chars[$index] = empty($char) ? '' : (($index <= ($start - 1) OR $index >= ($length - $stop)) ? $char : $mask);
		}

		return implode('', $chars);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('mask_data'))
{
	/**
	 * Similar as 'mask_string' except that it allows you to choose the
	 * length of masked string instead of choosing when to stop masking.
	 *
	 * @param 	string 	$str 	The string to mask.
	 * @param 	int 	$start 	Where to start masking.
	 * @param 	int 	$length	The length of masked section.
	 * @param 	string 	$mask 	The mask character to use.
	 */
	function mask_data($str, $start = 0, $length = 4, $mask = '*')
	{
		return substr($str, 0, $start).str_repeat($mask, $length).substr($str, $start + $length);
	}
}
