<?php

defined('BASEPATH') || exit('A moment of silence for your attempt.');

/**
 * Application Constants
 *
 * This file is intended for defining constants specific to the application.
 * These may include keys, API endpoints, feature flags, or other settings
 * unique to this project.
 *
 * Constants defined here are strictly application-level and are not meant
 * to override or interfere with those defined in the skeleton.
 *
 * @package     App\Config
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2025, Kader Bouyakoub
 * @since       2.18
 */

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
 * APP_LOGIN     Route used for the login page.
 * APP_LOGOUT    Route used to log users out.
 * APP_REGISTER  Route used for the registration page.
 * APP_OFFLINE   Route used when the site is in maintenance/offline mode.
 */
const APP_ADMIN    = 'admin';
const APP_BASE     = 'welcome';
const APP_LOGIN    = 'login';
const APP_LOGOUT   = 'logout';
const APP_REGISTER = 'register';
const APP_OFFLINE  = 'offline';

// --------------------------------------------------------------------

/**
 * UserLevel Class
 *
 * Application-specific access levels based on the core system's AccessLevel.
 *
 * This class is meant to be edited freely within the application to customize
 * or extend the default levels defined by CI Skeleton. All role-based access
 * checks should use this class instead of hard-coding numeric values.
 *
 * You can also define new constants (e.g., ACP, SUPPORT, etc) that refer to any
 * existing level from AccessLevel or define custom values for your own use.
 *
 * ---
 *
 * Dashboard Access:
 * To restrict access to the main panel or specific areas of the back-end,
 * use the `ACP` constant. For example, to require users to be at least
 * authors to access the dashboard.
 *
 * 	UserLevel::ACP = UserLevel::AUTHOR
 *
 * ---
 *
 * Note:
 * Controller can define `$access_level` property to restrict access to
 * specific roles:
 *
 * 	protected $access_level = UserLevel::EDITOR
 *
 * ---
 *
 * Default Role Levels:
 *
 * - UserLevel::REGULAR => AccessLevel::REGULAR  (1) (Regular user)
 * - UserLevel::AUTHOR  => AccessLevel::AUTHOR  (10) (Can create content)
 * - UserLevel::EDITOR  => AccessLevel::EDITOR  (20) (Can edit others' content)
 * - UserLevel::MANAGER => AccessLevel::MANAGER (30) (Access to content and user management)
 * - UserLevel::ADMIN   => AccessLevel::ADMIN   (40) (Administrative privileges)
 * - UserLevel::OWNER   => AccessLevel::OWNER   (50) (Full unrestricted access)
 *
 * @since 2.168
 */
final class UserLevel
{
	// --------------------------------------------------------------------
	// CI Skeleton default levels (DO NOT MODIFY)
	// --------------------------------------------------------------------

	/**
	 * This is the default role assigned to new users, and used
	 * as a fallback when no valid role is provided.
	 * @var int
	 */
	public const REGULAR = AccessLevel::REGULAR;

	/**
	 * Can create content but not edit others'.
	 * @var int
	 */
	public const AUTHOR = AccessLevel::AUTHOR;

	/**
	 * Can review, edit, or moderate content by others.
	 * @var int
	 */
	public const EDITOR = AccessLevel::EDITOR;

	/**
	 * Can manage content, users, and moderate high-level ares.
	 * @var int
	 */
	public const MANAGER = AccessLevel::MANAGER;

	/**
	 * Has access to system settings and full control over data.
	 * @var int
	 */
	public const ADMIN = AccessLevel::ADMIN;

	/**
	 * Top-level access - can do anything in the system.
	 * @var int
	 */
	public const OWNER = AccessLevel::OWNER;

	// --------------------------------------------------------------------
	// App-level aliases or custom levels.
	// --------------------------------------------------------------------

	/**
	 * Minimum level required to access the dashboard/admin panel.
	 * Change this to elevate or restrict who can log into the back-end.
	 *
	 * @var int
	 */
	public const ACP = self::AUTHOR;

	/**
	 * Minimum level required to bypass demo mode restrictions.
	 * Change this to control which users retain full access in demo mode.
	 *
	 * @var int
	 */
	public const DEMO = self::OWNER;

	/**
	 * These are merged with AccessLevel::$labels when generating levels.
	 * Only define labels for roles intended to be shown to users.
	 *
	 * @example
	 * 	self::MODERATOR => 'role_moderator'
	 *
	 * @var array<int, string>
	 */
	public static $labels = [];

}
