<?php

/**
 * Form Validation Rules
 *
 * This file contains all application's form validation rules.
 *
 * Rules follow the CiSkeleton format expected by the form validation library.
 *
 * @example
 * ```php
 * // Registration:
 * $config['account/password'] = [
 *     [
 *         'field' => 'opassword',
 *         'label' => 'lang:current_password',
 *         'rules' => '...'
 *     ],
 *     [
 *         'field' => 'npassword',
 *         'label' => 'lang:new_password',
 *         'rules' => '...'
 *     ],
 *     [
 *         'field' => 'cpassword',
 *         'label' => 'lang:confirm_password',
 *         'rules' => '...'
 *     ]
 * ];
 *
 * // Usage in Controller:
 * $this->hub->form->prep('account/password');
 * ```
 *
 * @package    App\Config
 * @category   Form Validation
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2026, Kader Bouyakoub
 * @since      0.0.1
 */
