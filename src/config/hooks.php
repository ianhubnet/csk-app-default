<?php

/**
 * Hooks Configuration (common)
 *
 * Allows the application to register additional hooks beyond
 * the defaults provided by CiSkeleton. Use this file to customize
 * runtime behavior such as maintenance mode, logging, profiling,
 * or third-party integrations.
 *
 * --------------------------------------------------------------------
 * Usage Guidelines
 * --------------------------------------------------------------------
 * - You *may* define hooks directly inside this file, but the
 *   recommended approach is to place each hook in its own file
 *   under: APPPATH.'hooks/'.
 *
 * - Then, explicitly load them here using `include_once`, e.g.:
 *       include_once APPPATH.'hooks/maintenance.php';
 *
 * - You can conditionally include hooks depending on constants
 *   such as:
 *       if (CI_DEBUG) { ... }   // Development or testing only
 *       if (CI_LIVE)  { ... }   // Production/live only
 *
 * - This keeps your hooks modular, easier to maintain, and
 *   consistent with the structure used by the core hooks.
 *
 * --------------------------------------------------------------------
 * Example
 * --------------------------------------------------------------------
 * include_once APPPATH.'hooks/maintenance.php';   // Always load
 * if (CI_DEBUG) {
 *     include_once APPPATH.'hooks/profiler.php';  // Debug only
 * }
 *
 * @package    App\Config
 * @category   Hooks
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2026, Kader Bouyakoub
 * @since      0.0.1
 */

// Uncomment to register custom JSON-RPC methods for your app:
// include_once APPPATH.'hooks/jsonrpcs.php';
