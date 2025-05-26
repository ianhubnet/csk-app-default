<?php
defined('BASEPATH') OR die;

/**
 * CI_Events
 *
 * A simple and efficient events system for CodeIgniter or other applications.
 * Supports registering callbacks, triggering events, and managing event listeners.
 *
 * @package     CodeIgniter Skeleton
 * @subpackage  Core
 * @category    Events
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 */
class CI_Events
{
	/**
	 * Supported return types for triggered events.
	 */
	const RETURN_TYPE_JSON       = 'json';
	const RETURN_TYPE_NONE       = 'none';
	const RETURN_TYPE_SERIALIZED = 'serialized';
	const RETURN_TYPE_STRING     = 'string';
	const RETURN_TYPE_ARRAY      = 'array';

	/**
	 * List of valid return types.
	 *
	 * @var array
	 */
	private array $_valid_return_types = array(
		self::RETURN_TYPE_JSON,
		self::RETURN_TYPE_NONE,
		self::RETURN_TYPE_SERIALIZED,
		self::RETURN_TYPE_STRING,
		self::RETURN_TYPE_ARRAY
	);

	/**
	 * Registered event listeners.
	 *
	 * @var array
	 */
	private array $_events = array();

	/**
	 * Cached event checks for optimization.
	 *
	 * @var array
	 */
	private array $_event_cache = array();

	/**
	 * Lock file resources.
	 *
	 * @var resource|null
	 */
	private $_lock_file;

	/**
	 * Path to the lock file.
	 *
	 * @var string
	 */
	private $_lock_file_path;

	// --------------------------------------------------------------------

	/**
	 * Constructor: initializes the lock system.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->_lock_file_path = APPPATH.'cache/ci_events.lock';

		// Ensure the lock file is created and writable
		is_file($this->_lock_file_path) OR touch($this->_lock_file_path);

		if (($this->_lock_file = fopen($this->_lock_file_path, 'c')) === false)
		{
			throw new \RuntimeException('CI_Events: Unable to open lock file for writing.');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Destructor: Ensures lock file is released and closed.
	 *
	 * @return 	void
	 */
	public function __destruct()
	{
		if (is_file($this->_lock_file_path) && is_resource($this->_lock_file))
		{
			flock($this->_lock_file, LOCK_UN);
			fclose($this->_lock_file);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Registers a callback for a specific event (thread-safe).
	 *
	 * @param string   $event     The name of the event to listen for.
	 * @param callable $callback  The listener callback function.
	 * @param bool     $prepend   If true, the callback is prepended to the event listeners.
	 * @return bool    True if the callback was registered, false if it already exists.
	 */
	public function register(string $event, callable $callback, bool $prepend = false): bool
	{
		if ( ! $this->_acquire_lock())
		{
			return false;
		}

		try {
			if ( ! isset($this->_events[$event = $this->_format_event_name($event)]))
			{
				$this->_events[$event] = array();
			}

			if ( ! is_callable($callback) OR in_array($callback, $this->_events[$event], true))
			{
				return false;
			}
			elseif ($prepend)
			{
				array_unshift($this->_events[$event], $callback);
			}
			else
			{
				$this->_events[$event][] = $callback;
			}

			return true;
		} finally {
			$this->_release_lock();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Removes a specific callback or all callbacks for an event (thread-safe).
	 *
	 * @param string        $event    The event name.
	 * @param callable|null $callback The specific callback to remove. Null removes all.
	 * @return bool         True if any callbacks were removed, false otherwise.
	 */
	public function unregister(string $event, callable $callback = null): bool
	{
		if ( ! $this->_acquire_lock())
		{
			return false;
		}

		try {
			if ( ! isset($this->_events[$event = $this->_format_event_name($event)]))
			{
				return false;
			}
			elseif ($callback === null)
			{
				unset($this->_events[$event]);
				return true;
			}
			else
			{
				$this->_events[$event] = array_filter(
					$this->_events[$event],
					function ($listener) use ($callback) {
						return $listener !== $callback;
					}
				);

				return true;
			}
		} finally {
			$this->_release_lock();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Triggers an event, passing data to all registered callbacks (thread-safe).
	 *
	 * @param string $event        The event to trigger.
	 * @param mixed  $data         Data passed to each listener.
	 * @param string $return_type  The format of the return value (json, serialized, etc.).
	 * @param bool   $reversed     If true, callbacks are executed in reverse order.
	 * @return mixed               The return value from all callbacks, formatted based on return type.
	 */
	public function trigger(string $event, $data = '', string $return_type = self::RETURN_TYPE_ARRAY, bool $reversed = false)
	{
		if ( ! $this->_acquire_lock())
		{
			return false;
		}

		try {
			$calls = array();

			if ($this->has_events($event = $this->_format_event_name($event)))
			{
				$events = $reversed ? array_reverse($this->_events[$event]) : $this->_events[$event];

				foreach ($events as $callback)
				{
					$calls[] = $callback($data);
				}
			}

			return $this->_format_return($calls, $return_type);
		} finally {
			$this->_release_lock();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether an event has any registered listeners.
	 *
	 * @param string $event The event name to check.
	 * @return bool True if listeners are registered, false otherwise.
	 */
	public function has_events(string $event): bool
	{
		// Already checked?
		if (isset($this->_event_cache[$event = $this->_format_event_name($event)]))
		{
			return $this->_event_cache[$event];
		}
		// Lock to prevent concurrent updates
		elseif ($this->_acquire_lock())
		{
			try {
				$this->_event_cache[$event] = (isset($this->_events[$event]) && ! empty($this->_events[$event]));
			} finally {
				$this->_release_lock();
			}
		}

		return isset($this->_event_cache[$event]) ? $this->_event_cache[$event] : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Formats the event name to support namespacing.
	 *
	 * @param string $event The event name.
	 * @return string The formatted event name with namespace handling.
	 */
	private function _format_event_name(string $event): string
	{
		return strtolower($event);
	}

	// --------------------------------------------------------------------

	/**
	 * Formats the return value based on the specified type.
	 *
	 * @param array  $calls       Array of callback results.
	 * @param string $return_type The desired return format (json, serialized, etc.).
	 * @return mixed              The formatted return value.
	 */
	private function _format_return(array $calls, string $return_type = self::RETURN_TYPE_ARRAY)
	{
		in_array($return_type, $this->_valid_return_types) OR $return_type = self::RETURN_TYPE_ARRAY;

		switch ($return_type)
		{
			case 'json':
				return json_encode($calls);

			case 'none':
				return null;

			case 'serialized':
				return serialize($calls);

			case 'string':
				return implode('', $calls);

			case 'array':
			default:
				return $calls;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Acquires a lock to ensure thread-safe operations.
	 *
	 * @return bool True if lock acquired, false otherwise.
	 */
	private function _acquire_lock(): bool
	{
		if (is_resource($this->_lock_file))
		{
			if ( ! flock($this->_lock_file, LOCK_EX))
			{
				log_message('critical', 'Failed to acquire lock for CI_Events.');
				return false;
			}

			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Releases the lock.
	 *
	 * @return void
	 */
	private function _release_lock(): void
	{
		if (is_resource($this->_lock_file))
		{
			flock($this->_lock_file, LOCK_UN);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Prevents cloning of the class instance.
	 */
	public function __clone() {}

	// --------------------------------------------------------------------

	/**
	 * Prevents unserialization of the class instance.
	 */
	public function __wakeup() {}

}
