<?php
defined('BASEPATH') OR die;

/**
 * KB_array_helper
 *
 * Extending and overriding some of CodeIgniter array function.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Helpers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       Version 1.0
 * @version     1.0.0
 */

if ( ! function_exists('array_sort_by_array')) {
	/**
	 * Sorts an $array1 but indexes of $array2.
	 *
	 * @since 	2.105
	 *
	 * @param 	array 	$array1 	The array to sort.
	 * @param 	array 	$array2 	The sorter
	 * @return 	array
	 */
	function array_sort_by_array(array $array1, array $array2)
	{
		return array_merge(array_flip($array2), $array1);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_remove')) {
	/**
	 * A function to remove elements from a non-associative array.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   the first element is the array, the rest values.
	 * @return  array   the array after being cleaned.
	 */
	function array_remove()
	{
		$args = func_get_args();
		return array_values(array_diff(array_shift($args), $args));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_map_prefix')) {
	/**
	 * A function used to prepend a prefix to the given array values.
	 * NOTE: an underscore is added between $prefix and each value.
	 * This function is meant to be used internally anyways.
	 *
	 * @since 	2.63
	 *
	 * @param 	array 	$array
	 * @param 	string 	$prefix
	 * @return 	array
	 */
	function array_map_prefix(array $array, $prefix = '')
	{
		return empty($prefix) ? $array : array_map(fn($val) => $prefix.'_'.$val, $array);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('iin_array')) {
	/**
	 * A case-insensitive version of PHP in_array.
	 *
	 * @since 	2.0
	 *
	 * @param 	mixed 	$needle
	 * @param 	array 	$haystack
	 * @return 	bool
	 */
	function iin_array($needle, $haystack)
	{
		$needle   = strtolower($needle);
		$haystack = array_map('strtolower', $haystack);

		return in_array($needle, $haystack);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_keys_exist'))
{
	/**
	 * Takes an array of keys and make sure they all exist in the array.
	 * @param   array   $needles   keys to check
	 * @param   array   $haystack  array to use for check
	 * @return  boolean
	 */
	function array_keys_exist(array $needles, array $haystack)
	{
		return ! array_diff_key(array_flip($needles), $haystack);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_any_key_exists'))
{
	/**
	 * Takes an array of keys and check in at least of of them is present.
	 * @param   array   $needles
	 * @param   array   $haystack
	 * @return  boolean
	 */
	function array_any_key_exists(array $needles, array $haystack)
	{
		foreach ($needles as $key)
		{
			if (array_key_exists($key, $haystack))
			{
				return true;
			}
		}

		return false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_orderby'))
{
	/**
	 * An alternative array sorter that is a bit faster then array_sorter
	 * @link    https://php.net/manual/en/function.array-multisort.php
	 *
	 * @param   array   The array of data
	 * @param   string  The column to sort by
	 * @param   string  The direction (asc/desc)
	 * @return  array
	 */
	function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field)
		{
			if (is_string($field))
			{
				$tmp = array();
				foreach ($data as $key => $row) $tmp[$key] = $row[$field];
				$args[$n] = $tmp;
			}
		}
		$args[] = & $data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_sorter'))
{
	/**
	 * Array sorter that will sort on an array's key and allows for asc/desc order
	 *
	 * @param   array
	 * @param   string
	 * @param   string
	 * @param   boolean
	 * @param   boolean
	 * @return  array
	 */
	function array_sorter(&$haystack, $index, $order = 'asc', $nat_sort = false, $case_sensitive = false)
	{
		if (is_array($haystack) && count($haystack) > 0)
		{
			foreach (array_keys($haystack) as $key)
			{
				$temp[$key] = $haystack[$key][$index];
				if ( ! $nat_sort)
				{
					($order == 'asc') ? asort($temp) : arsort($temp);
				}
				else
				{
					($case_sensitive) ? natsort($temp) : natcasesort($temp);
				}
				if ($order != 'asc') $temp = array_reverse($temp, true);
			}
			foreach (array_keys($temp) as $key)
			{
				(is_numeric($key)) ? $sorted[] = $haystack[$key] : $sorted[$key] = $haystack[$key];
			}
			return $sorted;
		}
		return $haystack;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('object_sorter'))
{
	/**
	 * Array sorter that will sort an array of objects based on an objects
	 * property and allows for asc/desc order. Changes the original object
	 *
	 * @param   mixed
	 * @param   string
	 * @param   string
	 * @return  void
	 */
	function object_sorter(&$data, $key, $order = 'asc')
	{
		for ($i = count($data) - 1;$i >= 0;$i--)
		{
			$swapped = false;
			for ($j = 0;$j < $i;$j++)
			{
				if ($order == 'desc')
				{
					if ($data[$j]->$key < $data[$j + 1]->$key)
					{
						$tmp = $data[$j];
						$data[$j] = $data[$j + 1];
						$data[$j + 1] = $tmp;
						$swapped = true;
					}
				}
				else
				{
					if ($data[$j]->$key > $data[$j + 1]->$key)
					{
						$tmp = $data[$j];
						$data[$j] = $data[$j + 1];
						$data[$j + 1] = $tmp;
						$swapped = true;
					}

				}

			}
			if ( ! $swapped) return;
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_divide'))
{
	/**
	 * Divides an array into two arrays, one with keys and the other with values.
	 *
	 * @param 	array 	$haystack
	 * @return 	array
	 */
	function array_divide($haystack)
	{
		return array(array_keys($haystack), array_values($haystack));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_subset'))
{
	/**
	 * Returns a subset of the items from the given array.
	 *
	 * @param 	array 	$haystack
	 * @param 	array 	$needles
	 * @return 	array
	 */
	function array_subset($haystack, $needles)
	{
		return array_intersect_key($haystack, array_flip((array) $needles));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('array_except'))
{
	/**
	 * Returns all of the given array except for the specified items.
	 *
	 * @param 	array 	$haystack
	 * @param 	array 	$needles
	 * @return 	array
	 */
	function array_except($haystack, $needles)
	{
		return array_diff_key((array) $haystack, array_flip((array) $needles));
	}
}
