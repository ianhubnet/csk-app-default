<?php
defined('BASEPATH') OR die;

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
*/
require(APPPATH.'config/base_controller.php');
$route['default_controller'] = isset($config, $config['base_controller']) ? $config['base_controller'] : KB_BASE;

/**
 * 404 Page Route & URI Dashes
 * @since 	2.16
 */
$route['404_override'] = 'main/error_404';
$route['translate_uri_dashes'] = true;

/**
 * SEO & App: 'robots.txt', 'sitemap.xml' and 'manifest.json'
 * @since 	2.103
 */
Route::get('robots.txt', 'seo/robots');
Route::get('sitemap.xml', 'seo/sitemap');
Route::get('manifest.json', 'seo/manifest');
Route::block('seo');

/**
 * Main Controller's Routes.
 * @since   2.16
 */

/* Site offline page. */
Route::any(KB_OFFLINE, 'main/offline', 'offline');

/* Switch language URI. */
Route::any('locale/([a-zA-Z0-9-]+)', 'main/switch_language/$1');
Route::any('locale', 'main/switch_language', 'switch-language');

/* Contact page. */
Route::any('contact', 'main/contact', 'contact');

/* User profile. */
Route::any('user', 'main/user', 'user');
Route::any('user/([a-zA-Z0-9-]+)', 'main/user/$1');

/* Switch account. */
Route::any('switch/([0-9]+)', 'main/switch_account/$1');
Route::any('switch', 'main/switch_account', 'switch-account');

/* Disable and Delete account */
Route::any('disable-account', 'main/disable_account');
Route::any('delete-account', 'main/delete_account');

/* Block direct access to main controller. */
Route::block('main', 'main/(.+)');

/* Ajax Keep Alive (Sessions) */
Route::any('keep-alive', 'ajax/keep_alive');

/**
 * Authentication routes.
 * @since   2.0
 */
Route::any(KB_LOGIN, 'auth/login', 'login', function() {
	Route::any('verify', 'auth/verify', 'login-2fa');
	Route::any('recover', 'auth/recover', 'lost-password');
	Route::any('reset', 'auth/reset', 'reset-password');
	Route::any('reset/([a-zA-Z0-9]+)', 'auth/reset/$1');
	Route::any('restore', 'auth/restore', 'restore-account');
	Route::any('link', 'auth/link', 'quick-login');
	Route::any('link/([a-zA-Z0-9]+)', 'auth/link/$1');
});
Route::any(KB_LOGOUT, 'auth/logout', 'logout');

/**
 * Account creation routes.
 * @since   2.0
 */
Route::any(KB_REGISTER, 'auth/register', 'register', function() {
	Route::any('resend', 'auth/resend', 'resend-link');
	Route::any('activate', 'auth/activate', 'activate-account');
	Route::any('activate/([a-zA-Z0-9]+)', 'auth/activate/$1');
});

/* OAuth routes. */
Route::any('oauth', 'auth/index', 'oauth');
Route::any('oauth/(:any)', 'auth/index/$1');

// Prevent direct access to auth controller.
Route::block('auth', 'auth/(.+)');

/**
 * User settings.
 * @since 	2.72
 */
Route::prefix('settings', function() {
	Route::any('email/([a-zA-Z0-9]+)', 'settings/change_email/$1', 'process-change-email');
}, 'settings/index', 'settings');

/**
 * The application has a built-in administration panel. Each module can
 * have context controllers.
 * You can add contexts using KPlatform:add_[admin|public]_context
 * @since   1.0
 */
Route::prefix(KB_ADMIN, function() {

	// Admin login section.
	Route::any(KB_LOGIN, 'admin/login/index', 'admin-login');

	// System information route first.
	Route::any('settings', 'admin/settings');
	Route::any('settings/sysinfo', 'admin/settings/sysinfo');

	// Profile section.
	Route::any('profile', 'admin/profile', 'admin-profile');

	/**
	 * Reserved dashboard sections.
	 * @since   2.0
	 */
	foreach (KPlatform::protected_modules() as $adm_mod)
	{
		Route::any("{$adm_mod}/(:any)(.+)", "admin/{$adm_mod}/$1$2");
		Route::any("{$adm_mod}/(:any)", "admin/{$adm_mod}/$1");
		Route::any($adm_mod, "admin/{$adm_mod}/index", 'admin-'.$adm_mod);
	}

	/**
	 * AJAX and Process contexts
	 * @since   2.16
	 */
	$contexts = implode('|', KPlatform::contexts());
	Route::any("({$contexts})/(:any)/(:any)/(:any)", 'admin/$1/$2/$3/$4');
	Route::any("({$contexts})/(:any)/(:any)", 'admin/$1/$2/$3');
	Route::any("({$contexts})/(:any)", 'admin/$1/$2');
	Route::any("({$contexts})", 'admin/$1/index');
	unset($contexts);

	/**
	 * Logs section
	 * @since 	2.16
	 */
	Route::get('logs', 'admin/logs/index', 'log');
	Route::get('logs/(:any)', 'admin/logs/view/$1');
}, 'admin/index', 'admin');

/**
 * Front-end contexts.
 * @since   1.0
 */
$contexts = implode('|', KPlatform::contexts());
Route::any("({$contexts})/(:any)/(:any)/(:any)", '$1/$2/$3/$4');
Route::any("({$contexts})/(:any)/(:any)", '$1/$2/$3');
Route::any("({$contexts})/(:any)", '$1/$2');
Route::any("({$contexts})", '$1/index');
unset($contexts);
