<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_backups Class
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.112
 */
final class Kbcore_backups extends KB_Driver
{
	/**
	 * Folder that holds backup files.
	 * @var 	string
	 */
	protected $backup_path;

	/**
	 * Patters used for reading files.
	 * @var 	array
	 */
	protected $patterns = array(
		'/^[A-Za-z0-9\_-]{22}.(zip|gzip|sql|txt)$/' => array('locked' => false),
		'/^[A-Za-z0-9\_-]{22}_locked.(zip|gzip|sql|txt)$/' => array('locked' => true),
		'/^[0-9]{10}.(zip|gzip|sql|txt)$/' => array('locked' => false),
		'/^[0-9]{10}_locked.(zip|gzip|sql|txt)$/' => array('locked' => true)
	);

	/**
	 * Holds error string and can be used for display.
	 * @var 	string
	 */
	public $message = '';

	/**
	 * Config array used for loading database.
	 * @var 	arrau
	 */
	protected $_config_names = array(
		'hostname',
		'username',
		'password',
		'database',
		'dbdriver',
		'dbprefix',
		'pconnect',
		'db_debug',
		'cache_on',
		'cachedir',
		'char_set',
		'dbcollat',
		'swap_pre',
		'stricton'
	);

	/**
	 * Instance of Cache_Dir object.
	 * @var 	object
	 */
	private $_cache;

	/**
	 * Holds a unique key used for cached paths.
	 * @var 	string
	 */
	private $_cache_key;

	/**
	 * Array of found backup files.
	 * @var 	array
	 */
	private $_backup_files;

	// --------------------------------------------------------------------

	/**
	 * Class initialize.
	 *
	 * @return 	void
	 */
	public function for_dashboard($is_homepage = false)
	{
		if ($is_homepage OR ! $this->ci->router->is_dashboard('backups'))
		{
			return;
		}

		// Real init.
		$this->_init();

		// We make sure to load database if not loaded.
		if ( ! isset($this->ci->db))
		{
			$config = array();
			foreach ($this->_config_names as $key)
			{
				$config[$key] = ($key === 'dbdriver') ? 'mysqli' : $this->ci->db->$key;
			}

			$this->ci->db = $this->ci->load->database($config, true);
		}

		// Load what we need
		$this->ci->load->dbutil();
		$this->ci->load->helper('file');
	}

	// --------------------------------------------------------------------

	/**
	 * This method had to be added because of the scenario of using "purge"
	 * method but stuff are missing.
	 *
	 * @return 	void
	 */
	private function _init()
	{
		if ( ! isset($this->backup_path))
		{
			// Prepare our backup path.
			$this->backup_path = normalize_path(APPPATH.'backups/database');

			// Start dir cache
			$this->_cache =& get_dir_cache();
			isset($this->_cache_key) OR $this->_cache_key = sha1($this->_cache->path_to());
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Lists all available backup files.
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	public function list()
	{
		// Already set?
		if (isset($this->_backup_files))
		{
			return $this->_backup_files;
		}

		// Check cache?
		$from_cache = $this->_cache->get($this->_cache_key);
		if ($from_cache && $from_cache['time'] == filemtime($this->_cache->path_to('')))
		{
			$this->_backup_files = $from_cache['files'];
			return $this->_backup_files;
		}

		// Nothing so far, let's read the directory.
		$this->_backup_files = array();
		is_dir($this->backup_path) OR @mkdir($this->backup_path, 0777, true);

		if ($dir = opendir($this->backup_path))
		{
			while (false !== ($file_name = readdir($dir)))
			{
				if ($file_name !== '.' && $file_name !== '..' && $file_name !== '.gitkeep')
				{
					if (false !== ($info = $this->check_file($file_name)))
					{
						$this->_backup_files[] = array(
							'name'     => str_replace('_locked', '', $file_name),
							'filename' => $file_name,
							'locked'   => $info['locked'],
							'ext'      => pathinfo($file_name, PATHINFO_EXTENSION),
							'size'     => filesize($this->backup_path.'/'.$file_name),
							'time'     => filemtime($this->backup_path.'/'.$file_name)
						);

					}
				}
			}

			closedir($dir);
		}

		foreach ($this->_backup_files as $key => $row)
		{
			$files[$key] = $row['time'];
		}

		empty($this->_backup_files) OR array_multisort($files, SORT_DESC, $this->_backup_files);

		// Cache it
		$this->_cache->set($this->_cache_key, array(
			'files' => $this->_backup_files,
			'time' => filemtime($this->_cache->path_to())
		));

		return $this->_backup_files;
	}

	// --------------------------------------------------------------------

	/**
	 * Creates a backup file.
	 * @param 	string 	$extension 	the extension of the backup file.
	 * @param 	array 	$config 	array of DBUtil config.
	 * @return 	bool
	 */
	public function create($extension = 'sql', $config = array())
	{
		// The path isn't writable?
		if ( ! is_really_writable($this->backup_path))
		{
			$this->message = is_cli()
				? sprintf('Unable to create backup file. Make sure the folder "%s" is writable.', $this->backup_path)
				: $this->ci->lang->sline('admin_database_backup_create_error', $this->backup_path);

			return false;
		}

		// Generate the file name
		$file_name = 'backup_'.date('Ymd_His');

		// Locked?
		if (isset($config['locked']))
		{
			$file_name .= '_locked';
			unset($config['locked']);
		}

		// Load dbutil and pass config.
		$config = array_merge(array('format' => ($extension === 'sql') ? 'txt' : $extension), $config);
		$this->ci->dbutil->optimize_database(); // Optimize database.
		$backup = $this->ci->dbutil->backup($config);

		// Write file.
		$file_path = $this->backup_path.'/'.$file_name.'.'.$extension;
		if (false !== write_file($file_path, $backup))
		{
			// Clear cached paths.
			$this->_cache->delete($this->_cache_key);
			$this->message = $this->ci->lang->sline(
				'admin_database_backup_create_success',
				basename(str_replace('_locked', '', $file_path))
			);
			return true;
		}

		// Something went wrong?
		$this->message = is_cli()
			? sprintf('Unable to create backup file. Make sure the folder "%s" is writable.', $this->backup_path)
			: $this->ci->lang->sline('admin_database_backup_create_error', $this->backup_path);

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes the give backup file(s).
	 * @access 	public
	 * @param 	string 	$file
	 * @param 	bool 	$bulk 	for deleting multiple files.
	 * @return 	bool
	 */
	public function delete($file, $bulk = false)
	{
		// Bulk deleting files?
		if (true === $bulk)
		{
			$file = explode(',', $file);

			foreach ($file as $f)
			{
				if (true !== $this->delete($f))
				{
					return false;
				}
			}

			return true;
		}

		// Ignore locked files.
		if (true === $this->check_file($file, 'locked'))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_locked_error');
			return false;
		}

		$file_path = $this->backup_path.'/'.$file;
		// No file?
		if ( ! is_file($file_path))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_missing_error');
			return false;
		}

		// Couldn't be deleted?
		if (true !== @unlink($file_path))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_delete_error');
			return false;
		}

		$this->_cache->delete($this->_cache_key);
		$this->message = $this->ci->lang->line('admin_database_backup_delete_success');
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Locks the give backup file(s).
	 * @access 	public
	 * @param 	string 	$file
	 * @param 	bool 	$bulk
	 * @return 	bool
	 */
	public function lock($file, $bulk = false)
	{
		if (true === $bulk)
		{
			$file = explode(',', $file);

			foreach ($file as $f)
			{
				if (true !== $this->lock($f))
				{
					return false;
				}
			}

			return true;
		}

		if (false !== $this->check_file($file, 'locked'))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_lock_error');
			return false;
		}

		$file_path = $this->backup_path.'/'.$file;
		$path_info = pathinfo($file_path);
		$new_file_path = implode('', array(
			$path_info['dirname'].'/',
			$path_info['filename'],
			'_locked.',
			$path_info['extension'],
		));

		if (true !== @rename($file_path, $new_file_path))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_lock_error');
			return false;
		}

		$this->_cache->delete($this->_cache_key);
		$this->message = $this->ci->lang->line('admin_database_backup_lock_success');
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Unlocks the give backup file(s).
	 * @access 	public
	 * @param 	string 	$file
	 * @param 	bool 	$bulk
	 * @return 	bool
	 */
	public function unlock($file, $bulk = false)
	{
		if (true === $bulk)
		{
			$file = explode(',', $file);

			foreach ($file as $f)
			{
				if (true !== $this->unlock($f))
				{
					return false;
				}
			}

			return true;
		}

		if (true !== $this->check_file($file, 'locked'))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_lock_error');
			return false;
		}

		$file_path = $this->backup_path.'/'.$file;
		$path_info = pathinfo($file_path);
		$new_file_path = implode('', array(
			$path_info['dirname'].'/',
			str_replace('_locked', '.', $path_info['filename']),
			$path_info['extension'],
		));

		if (true !== @rename($file_path, $new_file_path))
		{
			$this->message = $this->ci->lang->line('admin_database_backup_unlock_error');
			return false;
		}

		$this->_cache->delete($this->_cache_key);
		$this->message = $this->ci->lang->line('admin_database_backup_unlock_success');
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Cleans old backup files.
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function purge()
	{
		// Real init.
		isset($this->backup_path) OR $this->_init();

		// List all files and calculate total file sizes.
		$size = 0;
		foreach ($this->list() as $file)
		{
			$size += $file['size'];
		}

		// Maxsize can be define in config (Default: 10mb)
		$max_size = ($this->ci->config->item('backup_maxsize', null, 10) * 1024 * 1024);

		// start deleting if overload more then max size
		if ($size > $max_size)
		{
			// Time limit (Default: 1 month)
			$time = TIME - (MONTH_IN_SECONDS * $this->ci->config->item('backup_term', null, 3));

			$delete_size = $size - $max_size;
			$deleteed_on_size = 0;
			$file_count = 0;

			do {
				// Grab oldest file.
				$file_to_delete = $this->oldest_file();

				// No more files to delete?
				// OR overrun max date?
				if ($file_to_delete == false OR $file_to_delete['time'] > $time)
					break;

				$file_count++;
				$deleteed_on_size += $file_to_delete['size'];
				@unlink($this->backup_path.'/'.$file_to_delete['filename']);
			} while ($deleteed_on_size < $delete_size);

			// Only set the message if not a cli request.
			if (is_cli())
			{
				log_message('debug', "[CRON] Backup Purge: Complete - {$file_count} backup files deleted.");
				echo sprintf('%s - [CRON] Backup Purge: Complete - %d backup files deleted.', date('Y-m-d H:i', TIME), $file_count).PHP_EOL;
				return $file_count;
			}

			$this->message = $this->ci->lang->sline('admin_database_backup_clean_success', $file_count, $deleteed_on_size / 1024);
			return $file_count;
		}

		$this->_cache->delete($this->_cache_key);

		if (is_cli())
		{
			log_message('debug', "[CRON] Backup Purge: Complete - no backup files deleted.");
			echo sprintf('%s - [CRON] Backup Purge: Complete - no backup files deleted.', date('Y-m-d H:i', TIME)).PHP_EOL;
			return 0;
		}

		$this->message = $this->ci->lang->line('admin_database_backup_clean_error');
		return 0;
	}

	// --------------------------------------------------------------------

	/**
	 * Simple check for backup files.
	 * @access 	private
	 * @param 	string 	$file 	the file's name
	 * @param 	string 	$param 	parameters key to check
	 * @return 	bool
	 */
	private function check_file($file, $param = null)
	{
		foreach ($this->patterns as $pattern => $params)
		{
			if (preg_match($pattern, $file) === 1)
			{
				return is_null($param) ? $params : (isset($params[$param]) ? $params[$param] : false);
			}
		}
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the oldest file from the list of files.
	 * @access 	public
	 * @param 	none
	 * @return 	string
	 */
	private function oldest_file()
	{
		// getting only files that allow to delete by pattern
		$files = array();
		foreach ($this->list() as $file)
		{
			if (false === $this->check_file($file['filename'], 'locked'))
			{
				$files[] = $file;
			}
		}

		if ( ! count($files) > 0)
		{
			return false;
		}

		$min_key = 0;
		$min_time = $files[0]['time'];

		$count_files = count($files);
		for ($i = 1; $i < $count_files; $i++)
		{
			if ($min_time > $files[$i]['time'])
			{
				$min_time = $files[$i]['time'];
				$min_key  = $i;
			}
		}

		return $files[$min_key];
	}

}
