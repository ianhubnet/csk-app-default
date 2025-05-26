<?php
defined('BASEPATH') OR die;

/**
 * Hash Class
 *
 * Hashes, encrypts/decrypts strings and generates random strings.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
class Hash
{
	/**
	 * @var object - instance of CI object.
	 */
	protected $ci;

	/**
	 * @var string - glue used for encryption and imploding;
	 */
	private $glue = '~';

	/**
	 * @var object - instance of PasswordHash object.
	 */
	private $phpass;

	/**
	 * Class Contructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}

	// --------------------------------------------------------------------
	// Random String Generator
	// --------------------------------------------------------------------

	/**
	 * Generate a random string using string_helper.
	 * @param 	int 	$length 	random string's length.
	 * @param 	string 	$type 		random string's type.
	 * @return 	string
	 */
	public function random($length = 8, $type = 'alnum')
	{
		// Load string helper only if not loaded.
		function_exists('random_string') OR $this->ci->load->helper('string');

		return random_string($type, $length);
	}

	// --------------------------------------------------------------------

	/**
	 * Portable hashed string using Bcrypt.
	 * @param 	string 	$plaintext 	The plaintext to hash
	 * @return 	string 	the string after being hashed.
	 */
	public function hash($plaintext)
	{
		return $this->getPasswordHash(8, true)->HashPassword($plaintext);
	}

	// --------------------------------------------------------------------
	// Encode and Decode using Encryption library.
	// --------------------------------------------------------------------

	/**
	 * Takes any number or arguments, implode them and encrypts.
	 * @param 	mixed 	array or multiple arguments.
	 * @return 	string
	 */
	public function encode()
	{
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];

			// Load Encrypt library if not loaded.
			(isset($this->ci->encryption)) OR $this->ci->load->library('encryption');

			return $this->ci->encryption->encrypt(implode($this->glue, $args));
		}

		return null;
	}

	// --------------------------------------------------------------------

	/**
	 * Takes a string and try to decrypt it using encryption library.
	 * @param 	string 	$str 	the string to decrypt
	 * @return 	array|null
	 */
	public function decode($str)
	{
		if ( ! empty($str))
		{
			// Load Encrypt library if not loaded.
			(isset($this->ci->encryption)) OR $this->ci->load->library('encryption');

			$decoded = $this->ci->encryption->decrypt($str);

			return (empty($decoded)) ? null : explode($this->glue, $decoded);
		}

		return null;
	}

	// --------------------------------------------------------------------
	// Hash and Check Password methods.
	// --------------------------------------------------------------------

	/**
	 * Hashes a password using Bcrypt library.
	 * @param 	string 	$plaintext 	The plaintext password to hash.
	 * @return 	string 	The password after being hashed.
	 */
	public function hash_password($plaintext)
	{
		return $this->getPasswordHash()->HashPassword($plaintext);
	}

	// --------------------------------------------------------------------

	/**
	 * Compare between a known password and a hash.
	 * @param 	string 	$plaintext 	The plaintext password to validate.
	 * @param 	string 	$hashed 	The password hash to validate against.
	 * @return 	bool 	true if password is valid, else false.
	 */
	public function check_password($plaintext, $hashed)
	{
		return $this->getPasswordHash()->CheckPassword($plaintext, $hashed);
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a random token for the given user's ID.
	 * @param 	int 	$user_id 	The user's ID.
	 * @return 	string 	The randomly generated user token.
	 */
	public function user_token($user_id = 0)
	{
		if ( ! is_numeric($user_id) OR 0 >= $user_id = (int) $user_id)
		{
			return false;
		}

		(isset($this->ci->session)) OR $this->ci->load->library('session');
		return $this->hash($user_id . session_id() . rand());
	}

	// --------------------------------------------------------------------

	/**
	 * Gets an instance of PasswordHash class.
	 * @since 	2.20
	 * @return 	PasswordHash
	 */
	private function getPasswordHash($iteration_count = 10, $portable = true)
	{
		return new PasswordHash($iteration_count, $portable);
	}

}
