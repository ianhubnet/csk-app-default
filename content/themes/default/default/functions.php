<?php
defined('BASEPATH') OR die;

/**
 * Default theme functions.php file.
 *
 * This file is an example of how to use functions.php for your themes.
 * You can use a class or simply a list of functions to add your hooks.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Themes
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	1.33
 */
// --------------------------------------------------------------------
if ( ! class_exists('Default_theme', false)):
// --------------------------------------------------------------------
#[AllowDynamicProperties]
final class Default_theme {

	/**
	 * Instance of CI object.
	 * @var objet
	 */
	protected $ci;

	/**
	 * Flag used to set theme as active or not.
	 * @var bool
	 */
	public $active = false;

	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct() {
		$this->ci =& get_instance();

		// Make sure to load theme translation.
		add_filter('theme_translation', array($this, 'theme_translation'));

		// Register theme menus.
		add_action('theme_menus', array($this, 'theme_menus'));

		// Register theme thumbnails sizes and names.
		add_action('theme_images', array($this, 'theme_images'));

		if (is_dashboard()) {
			return;
		}

		$this->active = true;

		// Layouts files.
		add_filter('theme_layouts_path', array($this, 'theme_layouts_path'));

		// Partials files.
		add_filter('theme_partials_path', array($this, 'theme_partials_path'));

		// Widgets files.
		add_filter('theme_widgets_path', array($this, 'theme_widgets_path'));

		// Views files.
		add_filter('theme_views_path', array($this, 'theme_views_path'));

		// Enqueue our assets.
		add_action('after_theme_setup', array($this, 'after_theme_setup'));

		// Add IE8 support.
		add_filter('extra_head', array($this, 'extra_head'));

		// Partials enqueue for caching purpose.
		add_action('enqueue_partials', array($this, 'enqueue_partials'));

		// Theme layout manager.
		add_filter('theme_layout', array($this, 'theme_layout'));

		add_filter('pagination', array($this, 'pagination'));

		add_filter('skeleton_copyright', array($this, 'skeleton_copyright'));

		add_filter('skeleton_generator', array($this, 'skeleton_generator'));

		add_filter('alert_template', array($this, 'alert_template'));

		add_filter('alert_template_js', array($this, 'alert_template_js'));

		add_filter('jquery_validate_errorClass', array($this, 'jquery_validate_errorClass'));

		add_filter('jquery_validate_successClass', array($this, 'jquery_validate_successClass'));

		add_filter('jquery_validate_errorElement', array($this, 'jquery_validate_errorElement'));

		add_filter('jquery_validate_errorPlacement', array($this, 'jquery_validate_errorPlacement'));

		add_filter('jquery_validate_highlight', array($this, 'jquery_validate_highlight'));
	}

	// --------------------------------------------------------------------
	// Views paths methods.
	// --------------------------------------------------------------------

	/**
	 * Change paths to layouts files.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_layouts_path() {
		return $this->ci->config->theme_path('templates/layouts/');
	}

	/**
	 * Change paths to partials files.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_partials_path() {
		return $this->ci->config->theme_path('templates/partials/');
	}

	/**
	 * Change paths to widgets files.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_widgets_path() {
		return $this->ci->config->theme_path('templates/widgets/');
	}

	/**
	 * Change paths to views files.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_views_path() {
		return $this->ci->config->theme_path('templates/');
	}

	// --------------------------------------------------------------------
	// Theme translation.
	// --------------------------------------------------------------------

	/**
	 * Set the path to theme translation files.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_translation($path) {
		return $this->ci->config->theme_path('language');
	}

	// --------------------------------------------------------------------
	// Theme menus.
	// --------------------------------------------------------------------

	/**
	 * Register themes available menus.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_menus() {
		if ( ! isset($this->ci->menus))
		{
			return;
		}

		$this->ci->menus->add_location(array(
			'header-menu'  => 'lang:main_menu',		// Main menu (translated)
			'footer-menu'  => 'lang:footer_menu',	// Footer menu (translated)
			'sidebar-menu' => 'lang:sidebar_menu',	// Sidebar menu (translated)
		));
	}

	// --------------------------------------------------------------------
	// Theme images sizes.
	// --------------------------------------------------------------------

	/**
	 * Register themes images sizes.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_images() {
		// These sizes are dummy ones. Use yours depending on your theme.
		$this->ci->files->add_image_size('thumb', 260, 180, true);
		$this->ci->files->add_image_size('avatar', 100, 100, true);
	}

	// --------------------------------------------------------------------
	// Assets methods.
	// --------------------------------------------------------------------

	/**
	 * This method is triggered after theme was installed.
	 * @access 	public
	 */
	public function after_theme_setup() {
		// Load Google fonts.
		$this->ci->assets->css(
			'//fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Noto+Kufi+Arabic:wght@300;400;500;700&display=swap',
			'google-fonts',
			null,
			true);

		// Assets we need.
		$this->ci->assets
			->fontawesome()
			->bootstrap();

		// Live?
		if ($this->ci->core->is_live)
		{
			$this->ci->theme
				->add_meta('dns-prefetch', '//fonts.googleapis.com/', 'rel')
				->add_meta('dns-prefetch', '//cdnjs.cloudflare.com/', 'rel');

			$this->ci->assets->css($this->ci->config->theme_url('style.min.css'), 'style', null, true);
		}
		else
		{
			$this->ci->assets->css($this->ci->config->theme_url('style.css'), 'style', null, true);
		}
	}

	/**
	 * Add output before closing </head>
	 * @access 	public
	 */
	public function extra_head($output) {
		// We add support for old browsers.
		add_ie9_support($output, (ENVIRONMENT !== 'development'));
		return $output;
	}

	/**
	 * We enqueue our partial views so they get cached.
	 * @access 	public
	 */
	public function enqueue_partials() {
		$this->ci->theme->add_partial('navbar');
		$this->ci->theme->add_partial('sidebar');
		$this->ci->theme->add_partial('footer');
	}

	/**
	 * Handle our theme layouts.
	 * @access 	public
	 * @param 	string 	$layout 	The layout to use.
	 * @return 	string 	The layout to be used.
	 */

	public function theme_layout($layout) {
		// Change layout of Auth controller.
		if (is_controller('auth')) {
			return 'clean';
		}
		// In case of admin area.
		elseif (is_controller('admin')) {
			return 'admin';
		}

		// Always return the layout.
		return $layout;
	}

	// --------------------------------------------------------------------

	/**
	 * Uses Bootstrap for pagination.
	 * @access 	public
	 * @param 	array
	 * @return 	array
	 */
	public function pagination($args)
	{
		$args['full_tag_open']   = '<div class="text-center"><ul class="pagination pagination-sm pagination-centered mb-0">';
		$args['full_tag_close']  = '</ul></div>';
		$args['num_links']       = 5;
		$args['num_tag_open']    = '<li class="page-item">';
		$args['num_tag_close']   = '</li>';
		$args['prev_tag_open']   = '<li class="page-item">';
		$args['prev_tag_close']  = '</li>';
		$args['prev_link']       = '<i class="fa fa-fw fa-backward"></i>';
		$args['next_tag_open']   = '<li class="page-item">';
		$args['next_tag_close']  = '</li>';
		$args['next_link']       = '<i class="fa fa-fw fa-forward"></i>';
		$args['first_tag_open']  = '<li class="page-item">';
		$args['first_tag_close'] = '</li>';
		$args['first_link']      = '<i class="fa fa-fw fa-fast-backward"></i>';
		$args['last_tag_open']   = '<li class="page-item">';
		$args['last_tag_close']  = '</li>';
		$args['last_link']       = '<i class="fa fa-fw fa-fast-forward"></i>';
		$args['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
		$args['cur_tag_close']   = '<span class="sr-only">(current)</span></span></li>';

		return $args;
	}

	// --------------------------------------------------------------------
	// Other...
	// --------------------------------------------------------------------

	/**
	 * The CodeIgniter Skeleton comes with a copyright added to your final
	 * HTML output. We really appreciate the fact that you keep it, it is a
	 * way to say "Oh yeah, another person using my project.".
	 * If you want to remove it, and you have the right to do it, the filter
	 * below show you how to do it.
	 * @param 	string 	$copyright
	 * @return 	string
	 */
	public function skeleton_copyright($content)
	{
		// Change it or return an empty string
		// return null or $content = null;
		return $content;
	}

	// --------------------------------------------------------------------

	/**
	 * Removes or changes the Skeleton generator meta tag.
	 * @param 	string 	$content
	 * @return 	string
	 */
	function skeleton_generator($content)
	{
		// Change it or return an empty string
		// return null or $content = null;
		return $content;
	}

	// --------------------------------------------------------------------

	/**
	 * Because the Theme library comes with Bootstrap 4 alert template,
	 * we make sure to change the template to use Bootstrap 3 alert.
	 * @since 	2.0
	 */
	private $_alert_template = <<<HTML
<div class="{class} alert-dismissible" role="alert">
	{message}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%s"></button>
</div>
HTML;
	public function alert_template($output)
	{
		return $this->_alert_template;
	}

	// --------------------------------------------------------------------

	/**
	 * Because the Theme library comes with Bootstrap 4 alert template,
	 * we make sure to change the template to use Bootstrap 3 alert.
	 * @since 	2.0
	 */
	private $_alert_template_js = <<<JS
'<div class="{class} alert-dismissible fade show" role="alert">'
+ '{message}'
+ '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%s"></button>'
+ '</div>'
JS;
	public function alert_template_js($output)
	{
		return $this->_alert_template_js;
	}

	// --------------------------------------------------------------------

	/**
	 * The jQuery validate library comes with Bootstrap 4 defaults. We make
	 * sure to change to Bootstrap 3.
	 * @since 	2.0
	 */

	/**
	 * Class used for invalid inputs.
	 * @see https://jqueryvalidation.org/validate/#errorclass
	 */
	public function jquery_validate_errorClass($class)
	{
		return 'is-invalid';
	}

	// --------------------------------------------------------------------

	/**
	 * Class used for valid inputs.
	 * @see https://jqueryvalidation.org/validate/#errorclass
	 */
	public function jquery_validate_successClass($class)
	{
		return 'is-valid';
	}

	// --------------------------------------------------------------------

	/**
	 * Use this element type to create error messages and to look
	 * for existing error messages. Default: "label".
	 * @see https://jqueryvalidation.org/validate/#errorelement
	 */
	public function jquery_validate_errorElement($el)
	{
		return 'small';
	}

	// --------------------------------------------------------------------

	/**
	 * Customize placement of created error labels.
	 * @see https://jqueryvalidation.org/validate/#errorplacement
	 */
	private $_jquery_validate_errorPlacement = 'function (error, element) { error.addClass("invalid-feedback"); element.next(".invalid-feedback").remove(); if (element.prop("type") === "checkbox") { error.insertAfter(element.parent("label")); } else { error.insertAfter(element); } }';
	public function jquery_validate_errorPlacement($output)
	{
		return $this->_jquery_validate_errorPlacement;
	}

	// --------------------------------------------------------------------

	/**
	 * How to highlight invalid fields.
	 * @see https://jqueryvalidation.org/validate/#highlight
	 */
	private $_jquery_validate_highlight = 'function (element, errorClass, validClass) { $(element).addClass("is-invalid").removeClass("is-valid"); }';
	public function jquery_validate_highlight($function)
	{
		return $this->_jquery_validate_highlight;
	}

	// --------------------------------------------------------------------

	/**
	 * Called to revert changes made by option highlight,
	 * same arguments as highlight.
	 * @see https://jqueryvalidation.org/validate/#unhighlight
	 */
	private $_jquery_validate_unhighlight = 'function (element, errorClass, validClass) { $(element).addClass("is-valid").removeClass("is-invalid"); }';
	public function jquery_validate_unhighlight($function)
	{
		return $this->_jquery_validate_unhighlight;
	}

}
// --------------------------------------------------------------------
endif; // End of class Default_theme.
// --------------------------------------------------------------------

// Initialize class but skip the rest for dashboard.
$default_theme = new Default_theme();
if ( ! $default_theme->active) {
	return;
}

if ( ! function_exists('fa_icon')) {
	/**
	 * Useful to generate a fontawesome icon.
	 * @param   none
	 * @param   string $after
	 * @param   string $before
	 * @return  string
	 */
	function fa_icon($class = '', string $after = '', string $before = '') {
		static $template; // remember it.

		(empty($template)) && $template = '<i class="fa fa-fw fa-%s"></i>';

		$icon = sprintf($template, $class);

		(empty($after)) OR $icon .= $after;

		(empty($before)) OR $icon = $before.$icon;

		return $icon;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('bs_label')) {
	function bs_label($content = '', $type = 'default') {
		return "<span class=\"label label-{$type}\">{$content}</span>";
	}
}

/**
 * Example filters on how to edit captcha the way you want
 * @since 	1.0.0
 * We use a class for better performance and to avoid any possible
 * conflict with other components.
 */
if ( ! class_exists('Csk200_captcha_class', false))
{
	class Csk200_captcha_class {
		protected $ci;

		public function __construct() {
			$this->ci =& get_instance();
		}

		public function init() {
			add_filter('captcha_font_path',        array($this, 'font_path'));
			add_filter('captcha_font_size',        array($this, 'font_size'));
			add_filter('captcha_word_length',      array($this, 'word_length'));
			add_filter('captcha_img_width',        array($this, 'img_width'));
			add_filter('captcha_img_height',       array($this, 'img_height'));
			add_filter('captcha_background_color', array($this, 'background_color'));
			add_filter('captcha_border_color',     array($this, 'border_color'));
			add_filter('captcha_text_color',       array($this, 'text_color'));
			add_filter('captcha_grid_color',       array($this, 'grid_color'));
		}

		// Font file.
		public function font_path($path) {
			// To use theme's provided font.
			// return $this->ci->config->theme_path('assets/fonts/Vigasr.ttf');

			// To use CodeIgniter texb.ttf:
			// return BASEPATH.'fonts/texb.ttf';

			// To use GD ugly font:
			// return false;

			// To use default.
			return $path;
		}

		// Font size.
		public function font_size($size) {
			$size = 16;
			return $size;
		}

		// Word length.
		public function word_length($length) {
			$length = 7;
			return $length;
		}

		// Image width.
		public function img_width($w) {
			$w = 150;
			return $w;
		}

		// Image height.
		public function img_height($h) {
			$h = 34;
			return $h;
		}

		// Background color.
		public function background_color($rgb) {
			// Return RGB color.
			return array(255, 255, 255);
		}

		// Border color:
		public function border_color($rgb) {
			// Return RGB color.
			return array(204, 204, 204);
		}

		// Text Color:
		public function text_color($rgb) {
			// Return RGB color.
			return array(40, 96, 144);
		}

		// Grid color.
		public function grid_color($rgb) {
			// Return RGB color.
			return array(101, 173, 233);
		}

	}

	// Initialize class.
	$csk200_captcha_class = new Csk200_captcha_class();
	$csk200_captcha_class->init();
}
