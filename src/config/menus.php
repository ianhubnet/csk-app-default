<?php

/**
 * Application Menus Configuration
 *
 * Defines all menus available to the application, including their labels and
 * associated menu items. Themes may expose specific menu locations and allow
 * administrators to assign these menus accordingly.
 *
 * Structure:
 *   '<menu_id>' => [
 *       'label' => 'lang:<language_key>',
 *       'items' => [
 *           [
 *               'title'   => 'lang:<language_key>' | '<HTML>',
 *               'href'    => '<URL or URI>',
 *               'show_if' => <optional visibility rule>,
 *               'attrs'   => <optional HTML attributes array>
 *           ],
 *           ...
 *       ]
 *   ]
 *
 * Notes:
 * - `title` may be plain text of HTML (icons supported).
 * - `show_if` allows conditional visibility base on modules, entities, etc.
 * - Internal URIs should be relative, beginning without a leading slash.
 * - This file is safe to modify. Custom menus should be registered here.
 *
 * @package    App\Config
 * @category   Menus
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 */

return [
	'menu_main' => [
		'label' => 'lang:menu_main',
		'items' => [
			[
				'title' => 'lang:home',
				'href'  => '/',
			],

			[
				'title' => 'lang:blog',
				'href'  => 'blog',
				'show_if' => ['module' => 'blog'],
			],

			[
				'title' => 'lang:contact_us',
				'href'  => 'contact',
				'show_if' => ['module' => 'contact'],
			],

			[
				'title' => 'lang:about',
				'href'  => 'page/about',
				'show_if' => [
					'module' => 'pages',
					'entity' => [
						'type' => 'object',
						'subtype' => 'page',
						'username' => 'about'
					]
				],
			]
		],
	],

	'menu_social' => [
		'label' => 'lang:menu_social',
		'items' => [
			// Ianhub website.
			[
				'title' => '<i class="fa fa-fw fa-globe"></i>',
				'href' => Platform::SITE_URL,
				'attrs' => ['rel' => 'external', 'target' => '_blank']
			],

			// Ianhub GitHub
			[
				'title' => '<i class="fab fa-fw fa-github"></i>',
				'href' => 'https://github.com/ianhubnet/',
				'attrs' => ['rel' => 'external', 'target' => '_blank']
			],

			// Ianhub LinkedIn
			[
				'title' => '<i class="fab fa-fw fa-linkedin"></i>',
				'href' => 'https://www.linkedin.com/company/ianhub/',
				'attrs' => ['rel' => 'external', 'target' => '_blank']
			]
		],
	],
];
