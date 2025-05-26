<?php
defined('BASEPATH') OR die;

if ( ! function_exists('currency_format'))
{
	/**
	 * Format the number in currency format
	 *
	 * @uses 	number_format
	 *
	 * @param 	int 	$num
	 * @param 	string 	$currency
	 * @param 	int 	$decimals
	 * @param 	string 	$decimal_separator
	 * @param 	string 	$thousands_separator
	 * @return 	string
	 */
	function currency_format($num, $currency = '$', $decimals = 0, $decimal_separator = '.', $thousands_separator = ' ')
	{
		$num = number_format($num, $decimals, $decimal_separator, $thousands_separator);

		return (empty($currency)) ? $num : sprintf(($currency === '$') ? '%2$s %1$s' : '%s %s', $num, $currency);
	}
}
