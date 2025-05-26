<?php
defined('BASEPATH') OR die;

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
 * Site reserved constants.
 * @since   2.0
 *
 * KB_ADMIN     URL used to access the dashboard. Default: "admin" for "/admin/".
 * KB_BASE      The default controller to use if none is set in database.
 * KB_LOGIN     It is the route using for the login page.
 * KB_LOGOUT    The logout URL.
 * KB_REGISTER  The route used for the registration page.
 * KB_OFFLINE   The route used for the offline/maintenance page.
 */
const KB_ADMIN    = 'admin';
const KB_BASE     = 'welcome';
const KB_LOGIN    = 'login';
const KB_LOGOUT   = 'logout';
const KB_REGISTER = 'register';
const KB_OFFLINE  = 'offline';

/**
 * Define the level that can access the dashboard/admin panel.
 * Note: It is up to you to define which sections are allowed
 * for which levels by adding the "$access_level" property
 * to controllers.
 *
 * Available roles and their levels are:
 *
 * KB_LEVEL_REGULAR:    1 (Regular)
 * KB_LEVEL_AUTHOR:    10 (Author)
 * KB_LEVEL_EDITOR:    20 (Editor)
 * KB_LEVEL_MANAGER:   30 (Manager)
 * KB_LEVEL_ADMIN:     40 (Admin)
 * KB_LEVEL_OWNER:     50 (Owner)
 *
 * @since   2.16
 */
const KB_LEVEL_ACP = KB_LEVEL_AUTHOR;
