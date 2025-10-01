<?php

/**
 * JSON-RPC Server Hook
 *
 * This hook provides a simple, global entry point for registering
 * custom JSON-RPC 2.0 methods and configuring authentication if
 * included in your `application/config/hooks.php`.
 *
 * Note: This hook applies globally, so all RPC endpoints
 * registered here will share the same authentication and setup.
 * For more flexible and modular RPC handling, consider extending
 * `RPC_Controller` and registering methods & authentication per controller.
 * This allows:
 *   - Different authentication per endpoint (e.g., bearer vs username/password)
 *   - Localized method registration inside controllers
 *   - Cleaner, more maintainable RPC logic without global hooks
 *
 * Usage:
 * - Wrap your logic in `once_action('post_controller_constructor', …)`
 *   to ensure CI services and controllers are fully initialized.
 * - Register authentication callbacks, RPC methods, or other logic
 *   that should run after the controller constructor.
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
 * - This hook is optional: you can safely remove it if you prefer
 *   to use `RPC_Controller` based endpoints only.
 * - Use this hook mainly for simple, global RPC setup or backward
 *   compatibility.
 *
 * @package    Aoo\Hooks
 * @category   JSON-RPC
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      1.0
 */
