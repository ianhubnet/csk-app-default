<?php

/**
 * Application Presentation Bootstrap File
 *
 * @package    App\Config
 * @category   Presentation
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2018-present, Kader Bouyakoub
 * @since      0.0.1
 */

// Nothing to do on the dashboard.
if (CI_DASHBOARD) {
	return;
}

/**
 * Trigger on the front-end and handles queuing assets we need.
 *
 * @param CI_Controller $ci
 * @return void
 */
once_action('theme_setup', function ($ci) {
	// Queue default dashboard font 'Fira Sans':
	$ci->hub->assets->google_font('Fira+Sans', 'ital,wght@0,400;0,700;1,400;1,700');

	// Queue Arabic/Persian dashboard font 'Noto Kufi Arabic':
	if ($ci->lang->current === 'arabic' || $ci->lang->current === 'persian') {
		$ci->hub->assets->google_font('Noto Kufi Arabic', '100..900');
	}

	// Enqueue FontAwesome and Bootstrap.
	$ci->hub->assets->fontawesome()->bootstrap();

	// Assets we queue in production mode.
	$style_css = 'assets/css/style'.(CI_DEBUG ? '' : '.min').'.css';
	$ci->hub->assets->css($ci->url->static($style_css), 'style', null, true);
});

/**
 * Adds IE9 support.
 *
 * @param  string $content
 * @return string
 */
once_filter('extra_head', function ($content) {
	add_ie9_support($output, !CI_DEBUG);
	return $content;
});

/**
 * Handles setting current layout to use.
 *
 * @param  string        $layout  Layout being filtered.
 * @param  CI_Controller $ci
 * @return string
 */
once_filter('theme_layout', function ($layout, $ci) {
	// Use `clean` layout for authentication (`Auth.php` controller).
	// if ($ci->router->is_class('auth')) {
	// 	return 'auth';
	// }

	// Use `full` layout for blog module.
	// if ($ci->router->is_module('pages')) {
	// 	return 'default';
	// }

	// Return what was passed as-is.
	return $layout;
});

/**
 * Enqueue partial view files.
 *
 * @param  CI_Controller $ci
 * @return void
 */
once_action('enqueue_partials', function ($ci) {
	// Default partials, queued no matter the section.
	$ci->hub->theme
		->add_partial('header')
		->add_partial('footer');

	// Nothing to had for blog module.
	if ($ci->router->module === 'blog') {
		return;
	}

	// Add default sidebar.
	$ci->hub->theme->add_partial('sidebar');
});

/**
 * Sets pagination configuration.
 *
 * @param  array         $config
 * @param  CI_Controller $ci
 * @return array
 */
once_filter('pagination', function ($config) {
	$config['full_tag_open'] = '<ul class="pagination pagination-sm pagination-centered d-flex align-items-start justify-content-center m-0">';
	$config['full_tag_close'] = '</ul>';
	$config['num_links'] = 2;
	$config['num_tag_open'] = '<li class="page-item">';
	$config['num_tag_close'] = '</li>';
	$config['prev_tag_open'] = '<li class="page-item">';
	$config['prev_tag_close'] = '</li>';
	$config['prev_link'] = '<i class="fas fa-fw fa-angle-left"></i>';
	$config['next_tag_open'] = '<li class="page-item">';
	$config['next_tag_close'] = '</li>';
	$config['next_link'] = '<i class="fas fa-fw fa-angle-right"></i>';
	$config['first_tag_open'] = '<li class="page-item">';
	$config['first_tag_close'] = '</li>';
	$config['first_link'] = '<i class="fas fa-fw fa-angles-left"></i>';
	$config['last_tag_open'] = '<li class="page-item">';
	$config['last_tag_close'] = '</li>';
	$config['last_link'] = '<i class="fas fa-fw fa-angles-right"></i>';
	$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
	$config['cur_tag_close'] = '<span class="visually-hidden">(current)</span></span></li>';
	$config['page_query_key'] = 'page';
	$config['display_pages'] = true;
	$config['attributes'] = ['class' => 'page-link'];
	$config['summary_tag_open'] = '<caption class="text-center small text-muted">';
	$config['summary_tag_close'] = '</caption>';
	$config['summary_text'] = 'pagination_summary_text';

	return $config;
});

/**
 * Change CAPTCHA image height, background color, border color,
 * text color, grid color, and image class.
 */
once_filter('captcha_font_size', fn($size) => 22);
once_filter('captcha_img_height', fn($height) => 41);
once_filter('captcha_background_color', fn($rgb) => [255, 255, 255]);
once_filter('captcha_border_color', fn($rgb) => [206, 212, 218, 127]);
once_filter('captcha_text_color', fn($rgb) => [33, 37, 41]);
once_filter('captcha_grid_color', fn($rgb) => [173, 181, 189, 50]);
once_filter('captcha_img_class', fn($class) => 'img-fluid rounded-2');

/**
 * Filters to set reCAPTCHA theme and size.
 * Only applicable if reCAPTCHA is enabled.
 */
once_filter('recaptcha_theme', fn($theme) => 'light'); // light or dark
once_filter('recaptcha_size', fn($size) => 'normal'); // normal or compact
