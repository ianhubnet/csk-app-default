<?php

/**
 * JSON-RPC Server Hook
 *
 * This hook is automatically loaded if included in your
 * `application/config/hooks.php` file. It provides a convenient
 * entry point for registering custom JSON-RPC 2.0 methods
 * and configuring authentication for your application.
 *
 * Usage:
 * - Define all JSON-RPC related setup here.
 * - You may register authentication callbacks, RPC methods,
 *   or other logic that should run after the controller
 *   constructor is available.
 *
 * @example
 * ```php
 * once_action('post_controller_constructor', function ($ci) {
 *     $jsonrpcs = library('jsonrpcs');
 *
 *     // Optional authentication
 *     $jsonrpcs->set_auth([
 *         'type' => 'bearer',
 *         'callback' => [service('auth'), 'check_token']
 *     ]);
 *
 *     // Register your custom RPC methods
 *     $jsonrpcs
 *         ->register('user.get', [service('user'), 'get'])
 *         ->register('user.update', [service('user'), 'update']);
 * });
 * ```
 *
 * Notes:
 * - Always wrap your logic in once_action('post_controller_constructor', …)
 *   to ensure that CI’s services and controllers are fully initialized.
 * - This file is intentionally empty by default: it is safe to remove if
 *   you do not need JSON-RPC support in your project.
 *
 * @package    Aoo\Hooks
 * @category   JSON-RPC
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      1.0
 */

