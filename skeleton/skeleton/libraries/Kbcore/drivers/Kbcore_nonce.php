<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_nonce Class
 *
 * Handles all operations done with nounces.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_nonce extends KB_Driver
{
	/**
	 * Nonce default lifespan.
	 * @var int
	 */
	private $nonce_life = 7200;

	/**
	 * Hash salt to remember.
	 * @var string
	 */
	private $salt;

	/**
	 * Hash algorithm.
	 * @var string
	 */
	private $algo = 'sha1';

	/**
	 * Holds errors message.
	 * @var string
	 */
	public $message = '';

	// --------------------------------------------------------------------

	/**
	 * __call magic method.
	 *
	 * The reason behind using this method is to prevent initializing class
	 * properties when we do not really need them.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	mixed
	 */
	public function __call($method, $params = array())
	{
		if (method_exists($this, $method) && $method !== 'init')
		{
			$this->init();
			return call_user_func_array(array($this, $method), $params);
		}

		return parent::__call($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * Class initialize function.
	 * @return 	void
	 */
	protected function init()
	{
		// Already initialized since salt is defined.
		if (isset($this->salt))
		{
			return;
		}

		$this->nonce_life = apply_filters('nonce_life', $this->ci->config->item('csrf_expire'));
		$this->message = '';

		// Generate salt if required
		if (empty($this->salt = $this->ci->config->item('encryption_key_256')))
		{
			$this->salt = sha1($this->ci->config->item('encryption_key'));
		}

		// Set hash algorithm.
		$this->algo = defined('KB_HMAC_ALGO') ? KB_HMAC_ALGO : (function_exists('hash') ? 'sha256' : 'sha1');
	}

	// --------------------------------------------------------------------

	/**
	 * create
	 *
	 * Creates a cryptographic token tied to the selection action, used,
	 * user session id and window of time.
	 *
	 * @param 	string 	$action 	Scalar value to add to the nonce.
	 * @return 	string
	 */
	protected function create($action = -1)
	{
		return $this->_nonce_hash(
			$this->_nonce_tick(),
			$this->_sanitize_action($action),
			$this->_nonce_token(),
			$this->_parent->auth->user_id()
		);
	}

	// --------------------------------------------------------------------

	/**
	 * verify
	 *
	 * Method for verifying that a correct token was used within the time limit.
	 * The user is given an amount of time to use the token, so thereforc, since
	 * the $user_id and $action remain the same, the independent variable is time.
	 *
	 * @param 	string 	$token 	The nonce token that was used in the action.
	 * @param 	mixed 	$action The action for which the nonce was created.
	 * @return 	bool 	True if the token is valid, else false.
	 */
	protected function verify($nonce, $action = -1)
	{
		// No token provided?
		if (empty($nonce))
		{
			$this->message = $this->ci->lang->line('error_csrf');
			return false;
		}

		$known_nonce = $this->_nonce_hash(
			$this->_nonce_tick(),
			$this->_sanitize_action($action),
			$this->_nonce_token(),
			$this->_parent->auth->user_id()
		);

		if ( ! hash_equals($nonce, $known_nonce))
		{
			log_message('error', sprintf(
				'Invalid nonce verification: IP=%s, User=%s, URI=%s, Action=%s',
				ip_address(),
				$this->_parent->auth->user_id(),
				$this->ci->uri->uri_string(true),
				$action
			));

			$this->message = $this->ci->lang->line('error_csrf');
			return false;
		}
		else
		{
			$this->message = ''; // reset error message
			return true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * verify_request
	 *
	 * Method for checking forms with added security nonce.
	 *
	 * @param   string  $action     The action attached (Optional).
	 * @param   string  $action     The action nonce (Optional).
	 * @return  bool
	 */
	protected function verify_request($action = null, $nonce = null)
	{
		// No nonce? pass...
		if (empty($nonce) && empty($nonce = $this->ci->input->post_get(COOK_CSRF)))
		{
			return true;
		}
		elseif (empty($action))
		{
			if ( ! empty($action = $this->ci->input->post('action')))
			{
				($decrypted = decrypt_data($action)) && $action = $decrypted;
			}
			elseif (empty($action = $this->ci->input->get('action')))
			{
				$action = $this->ci->uri->uri_string();
			}
		}

		return $this->verify($nonce, $action);
	}

	// --------------------------------------------------------------------

	/**
	 * _sanitize_action
	 *
	 * Method for sanitizing action.
	 *
	 * @param 	mixed 	$action
	 * @return 	string
	 */
	private function _sanitize_action($action = -1)
	{
		$action = mb_strtolower($action); // lowercase.
		$action = strip_tags($action); // strip all tags.
		$action = stripslashes($action); // remove all slashes.
		$action = html_entity_decode($action); // html encode.
		$action = str_replace('\'', '', $action); // Remove quotes.
		$action = preg_replace('/[^a-zA-Z0-9]+/', '_', $action); // Replace non-alpha numeric.
		return trim($action, '_');
	}

	// --------------------------------------------------------------------

	/**
	 * _nonce_tick
	 *
	 * Method for getting the time-dependent variable used for nonce creation.
	 * A nonce have a lifespan of two ticks, it may be updated in its second tick.
	 *
	 * @return 	float
	 */
	private function _nonce_tick()
	{
		return ceil(TIME / ($this->nonce_life * 0.5));
	}

	// --------------------------------------------------------------------

	/**
	 * _nonce_hash
	 *
	 * Method for hashing the given string and return the nonce.
	 *
	 * @param 	string 	$tick
	 * @param 	string 	$action
	 * @param 	string 	$token
	 * @param 	string 	$user_id
	 * @return 	string
	 */
	private function _nonce_hash($tick, $action, $token, $user_id)
	{
		return substr(hash_hmac($this->algo, $tick.'|'.$action.'|'.$token.'|'.$user_id, $this->salt), -16, 10);
	}

	// --------------------------------------------------------------------

	/**
	 * _nonce_token
	 *
	 * Method for returning either to cookie or session token.
	 *
	 * @return 	string
	 */
	private function _nonce_token()
	{
		$token = $this->ci->input->cookie(COOK_USER_AUTH);
		empty($token) && $token = $this->ci->session->userdata(SESS_USER_ID);
		return empty($token) ? '' : $token;
	}

}

// --------------------------------------------------------------------
// Helper Functions.
// --------------------------------------------------------------------

if ( ! function_exists('create_nonce'))
{
	/**
	 * create_nonce
	 *
	 * Creates a cryptographic token tied to the selection action, used,
	 * user session id and window of time.
	 *
	 * @param 	string 	$action 	Scalar value to add to the nonce.
	 * @return 	string
	 */
	function create_nonce($action = -1)
	{
		return get_instance()->nonce->create($action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('verify_nonce'))
{
	/**
	 * verify_nonce
	 *
	 * Function for verifying that a correct token was used within the time limit.
	 * The user is given an amount of time to use the token, so thereforc, since
	 * the $user_id and $action remain the same, the independent variable is time.
	 *
	 * @param 	string 	$token 	The nonce token that was used in the action.
	 * @param 	mixed 	$action The action for which the nonce was created.
	 * @return 	bool 	True if the token is valid, else false.
	 */
	function verify_nonce($nonce, $action = -1)
	{
		return get_instance()->nonce->verify($nonce, $action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('verify_request_nonce'))
{
	/**
	 * verify_request_nonce
	 *
	 * Function for checking forms with added security nonce.
	 *
	 * @param   string  $action     The action attached (Optional).
	 * @param   string  $action     The action nonce (Optional).
	 * @return  bool
	 */
	function verify_request_nonce($nonce, $action = -1)
	{
		return get_instance()->nonce->verify_request($nonce, $action);
	}
}
