<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Third Party
 * @category 	Cache_Dir
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

/**
 * Cache_Dir
 *
 * This class and its helpers can used to store anything in the global scope
 * in order to reduce DB access for example. The cache object stores all of
 * the cache data to memory and makes the cache contents available by using
 * a key, which is used to name and retrieve the cache contents.
 *
 * @since 2.16
 */
#[AllowDynamicProperties]
final class Cache_Dir
{
	/**
	 * Holds the path to cache folder
	 * @var string
	 */
	private $_path;

	/**
	 * class constructor
	 * @param 	void
	 */
	public function __construct($path)
	{
		is_writable($path) OR @trigger_error("Path Not Writeable.");
		is_dir($path) OR @trigger_error("Path Not a Directory");
		$this->_path = $path;
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to retrieve a cache file using the give key.
	 * @param 	$key 	sring
	 * @return 	array of whatever was stored, else fALSE
	 */
	public function get($key)
	{
		if (false === ($file = normalize_path($this->_path.'/'.$key, true)))
		{
			return false;
		}

		$data = file_get_contents($file);
		str_starts_with($data, "##") && $data = gzinflate(substr($data, 2));
		return json_decode($data, true, JSON_PRETTY_PRINT);
	}

	// --------------------------------------------------------------------

	/**
	 * Stores cached info into a file.
	 * @param 	$key 	 	the key used to store the file.
	 * @param 	$value 	 	the value to store.
	 * @param 	$compress 	whether to compress the file (gzdeflate)
	 * @return 	void
	 */
	public function set($key, $value, $compress = false)
	{
		$data = json_encode($value);
		$compress && $data = gzdeflate($data, 9) && $data = "##" . $data;
		return file_put_contents(normalize_path($this->_path.'/'.$key), $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete an old cache directory file.
	 * @param 	string 	$key
	 * @return 	bool
	 */
	public function delete($key)
	{
		return (false !== @unlink(normalize_path($this->_path.'/'.$key)));
	}

	// --------------------------------------------------------------------

	/**
	 * Dummy path builder since we don't have access to $_path
	 * @param 	$uri 	the uri to append.
	 * @return 	string 	the full path
	 */
	public function path_to($uri = '')
	{
		return normalize_path($this->_path.'/'.$uri);
	}
}
