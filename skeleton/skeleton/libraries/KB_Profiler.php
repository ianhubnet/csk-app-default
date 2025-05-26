<?php
defined('BASEPATH') OR die;

/**
 * KB_Profile Class
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.20
 * @version 	1.0
 */
class KB_Profiler extends CI_Profiler
{
	/**
	 * Class constructor
	 *
	 * Initialize Profiler.
	 *
	 * @param 	array 	$config 	Parameters
	 */
	public function __construct($config = array())
	{
		$this->_available_sections = array(
			'benchmarks',
			'registry',
			'languages',
			'get',
			'memory_usage',
			'post',
			'uri_string',
			'controller_info',
			'queries',
			'http_headers',
			'session_data',
			'config'
		);

		parent::__construct($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Registry Profiler.
	 *
	 * @return 	array
	 */
	protected function _compile_registry()
	{
		$output = "\n\n"
			.'<fieldset id="ci_profiler_registry" style="border:1px solid #900;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
			."\n"
			.'<legend style="color:#f2711c;">&nbsp;&nbsp;Object&nbsp;Cache&nbsp;(Hits:&nbsp;<strong>'.$this->CI->registry->cache_hits
			.'</strong>&nbsp;-&nbsp;Misses:&nbsp;<strong>'.$this->CI->registry->cache_misses
			.'</strong>)</legend>'
			."\n\n\n<table style=\"width:100%;\">\n";

		foreach ($this->CI->registry->cache() as $group => $cache)
		{
			$group = ucwords(str_replace(array('_', '-'), ' ', $group));
			$output .= '<tr><td style="padding:5px;width:50%;color:#000;font-weight:bold;background-color:#ddd;">'
					.$group.'&nbsp;&nbsp;</td><td style="padding:5px;width:50%;color:#900;font-weight:normal;background-color:#ddd;">'
					.number_format(strlen(serialize($cache)) / KB_IN_BYTES, 2)." kb</td></tr>\n";
		}

		return $output."</table>\n</fieldset>";
	}

	// --------------------------------------------------------------------

	/**
	 * Language Profiler.
	 *
	 * @return 	array
	 */
	protected function _compile_languages()
	{
		$output = "\n\n"
			.'<fieldset id="ci_profiler_languages" style="border:1px solid #900;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee;">'
			."\n"
			.'<legend style="color:#e03997;">&nbsp;&nbsp;'.$this->CI->lang->sline('profiler_language_files', count($this->CI->lang->is_loaded))."&nbsp;&nbsp;</legend>\n"
			."\n\n\n<table style=\"width:100%;\">\n";

		foreach ($this->CI->lang->is_loaded as $file => $idiom)
		{
			$output .= '<tr><td style="padding:5px;width:50%;color:#000;font-weight:bold;background-color:#ddd;">'
					.$file.'&nbsp;&nbsp;</td><td style="padding:5px;width:50%;color:#900;font-weight:normal;background-color:#ddd;">'
					.$idiom."</td></tr>\n";
		}

		return $output."</table>\n</fieldset>";
	}
}
