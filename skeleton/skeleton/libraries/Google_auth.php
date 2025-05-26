<?php
defined('BASEPATH') OR die;

/**
 * Google Authenticator Library
 *
 * CodeIgniter library for handling Google Authenticator
 * Two-factor authentication.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.92
 */
class Google_auth
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $CI;

	/**
	 * Length of the code.
	 * @var int
	 */
	protected $length = 6;

	/**
	 * QR Code default width and length.
	 * @var int
	 */
	protected $qrcode_size = 200;

	/**
	 * QR Code default level.
	 * @var string
	 */
	protected $qrcode_level = 'M';
	protected $qrcode_availablevel_levels = array('L', 'M', 'Q', 'H');
	protected $qrcode_url = 'https://api.qrserver.com/v1/create-qr-code/?data=%s&size=%sx%s&ecc=%s';

	// --------------------------------------------------------------------

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	// --------------------------------------------------------------------
	// Secret Methods
	// --------------------------------------------------------------------

	/**
	 * Creates a 16 chars secret, randomly chosen from the allowed base32 chars.
	 *
	 * @param 	int 	$length
	 * @return 	string
	 */
	public function create_secret($length = 16)
	{
		$chars = $this->_get_base32_lookup_table();

		if ($length < 16 OR $length > 128)
		{
			throw new Exception('Google Authenticator: Bad secret length');
		}

		$secret = '';
		$random = false;

		if (function_exists('random_bytes'))
		{
			$random = random_bytes($length);
		}
		elseif (function_exists('mcrypt_create_iv'))
		{
			$random = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
		}
		elseif (function_exists('openssl_random_pseudo_bytes'))
		{
			$random = openssl_random_pseudo_bytes($length, $crypto_strenght);
			$crypto_strenght OR $random = false;
		}

		if (false === $random)
		{
			throw new Exception('Google Authenticator: No source of secure random.');
		}

		for ($i = 0; $i < $length; ++$i)
		{
			$secret .= $chars[ord($random[$i]) & 31];
		}

		return $secret;
	}

	// --------------------------------------------------------------------
	// Code Methods
	// --------------------------------------------------------------------

	/**
	 * Calculates the code, with given secret and point in time.
	 *
	 * @param 	string 	$secret
	 * @param 	mixed 	$time 	Time or null.
	 * @return 	string
	 */
	public function get_code($secret, $time = null)
	{
		empty($time) && $time = floor(TIME / 30);

		$secret_key = $this->_base32_decode($secret);

		$time = chr(0).chr(0).chr(0).chr(0).pack('N*', $time);

		$hm = hash_hmac('SHA1', $time, $secret_key, true);

		$offset = ord(substr($hm, -1)) & 0x0F;

		$hash_part = substr($hm, $offset, 4);

		$value = unpack('N', $hash_part);
		$value = $value[1];
		$value = $value & 0x7FFFFFFF;

		$modulo = pow(10, $this->length);

		return str_pad($value % $modulo, $this->length, '0', STR_PAD_LEFT);
	}

	// --------------------------------------------------------------------

	/**
	 * Check if the code is correct. This will accept codes starting from
	 * $discrepancy x 30 sec ago to $discrepancy * 30sec from now.
	 *
	 * @param 	string 		$secret
	 * @param 	string 		$code
	 * @param 	int 		$discrepancy
	 * @param 	int|null 	$current_time
	 * @return 	bool
	 */
	public function check_code($secret, $code, $discrepancy = 1, $current_time = null)
	{
		empty($current_time) && $current_time = floor(TIME / 30);

		if (6 != strlen($code))
		{
			return false;
		}

		for ($i = -$discrepancy; $i <= $discrepancy; ++$i)
		{
			$calculated_code = $this->get_code($secret, $current_time + $i);

			if (hash_equals($calculated_code, $code))
			{
				return true;
			}
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Set the code length, should be >=6.
	 *
	 * @param 	int 	$length
	 * @return 	Google_auth
	 */
	public function set_code_length($length)
	{
		$this->length = $length;
		return $this;
	}

	// --------------------------------------------------------------------
	// QR Code Methods
	// --------------------------------------------------------------------

	/**
	 * Gets QR-Code URL for image, from Google charts.
	 *
	 * @param 	string 	$name
	 * @param 	string 	$secret
	 * @param 	array 	$params
	 * @return 	string
	 */
	public function get_qrcode_url($name, $secret, $params = array())
	{
		$width = ( ! empty($params['width']) && (int) $params['width'] > 0) ? $params['width'] : $this->qrcode_size;
		$height = ( ! empty($params['height']) && (int) $params['height'] > 0) ? $params['height'] : $this->qrcode_size;
		$level = ( ! empty($params['level']) && false !== array_search($params['level'], $this->qrcode_availablevel_levels)) ? $params['level'] : $this->qrcode_level;

		$url_encoded = urlencode('otpauth://totp/'.$name.'?secret='.$secret.'&issuer='.$this->CI->config->item('site_name'));

		return sprintf($this->qrcode_url, $url_encoded, $width, $height, $level);
	}

	// --------------------------------------------------------------------
	// Private Methods.
	// --------------------------------------------------------------------

	/**
	 * Method to decode base32.
	 *
	 * @param 	string 	$secret
	 * @return 	string
	 */
	private function _base32_decode($secret)
	{
		if (empty($secret))
		{
			return '';
		}

		$base32_chars = $this->_get_base32_lookup_table();
		$base32_chars_flipped = array_flip($base32_chars);

		$pad_char_count = substr_count($secret, $base32_chars[32]);
		$allowed_values = array(6, 4, 3, 1, 0);

		if ( ! in_array($pad_char_count, $allowed_values))
		{
			return false;
		}

		for ($i = 0; $i < 4; ++$i)
		{
			if ($pad_char_count == $allowed_values[$i]
				&& substr($secret, -($allowed_values[$i])) != str_repeat($base32_chars[32], $allowed_values[$i]))
			{
				return false;
			}
		}

		$secret     = str_split(str_replace('=', '', $secret));
		$binary_str = '';

		for ($i = 0; $i < count($secret); $i = $i + 8)
		{
			$x = '';

			if ( ! in_array($secret[$i], $base32_chars))
			{
				return false;
			}

			for ($j = 0; $j < 8; ++$j)
			{
				$x .= str_pad(base_convert(@$base32_chars_flipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
			}

			$eight_bits = str_split($x, 8);

			for ($z = 0; $z < count($eight_bits); ++$z)
			{
				$binary_str .= (($y = chr(base_convert($eight_bits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
			}
		}

		return $binary_str;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array with all 32 characters for decoding from or
	 * encoding to base32.
	 *
	 * @return 	array
	 */
	private function _get_base32_lookup_table()
	{
		return array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
			'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
			'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
			'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
			'=',  // padding char
		);
	}

}
