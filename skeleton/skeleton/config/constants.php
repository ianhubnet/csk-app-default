<?php
defined('BASEPATH') OR die;

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
const FILE_READ_MODE  = 0644;
const FILE_WRITE_MODE = 0666;
const DIR_READ_MODE   = 0755;
const DIR_WRITE_MODE  = 0755;

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
const FOPEN_READ                          = 'rb';
const FOPEN_READ_WRITE                    = 'r+b';
const FOPEN_WRITE_CREATE_DESTRUCTIVE      = 'wb'; // truncates existing file data, use with care
const FOPEN_READ_WRITE_CREATE_DESTRUCTIVE = 'w+b'; // truncates existing file data, use with care
const FOPEN_WRITE_CREATE                  = 'ab';
const FOPEN_READ_WRITE_CREATE             = 'a+b';
const FOPEN_WRITE_CREATE_STRICT           = 'xb';
const FOPEN_READ_WRITE_CREATE_STRICT      = 'x+b';

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       https://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
const EXIT_SUCCESS        = 0; // no errors
const EXIT_ERROR          = 1; // generic error
const EXIT_CONFIG         = 3; // configuration error
const EXIT_UNKNOWN_FILE   = 4; // file not found
const EXIT_UNKNOWN_CLASS  = 5; // unknown class
const EXIT_UNKNOWN_METHOD = 6; // unknown class member
const EXIT_USER_INPUT     = 7; // invalid user input
const EXIT_DATABASE       = 8; // database error
const EXIT__AUTO_MIN      = 9; // lowest automatically-assigned error code
const EXIT__AUTO_MAX      = 125; // highest automatically-assigned error code

/**
 * Access levels for default provided roles.
 * @since   2.16
 */
const KB_LEVEL_REGULAR = 1;
const KB_LEVEL_AUTHOR  = 10;
const KB_LEVEL_EDITOR  = 20;
const KB_LEVEL_MANAGER = 30;
const KB_LEVEL_ADMIN   = 40;
const KB_LEVEL_OWNER   = 50;

/**
 * Various session and cookie names used across the app.
 * @since 	2.93
 */
const COOK_CONSENT      = 'ci_cookies';
const COOK_CSRF         = 'ci_csrf_token';
const COOK_LANG         = 'ci_lang';
const COOK_USER_AUTH    = 'ci_user';
const SESS_ALERT        = '__ci_alert';
const SESS_LANG         = '__ci_language';
const SESS_NEXT_URI     = '__ci_next_uri';
const SESS_POSTDATA     = '__ci_post_data';
const SESS_PREV_URI     = '__ci_previous_uri';
const SESS_USER_ID      = '__ci_user_id';
const SESS_USER_2FA     = '__ci_2fa_user_id';
const SESS_PREV_USER_ID = '__ci_prev_user_id';
const SESS_USER_TOKEN   = '__ci_user_token';
const SESS_USER_AGENT   = '__ci_user_agent';
const SESS_IP_ADDRESS   = '__ci_ip_address';
const SESS_CAPTCHA_WORD = '__ci_captcha_word';
const SESS_CAPTCHA_TIME = '__ci_captcha_time';
