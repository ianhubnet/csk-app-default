<?php

/**
 * CI Skeleton Application version.
 * @since 2.145
 */
const APP_VERSION = '0.1';

/*
|--------------------------------------------------------------------------
| Application Info
|--------------------------------------------------------------------------
|
| Here you can define your application global information.
|
|	APP_LABEL:	App name (default: CI Skeleton)
|	APP_SHORT:	App short name (default: CSK)
|	APP_SLOGAN:	App default description.
|	APP_AUTHOR:	App global author name.
| 	APP_KEYWORDS:	App name (default: combines label and description)
|
*/
const APP_LABEL      = KPlatform::LABEL;
const APP_SHORT      = KPlatform::SHORT;
const APP_SLOGAN     = KPlatform::SLOGAN;
const APP_AUTHOR     = KPlatform::AUTHOR;
const APP_KEYWORDS   = KPlatform::KEYWORDS;

/**
 * Because Web Hosting providers set a limit to how my
 * PHP scripts you have running at a single time, setting
 * a limit to reqests that will be used by KB_Router can
 * be handy.
 *
 * However, there is another way (though not test), using .htaccess
 * by passing the following code somewhere:
 *
 * <IfModule mod_limitipconn.c>
 *     MaxConnPerIP 10 # limit to 10
 *     OnlyIPLimit application/x-php
 * </IfModule>
 *
 * The default limit used by KB_Router is 20, if you feel it is too high
 * or too low, please uncomment the line below and set the limit you wish.
 */
// const CI_REQUEST_LIMIT = 20;

// --------------------------------------------------------------------
// YOU MAY EDIT LINES BELOW.
// --------------------------------------------------------------------

// Application classes.
// Autoloader::add_classes(array(
/**
 * Add classes you want to add/override here.
 * @example: 'Classname' => APPPATH.'libraries/Classname.php'
 */
// ));

/**
 * This filter is fired before loading default language files.
 * @since 2.18
 */
// add_filter('language_files', function($files) {
// 	return $files; // always return $files
// });

/**
 * This action is fired before Skeleton libraries are loaded.
 * @since 2.13
 */
// add_action('init', function() {
// 	// Do your magic.
// });

// --------------------------------------------------------------------
// Additional modules and themes details.
// --------------------------------------------------------------------

/**
 * In case you want to add more details to modules headers, please
 * use the action below.
 * @since 2.12
 */
// add_action('modules_headers', function($headers) {
// 	return $headers;
// });
