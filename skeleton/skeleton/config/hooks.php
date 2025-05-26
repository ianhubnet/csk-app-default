<?php
defined('BASEPATH') OR die;

/**
 * Skeleton-related hooks config file.
 * @since 	1.0
 */

// --------------------------------------------------------------------
// Pre System Hooks
// --------------------------------------------------------------------

/**
 * PHP Whoops integration.
 * @since 	2.16
 */
$hook['pre_system'][] = array(
	'class'    => 'Skeleton_pre_system',
	'function' => 'whoops',
	'filename' => 'pre_system.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Request limiter.
 * @since 	2.91
 */
$hook['pre_system'][] = array(
	'class'    => 'Skeleton_pre_system',
	'function' => 'limit_request',
	'filename' => 'pre_system.php',
	'filepath' => KBPATH.'hooks',
	'params'   => ip_address(),
);

/**
 * Stupid bad request blocker.
 * @since 	2.130
 */
$hook['pre_system'][] = array(
	'class'    => 'Skeleton_pre_system',
	'function' => 'monitor_request',
	'filename' => 'pre_system.php',
	'filepath' => KBPATH.'hooks'
);

// --------------------------------------------------------------------
// Post Controller Constructor Hooks
// --------------------------------------------------------------------

/**
 * Register PHP Exception handler.
 * @since 	2.130
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'register_exception_listener',
	'filename' => 'post_controller_constructor.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Appropriate headers and redirection for SSL websites.
 * @since 	2.16
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'redirect_ssl',
	'filename' => 'post_controller_constructor.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Moved from KB_Controller and stores the current URL
 * that can be later used to redirect to previous page.
 * @since 	2.127
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'prep_previous_uri',
	'filename' => 'redirects.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Moved from KB_Controller and stores the 'next' request
 * that can be used to redirect the user to the given URI.
 * @since 	2.127
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'prep_next_uri',
	'filename' => 'redirects.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Allows us to put the side offline.
 * @since 	2.16
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'offline',
	'filename' => 'post_controller_constructor.php',
	'filepath' => KBPATH.'hooks'
);

/**
 * Session Security Hook
 * @since 	2.102
 */
$hook['post_controller_constructor'][] = array(
	'class'    => 'Skeleton_post_controller_constructor',
	'function' => 'validate_session',
	'filename' => 'post_controller_constructor.php',
	'filepath' => KBPATH.'hooks'
);

// --------------------------------------------------------------------
// Post System Hooks
// --------------------------------------------------------------------

/**
 * Custom Error Logging Hook
 * @since 	2.102
 */
$hook['post_system'][] = array(
	'class'    => 'Skeleton_post_system',
	'function' => 'log_last_error',
	'filename' => 'post_system.php',
	'filepath' => KBPATH.'hooks'
);
