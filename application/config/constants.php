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

// --------------------------------------------------------------------

/**
 * The default controller to use if none is set in config.
 *
 * @var string
 */
const APP_BASE = 'welcome';

// --------------------------------------------------------------------

/**
 * CookieKey
 *
 * Final implementation of {@see CookieKeyInterface}.
 * Exists as a convenience class to allow type-hinting against a concrete
 * implementation instead of the interface when needed.
 *
 * @since 3.9.0
 */
final class CookieKey implements CookieKeyInterface
{
}

// --------------------------------------------------------------------

/**
 * SessionKey
 *
 * Final implementation of {@see SessionKeyInterface}.
 * Exists as a convenience class for developers to type-hint
 * against a concrete implementation instead of the interface.
 *
 * Applications can extend this class to define custom session keys.
 *
 * @since 3.9.0
 */
final class SessionKey implements SessionKeyInterface
{
}

// --------------------------------------------------------------------

/**
 * UserLevel
 *
 * Application-specific access levels extending the core system's
 * {@see UserLevelInterface}. This class provides aliases and
 * customization points for role-based access checks.
 *
 * Developers are encouraged to use this class (instead of raw integers)
 * when defining permissions, and may freely extend it to suit their
 * application's needs.
 *
 * ---
 * Example: restricting dashboard access
 *
 * ```php
 * UserLevel::ACP = UserLevel::AUTHOR;
 * ```
 *
 * Controllers can also restrict access via the `$class_level` property:
 *
 * ```php
 * protected $class_level = UserLevel::EDITOR;
 * ```
 *
 * ---
 * Default role mapping:
 *
 * - UserLevel::REGULAR → 1 (Regular user)
 * - UserLevel::AUTHOR  → 10 (Can create content)
 * - UserLevel::EDITOR  → 20 (Can edit others' content)
 * - UserLevel::MANAGER → 30 (Content/user management)
 * - UserLevel::ADMIN   → 40 (Administrative privileges)
 * - UserLevel::OWNER   → 50 (Full unrestricted access)
 *
 * @since 2.168
 * @since 3.9.0  Implements the new `UserLevelInterface` interface.
 */
final class UserLevel implements UserLevelInterface
{
	// --------------------------------------------------------------------
	// Application-level aliases or custom levels
	// --------------------------------------------------------------------

	/**
	 * Minimum access level required for dashboard/admin panel access.
	 * Adjust to widen or restrict who can log into the back-end.
	 *
	 * @var int
	 */
	public const ACP = self::AUTHOR;

	/**
	 * Minimum access level required to bypass demo mode restrictions.
	 * Adjust to define which users retain full access in demo mode.
	 *
	 * @var int
	 */
	public const DEMO = self::OWNER;

	/**
	 * Translatable labels for CSK's core roles.
	 * Maps access level integers to language keys used in dropdowns,
	 * user profiles, and other UI elements.
	 *
	 * If you add new role constants above, please make sure to add their
	 * corresponding language keys, and DO NOT alter core labels.
	 *
	 * @example
	 * self::EDITOR => 'role_editor'
	 *
	 * @var array<int, string>
	 */
	public static $labels = [
		// Core-reserved role labels (do not touch)
		self::REGULAR => 'role_regular',
		self::AUTHOR => 'role_author',
		self::EDITOR => 'role_editor',
		self::MANAGER => 'role_manager',
		self::ADMIN => 'role_admin',
		self::OWNER => 'role_owner',
		// Application-specific role labels (add your below)
	];

}

// --------------------------------------------------------------------

/**
 * Application-Specific Relation Keys.
 *
 * Extends the base `RelationKeyInterface` with project-level constants
 * for defining relationships. This is the class you should use within
 * your application when referencing relation keys, rather than directly
 * using the core interface.
 *
 * Benefits of using this wrapper:
 * - Keeps application-level relation constants centralized.
 * - Allows extension with domain-specific keys (e.g., "follower",
 *   "subscriber", "liked", etc.).
 * - Protects core definitions while enabling customization.
 *
 * @example
 * ```php
 * RelationKey::PARENT; // Returns 'parent'
 * RelationKey::OWNER;  // Returns 'owner'
 * ```
 *
 * @since 3.9.1
 */
final class RelationKey implements RelationKeyInterface
{
	// Currently inherits all constants from RelationKeyInterface.
	// Add application-specific relation keys here if needed.
}
