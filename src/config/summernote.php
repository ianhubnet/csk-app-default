<?php

/**
 * Summernote Configuration (App)
 *
 * This file allows overriding or extending Summernote profiles
 * defined in the core configuration.
 *
 * You only need to define the profiles or keys you want to change.
 *
 * Example:
 *
 * $config['summernote_profiles']['minimal']['height'] = 180;
 *
 * $config['summernote_profiles']['custom'] = [
 *     'toolbar' => "[['font',['bold']]]",
 *     'popover' => '{}',
 *     'media'   => false,
 * ];
 *
 * Merge behavior:
 * - This file is merged with the core config using array_replace_recursive()
 * - Matching keys override core values
 * - New profiles are appended
 *
 * @package    App\Config
 * @category   Summernote
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2026, Kader Bouyakoub
 * @since      0.0.1
 */

$config['summernote_profiles'] = [
	// Override or add profiles here
];
