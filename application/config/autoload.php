<?php

/**
 * Auto-loader
 *
 * This file specifies which components should be automatically loaded
 * with every request. It supports both:
 *
 *  1. `$autoload` - Items loaded early in the request lifecycle.
 *  2. `$deferred` - Items loaded later, when the "post_loader" hook
 *     is triggered manually via `do_action('post_loader')`.
 *
 * You can autoload the following components:
 *
 *   - packages  : Array of package paths.
 *   - libraries : Array of library names.
 *   - drivers   : Array of drivers (optionally aliased).
 *   - helper    : Array of helper files.
 *   - config    : Array of custom config files.
 *   - language  : Array of language files (better use `$deferred` for them).
 *   - model     : Array of models.
 *
 * NOTE: Language files should generally go in `$deferred` to ensure
 * that locale/theme-dependent context is available when loading them.
 *
 * @package    App\Config
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      1.0
 */

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = [APPPATH.'third_party', '/usr/local/shared'];
|
*/
$autoload['packages'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = ['database', 'email', 'session'];
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = ['user_agent' => 'ua'];
*/
$autoload['libraries'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = ['cache'];
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = ['cache' => 'cch'];
|
*/
$autoload['drivers'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = ['url', 'file'];
*/
$autoload['helper'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = ['config1', 'config2'];
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = ['lang1', 'lang2'];
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as ['codeigniter'];
|
*/
$autoload['language'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = ['first_model', 'second_model'];
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = ['first_model' => 'first'];
*/
$autoload['model'] = [];
