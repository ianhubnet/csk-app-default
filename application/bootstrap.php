<?php

/*
|--------------------------------------------------------------------------
| Application Version and Repository
|--------------------------------------------------------------------------
|
| Used to track app status and possibly check for updates.
|
*/
const APP_VERSION = '0.0.2';
const APP_REPO = 'https://github.com/ianhubnet/csk-app-default';

/*
|--------------------------------------------------------------------------
| Request Limiter
|--------------------------------------------------------------------------
|
| Because Web Hosting providers set a limit to how many PHP scripts you have
| running at a single time, setting a limit to requests can be handy.
|
| However, there is another way (though not tested), using `.htaccess` by
| pasting the following somewhere in the file:
|
| <IfModule mod_limitipconn.c>
|     MaxConnPerIP 10 # limit to 10
|     OnlyIPLimit application/x-php
| </IfModule>
|
| The default limit used is 20, if you it is too low or too high, please
| uncomment the line below and set the limit you wish to use.
|
*/
// const CI_REQUEST_LIMIT = 20;

// --------------------------------------------------------------------
// BELOW ARE CODE EXAMPLES | FEEL FREE TO EDIT
// --------------------------------------------------------------------

/*
|--------------------------------------------------------------------------
| Autoloader Class Registration
|--------------------------------------------------------------------------
|
| In order to speed up class loading and usage, it is advised to add your
| application classes to `Autoloader`.
|
| Examples:
| 1. Registering a single class:
|     Autoloader::add_class('MyClass', __DIR__.'/core/MyClass.php');
|
| 2. Registering multiple classes:
|     Autoloader::add_classes([
|         'MyClass1' => __DIR__.'/MyClass1.php',
|         'MyClass2' => APPPATH.'/core/MyClass2.php',
|     ]);
|
| Both methods of registering classes accept an additional argument that is used
| to conditionally register classes. In the example below, we want to register
| a new class but only for the admin area of the application:
|
|     Autoloader::add_class('MyAdminClass', __DIR__.'/MyAdminClass.php', CI_ADMIN);
|
*/
// Autoloader::add_classes([]);

/*
|--------------------------------------------------------------------------
| Hooks (Actions & Filters)
|--------------------------------------------------------------------------
|
| Actions and filters (hooks in general) are the beauty of CiSkeleton.
| They can be registered from the application itself, modules, themes, or
| event plugins.
|
| You can register your hooks early in this file and they will be executed
| during the request process.
|
| `add_filter`  : Hooks a function or method to a specific filter action.
| `add_action`  : Hooks a function to a specific action.
| `once_action` : Hooks a one-time function to a specific action.
|
| The example below is an action that is triggered right after all core classes
| and libraries have been loaded.
|
*/
// add_action('init', function () {
// 	// Do your magic.
// });

/*
|--------------------------------------------------------------------------
| Hub Drivers Registration
|--------------------------------------------------------------------------
|
| The core driver in CiSkeleton is `CI_Hub`. It is an all-in-one driver that
| makes the heart of the framework and gives you all what you need to get started.
|
| If you wish to add extra drivers from the application side, simply make sure
| to have `libraries/Hub/drivers/` directory, inside which you create as many
| drivers as you want. In the example below, we are registering a driver called
| 'test', so in order for this to work:
| 1. We need the file: `libraries/Hub/drivers/Hub_test.php`.
| 2. We need the class `Hub_test extends CI_Driver` (or even `CI_Hub_test`).
| 3. Optional: To speed up class lookup, do not forget to register the driver's
|    class with `Autoloader::add_class('Hub_test', APPPATH.'libraries/Hub/drivers/Hub_test.php')`.
|
*/
// once_filter('hub_drivers', function ($drivers) {
// 	array_push($drivers, 'example');
// 	return $drivers;
// });

/*
|--------------------------------------------------------------------------
| Request Rewrites
|--------------------------------------------------------------------------
|
| If you wish to register a URI rewrite rule that will redirect the user to
| the appropriate URI, you can use `rewrite_rules` filter.
|
| Example:
|
| once_filter('rewrite_rules', function ($rules) {
|      $rules['blog_category'] = 'blog/category';
|      return $rules;
| });
|
| In the example above, if the user visits `/blog_category/gaming` for example
| they will be redirected to `blog/category/gaming`.
|
*/
// once_filter('rewrite_rules', function ($rules) {
// 	$rules['blog_category'] = 'blog/category';
// 	return $rules;
// });

/*
|--------------------------------------------------------------------------
| Application Website Settings
|--------------------------------------------------------------------------
|
| The `settings_tab_app` filter allows the application to define its own
| core website-related settings (for example: address, phone number, etc.)
| that will appear under the "Website" tab in the admin dashboard.
|
| This filter is optional. The "Website" tab will be displayed as long as
| at least one component (application, module, plugin, or theme) contributes
| fields via either:
|
| - `settings_tab_app`        (application-owned settings)
| - `app_settings_fields`    (extension-contributed settings)
|
| Registering this filter in the application is recommended when the app
| wants to explicitly define or reserve website-level settings, even if
| no fields are currently needed.
|
| IMPORTANT:
| - Use `add_filter()` instead of `once_filter()`.
|   The filter must remain registered for the entire request lifecycle so
|   that CiSkeleton can both:
|     - collect fields during tab preparation
|     - detect the hook during sub-menu rendering
|
| Example:
|
| add_filter('settings_tab_app', function ($fields, $ci) {
|     $fields[] = 'site_address';
|     $fields[] = 'site_phone';
|     return $fields;
| });
|
*/
// add_filter('settings_tab_app', function ($fields, $ci) {
// 	return $fields;
// });
