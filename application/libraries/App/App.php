<?php

/**
 * CI_App
 *
 * Application-level driver library.
 *
 * Acts as the main entry point for application-specific logic and domain
 * drivers, fully isolated from the framework/core layer (CI_Hub).
 *
 * This class is intentionally lightweight by default and may remain unused
 * unless explicitly autoloaded by the application..
 *
 * @package     App\Libraries
 * @subpackage  Drivers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2025, Kader Bouyakoub
 */

final class CI_App extends CI_Driver_Library
{
	/**
	 * Reference to the CI super-object.
	 *
	 * Used to access loaders, hooks, config, and other shared services.
	 *
	 * @var CI_Controller
	 */
	protected $ci;

	/**
	 * List of allowed application drivers.
	 *
	 * This list is filterable to allow applications or modules to register
	 * their own drivers without modifying this class.
	 *
	 * @var array<string>
	 */
	protected $valid_drivers;

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * Initializes the application driver library and prepares the list
	 * of valid drivers. Logic here is not executed unless the library is
	 * explicitly (auto)loaded.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Cache the CI super-object for convenience and performance.
		$this->ci ??= CI_Controller::get_instance();

		/**
		 * Filter the list of application drivers.
		 *
		 * Applications are expected to register their drivers through
		 * the `app_drivers` filter when needed. However, default drivers
		 * can be added here and the filter is left to modules and plugins.
		 */
		$this->valid_drivers = $this->ci->hooks->filter(
			'app_drivers',
			[], // Add default drivers here.
			$this->ci,
			$this
		);

		// Skip runtime setup during installation.
		if (CI_INSTALL) {
			return;
		}

		// Allow the application to hook into runtime execution.
		$this->setup_runtime();
	}

	// --------------------------------------------------------------------

	/**
	 * Performs optional runtime setup.
	 *
	 * Intended for registering hooks, menus, listeners, or other
	 * application-specific behavior.
	 *
	 * This method is intentionally empty in the default distribution
	 * and should only be implemented when the application requires it.
	 *
	 * @return void
	 */
	private function setup_runtime()
	{
		// Intentionally left blank.
	}

}
