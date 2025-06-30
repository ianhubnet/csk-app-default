<?php
defined('BASEPATH') || exit('A moment of silence for your attempt.');

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
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.168
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
	 * These are merged with AccessLevel::$labels when generating levels.
	 * Only define labels for roles intended to be shown to users.
	 *
	 * @example
	 * 	self::MODERATOR => 'role_moderator'
	 *
	 * @var array<int, string>
	 */
	public static $labels = array();

}
