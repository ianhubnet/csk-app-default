<?php
defined('BASEPATH') || exit('A moment of silence for your attempt.');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to true, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
const SHOW_DEBUG_BACKTRACE = true;

/**
 * Route reserved constants.
 * AR_* = "App Route"
 *
 * APP_ADMIN     URL used to access the dashboard. Default: "admin" for "/admin/".
 * APP_BASE      The default controller to use if none is set in database.
 * APP_LOGIN     It is the route using for the login page.
 * APP_LOGOUT    The logout URL.
 * APP_REGISTER  The route used for the registration page.
 * APP_OFFLINE   The route used for the offline/maintenance page.
 */
const APP_ADMIN    = 'admin';
const APP_BASE     = 'welcome';
const APP_LOGIN    = 'login';
const APP_LOGOUT   = 'logout';
const APP_REGISTER = 'register';
const APP_OFFLINE  = 'offline';

/**
 * Define the level that can access the dashboard/admin panel.
 * Note: It is up to you to define which sections are allowed
 * for which levels by adding the "$access_level" property
 * to controllers.
 *
 * Available roles and their levels are:
 *
 * USER_LEVEL_REGULAR:    1 (Regular)
 * USER_LEVEL_AUTHOR:    10 (Author)
 * USER_LEVEL_EDITOR:    20 (Editor)
 * USER_LEVEL_MANAGER:   30 (Manager)
 * USER_LEVEL_ADMIN:     40 (Admin)
 * USER_LEVEL_OWNER:     50 (Owner)
 *
 * @since   2.16
 */
const USER_LEVEL_ACP = USER_LEVEL_AUTHOR;
