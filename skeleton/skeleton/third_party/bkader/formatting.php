<?php
defined('BASEPATH') OR die;

// --------------------------------------------------------------------
// JSON checker/encode/decode function.
// --------------------------------------------------------------------

if ( ! function_exists('json_validate')) {
	/**
	 * json_validate
	 *
	 * Checks if the given string is JSON encoded.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0
	 *
	 * @param 	string
	 * @return 	bool
	 */
	function json_validate($string, $depth = 512, $flags = 0) {
		if (is_string($string)) {
			json_decode($string, null, $depth, $flags);
			return (json_last_error() === JSON_ERROR_NONE);
		}

		return false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('maybe_json_encode')) {
	/**
	 * Turns arrays and objects into json encoded strings
	 * @param   mixed   $value
	 * @author  Kader Bouyakoub <bkader[at]mail[dot]com>
	 * @link    http://bit.ly/KaderGhb
	 * @return  string
	 */
	function maybe_json_encode($value, $flags = 0, $depth = 512) {
		return (is_array($value) OR is_object($value)) ? json_encode($value, $flags, $depth) : $value;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('maybe_json_decode')) {
	/**
	 * Turns a json encoded string into its true nature
	 * @param   string  $json
	 * @author  Kader Bouyakoub <bkader[at]mail[dot]com>
	 * @link    http://bit.ly/KaderGhb
	 * @return  array
	 */
	function maybe_json_decode($string, $assoc = false, $depth = 512, $flags = 0) {
		return json_validate($string) ? json_decode($string, $assoc, $depth, $flags) : $string;
	}
}

// --------------------------------------------------------------------
// PHP check serialized, serialize and unserialize.
// --------------------------------------------------------------------

if ( ! function_exists('is_serialized')) {
	/**
	 * is_serialized
	 *
	 * Checks whether the given string is a serialized.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0
	 *
	 * @param 	string 	$data
	 * @param 	bool 	$strict
	 * @return 	bool
	 */
	function is_serialized($data, $strict = true) {
		// If it isn't a string, it isn't serialized.
		if ( ! is_string($data)) {
			return false;
		}

		$data = trim($data);
		if ('N;' === $data) {
			return true;
		} elseif (strlen($data) < 4) {
			return false;
		} elseif (':' !== $data[1]) {
			return false;
		} elseif ($strict) {
			$lastc = substr($data, -1);
			if (';' !== $lastc && '}' !== $lastc) {
				return false;
			}
		} else {
			$semicolon = strpos($data, ';');
			$brace = strpos($data, '}');

			if (false === $semicolon && false === $brace) {
				return false;
			} elseif (false !== $semicolon && $semicolon < 3) {
				return false;
			} elseif (false !== $brace && $brace < 4) {
				return false;
			}
		}

		$token = $data[0];
		switch ($token) {
			case 's':
			if ($strict) {
				if ('"' !== substr($data, -2, 1)) {
					return false;
				}
			} elseif ( ! str_contains($data, '"')) {
				return false;
			}
		case 'a':
		case 'O':
		case 'E':
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
		}

		return false;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('maybe_serialize')) {
	/**
	 * maybe_serialize
	 *
	 * Turns Array an Objects into serialized strings;
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0
	 *
	 * @param 	mixed 	$data
	 * @return 	string
	 */
	function maybe_serialize($data) {
		return (is_array($data) OR is_object($data)) ? serialize($data) : $data;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('maybe_unserialize')) {
	/**
	 * maybe_unserialize
	 *
	 * Turns a serialized string into its nature.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0
	 *
	 * @param 	string 	$string
	 * @return 	mixed
	 */
	function maybe_unserialize($string) {
		return is_serialized($string) ? unserialize($string) : $string;
	}
}

// --------------------------------------------------------------------
// Boolean string representations.
// --------------------------------------------------------------------

if ( ! function_exists('str2bool')) {
	/**
	 * Coverts a string boolean representation to a true boolean
	 * @access  public
	 * @param   string
	 * @param   boolean
	 * @author  Kader Bouyakoub <bkader[at]mail[dot]com>
	 * @link    http://bit.ly/KaderGhb
	 * @return  boolean
	 */
	function str2bool($str, $strict = false) {
		// If no string is provided, we return 'false'
		if (empty($str)) {
			return false;
		}

		// If the string is already a boolean, no need to convert it
		if (is_bool($str)) {
			return $str;
		}

		$str = strtolower( @(string) $str);

		if ('no' == $str OR 'n' == $str OR 'false' == $str OR 'off' == $str) {
			return false;
		}

		if ($strict) {
			return ('yes' == $str OR 'y' == $str OR 'true' == $str OR 'on' == $str) ? true : false;
		}

		return true;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('str_to_bool')) {
	/**
	 * str2bool function wrapper. Kept for backward-compatibility.
	 */
	function str_to_bool($str, $strict = false) {
		return str2bool($str, $strict);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_str2bool')) {
	/**
	 * is_str2bool
	 *
	 * Function for checking whether the given string is a string
	 * representation of a boolean.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$str
	 * @param 	bool 	$string
	 * @return 	bool
	 */
	function is_str2bool($str, $strict = false) {
		if ($strict === false) {
			$str_test = @(string) $str;

			if (is_numeric($str_test)) {
				return true;
			}
		}

		return (!str2bool($str) OR str2bool($str, true));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('is_str_to_bool')) {
	/**
	 * Wrapper of the "is_str2bool" function.
	 */
	function is_str_to_bool($str, $strict = false) {
		return is_str2bool($str, $strict);
	}
}

// --------------------------------------------------------------------
// Value preparation before inserting and after getting from database.
// --------------------------------------------------------------------

if ( ! function_exists('to_bool_or_serialize')) {
	/**
	 * Takes any type of arguments and turns it into its string
	 * representations before inserting into databases.
	 * @param 	mixed 	$value
	 * @return 	string 	the string representation of "$value".
	 */
	function to_bool_or_serialize($value) {
		return is_bool($value) ? (true === $value ? 'true' : 'false') : maybe_serialize($value);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('from_bool_or_serialize')) {
	/**
	 * Takes any type of data retrieved from database and turns it into
	 * it's original data type.
	 * @param 	string 	$str
	 * @return 	mixed
	 */
	function from_bool_or_serialize($string) {
		return empty($string) ? $string : (is_str2bool($string, true) ? str2bool($string) : maybe_unserialize($string));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('to_bool_or_json')) {
	/**
	 * Takes any type of arguments and turns it into its string
	 * representations before inserting into databases.
	 * @param 	mixed 	$value
	 * @param 	bool 	$assoc
	 * @param 	int 	$depth
	 * @return 	string 	the string representation of "$value".
	 */
	function to_bool_or_json($value, $flags = 0, $depth = 512) {
		return is_bool($value) ? (true === $value ? 'true' : 'false') : maybe_json_encode($value, $flags, $depth);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('from_bool_or_json')) {
	/**
	 * Takes any type of data retrieved from database and turns it into
	 * it's original data type.
	 * @param 	string 	$str
	 * @param 	bool 	$assoc
	 * @param 	int 	$depth
	 * @param 	int 	$flags
	 * @return 	mixed
	 */
	function from_bool_or_json($string, $assoc = false, $depth = 512, $flags = 0) {
		return empty($string) ? $string : (is_str2bool($string, true) ? str2bool($string) : maybe_json_decode($string, $assoc, $depth, $flags));
	}
}

// --------------------------------------------------------------------
// Sanitization functions.
// --------------------------------------------------------------------

if ( ! function_exists('deep_map')) {
	/**
	 * Maps a function to all non-iterable elements of an array or object.
	 * A clone of 'array_walk_recursive' that works for objects too.
	 *
	 * @since   2.16
	 *
	 * @param   mixed       $value      the array, object or scalar.
	 * @param   callback    $callback   the function to map onto $value.
	 * @return  mixed   the value with callback executed.
	 */
	function deep_map($value, $callback) {
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = deep_map($val, $callback);
			}
		} elseif (is_object($value)) {
			$vars = get_object_vars($value);
			foreach ($vars as $key => $val) {
				$value->$key = deep_map($val, $callback);
			}
		} else {
			$value = call_user_func($callback, $value);
		}

		return $value;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('stripslashes_from_strings_only')) {
	/**
	 * A callback for 'deep_stripslashes' which strips slashes from strings.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   $value  the array or string to be stripped.
	 * @return  mixed   the stripped value.
	 */
	function stripslashes_from_strings_only($value) {
		return is_string($value) ? stripslashes($value) : $value;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_stripslashes')) {
	/**
	 * Goes through an array, object, or scalar and removes slashes from values.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   $value  the value to be stripped.
	 * @return  mixed   stripped value.
	 */
	function deep_stripslashes($value) {
		return deep_map($value, 'stripslashes_from_strings_only');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_specialchars')) {
	function deep_specialchars($string, $flags = ENT_NOQUOTES, $encoding = null, $double_encode = false) {
		if ( ! is_array($string)) {
			$string = (string) $string;

			if (0 === strlen($string)) {
				return '';
			}

			// Don't bother if there are no specialchars - saves some processing
			if ( ! preg_match('/[&<>"\']/', $string)) {
				return $string;
			}

			if (empty($flags)) {
				$flags = ENT_NOQUOTES;
			} elseif ( ! in_array($flags, array(0, 2, 3, 'single', 'double'), true)) {
				$flags = ENT_NOQUOTES;
			}

			if (empty($encoding)) {
				static $_encoding = null;
				if ( ! isset($_encoding)) {
					$_encoding = config_item('charset') ?: 'UTF-8';
				}
				$encoding = $_encoding;
			}

			if (in_array($encoding, array('utf8', 'utf-8', 'UTF8', 'UTF-8'))) {
				$encoding = 'UTF-8';
			}

			$_flags  = $flags;

			if ($flags === 'double') {
				$flags = ENT_COMPAT;
				$_flgas = ENT_COMPAT;
			} elseif ($flags === 'single') {
				$flags = ENT_NOQUOTES;
			}

			($double_encode) && $string = kses_normalize_entities($string);

			$string = htmlspecialchars($string, $flags, $encoding, $double_encode);

			if ('single' === $_flags) {
				$string = str_replace("'", '&#039;', $string);
			}

			return $string;
		}

		foreach ($string as $key => $val) {
			$string[$key] = deep_specialchars($val, $flags, $encoding, $double_encode);
		}

		return $string;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_urlencode')) {
	/**
	 * Encodes URL of the given array, object or scalar.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   $value  the array or string to be encoded.
	 * @return  mixed   the encoded value.
	 */
	function deep_urlencode($value) {
		return deep_map($value, 'urlencode');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_urldecode')) {
	/**
	 * Decodes URL of the given array, object or scalar.
	 *
	 * @since 2.16
	 *
	 * @param   mixed   $value  the array or string to be decoded.
	 * @return  mixed   the decoded value.
	 */
	function deep_urldecode($value) {
		return deep_map($value, 'urldecode');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_rawurlencode')) {
	/**
	 * Raw-encodes URL of the given array, object or scalar.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   $value  the array or string to be encoded.
	 * @return  mixed   the encoded value.
	 */
	function deep_rawurlencode($value) {
		return deep_map($value, 'rawurlencode');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_rawurldecode')) {
	/**
	 * Raw-decodes URL of the given array, object or scalar.
	 *
	 * @since   2.16
	 *
	 * @param   mixed   $value  the array or string to be decoded.
	 * @return  mixed   the decoded value.
	 */
	function deep_rawurldecode($value) {
		return deep_map($value, 'rawurldecode');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_replace')) {
	/**
	 * Performs a deep string replace operation to ensure the values in
	 * $search are replace with values from $replace
	 * @param 	mixed 	$search
	 * @param 	mixed 	$replace
	 * @param 	mixed 	$subject 	String for single item, or array.
	 * @return 	mixed
	 */
	function deep_replace($search, $replace, $subject) {
		if ( ! is_array($subject)) {
			$subject = (string) $subject;
			$count = 1;
			while($count) {
				$subject = str_replace($search, $replace, $subject, $count);
			}

			return $subject;
		}

		foreach ($subject as $key => $val) {
			$subject[$key] = deep_replace($search, $replace, $val);
		}

		return $subject;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_htmlentities')) {
	/**
	 * Function for using "htmlentities" on anything.
	 *
	 * @since 	2.0
	 *
	 * @param 	mixed 	$value
	 * @param 	int 	$flags
	 * @param 	string 	$encoding
	 * @param 	bool 	$double_encode
	 * @return 	string
	 */
	function deep_htmlentities($value, $flags = null, $encoding = null, $double_encode = null) {
		static $cached = array();

		(null === $flags) && $flags = ENT_QUOTES;
		(null === $encoding) && $encoding = 'UTF-8';
		(null === $double_encode) && $double_encode = false;

		if ( ! is_array($value)) {
			if ( ! isset($cached[$value])) {
				$cached[$value] = htmlentities($value, $flags, $encoding, $double_encode);
			}

			return $cached[$value];
		}

		foreach ($value as $key => $val) {
			$value[$key] = deep_htmlentities($val, $flags, $encoding, $double_encode);
		}

		return $value;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_addslashes')) {
	/**
	 * Quote string with slashes
	 *
	 * @since 	2.0
	 *
	 * @param 	mixed 	$value
	 * @return 	mixed
	 */
	function deep_addslashes($value) {
		return deep_map($value, 'addslashes');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('deep_strip_tags')) {
	/**
	 * Function for striping tags of string with recursive action on arrays.
	 *
	 * @since 	2.0
	 *
	 * @param 	mixed 	$value
	 * @return 	mixed
	 */
	function deep_strip_tags($value) {
		if ( ! is_array($value)) {
			return filter_var($value, FILTER_SANITIZE_STRING);
		}

		foreach ($value as $key => $val) {
			$value[$key] = deep_strip_tags($val);
		}

		return $value;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('strip_all_tags')) {
	/**
	 * strip_all_tags
	 *
	 * Properly strip all HTML tags including script and style.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$string 	The string containing HTML.
	 * @param 	bool 	$breaks 	Whether to remove left over line breaks.
	 * @return 	string
	 */
	function strip_all_tags($string, $breaks = false) {
		$string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
		$string = strip_tags($string);

		($breaks) && $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
		return trim($string);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('e')) {
	/**
	 * Encodes the given string using the deep_htmlentities function.
	 *
	 * @since 	2.0
	 *
	 * @param 	mixed 	$string
	 * @return 	mixed
	 */
	function e($string) {
		return function_exists('deep_htmlentities')
			? deep_htmlentities($string)
			: htmlentities($string, ENT_QUOTES, 'UTF-8');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('convert_accents')) {
	/**
	 * Just like CodeIgniter "convert_accented_characters" function.
	 *
	 * @since 	2.0
	 *
	 * @param 	string 	$str 	input string.
	 * @return 	string
	 */
	function convert_accents($str) {
		static $foreign_characters, $chars_from, $chars_to;

		if (empty($foreign_characters)) {
			$foreign_characters = array(
				'/ä|æ|ǽ/' => 'ae',
				'/ö|œ/' => 'oe',
				'/ü/' => 'ue',
				'/Ä/' => 'Ae',
				'/Ü/' => 'Ue',
				'/Ö/' => 'Oe',
				'/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|Α|Ά|Ả|Ạ|Ầ|Ẫ|Ẩ|Ậ|Ằ|Ắ|Ẵ|Ẳ|Ặ|А/' => 'A',
				'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|α|ά|ả|ạ|ầ|ấ|ẫ|ẩ|ậ|ằ|ắ|ẵ|ẳ|ặ|а/' => 'a',
				'/Б/' => 'B',
				'/б/' => 'b',
				'/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
				'/ç|ć|ĉ|ċ|č/' => 'c',
				'/Д/' => 'D',
				'/д/' => 'd',
				'/Ð|Ď|Đ|Δ/' => 'Dj',
				'/ð|ď|đ|δ/' => 'dj',
				'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ε|Έ|Ẽ|Ẻ|Ẹ|Ề|Ế|Ễ|Ể|Ệ|Е|Э/' => 'E',
				'/è|é|ê|ë|ē|ĕ|ė|ę|ě|έ|ε|ẽ|ẻ|ẹ|ề|ế|ễ|ể|ệ|е|э/' => 'e',
				'/Ф/' => 'F',
				'/ф/' => 'f',
				'/Ĝ|Ğ|Ġ|Ģ|Γ|Г|Ґ/' => 'G',
				'/ĝ|ğ|ġ|ģ|γ|г|ґ/' => 'g',
				'/Ĥ|Ħ/' => 'H',
				'/ĥ|ħ/' => 'h',
				'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Η|Ή|Ί|Ι|Ϊ|Ỉ|Ị|И|Ы/' => 'I',
				'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|η|ή|ί|ι|ϊ|ỉ|ị|и|ы|ї/' => 'i',
				'/Ĵ/' => 'J',
				'/ĵ/' => 'j',
				'/Ķ|Κ|К/' => 'K',
				'/ķ|κ|к/' => 'k',
				'/Ĺ|Ļ|Ľ|Ŀ|Ł|Λ|Л/' => 'L',
				'/ĺ|ļ|ľ|ŀ|ł|λ|л/' => 'l',
				'/М/' => 'M',
				'/м/' => 'm',
				'/Ñ|Ń|Ņ|Ň|Ν|Н/' => 'N',
				'/ñ|ń|ņ|ň|ŉ|ν|н/' => 'n',
				'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ο|Ό|Ω|Ώ|Ỏ|Ọ|Ồ|Ố|Ỗ|Ổ|Ộ|Ờ|Ớ|Ỡ|Ở|Ợ|О/' => 'O',
				'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ο|ό|ω|ώ|ỏ|ọ|ồ|ố|ỗ|ổ|ộ|ờ|ớ|ỡ|ở|ợ|о/' => 'o',
				'/П/' => 'P',
				'/п/' => 'p',
				'/Ŕ|Ŗ|Ř|Ρ|Р/' => 'R',
				'/ŕ|ŗ|ř|ρ|р/' => 'r',
				'/Ś|Ŝ|Ş|Ș|Š|Σ|С/' => 'S',
				'/ś|ŝ|ş|ș|š|ſ|σ|ς|с/' => 's',
				'/Ț|Ţ|Ť|Ŧ|τ|Т/' => 'T',
				'/ț|ţ|ť|ŧ|т/' => 't',
				'/Þ|þ/' => 'th',
				'/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ũ|Ủ|Ụ|Ừ|Ứ|Ữ|Ử|Ự|У/' => 'U',
				'/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|υ|ύ|ϋ|ủ|ụ|ừ|ứ|ữ|ử|ự|у/' => 'u',
				'/Ƴ|Ɏ|Ỵ|Ẏ|Ӳ|Ӯ|Ў|Ý|Ÿ|Ŷ|Υ|Ύ|Ϋ|Ỳ|Ỹ|Ỷ|Ỵ|Й/' => 'Y',
				'/ẙ|ʏ|ƴ|ɏ|ỵ|ẏ|ӳ|ӯ|ў|ý|ÿ|ŷ|ỳ|ỹ|ỷ|ỵ|й/' => 'y',
				'/В/' => 'V',
				'/в/' => 'v',
				'/Ŵ/' => 'W',
				'/ŵ/' => 'w',
				'/Ź|Ż|Ž|Ζ|З/' => 'Z',
				'/ź|ż|ž|ζ|з/' => 'z',
				'/Æ|Ǽ/' => 'AE',
				'/ß/' => 'ss',
				'/Ĳ/' => 'IJ',
				'/ĳ/' => 'ij',
				'/Œ/' => 'OE',
				'/ƒ/' => 'f',
				'/ξ/' => 'ks',
				'/π/' => 'p',
				'/β/' => 'v',
				'/μ/' => 'm',
				'/ψ/' => 'ps',
				'/Ё/' => 'Yo',
				'/ё/' => 'yo',
				'/Є/' => 'Ye',
				'/є/' => 'ye',
				'/Ї/' => 'Yi',
				'/Ж/' => 'Zh',
				'/ж/' => 'zh',
				'/Х/' => 'Kh',
				'/х/' => 'kh',
				'/Ц/' => 'Ts',
				'/ц/' => 'ts',
				'/Ч/' => 'Ch',
				'/ч/' => 'ch',
				'/Ш/' => 'Sh',
				'/ш/' => 'sh',
				'/Щ/' => 'Shch',
				'/щ/' => 'shch',
				'/Ъ|ъ|Ь|ь/' => '',
				'/Ю/' => 'Yu',
				'/ю/' => 'yu',
				'/Я/' => 'Ya',
				'/я/' => 'ya'
			);

			$chars_from = array_keys($foreign_characters);
			$chars_to = array_values($foreign_characters);
		}

		return preg_replace($chars_from, $chars_to, $str);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('html_excerpt')) {
	/**
	 * html_excerpt
	 *
	 * Safely extracts not more than the first $length characters from the
	 * given string, even if HTML.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$str 	 	The string to generate excerpt from.
	 * @param 	int 	$length 	Maximum number of characters.
	 * @param 	string 	$more 		What to append if $str needs to be trimmed.
	 * @return 	string 	The final excerpt.
	 */
	function html_excerpt($str, $length, $more = null) {
		$more OR $more = '';
		$str = strip_all_tags($str, true);
		$excerpt = mb_substr($str, 0, $length);

		// Remove part of an entity at the end.
		$excerpt = preg_replace('/&[^;\s]{0,6}$/', '', $excerpt);

		if ($str !== $excerpt) {
			$excerpt = trim($excerpt).$more;
		}

		return $excerpt;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('html_wexcerpt')) {
	/**
	 * html_wexcerpt
	 *
	 * Does the same job as the "html_excerpt" function, except that
	 * it uses words count instead of characters count.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @param 	string 	$str 	The string to generate excerpt from.
	 * @param 	int 	$count 	How many words to keep.
	 * @param 	string 	$more 	What to append if $str needs to be trimmed.
	 * @return 	string 	The final excerpt.
	 */
	function html_wexcerpt($str, $count, $more = null) {
		// Make sure $more is always a string.
		$more OR $more = '';

		// Remove all tags and prepare the array of all words.
		$excerpt = strip_all_tags($str);
		$words   = str_word_count($excerpt, 2);

		// Proceed only if $str has more words that requested.
		if (count($words) > $count) {
			$words = array_slice($words, 0, $count, true);
			end($words);

			$pos     = key($words) + strlen(current($words));
			$excerpt = substr($excerpt, 0, $pos).$more;
		}

		return $excerpt;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('convert_invalid_utf8')) {
	function convert_invalid_utf8($string, $strip = false) {
		$string = (string) $string;
		if (0 === strlen($string)) {
			return '';
		}

		static $is_utf8 = null;
		if ( ! isset($is_utf8)) {
			$is_utf8 = in_array(
				config_item('charset'),
				array('utf8', 'utf-8', 'UTF8', 'UTF-8')
			);
		}

		if ( ! $is_utf8) {
			return $string;
		}

		static $utf8_pcre = null;
		if ( ! isset($utf8_pcre)) {
			$utf8_pcre = @preg_match('/^./u', 'a');
		}

		if ( ! $utf8_pcre) {
			return $string;
		}

		// preg_match fails when it encounters invalid UTF8 in $string
		if (1 === @preg_match('/^./us', $string)) {
			return $string;
		}

		// Attempt to strip the bad chars if requested (not recommended)
		if ($strip && function_exists('iconv')) {
			return iconv('utf-8', 'utf-8', $string);
		}

		return '';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('convert_invalid_entities')) {
	/**
	 * Converts invalid Unicode references range to valid range.
	 *
	 * @param 	string 	$content 	String with entities that need converting.
	 * @return 	string 	Converted string.
	 */
	function convert_invalid_entities($content) {
		static $replacements;
		if (empty($replacements)) {
			$replacements = array(
				'&#128;' => '&#8364;', // the Euro sign
				'&#129;' => '',
				'&#130;' => '&#8218;', // these are Windows CP1252 specific characters
				'&#131;' => '&#402;',  // they would look weird on non-Windows browsers
				'&#132;' => '&#8222;',
				'&#133;' => '&#8230;',
				'&#134;' => '&#8224;',
				'&#135;' => '&#8225;',
				'&#136;' => '&#710;',
				'&#137;' => '&#8240;',
				'&#138;' => '&#352;',
				'&#139;' => '&#8249;',
				'&#140;' => '&#338;',
				'&#141;' => '',
				'&#142;' => '&#381;',
				'&#143;' => '',
				'&#144;' => '',
				'&#145;' => '&#8216;',
				'&#146;' => '&#8217;',
				'&#147;' => '&#8220;',
				'&#148;' => '&#8221;',
				'&#149;' => '&#8226;',
				'&#150;' => '&#8211;',
				'&#151;' => '&#8212;',
				'&#152;' => '&#732;',
				'&#153;' => '&#8482;',
				'&#154;' => '&#353;',
				'&#155;' => '&#8250;',
				'&#156;' => '&#339;',
				'&#157;' => '',
				'&#158;' => '&#382;',
				'&#159;' => '&#376;'
			);
		}

		return str_contains($content, '&#1') ? strtr($content, $replacements) : $content;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('zeroise')) {
	/**
	 * Adds leading zeros when necessary.
	 *
	 * @since   2.16
	 *
	 * @param   int     $number     number to append zeros to.
	 * @param   int     $threshold  digit places number needed to not add zeros.
	 * @return  string  adds leading zeros to number if needed.
	 */
	function zeroise($number, $threshold) {
		return sprintf('%0'.$threshold.'s', $number);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('antispambot')) {
	/**
	 * Converts email addresses characters to HTML entities to prevent
	 * those nasty spam bots.
	 *
	 * @since   2.16
	 *
	 * @param   string  $email          the email address to convert.
	 * @param   int     $hex_encoding   (Optional) Set to 1 to enable hex encoding.
	 * @return  string  converted email address.
	 */
	function antispambot($email, $hex_encoding = 0) {
		$new_email = '';
		for ($i = 0, $len = strlen($email); $i < $len; $i++) {
			$j = rand(0, 1 + $hex_encoding);
			if (0 == $j) {
				$new_email .= '&#'.ord($email[$i]).';';
			} elseif (1 == $j) {
				$new_email .= $email[$i];
			} elseif (2 == $j) {
				$new_email .= '%'.zeroise(dechex(ord($email[$i])), 2);
			}
		}

		return str_replace('@', '&#64;', $new_email);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_key')) {
	/**
	 * sanitize_key
	 *
	 * Sanitizes a string for using as internal identifiers.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string
	 * @return  string
	 */
	function sanitize_key($key) {
		return preg_replace('/[^a-z0-9_\-]/', '', strtolower(convert_accents($key)));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_username')) {
	/**
	 * Sanitizes a username, stripping out unsafe character.
	 *
	 * Removes tags, octets, entities and if strict is enable, only keeps
	 * alphanumeric, _, space, ., -, @.
	 *
	 * @since   2.16
	 *
	 * @param   string  $username   the username to sanitize.
	 * @param   bool    $strict     (Optional) limits to specific characters.
	 * @return  string  the sanitized username, after passing through filters.
	 */
	function sanitize_username($username, $strict = false) {
		$username = convert_accents(strip_all_tags($username));
		// Kill octets & entities.
		$username = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '', $username);
		$username = preg_replace('/&.+?;/', '', $username);

		// If strict, reduce to ASCII for max portability.
		($strict) && $username = preg_replace('|[^a-z0-9 _.\-@]|i', '', $username);

		// Consolidate contiguous whitespace.
		return preg_replace('|\s+|', ' ', trim($username));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_email')) {
	/**
	 * Strips out all characters that are not allowed in an email.
	 *
	 * @since 	2.16
	 *
	 * @param 	string 	$email 	email address to filter.
	 * @return 	string 	filtered and sanitized email address.
	 */
	function sanitize_email($email) {
		// Initial sanitization using filter_var
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);

		// Doesn't have the required length OR doesn't have the @ character?
		if ( ! $email OR strlen($email) < 6 OR strpos($email, '@', 1) === false) {
			return $email; // email_too_short
		}

		// Split to local and domain.
		[$local, $domain] = explode('@', $email, 2);

		// Check local for invalid characters.
		if (empty($local = preg_replace('/[^a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]/', '', $local))) {
			return $email; // local_invalid_chars
		}

		// Check domain for sequences of periods.
		// Check for leading and trailing periods and whitespace.
		elseif (empty($domain = preg_replace('/\.{2,}/', '', $domain))
			OR empty($domain = trim($domain, "\t\n\r\0\x0B."))) {
			return $email; // domain_period_sequence
		}

		// Split the domain into subs.
		$subs = explode('.', $domain);

		// Assume the domain will have at least 2 subs.
		if (2 > count($subs)) {
			return $email; // domain_no_periods
		}

		// Array that will contain valid subs.
		$new_subs = array();

		// Now we loop through subs and check them.
		foreach ($subs as $sub) {
			// Check for leading and trailing hyphens and whitespace.
			$sub = trim($sub, " \t\n\r\0\x0B-");

			// Check for invalid characters.
			$sub = preg_replace('/[^a-z0-9-]+/i', '', $sub);

			// Add anything that's left.
			(empty($sub)) OR $new_subs[] = $sub;
		}

		// Assume the domain will have at least 2 subs.
		if (2 > count($new_subs)) {
			return $email; // domain_no_valid_subs
		}

		// Join valid subs into the new domain.
		$domain = implode('.', $new_subs);

		// Put the email back together.
		return $local.'@'.$domain;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_sanitize_text_fields')) {
	/**
	 * _sanitize_text_fields
	 *
	 * Internal helper function used to sanitize a string from user
	 * input or from the database.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string  $str            The string to sanitize.
	 * @param   bool    $keep_newlines  Whether to keep newline.
	 * @return  string
	 */
	function _sanitize_text_fields($str, $keep_newlines = false) {
		$filtered = convert_invalid_utf8($str);

		if (str_contains($filtered, '<')) {
			$filtered = strip_all_tags($filtered);
			$filtered = str_replace("<\n", "&lt;\n", $filtered);
		}

		if ( ! $keep_newlines) {
			$filtered = preg_replace('/[\r\n\t ]+/', ' ', $filtered);
		}

		$filtered = trim($filtered);

		$found = false;
		$preg  = preg_match('/%[a-f0-9]{2}/i', $filtered, $match);

		while (preg_match('/%[a-f0-9]{2}/i', $filtered, $match)) {
			$filtered = str_replace($match[0], '', $filtered);
			$found = true;
		}

		($found) && $filtered = trim(preg_replace('/ +/', ' ', $filtered));

		return $filtered;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_text_field')) {
	/**
	 * Sanitizes a string from user input or from database.
	 *
	 * @since   2.0
	 *
	 * @see     sanitize_textarea_field()
	 * @uses    _sanitize_text_fields()
	 *
	 * @param   string  $str    string to sanitize.
	 * @return  string  sanitized string.
	 */
	function sanitize_text_field($str) {
		return _sanitize_text_fields($str, false);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('sanitize_textarea_field')) {
	/**
	 * Sanitizes a multiline string from user input or from database.
	 *
	 * @since   2.0
	 *
	 * @uses    _sanitize_text_fields()
	 *
	 * @param   string  $str    string to sanitize.
	 * @return  string  sanitized string.
	 */
	function sanitize_textarea_field($str) {
		return _sanitize_text_fields($str, true);
	}
}

// --------------------------------------------------------------------
// Escape Functions
// --------------------------------------------------------------------

if ( ! function_exists('esc_url')) {
	/**
	 * esc_url
	 *
	 * Removes certain characters from URL.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @see     http://uk1.php.net/manual/en/function.urlencode.php#97969
	 *
	 * @param   string
	 * @return  string
	 */
	function esc_url($url) {
		$search  = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replace = array('!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']');
		return deep_replace($search, $replace, $url);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('esc_js')) {
	/**
	 * esc_js
	 *
	 * Function for escaping string for echoing in JS. It is intended to be used for
	 * inline JS (in a tag attribute, onclick="..." for instance).
	 * Strings must be in single quotes.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string  $string
	 * @return  string
	 */
	function esc_js($string) {
		$safe = convert_invalid_utf8($string);
		$safe = deep_htmlentities($safe, ENT_COMPAT);
		$safe = preg_replace('/&#(x)?0*(?(1)27|39);?/i', "'", stripslashes($safe));
		$safe = str_replace("\r", '', $safe);
		return str_replace("\n", '\\n', addslashes($safe));
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('esc_html')) {
	/**
	 * esc_html
	 *
	 * Escapes HTML from the given string.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string  $string
	 * @return  string
	 */
	function esc_html($string) {
		return deep_htmlentities(convert_invalid_utf8($string), ENT_QUOTES);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('esc_attr')) {
	/**
	 * esc_attr
	 *
	 * Function to escaping HTML attributes.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string  $string
	 * @return  string
	 */
	function esc_attr($string) {
		return deep_htmlentities(convert_invalid_utf8($string), ENT_QUOTES);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('esc_textarea')) {
	/**
	 * esc_textarea
	 *
	 * Escaping for textarea values.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   string  $text
	 * @return  string
	 */
	function esc_textarea($text) {
		return deep_htmlentities($text, ENT_QUOTES);
	}
}

// --------------------------------------------------------------------
// Attributes functions.
// --------------------------------------------------------------------

if ( ! function_exists('array_to_attr')) {
	/**
	 * Takes an array of attributes and turns it into a string of HTML tag.
	 *
	 * @since   2.1
	 * @since 	2.44 	Added $js argument.
	 *
	 * @param   array   $attr   Attributes to turn to string.
	 * @return  string
	 */
	function array_to_attr($attr, $js = false) {
		if (empty($attr)) {
			return null;
		} elseif (is_string($attr)) {
			return ' '.$attr;
		} else {
			is_array($attr) OR $attr = (array) $attr;
		}

		$attr_str = '';

		foreach ($attr as $key => $val) {
			// We ignore null/false values.
			if ($val === null OR $val === false) {
				continue;
			}

			// Numeric keys must be something like disabled="disabled"
			is_numeric($key) && $key = $val;

			$attr_str .= ($js) ? $key.'='.$val.',' : ' '.$key.'="'.str_replace('"', '&quot;', $val).'"';
		}

		// We strip extra spaces before and after.
		return rtrim($attr_str, ',');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('attr_to_array')) {
	/**
	 * Takes a string of HTML attributes and turns it into an array.
	 *
	 * @since   2.1
	 *
	 * @param   string  $str    HTML attributes string.
	 * @return  array
	 */
	function attr_to_array($str) {
		preg_match_all('#(\w+)=([\'"])(.*)\\2#U', $str, $matches);
		$params = array();

		foreach($matches[1] as $key => $val) {
			empty($matches[3]) OR $params[$val] = $matches[3][$key];
		}

		return $params;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('merge_attributes')) {
	/**
	 * Simple function that takes as many arrays as possible, merge them all
	 * and if they have common array keys, they will be appended to each
	 * other, separated by a simple space.
	 *
	 * @param 	...array
	 * @return 	array
	 */
	function merge_attributes()
	{
		$result = call_user_func_array('array_merge_recursive', func_get_args());

		foreach ($result as $key => $value)
		{
			is_array($value) && $result[$key] = implode(' ', $value);
		}

		return $result;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('_jsonify_attributes')) {
	/**
	 * Does the same thing as CodeIgniter _stringify_attributes function
	 * except that it always returns attributes in the following format:
	 *
	 * 	key1:value1;key2:value2...
	 *
	 * @param   mixed   string, array, object
	 * @param   bool
	 * @return  string
	 */
	function _jsonify_attributes($attributes) {
		if (empty($attributes)) {
			return null;
		}

		if (is_string($attributes)) {
			return ' '.$attributes;
		}

		$attributes = (array) $attributes;

		$atts = '';
		foreach ($attributes as $key => $val) {
			$atts .= $key.':'.$val.';';
		}

		return rtrim($atts, ';');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('strtolower_utf8')) {
	/**
	 * strtolower_utf8
	 *
	 * Converts string to lowercase using mb_convert_case.
	 *
	 * @since 	2.26
	 *
	 * @param 	string 	$string 	String to convert to lowercase.
	 * @return 	string 	String after being converted.
	 */
	function strtolower_utf8($string) {
		return mb_convert_case($string, MB_CASE_LOWER, 'UTF-8');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('strtoupper_utf8')) {
	/**
	 * strtoupper_utf8
	 *
	 * Converts string to uppercase using mb_convert_case.
	 *
	 * @since 	2.26
	 *
	 * @param 	string 	$string 	String to convert to lowercase.
	 * @return 	string 	String after being converted.
	 */
	function strtoupper_utf8($string) {
		return mb_convert_case($string, MB_CASE_UPPER, 'UTF-8');
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('strtosnake'))
{
	/**
	 * strtosnake
	 *
	 * Convert a string to snake case.
	 *
	 * @since 	2.93
	 * @param 	string 	$string
	 * @param 	string  $delimiter
	 * @return 	string
	 */
	function strtosnake($string, $delimiter = '_')
	{
		$string = preg_replace('/\s+/u', '', ucwords($string));
		return strtolower_utf8(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $string));
	}
}
