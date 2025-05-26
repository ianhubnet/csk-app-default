<?php
defined('BASEPATH') OR die;

/**
 * KB_date_helper
 *
 * Extending CodeIgniter date helper.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Helpers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.0.1
 * @version 	2.0.1
 */

if ( ! function_exists('now'))
{
	/**
	 * Replaces CodeIgniter default function to get "now" time with optional
	 * timezones, output format and time offset.
	 *
	 * @param 	string 	$timezone
	 * @param 	string 	$type
	 * @param 	int 	$offset
	 * @return 	mixed 	Int for timestamp format, else string
	 */
	function now($timezone = null, $type = 'timestamp', $offset = 0)
	{
		// Determine the timezone and time to use.
		empty($timezone) && $timezone = config_item('time_reference');
		$curr_time = defined('TIME') ? TIME : time();

		// Apply offset if specified!
		($offset !== 0) && $curr_time = $curr_time + ($offset * HOUR_IN_SECONDS);

		// Return as timestamp is requested.
		if ($type === 'timestamp' OR $type === 'U')
		{
			return $curr_time;
		}
		// Use 'Y-m-d H:i:s' for MySQL-compatible output.
		elseif ($type === 'mysql')
		{
			$type = 'Y-m-d H:i:s';
		}

		// Create DateTime object and format it.
		$datetime = new DateTime("@$curr_time");
		$datetime->setTimezone(new DateTimeZone($timezone));
		return $datetime->format($type);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('human_time_diff'))
{
	/**
	 * Determines the differences between two timestamps and returns
	 * a human readable string.
	 *
	 * @param 	int 	$from 	Unix timestamp from which the different begins.
	 * @param 	int 	$to 	Unix timestamp to end the time difference. Default time().
	 * @return 	string 	Human readable time difference.
	 */
	function human_time_diff($from, $to = null)
	{
		empty($to) && $to = TIME;
		$diff = abs($to - $from);

		// return $diff;

		if ($diff < MINUTE_IN_SECONDS)
		{
			$num = $diff;
			$line = 'second';
		}
		elseif ($diff < HOUR_IN_SECONDS)
		{
			$num  = round($diff / MINUTE_IN_SECONDS);
			$line = 'minute';
		}
		elseif ($diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS)
		{
			$num  = round($diff / HOUR_IN_SECONDS);
			$line = 'hour';
		}
		elseif ($diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS)
		{
			$num  = round($diff / DAY_IN_SECONDS);
			$line = 'day';
		}
		elseif ($diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS)
		{
			$num  = round($diff / WEEK_IN_SECONDS);
			$line = 'week';
		}
		elseif ($diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS)
		{
			$num  = round($diff / MONTH_IN_SECONDS);
			$line = 'month';
		}
		elseif ($diff >= YEAR_IN_SECONDS)
		{
			$num  = round($diff / YEAR_IN_SECONDS);
			$line = 'year';
		}

		$num = ($num <= 1) ? 1 : $num;
		($num >= 2) && $line .= 's';

		return apply_filters('human_time_diff', $num.' '.$line);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('date_formatter'))
{
	/**
	 * Returns the date into the specified format, also looks at the config.
	 *
	 * @param 	mixed 	$date 	timestamp or date
	 * @param 	string 	$format
	 * @return 	string
	 */
	function date_formatter($date, $format = 'date')
	{
		is_numeric($date) OR $date = strtotime($date);

		$CI = get_instance();

		// Date time format.
		if ('datetime' === $format)
		{
			return date($CI->config->item('datetime_format'), $date);
		}

		// Time only format
		elseif ('time' === $format)
		{
			return date($CI->config->item('time_format'), $date);
		}

		// Date format?
		elseif ('date' === $format)
		{
			return date($CI->config->item('date_format'), $date);
		}

		// fallback
		is_string($format) OR $format = 'd/m/Y';
		return date($format, $date);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('pretty_date'))
{
	/**
	 * Returns a string of time difference between the give date and now.
	 *
	 * @param 	mixed 	$date 		timestamp or date
	 * @param 	bool 	$use_gmt 	whether to use GMT
	 * @return 	string
	 */
	function pretty_date($date, $use_gmt = false)
	{
		// Convert to timestamp.
		is_numeric($date) OR $date = strtotime($date);

		// Calcualte the difference.
		$now = $use_gmt ? mktime() : TIME;
		$diff = $now - $date;
		$day_diff = floor($diff / 86400);

		// Don't go beyong!!
		if ($day_diff < 0)
		{
			return;
		}

		$CI =& get_instance();

		// Less than a minute ago.
		if ($diff < 60)
		{
			return $CI->lang->line('date_just_now');
		}

		// Less than 2 minutes ago.
		elseif ($diff < 120)
		{
			return $CI->lang->line('date_one_minute_ago');
		}

		// Less than 1 hour.
		elseif ($diff < 3600)
		{
			return $CI->lang->sline('date_num_minutes_ago', floor($diff / 60));
		}

		// Less than 1 day
		elseif ($diff < 86400)
		{
			return $CI->lang->sline('date_num_hours_ago', floor($diff / 3600));
		}

		// Yesterday
		elseif ($day_diff === 1)
		{
			return $CI->lang->line('date_yesterday');
		}

		// Less than a week ago.
		elseif ($day_diff < 7)
		{
			return $CI->lang->sline('date_num_days_ago', $day_diff);
		}

		// Less than 1 month
		elseif ($day_diff < 30)
		{
			return $CI->lang->sline('date_num_weeks_ago', ceil($day_diff / 7));
		}

		// Less than 1 year
		elseif ($diff < YEAR_IN_SECONDS)
		{
			return $CI->lang->sline('date_num_months_ago', ceil($diff / MONTH_IN_SECONDS));
		}

		// Over 1 year
		{
			return $CI->lang->sline('date_num_years_ago', ceil($diff / YEAR_IN_SECONDS));
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('timezone_list'))
{
	/**
	 * Returns a localized list of PHP timezones.
	 *
	 * This function generates a human-friendly, localized list of PHP timezone identifiers
	 * formatted with their current GMT offset and city/region name, e.g.:
	 *     (GMT +01:00) Paris
	 *     (GMT -05:00) New York
	 *
	 * It uses PHP's Intl extension to localize timezone names, and caches the result
	 * both in memory (per request) and in a file at APPPATH.'cache/' for better performance.
	 *
	 * @param 	string 	$locale 	The locale used for translation (e.g. "en-US", "fr-FR").
	 * @return 	array 	The list of localized timezones, keyed by timezone identifier.
	 */
	function timezone_list($locale = 'en-US')
	{
		// Per-request cache to avoid regenerating for the same locale
		static $cache = array();

		// Return from memory cache if already generated in this request
		if (isset($cache[$locale]))
		{
			return $cache[$locale];
		}

		// Cache file path based on locale
		$cache_file = KBPATH.'cache/timezones_'.strtolower(str_replace('-', '_', $locale)).'.json';

		// If a valid cache file exists, return it decoded
		if (is_file($cache_file))
		{
			$content = file_get_contents($cache_file);
			if ($content !== false && ($decoded = json_decode($content, true)) !== null)
			{
				return $cache[$locale] = $decoded;
			}
		}

		// Final result array
		$timezones = array();

		// List of all PHP timezone identifiers (e.g., "Europe/Paris", "America/New_York")
		$identifiers = DateTimeZone::listIdentifiers();


		foreach ($identifiers as $tz)
		{
			$timezone = new DateTimeZone($tz);
			$datetime = new DateTime('now', $timezone);

			// Get total offset in seconds
			$offset = $timezone->getOffset($datetime);

			// Convert seconds to hours and minutes
			$hours = (int)($offset / 3600);
			$minutes = abs(($offset % 3600) / 60);
			$sign = $offset >= 0 ? '+' : '-';

			// Format offset like "+02:00"
			$formatted_offset = sprintf('%s%02d:%02d', $sign, abs($hours), $minutes);

			// Use IntlTimeZone to get localized display name for the timezone
			$intl_tz = IntlTimeZone::createTimeZone($tz);
			$display_name = $intl_tz->getDisplayName(false, IntlTimeZone::DISPLAY_GENERIC_LOCATION, $locale);

			// Save as: "Africa/Algiers" => "(GMT +01:00) Algiers"
			$timezones[$tz] = "(GMT $formatted_offset) $display_name";
		}

		// Optional: Sort by name for better user experience in dropdowns
		uasort($timezones, static function($a, $b) {
			// Extract offsets like "+01:00" or "-05:00"
			preg_match('/GMT ([+-]\d{2}):(\d{2})/', $a, $matchA);
			preg_match('/GMT ([+-]\d{2}):(\d{2})/', $b, $matchB);

			// Convert offset strings to minutes for comparison
			$offsetA = isset($matchA[1], $matchA[2]) ? ((int)$matchA[1] * 60) + (int)$matchA[2] : 0;
			$offsetB = isset($matchB[1], $matchB[2]) ? ((int)$matchB[1] * 60) + (int)$matchB[2] : 0;

			// Sort by offset ascending (-ve first, 0 middle, +ve last)
			if ($offsetA !== $offsetB) {
				return $offsetA - $offsetB;
			}

			// If offsets are equal, sort alphabetically by timezone name
			return strcmp($a, $b);
		});

		// Save result to file for future use
		@file_put_contents($cache_file, json_encode($timezones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

		// Return and store in static memory cache
		return $cache[$locale] = $timezones;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('timezone_menu'))
{
	/**
	 * Override CodeIgniter default `timezone_menu` to use `timezone_list`
	 *
	 * Generates a drop-down menu of timezones.
	 *
	 * @param 	string 	timezone
	 * @param 	string 	classname
	 * @param 	string 	menu name
	 * @param 	mixed 	attributes
	 * @return 	string
	 */
	function timezone_menu($default = 'UTC', $class = '', $name = 'timezones', $attributes = '')
	{
		$default = ($default === 'GMT') ? 'UTC' : $default;

		$menu = '<select name="'.$name.'"';

		if ($class !== '')
		{
			$menu .= ' class="'.$class.'"';
		}

		$menu .= array_to_attr($attributes);

		// Firefox/Browser 'selected' hack (autocomplete)
		(strpos($menu, 'autocomplete') === false) && $menu .= ' autocomplete="off"';

		$menu .= ">\n";

		foreach (timezone_list() as $key => $val)
		{
			$menu .= '<option value="'.$key.'"';
			($default === $key) && $menu .= ' selected="selected"';
			$menu .= '>'.$val."</option>\n";
		}

		return $menu.'</select>';
	}
}
