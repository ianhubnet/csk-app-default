<?php
defined('BASEPATH') OR die;

/**
 * Captcha Configuration
 *
 * This files holds some default configurations that are passed
 * through filters making them editable by modules and themes.
 * @since 1.0
 */

// Images path and URL.
$config['img_path']  = FCPATH.'content/captcha/';
$config['img_url']   = common_url('captcha');
$config['img_class'] = apply_filters('captcha_img_class', 'csk-captcha');
$config['img_alt']   = apply_filters('captcha_img_alt', 'captcha');

// Catpcha font path, font size, word length and characters used.
$config['font_path']   = normalize_path(apply_filters('captcha_font_path', KBPATH.'fonts/vigasr.ttf'));
$config['font_size']   = apply_filters('captcha_font_size', 16);
$config['word_length'] = apply_filters('captcha_word_length', 6);
$config['pool']        = apply_filters('captcha_pool', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

// Captcha image dimensions and ID.
$config['img_width']  = apply_filters('captcha_img_width', 150);
$config['img_height'] = apply_filters('captcha_img_height', 32);
$config['img_id']     = apply_filters('captcha_img_id', 'captcha-img');

// Captcha expiration time.
$config['expiration'] = (MINUTE_IN_SECONDS * 5);

// Different elements colors.
$config['colors'] = array(
	'background' => apply_filters('captcha_background_color', array(255, 255, 255)),
	'border'     => apply_filters('captcha_border_color',     array(206, 212, 218)),
	'text'       => apply_filters('captcha_text_color',       array(73, 80, 87)),
	'grid'       => apply_filters('captcha_grid_color',       array(206, 212, 218)),
);
