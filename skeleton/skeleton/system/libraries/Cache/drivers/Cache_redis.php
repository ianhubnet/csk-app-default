<?php
defined('BASEPATH') OR die;

/**
 * CodeIgniter Redis Caching Class
 *
 * @package    CodeIgniter
 * @subpackage Libraries
 * @category   Core
 * @author     Anton Lindqvist <anton@qvister.se>
 * @link
 */
class CI_Cache_redis extends CI_Driver
{
	/**
	 * Default config
	 *
	 * @static
	 * @var	array
	 */
	protected static $_default_config = array(
		'host'     => '127.0.0.1',
		'password' => null,
		'port'     => 6379,
		'timeout'  => 0.0,
		'database' => 0
	);

	/**
	 * Redis connection
	 *
	 * @var Redis
	 */
	protected $_redis;

	/**
	 * del()/delete() method name depending on phpRedis version
	 *
	 * @var string
	 */
	protected static $_delete_name;

	/**
	 * sRem()/sRemove() method name depending on phpRedis version
	 *
	 * @var string
	 */
	protected static $_sRemove_name;

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Setup Redis
	 *
	 * Loads Redis config file if present. Will halt execution
	 * if a Redis connection can't be established.
	 *
	 * @return  void
	 * @throws  RedisException
	 * @see     Redis::connect()
	 */
	public function __construct()
	{
		if ( ! $this->is_supported())
		{
			log_message('error', 'Cache: Failed to create Redis object; extension not loaded?');
			return;
		}

		if ( ! isset(static::$_delete_name, static::$_sRemove_name))
		{
			if (version_compare(phpversion('redis'), '5', '>='))
			{
				static::$_delete_name  = 'del';
				static::$_sRemove_name = 'sRem';
			}
			else
			{
				static::$_delete_name  = 'delete';
				static::$_sRemove_name = 'sRemove';
			}
		}

		$CI =& get_instance();

		if ($CI->config->load('redis', true, true))
		{
			$config = array_merge(self::$_default_config, $CI->config->item('redis'));
		}
		else
		{
			$config = self::$_default_config;
		}

		$this->_redis = new Redis();

		// The following calls used to be wrapped in a try ... catch
		// and just log an error, but that only causes more errors later.
		if ( ! $this->_redis->connect($config['host'], ($config['host'][0] === '/' ? 0 : $config['port']), $config['timeout']))
		{
			log_message('error', 'Cache: Redis connection failed. Check your configuration.');
		}

		if (isset($config['password']) && ! $this->_redis->auth($config['password']))
		{
			log_message('error', 'Cache: Redis authentication failed.');
		}

		if (isset($config['database']) && $config['database'] > 0 && ! $this->_redis->select($config['database']))
		{
			log_message('error', 'Cache: Redis select database failed.');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Get cache
	 *
	 * @param   string  $key    Cache ID
	 * @return  mixed
	 */
	public function get($key)
	{
		$data = $this->_redis->hMGet($key, array('__ci_type', '__ci_value'));

		if ($value !== false && $this->_redis->sIsMember('_ci_redis_serialized', $key))
		{
			return false;
		}

		switch ($data['__ci_type'])
		{
			case 'array':
			case 'object':
				return unserialize($data['__ci_value']);
			case 'boolean':
			case 'integer':
			case 'double': // Yes, 'double' is returned and NOT 'float'
			case 'string':
			case 'null':
				return settype($data['__ci_value'], $data['__ci_type']) ? $data['__ci_value'] : false;
			case 'resource':
			default:
				return false;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Save cache
	 *
	 * @param   string  $id Cache ID
	 * @param   mixed   $data   Data to save
	 * @param   int $ttl    Time to live in seconds
	 * @param   bool    $raw    Whether to store the raw value (unused)
	 * @return  bool    true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60, $raw = false)
	{
		switch ($data_type = gettype($data))
		{
			case 'array':
			case 'object':
				$data = serialize($data);
				break;
			case 'boolean':
			case 'integer':
			case 'double': // Yes, 'double' is returned and NOT 'float'
			case 'string':
			case 'null':
				break;
			case 'resource':
			default:
				return false;
		}

		if ( ! $this->_redis->hMSet($id, array('__ci_type' => $data_type, '__ci_value' => $data)))
		{
			return false;
		}
		else
		{
			$this->_redis->{static::$_sRemove_name}('_ci_redis_serialized', $id);
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Delete from cache
	 *
	 * @param   string  $key    Cache key
	 * @return  bool
	 */
	public function delete($key)
	{
		if ($this->_redis->{static::$_delete_name}($key) !== 1)
		{
			return false;
		}

		$this->_redis->{static::$_sRemove_name}('_ci_redis_serialized', $key);

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Increment a raw value
	 *
	 * @param   string  $id Cache ID
	 * @param   int $offset Step/value to add
	 * @return  mixed   New value on success or false on failure
	 */
	public function increment($id, $offset = 1)
	{
		return $this->_redis->incrBy($id, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Decrement a raw value
	 *
	 * @param   string  $id Cache ID
	 * @param   int $offset Step/value to reduce by
	 * @return  mixed   New value on success or false on failure
	 */
	public function decrement($id, $offset = 1)
	{
		return $this->_redis->decrBy($id, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Clean cache
	 *
	 * @return  bool
	 * @see     Redis::flushDB()
	 */
	public function clean()
	{
		return $this->_redis->flushDB();
	}

	// --------------------------------------------------------------------

	/**
	 * Get cache driver info
	 *
	 * @param   string  $type   Not supported in Redis.
	 *              Only included in order to offer a
	 *              consistent cache API.
	 * @return  array
	 * @see     Redis::info()
	 */
	public function cache_info($type = null)
	{
		return $this->_redis->info();
	}

	// --------------------------------------------------------------------

	/**
	 * Get cache metadata
	 *
	 * @param   string  $key    Cache key
	 * @return  array
	 */
	public function get_metadata($key)
	{
		$value = $this->get($key);

		if ($value !== false)
		{
			return array(
				'expire' => TIME + $this->_redis->ttl($key),
				'data'   => $value
			);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Check if Redis driver is supported
	 *
	 * @return  bool
	 */
	public function is_supported()
	{
		return extension_loaded('redis');
	}

	// --------------------------------------------------------------------

	/**
	 * Class destructor
	 *
	 * Closes the connection to Redis if present.
	 *
	 * @return  void
	 */
	public function __destruct()
	{
		if ($this->_redis)
		{
			$this->_redis->close();
		}
	}
}
