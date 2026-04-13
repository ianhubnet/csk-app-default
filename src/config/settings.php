<?php

/**
 * Application Settings Extension
 *
 * Defines application-level settings sections that extend
 * or complement the core CiSkeleton settings schema.
 *
 * This file is intended for:
 * - Application-specific configuration sections
 * - Custom UI grouping
 * - Project-level feature toggles
 *
 * Sections declared here are automatically merged into the
 * centralized Settings controller.
 *
 * Developers may:
 * - Add new sections
 * - Add new fields to existing sections
 * - Override metadata (icon, help, label)
 *
 * @example
 * ```php
 * $config['settings'][section_key] = [
 *     'title'  => string (language key),
 *     'label'  => string (language key),
 *     'icon'   => string (UI icon definition),
 *     'help'   => string|null,
 *     'fields' => [
 *         'option_key' => [
 *             'type'     => string,
 *             'rules'    => string|null,
 *             'default'  => mixed,
 *             'level'    => int,
 *             'required' => bool,
 *             'order'    => int,
 *             'options'  => array|null,
 *             'attrs'    => array|null
 *         ]
 *     ]
 * ];
 * ```
 *
 * This file should remain declarative and contain no runtime logic.
 *
 * @package     CiSkeleton\Controllers
 * @subpackage  Admin
 * @category    Configuration
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2026, Kader Bouyakoub
 * @since       0.0.1
 */

$config['settings']['app'] = [
	'title' => 'settings_website',
	'label' => 'website',
	'icon'  => 'globe',
	'fields' => [],
];
