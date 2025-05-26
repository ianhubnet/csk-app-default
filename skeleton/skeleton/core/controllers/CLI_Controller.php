<?php
defined('BASEPATH') OR die;

/**
 * CLI_Controller Class
 *
 * Handles CLI requests.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.88
 */
class CLI_Controller extends CI_Controller
{
	/**
	 * Datetime format.
	 * @var string
	 */
	protected $datetime_format = 'Y-m-d H:i';

	/**
	 * Class constructor.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		// Make sure the request is a CLI request.
		if ( ! is_cli())
		{
			log_message('critical', 'Direct access to CLI controller attempted: '.ip_address());
			exit(EXIT_ERROR);
		}

		// Call parent constructor.
		parent::__construct();

		// Profiler should always be disabled.
		$this->output->enable_profiler(false);

		// Set datetime format.
		$this->datetime_format = $this->config->item('datetime_format', null, $this->datetime_format);
	}

	// --------------------------------------------------------------------

	/**
	 * Overrides parent's "_remap" method.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		if ( ! method_exists($this, $method))
		{
			exit(EXIT_ERROR);
		}

		return call_user_func_array(array($this, $method), $params);
	}

	// --------------------------------------------------------------------

	/**
	 * Just a place holder method.
	 *
	 * @return 	void
	 */
	public function index()
	{
		exit(EXIT_ERROR);
	}

}
