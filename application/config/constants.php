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
