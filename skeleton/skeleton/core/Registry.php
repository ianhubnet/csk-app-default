<?php
defined('BASEPATH') OR die;

/**
 * CI_Registry Class
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.20
 * @version     1.0
 */
class CI_Registry
{
	/**
	 * Flag to suspend cache.
	 *
	 * @var bool
	 */
	private $suspend = false;

	/**
	 * Array of cached objects.
	 *
	 * @var array
	 */
	protected $cache = array();

	/**
	 * Amount of times the cache data was already stored in the cache.
	 *
	 * @var int
	 */
	public $cache_hits = 0;

	/**
	 * Amount of times the cache did not have the request in cache.
	 *
	 * @var int
	 */
	public $cache_misses = 0;

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		log_message('info', 'CI_Registry Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Method for determining whether a key is valid.
	 *
	 * @param 	mixed 	$key 	Cache key to check for validity.
	 * @return 	bool 	True if valid, else false.
	 */
	protected function is_valid_key($key)
	{
		return (is_int($key) OR (is_string($key) && '' !== trim($key)));
	}

	// --------------------------------------------------------------------

	/**
	 * Method for determining whether a key exists in the cache.
	 *
	 * @param 	mixed 	$key 	Cache key to check for existence.
	 * @param 	string 	$group 	Cache group to check existence of key in.
	 * @return 	bool 	True if the cache key exists, else false.
	 */
	protected function exists($key, $group)
	{
		if (isset($group, $this->cache[$group]))
		{
			return (isset($key, $this->cache[$group][$key]) OR array_key_exists($key, $this->cache[$group]));
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Toggles suspend state if a boolean is provided, otherwise returns it.
	 *
	 * @param 	bool 	$suspend 	Optional.
	 * @return 	bool 	The current suspend state.
	 */
	public function suspend($state = null)
	{
		is_bool($state) && $this->suspend = $state;
		return $this->suspend;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds data to the cache if it doesn't already exist.
	 *
	 * @param 	mixed 	$key 	What to call the contents in the cache.
	 * @param 	mixed 	$data 	The contents to store in the cache.
	 * @param 	string 	$group 	Where to group the cache contents (optional).
	 * @return 	bool 	True on success, else false.
	 */
	public function add($key, $data, $group = 'default')
	{
		if ($this->suspend() OR ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ($this->exists($key, $group))
		{
			return false;
		}

		return $this->set($key, $data, $group);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds multiple values to the cache in one go.
	 *
	 * @param 	array 	$data 	Array of keys to values to be added.
	 * @param 	string 	$group 	Where the cache contents are grouped (optional)
	 * @return 	bool[]
	 */
	public function add_multiple(array $data, $group = 'default')
	{
		$values = array();

		foreach ($data as $key => $val)
		{
			$values[$key] = $this->add($key, $val, $group);
		}

		return $values;
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces the contents in the cache if it exists.
	 *
	 * @param 	mixed 	$key 	What the cache is called.
	 * @param 	mixed 	$data 	The contents to store in the cache.
	 * @param 	string 	$group 	Where the cache content was stored (optional).
	 * @return 	bool 	True if contents were replaced, else false.
	 */
	public function replace($key, $data, $group = 'default')
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ( ! $this->exists($key, $group))
		{
			return false;
		}

		return $this->set($key, $data, $group);
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the data contents into the cache.
	 *
	 * @param 	mixed 	$key 	What to call the contents in the cache.
	 * @param 	mixed 	$data 	The contents to store in the cache.
	 * @param 	string 	$group 	Where to group teh cache contents (optional).
	 * @return 	bool 	True if contents were cached, else false.
	 */
	public function set($key, $data, $group = 'default')
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		is_object($data) && $data = clone $data;

		$this->cache[$group][$key] = $data;
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets multiple values to the cache.
	 *
	 * @param 	array 	$data 	Array of key to value to be set.
	 * @param 	string 	$group 	Where to group teh cache contents (optional).
	 * @return 	bool[]
	 */
	public function set_multiple(array $data, $group = 'default')
	{
		$values = array();

		foreach ($data as $key => $val)
		{
			$values[$key] = $this->set($key, $val, $group);
		}

		return $values;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves the cache content.
	 *
	 * @param 	mixed 	$key
	 * @param 	string 	$group
	 * @param 	bool 	$found
	 * @return 	mixed
	 */
	public function get($key, $group = 'default', &$found = null)
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ( ! $this->exists($key, $group))
		{
			$found = false;
			$this->cache_misses++;
			return false;
		}

		$found = true;
		$this->cache_hits++;
		return is_object($this->cache[$group][$key]) ? clone $this->cache[$group][$key] : $this->cache[$group][$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves multiple values from the cache on one call.
	 *
	 * @param 	array 	$keys
	 * @param 	string 	$group
	 * @return 	array
	 */
	public function get_multiple(array $keys, $group = 'default')
	{
		$values = array();

		foreach ($keys as $key)
		{
			$values[$key] = $this->get($key, $group);
		}

		return $values;
	}

	// --------------------------------------------------------------------

	/**
	 * Removes the content of a cache by key.
	 *
	 * @param 	mixed 	$key
	 * @param 	string 	$group
	 * @return 	bool 	True on success, else false.
	 */
	public function delete($key, $group = 'default')
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ( ! $this->exists($key, $group))
		{
			return false;
		}

		unset($this->cache[$group][$key]);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes multiple values from cache in on call.
	 *
	 * @param 	array 	$keys
	 * @param 	string 	$group
	 * @return 	bool[]
	 */
	public function delete_multiple(array $keys, $group = 'default')
	{
		$values = array();

		foreach ($keys as $key)
		{
			$values[$key] = $this->delete($key, $group);
		}

		return $values;
	}

	// --------------------------------------------------------------------

	/**
	 * Increments numeric cache item's value.
	 *
	 * @param 	mixed 	$key
	 * @param 	int 	$offset
	 * @param 	string 	$group
	 * @return 	mixed 	Integer if successful, else false.
	 */
	public function incr($key, $offset = 1, $group = 'default')
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ( ! $this->exists($key, $group))
		{
			return false;
		}

		is_numeric($this->cache[$group][$key]) OR $this->cache[$group][$key] = 0;
		$this->cache[$group][$key] += (int) $offset;

		(0 > $this->cache[$group][$key]) && $this->cache[$group][$key] = 0;
		return $this->cache[$group][$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Decrements numeric cache item's value.
	 *
	 * @param 	mixed 	$key
	 * @param 	int 	$offset
	 * @param 	string 	$group
	 * @return 	mixed 	Integer if successful, else false.
	 */
	public function decr($key, $offset = 1, $group = 'default')
	{
		if ( ! $this->is_valid_key($key))
		{
			return false;
		}

		empty($group) && $group = 'default';

		if ( ! $this->exists($key, $group))
		{
			return false;
		}

		is_numeric($this->cache[$group][$key]) OR $this->cache[$group][$key] = 0;
		$this->cache[$group][$key] -= (int) $offset;

		(0 > $this->cache[$group][$key]) && $this->cache[$group][$key] = 0;
		return $this->cache[$group][$key];
	}

	// --------------------------------------------------------------------

	/**
	 * Clears all cache and all data.
	 *
	 * @return 	bool 	Always true.
	 */
	public function flush()
	{
		$this->cache = array();
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Remove all cache in the given group.
	 *
	 * @param 	string 	$group
	 * @return 	bool 	True if group exists and flushed, else false.
	 */
	public function flush_group($group)
	{
		if (isset($group, $this->cache[$group]))
		{
			unset($this->cache[$group]);
			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Echoes or returns the stats of caching.
	 *
	 * @return 	void
	 */
	public function stats()
	{
		$output = '<p>';
		$output .= "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
		$output .= "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
		$output .= '</p>';

		$output .= '<ul>';
		foreach ($this->cache as $group => $cache)
		{
			$output .= '<li><strong>Group</strong>: ';
			$output .= $group.' - (';
			$output .= number_format(strlen(serialize($cache)) / KB_IN_BYTES, 2);
			$output .= 'k)</li>';
		}
		$output .= '</ul>';
		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all cached objects.
	 *
	 * @return 	array
	 */
	public function cache($group = null)
	{
		return empty($group) ? $this->cache : (isset($this->cache[$group]) ? $this->cache[$group] : null);
	}

}
