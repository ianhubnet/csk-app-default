<?php
defined('BASEPATH') OR die;

/**
 * KB_Session Class
 *
 * Extends CI Session Library.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.120
 * @version 	1.0
 */
class KB_Session extends CI_Session
{
	/**
	 * Set userdata
	 *
	 * Legacy CI_Session compatibility method
	 *
	 * @param   mixed   $data   Session data key or an associative array
	 * @param   mixed   $value  Value to store
	 * @return  void
	 */
	public function set(mixed $data, mixed $value = null): void
	{
		if (is_array($data))
		{
			foreach ($data as $key => &$value)
			{
				$_SESSION[$key] = $value;
			}

			return;
		}

		$_SESSION[$data] = $value;
	}

	// --------------------------------------------------------------------

	/**
	 * Unset userdata
	 *
	 * Legacy CI_Session compatibility method
	 *
	 * @param   mixed   $key    Session data key(s)
	 * @return  void
	 */
	public function unset(mixed $key): void
	{
		if (is_array($key))
		{
			foreach ($key as $k)
			{
				unset($_SESSION[$k]);
			}

			return;
		}

		unset($_SESSION[$key]);
	}

	// --------------------------------------------------------------------

	/**
	 * Set flashdata
	 *
	 * @param   mixed   $data   Session data key or an associative array
	 * @param   mixed   $value  Value to store
	 * @return  void
	 */
	public function set_flashdata(mixed $data, mixed $value = null): void
	{
		if (is_array($data))
		{
			foreach ($data as $key => &$value)
			{
				$_SESSION[$key] = $value;
			}

			$this->mark_as_flash(array_keys($data));
			return;
		}

		$_SESSION[$data] = $value;
		$this->mark_as_flash($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Set tempdata
	 *
	 * Legacy CI_Session compatibility method
	 *
	 * @param   mixed   $data   Session data key or an associative array of items
	 * @param   mixed   $value  Value to store
	 * @param   int $ttl    Time-to-live in seconds
	 * @return  void
	 */
	public function set_tempdata(mixed $data, mixed $value = null, $ttl = 300): void
	{
		if (is_array($data))
		{
			foreach ($data as $key => &$value)
			{
				$_SESSION[$key] = $value;
			}

			$this->mark_as_temp(array_keys($data), $ttl);
			return;
		}

		$_SESSION[$data] = $value;
		$this->mark_as_temp($data, $ttl);
	}

	// -------------------------------------------------------------------

	/**
	 * Gets all user data.
	 *
	 * @return 	array
	 */
	public function all(): array
	{
		return $this->userdata();
	}

	// -------------------------------------------------------------------

	/**
	 * Checks if an attribute exists in the session.
	 *
	 * @param 	string 	$key
	 * @return 	bool
	 */
	public function has(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	// -------------------------------------------------------------------

	/**
	 * Gets an attribute by name from SESSION if found, else default.
	 *
	 * @param 	string 	$key
	 * @param 	mixed 	$default
	 * @return 	mixed
	 */
	public function get(string $key, mixed $default = null): mixed
	{
		return (($value = $this->userdata($key)) === null) ? $default : $value;
	}

	// -------------------------------------------------------------------

	/**
	 * Deletes a session attribute by name and returns its value or default.
	 *
	 * @param 	string 	$key
	 * @param 	mixed 	$default
	 * @return 	mixed
	 */
	public function pull(string $key, mixed $default = null): mixed
	{
		$value = $this->userdata($key) ?? $default;

		unset($_SESSION[$key]);

		return $value;
	}

	// -------------------------------------------------------------------

	/**
	 * Frees all session variables.
	 *
	 * @return 	void
	 */
	public function clear(): void
	{
		session_unset();
	}

	// -------------------------------------------------------------------

	/**
	 * Destroys the session.
	 *
	 * @return 	bool
	 */
	public function destroy(): bool
	{
		return session_destroy();
	}

	// -------------------------------------------------------------------

	/**
	 * Updates the current session id with a newly generated one.
	 *
	 * @param 	bool 	$destroy 	Destroy old session data.
	 * @return 	bool
	 */
	public function regenerate(bool $destroy = false): bool
	{
		$_SESSION['__ci_last_regenerate'] = TIME;
		return session_regenerate_id($destroy);
	}

}
