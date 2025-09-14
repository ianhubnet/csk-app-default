<?php

/**
 * Auto-loader
 *
 * This file specifies which components should be automatically loaded
 * with every request. It supports a single `$autoload` array.
 *
 * You can autoload the following components:
 *
 *   - packages  : Array of package paths.
 *   - libraries : Array of library names.
 *   - drivers   : Array of drivers (optionally aliased).
 *   - helper    : Array of helper files.
 *   - config    : Array of custom config files.
 *   - language  : Array of language files (these are always deferred
 *                 until the active language has been determined).
 *   - model     : Array of models.
 *
 * NOTE: Language files are *always* loaded later in the process,
 * after the application locale has been set. No special handling
 * is required from you.
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
| Example:
|  $autoload['packages'] = [APPPATH.'third_party', '/usr/local/shared'];
*/
$autoload['packages'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| Example:
|  $autoload['libraries'] = ['email', 'session'];
| You can also alias:
|  $autoload['libraries'] = ['agent' => 'ua'];
*/
$autoload['libraries'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| Example:
|  $autoload['drivers'] = ['cache'];
| You can also alias:
|  $autoload['drivers'] = ['cache' => 'cch'];
*/
$autoload['drivers'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Example:
|  $autoload['helper'] = ['url', 'file'];
*/
$autoload['helper'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Example:
|  $autoload['config'] = ['config1', 'config2'];
| NOTE: Only for custom config files.
*/
$autoload['config'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Example:
|  $autoload['language'] = ['core', 'app'];
|
| NOTE: Do not include the "_lang" suffix.
| E.g. "core_lang.php" → ['core']
|
| These are automatically deferred until the active language
| has been determined.
*/
$autoload['language'] = [];

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Example:
|  $autoload['model'] = ['first_model', 'second_model'];
| You can also alias:
|  $autoload['model'] = ['first_model' => 'first'];
*/
$autoload['model'] = [];
