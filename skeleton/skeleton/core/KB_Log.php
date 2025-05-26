<?php
defined('BASEPATH') OR die;

/**
 * KB_Log Class
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.93
 */
class KB_Log extends CI_Log
{
	/**
	 * Reference to the CI_Log singleton.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Predefined logging levels
	 *
	 * @var array
	 */
	protected $_levels = array('CRITICAL' => 0, 'ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4);

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		if (self::$instance)
		{
			throw new Exception("CI_Log: Use get_instance() instead of new CI_Log()");
		}

		self::$instance = $this;

		parent::__construct();

		// Disable log completely if set to -1.
		$this->_enabled && $this->_enabled = ($this->_threshold >= 0 OR ! empty($this->_threshold_array));
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI_Log singleton.
	 *
	 * @static
	 * @return 	object
	 */
	public static function get_instance()
	{
		isset(self::$instance) OR self::$instance = new self();
		return self::$instance;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns '$_enabled' property.
	 *
	 * @return 	bool
	 */
	public function is_enabled()
	{
		return $this->_enabled;
	}

	// --------------------------------------------------------------------

	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @param   string  $level 		The error level: 'error', 'debug' or 'info'
	 * @param   string  $msg 		The error message
	 * @param   string  $traceback 	Debug backtrack
	 * @return  bool
	 */
	public function write_log($level, $msg, $traceback = null)
	{
		if ($this->_enabled === false)
		{
			return false;
		}

		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return false;
		}

		$filepath = $this->_log_path.$this->_log_filename;
		$message = '';

		if ( ! is_file($filepath))
		{
			$newfile = true;
			// Only add protection to php files
			if (substr($this->_log_filename, -3, 3) === 'php')
			{
				$message .= "<?php defined('BASEPATH') OR die; ?>\n\n";
			}
		}

		if ( ! $fp = @fopen($filepath, 'ab'))
		{
			return false;
		}

		flock($fp, LOCK_EX);

		// Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
		if (strpos($this->_date_fmt, 'u') !== false)
		{
			$microtime_full = microtime(true);
			$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
			$date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
			$date = $date->format($this->_date_fmt);
		}
		else
		{
			$date = date($this->_date_fmt);
		}

		if (($level === 'ERROR' OR $level === 'CRITICAL') && defined('KB_REQUEST_ID'))
		{
			$msg .= ' | Request ID: '.KB_REQUEST_ID;
		}

		$message .= $this->_format_line($level, $date, $msg, $traceback);

		for ($written = 0, $length = self::strlen($message); $written < $length; $written += $result)
		{
			if (($result = fwrite($fp, self::substr($message, $written))) === false)
			{
				break;
			}
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		if (isset($newfile) && $newfile === true)
		{
			chmod($filepath, $this->_file_permissions);
		}

		return is_int($result);
	}

	// --------------------------------------------------------------------

	/**
	 * Format the log line.
	 *
	 * This is for extensibility of log formatting
	 * If you want to change the log format, extend the CI_Log class and override this method
	 *
	 * @param   string  $level 		The error level
	 * @param   string  $date 		Formatted date string
	 * @param   string  $message 	The log message
	 * @param   string  $traceback 	Traceback file and line.
	 * @return  string  Formatted log line with a new line character at the end
	 */
	protected function _format_line($level, $date, $message, $traceback = null)
	{
		if (empty($traceback))
		{
			return sprintf('[%s] [%s] --> %s', $date, $level, $message).PHP_EOL;
		}

		return sprintf('[%s] [%s] --> %s (%s:%s)', $date, $level, $message, $traceback['file'], $traceback['line']).PHP_EOL;
	}

}
