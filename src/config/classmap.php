<?php

/**
 * Application Class Map
 *
 * This file returns an associative array of class names to their corresponding
 * file paths. It is used by `Autoloader` to resolve class files manually,
 * typically for libraries or legacy code not using PSR-4 or composer.
 *
 * @package    App\Config
 * @category   Autoloader
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2018-present, Kader Bouyakoub
 */

return [
	// ----------------------------------------------------------------
	// App Drivers
	// ----------------------------------------------------------------
	'CI_App' => APPPATH.'libraries/App/App.php',
];
