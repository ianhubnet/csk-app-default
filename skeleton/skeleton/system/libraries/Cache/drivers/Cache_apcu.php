<?php
defined('BASEPATH') OR die;

/**
 * CodeIgniter APCu Caching Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		CodeIgniter Dev team
 */
class CI_Cache_apcu extends CI_Driver
{
	/**
	 * Class constructor
	 *
	 * Only present so that an error message is logged
	 * if APCu is not available.
	 *
	 * @return	void
	 */
	public function __construct()
	{
		if ( ! $this->is_supported())
		{
			log_message('error', 'Cache: Failed to initialize APCu; extension not loaded/enabled?');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Get
	 *
	 * Look for a value in the cache. If it exists, return the data
	 * if not, return false
	 *
	 * @param	string
	 * @return	mixed	value that is stored/false on failure
	 */
	public function get($id)
	{
		$success = false;
		$data = apcu_fetch($id, $success);

		if ($success === true)
		{
			return is_array($data) ? $data[0] : $data;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Cache Save
	 *
	 * @param	string	$id	Cache ID
	 * @param	mixed	$data	Data to store
	 * @param	int	$ttl	Length of time (in seconds) to cache the data
	 * @param	bool	$raw	Whether to store the raw value
	 * @return	bool	true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60, $raw = false)
	{
		$ttl = (int) $ttl;

		return apcu_store($id, ($raw === true ? $data : array($data, time(), $ttl)), $ttl);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param	mixed	unique identifier of the item in the cache
	 * @return	bool	true on success/false on failure
	 */
	public function delete($id)
	{
		return apcu_delete($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Increment a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to add
	 * @return	mixed	New value on success or false on failure
	 */
	public function increment($id, $offset = 1)
	{
		return apcu_inc($id, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Decrement a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to reduce by
	 * @return	mixed	New value on success or false on failure
	 */
	public function decrement($id, $offset = 1)
	{
		return apcu_dec($id, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Clean the cache
	 *
	 * @return	bool	false on failure/true on success
	 */
	public function clean()
	{
		return apcu_clear_cache();
	}

	// --------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * @return	mixed	array on success, false on failure
	 */
	public function cache_info()
	{
		return apcu_cache_info();
	}

	// --------------------------------------------------------------------

	/**
	 * Get Cache Metadata
	 *
	 * @param	mixed	key to get cache metadata on
	 * @return	mixed	array on success/false on failure
	 */
	public function get_metadata($id)
	{
		$success = false;
		$stored = apcu_fetch($id, $success);

		if ($success === false OR count($stored) !== 3)
		{
			return false;
		}

		[$data, $time, $ttl] = $stored;

		return array(
			'expire' => $time + $ttl,
			'mtime'  => $time,
			'data'   => $data
		);
	}

	// --------------------------------------------------------------------

	/**
	 * is_supported()
	 *
	 * Check to see if APCu is available on this system, bail if it isn't.
	 *
	 * @return	bool
	 */
	public function is_supported()
	{
		return (extension_loaded('apcu') && ini_get('apc.enabled'));
	}
}
