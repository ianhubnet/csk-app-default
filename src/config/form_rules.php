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
 *     'opassword' => [
 *         'field' => 'opassword',
 *         'label' => 'lang:current_password',
 *         'rules' => '...'
 *     ],
 *     'npassword' => [
 *         'field' => 'npassword',
 *         'label' => 'lang:new_password',
 *         'rules' => '...'
 *     ],
 *     'cpassword' => [
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
 * @copyright  Copyright (c) 2018-present, Kader Bouyakoub
 * @since      0.0.1
 */
