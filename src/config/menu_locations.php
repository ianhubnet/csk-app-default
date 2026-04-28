<?php

/**
 * Application Menu Locations
 *
 * Declares the menu locations supported by the app. Each location maps to
 * a translated label displayed to administrators when assigning menus.
 *
 * Structure:
 *   '<location_id>' => 'lang:<language_key>'
 *
 * Example:
 *   'primary' => 'lang:loc_main'
 *
 * Notes:
 * - Keys must be unique.
 * - Labels should use language references to ensure localization.
 * - Assigned menus are stored in application settings, not here.
 * - Themes may add or remove locations as needed.
 *
 * @package     Themes\Config
 * @subpackage  Menus
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2018-present, Kader Bouyakoub
 */

return [
	'primary' => 'lang:menu_loc_main',
	'footer'  => 'lang:menu_loc_footer',
	'sidebar' => 'lang:menu_loc_sidebar',
];
