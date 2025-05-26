<?php
defined('BASEPATH') OR die;

/**
 * AJAX_Controller Class
 *
 * Handles AJAX requests.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.88
 */
class AJAX_Controller extends Response_Controller
{
	/**
	 * Class constructor.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		// Make sure the request is an AJAX request.
		if ( ! is_ajax())
		{
			exit(EXIT_ERROR);
		}

		// Call parent constructor.
		parent::__construct();

		// Profiler should always be disabled.
		$this->output->enable_profiler(false);
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
