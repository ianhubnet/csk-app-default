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
 * @copyright   Copyright (c) 2018-present, Kader Bouyakoub
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
	protected $valid_drivers = [];

	/**
	 * List of application drivers to load during runtime boot.
	 *
	 * These must also be registered in $valid_drivers.
	 *
	 * @var string[]
	 */
	protected $autoload_drivers = [];

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
		$custom_drivers = $this->ci->hooks->filter('app_drivers', [], $this->ci, $this);
		if (!empty($custom_drivers)) {
			$this->valid_drivers = array_merge_unique(
				$this->valid_drivers,
				$custom_drivers
			);

			// Reset drivers map cache.
			$this->drivers_map = null;
		}

		// Skip runtime setup during installation.
		if (CI_INSTALL) {
			return;
		}

		// Allow the application to hook into runtime execution.
		$this->setup_runtime();

		/**
		 * Filter the list of application drivers to autoload.
		 *
		 * Applications, modules, or plugins may register drivers that should be
		 * eagerly loaded after runtime setup. Each autoloaded driver must also
		 * be registered through the `app_drivers` filter.
		 */
		$autoload_drivers = $this->ci->hooks->filter('app_autoload_drivers', [], $this->ci, $this);
		if (!empty($autoload_drivers)) {
			$this->autoload_drivers = array_merge_unique(
				$this->autoload_drivers,
				$autoload_drivers
			);
		}
		$this->load_drivers($this->autoload_drivers);
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
