<?php
defined('BASEPATH') OR die;

/**
 * CodeIgniter Session Driver Class
 *
 * @package CodeIgniter
 * @subpackage  Libraries
 * @category    Sessions
 * @author  Andrey Andreev
 * @link    https://codeigniter.com/userguide3/libraries/sessions.html
 */
#[AllowDynamicProperties]
abstract class CI_Session_driver
{
	/**
	 * Instance of CI object.
	 *
	 * @var object
	 */
	protected $_ci;

	protected $_config;

	/**
	 * Data fingerprint
	 *
	 * @var bool
	 */
	protected $_fingerprint;

	/**
	 * Lock placeholder
	 *
	 * @var mixed
	 */
	protected $_lock = false;

	/**
	 * Read session ID
	 *
	 * Used to detect session_regenerate_id() calls because PHP only calls
	 * write() after regenerating the ID.
	 *
	 * @var string
	 */
	protected $_session_id;

	/**
	 * Success and failure return values
	 *
	 * Necessary due to a bug in all PHP 5 versions where return values
	 * from userspace handlers are not handled properly. PHP 7 fixes the
	 * bug, so we need to return different values depending on the version.
	 *
	 * @see https://wiki.php.net/rfc/session.user.return-value
	 * @var mixed
	 */
	protected $_success, $_failure;

	/**
	 * Current user's IP address.
	 *
	 * @var string
	 */
	protected $_ip_address;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * @param   array   $params Configuration parameters
	 * @return  void
	 */
	public function __construct(&$params)
	{
		$this->_ci =& get_instance();

		$this->_ip_address = $this->_ci->input->ip_address();

		$this->_config =& $params;

		if (is_php('7'))
		{
			$this->_success = true;
			$this->_failure = false;
		}
		else
		{
			$this->_success = 0;
			$this->_failure = -1;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * PHP 5.x validate ID
	 *
	 * Enforces session.use_strict_mode
	 *
	 * @return  void
	 */
	public function php5_validate_id()
	{
		if ($this->_success === 0 && isset($_COOKIE[$this->_config['cookie_name']]) && ! $this->validateId($_COOKIE[$this->_config['cookie_name']]))
		{
			unset($_COOKIE[$this->_config['cookie_name']]);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Cookie destroy
	 *
	 * Internal method to force removal of a cookie by the client
	 * when session_destroy() is called.
	 *
	 * @return  bool
	 */
	protected function _cookie_destroy()
	{
		if ( ! is_php('7.3'))
		{
			$header = 'Set-Cookie: '.$this->_config['cookie_name'].'=';
			$header .= '; Expires='.gmdate('D, d-M-Y H:i:s T', 1).'; Max-Age=-1';
			$header .= '; Path='.$this->_config['cookie_path'];
			$header .= ($this->_config['cookie_domain'] !== '' ? '; Domain='.$this->_config['cookie_domain'] : '');
			$header .= ($this->_config['cookie_secure'] ? '; Secure' : '').'; HttpOnly; SameSite='.$this->_config['cookie_samesite'];
			header($header);
			return;
		}

		return setcookie(
			$this->_config['cookie_name'],
			'',
			array(
				'expires' => 1,
				'path' => $this->_config['cookie_path'],
				'domain' => $this->_config['cookie_domain'],
				'secure' => $this->_config['cookie_secure'],
				'httponly' => true,
				'samesite' => $this->_config['cookie_samesite']
			)
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Get lock
	 *
	 * A dummy method allowing drivers with no locking functionality
	 * (databases other than PostgreSQL and MySQL) to act as if they
	 * do acquire a lock.
	 *
	 * @param   string  $session_id
	 * @return  bool
	 */
	protected function _get_lock($session_id)
	{
		$this->_lock = true;
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Release lock
	 *
	 * @return  bool
	 */
	protected function _release_lock()
	{
		if ($this->_lock)
		{
			$this->_lock = false;
		}

		return true;
	}
}
