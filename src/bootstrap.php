<?php

/*
|--------------------------------------------------------------------------
| Application Version and Repository
|--------------------------------------------------------------------------
|
| Used to track app status and possibly check for updates.
|
*/
const APP_VERSION = '0.0.1';
const APP_VERSION_ID = 1;
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
| Default Homepage Routes
|--------------------------------------------------------------------------
|
| CiSkeleton uses the `default_controller` configuration value to determine
| which page is shown when visitors access the root URL of the website.
|
| The `default_controller` filter allows the application to define which
| routes can be used as the website homepage.
|
| While the active homepage is selected from the admin dashboard, applications,
| and modules may declare which routes are allowed to be used as homepage candidates.
|
| Each value represents a route in the form:
|
|     controller
|     controller/method
|     module/controller/method
|
| Examples:
| - 'front'        → Front
| - 'store'        → Store
| - 'store/cart'   → Store » Cart
|
| Notes:
| - The default 'front' controller is always available.
| - This filter defines *available choices*, not the active value.
| - Modules may also contribute routes using the same filter.
|
| This filter is executed only once during settings preparation.
|
| Example:
|
| once_filter('default_controller', function ($routes, $ci) {
|     $routes[] = 'store';
|     $routes[] = 'store/featured';
|     return $routes;
| });
|
*/
// once_filter('default_controller', function ($routes, $ci) {
// 	return $routes;
// });
