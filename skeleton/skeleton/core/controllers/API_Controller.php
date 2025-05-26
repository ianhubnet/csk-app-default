<?php
defined('BASEPATH') OR die;

/**
 * API_Controller Class
 *
 * Handles API requests.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 */
class API_Controller extends Response_Controller
{
	/**
	 * Class constructor.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		// Make sure the request is intended for API.
		if ( ! is_api())
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

	// --------------------------------------------------------------------

	/**
	 * Retrieves and decodes JSON input from the raw HTTP request body.
	 *
	 * This method reads the raw input stream (`php://input`), trims any
	 * whitespace, and decodes the JSON payload into an associative array.
	 * It is intended for use in API controllers that expect JSON input
	 * (e.g., application/json content type).
	 *
	 * @return array The decoded JSON data as an associative array.
	 *               Returns an empty array if the input is empty or invalid.
	 */
	protected function get_json_input(): array
	{
		return ($input = file_get_contents('php://input')) ? json_decode(trim($input), true) : array();
	}

}
