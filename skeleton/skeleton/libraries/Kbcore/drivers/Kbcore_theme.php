<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_theme Class
 *
 * The bare bone of this app, the cherry on the cake, handles all views.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_theme extends KB_Driver
{
	/**
	 * CodeIgniter Skeleton copyright.
	 * @var string
	 */
	protected $skeleton_copyright = <<<HTML
\n<!--nocompress--><!--
\tWebsite proudly powered by {skeleton} ({ianhub_url}).
\tProject developed and maintained by {author} ({author_url}).
--><!--/nocompress-->
HTML;

	/**
	 * Header template
	 * @var string
	 */
	private $_html_header = <<<HTML
{doctype}{skeleton_copyright}
<html{html_class}{language_attributes} xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
<head>
	{analytics}

	<meta charset="{charset}">
	<meta http-equiv="Content-Type" content="text/html; charset={charset}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>{title}</title>

	<!-- start of head meta -->
	{meta_tags}
	<!-- end of head meta -->

	<!-- start of head styles -->
	{stylesheets}
	<!-- end of head styles -->

	<!-- start of extra head stuff -->
	{extra_head}
	<!-- end of extra head stuff -->

	{facebook_pixel_head}

</head>
<!-- end of head -->
<!-- start of body -->
<body{body_class}>
	{facebook_pixel_body}
	{google_tagmanager}
	{extra_views}
HTML;

	/**
	 * Footer template.
	 * @var string
	 */
	private $_html_footer = <<<HTML
	<!-- start of footer scripts -->
	{javascripts}
	<!-- end of footer scripts -->
</body>
</html>
HTML;

	/**
	 * Google analytics template.
	 * @var string
	 */
	protected $_html_google_analytics = <<<HTML
<!-- start of google analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=%1\$s"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '%1\$s');
</script>
<!-- end of google analytics -->
HTML;

	/**
	 * Google Tag Manager template (<head> part>.
	 * This will be inserted into the <head> section when a
	 * tag manager ID is provided.
	 * @var string
	 */
	private $_html_google_tagmanager_head = <<<HTML
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','%s');</script>
<!-- End Google Tag Manager -->
HTML;

	/**
	 * Google Tag Manager template (<body> part>.
	 * This will be inserted into the <body> section when a
	 * tag manager ID is provided.
	 * @var string
	 */
	private $_html_google_tagmanager_body = <<<HTML
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
HTML;

	/**
	 * Facebook Pixel template (<head> part).
	 * This will be inserted into the <head> section when a
	 * pixel ID is provided.
	 * @var string
	 */
	private $_html_facebook_pixel_head = <<<HTML
<!-- Facebook Pixel Code -->
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','%s');fbq('track','PageView');</script>
<!-- End Facebook Pixel Code -->
HTML;

	/**
	 * Facebook Pixel template (<body> part).
	 * This will be inserted into the <body> section when a
	 * pixel ID is provided.
	 * @var string
	 */
	private $_html_facebook_pixel_body = <<<HTML
<!-- Facebook Pixel Code (noscript) -->
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=%s&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code (noscript) -->
HTML;

	/**
	 * Default alert message template to use
	 * as a fallback if none is provided.
	 */
	private $_html_alert = <<<HTML
<div class="{class} alert-dismissible" role="alert">
	{message}
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%s"></button>
</div>
HTML;

	/**
	 * JavaSript alert template.
	 */
	private $_html_alert_js = <<<JS
'<div class="{class} alert-dismissible fade show" role="alert">'
+ '{message}'
+ '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%s"></button>'
+ '</div>'
JS;

	/**
	 * Alert template to use with Theme::template
	 * @var string
	 */
	protected $template_alert = <<<HTML
<div class="alert alert-%2\$s alert-dismissible" role="alert">
	%1\$s
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%3\$s"></button>
</div>
HTML;

	/**
	 * JavaSript alert template.
	 */
	protected $template_alert_js = <<<JS
'<div class="alert alert-%2\$s alert-dismissible fade show" role="alert">'
+ '%1\$s'
+ '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="%3\$s"></button>'
+ '</div>'
JS;

	/**
	 * Dashboard button templates.
	 * @since 	2.16
	 */

	// Generic dashboard button
	protected $template_button = '<a role="button" href="%1$s" class="btn btn-%3$s">%2$s</a>';

	// Button with icon (responsive)
	protected $template_button_icon = <<<HTML
<a role="button" href="%1\$s" class="btn btn-%3\$s">
	<i class="fa fa-fw fa-%4\$s"></i>
	<span class="d-none d-md-inline ms-1">%2\$s</span>
</a>
HTML;

	// Button with business icon (responsive)
	protected $template_button_business_icon = <<<HTML
<a role="button" href="%1\$s" class="btn btn-%3\$s">
	<i class="fab fa-fw fa-%4\$s"></i>
	<span class="d-none d-md-inline ms-1">%2\$s</span>
</a>
HTML;

	// Button with icon (responsive)
	protected $template_button_icon_attrs = <<<HTML
<a role="button" href="%1\$s" class="btn btn-%3\$s" %5\$s>
	<i class="fa fa-fw fa-%4\$s"></i>
	<span class="d-none d-md-inline ms-1">%2\$s</span>
</a>
HTML;

	// Button with icon (responsive)
	protected $template_button_business_icon_attrs = <<<HTML
<a role="button" href="%1\$s" class="btn btn-%3\$s" %5\$s>
	<i class="fab fa-fw fa-%4\$s"></i>
	<span class="d-none d-md-inline ms-1">%2\$s</span>
</a>
HTML;

	/**
	 * Current site language and temporary language.
	 *
	 * The idea behind the temporay language is to allow users to translate
	 * the content of the page to the chosen language if available.
	 * Note that nothing else but KB_Object or KB_User are affected, meaning
	 * that the whole site will remain the current selected language.
	 *
	 * @var string
	 */
	protected $idiom;
	protected $temp_idiom;

	/**
	 * Array of default alerts classes.
	 * @var  array
	 */
	protected $alert_classes = array(
		'info'    => 'alert alert-info',
		'error'   => 'alert alert-danger',
		'warning' => 'alert alert-warning',
		'success' => 'alert alert-success',
	);

	/**
	 * Flag used to check if we can use hooks.
	 * @var boolean
	 */
	private $use_hooks = false;

	/**
	 * Flag to tell whether we are on the back-end or the front-end.
	 * @var bool
	 */
	protected $is_dashboard = false;

	/**
	 * Array of acceptable theme screenshot extensions.
	 * @var array
	 */
	protected $screenshot_ext = array('.png', '.jpg', '.jpeg', '.gif', '.webp');

	/**
	 * Holds array of available themes.
	 * @var array
	 */
	protected $themes;

	/**
	 * Holds array of current themes details
	 * @var array
	 */
	protected $theme_details;

	/**
	 * Holds array of valid filters.
	 * @var array
	 */
	protected $_valid_filters = array();

	/**
	 * Holds the current active theme.
	 * @var string
	 */
	protected $current;

	/**
	 * Set to true when the language file is loaded.
	 * @var boolean
	 */
	protected $translation_loaded = false;

	/**
	 * Holds the current theme's language index.
	 * @var string
	 */
	protected $theme_language_index = '';

	/**
	 * Holds the currently used module's name (folder).
	 * @var string
	 */
	protected $module = null;

	/**
	 * Holds the path to the current module.
	 * @var string
	 */
	protected $module_path = null;

	/**
	 * Holds the currently accessed controller.
	 * @var string
	 */
	protected $controller = null;

	/**
	 * Holds the currently accessed controller's method.
	 * @var string
	 */
	protected $method = null;

	/**
	 * Holds the currently used layout.
	 * @var string
	 */
	protected $layout;

	/**
	 * Holds the currently loaded view.
	 * @var string
	 */
	protected $view;

	/**
	 * Holds array of inline files/views to display.
	 * @var array
	 */
	protected $inline_views = array();

	/**
	 * Holds an array of loaded partial views.
	 * @var array
	 */
	protected $partials = array();

	/**
	 * Holds an array of loaded widget views.
	 * @var array
	 */
	protected $widgets = array();

	/**
	 * Holds array of cached header files.
	 * @var array
	 */
	protected $headers;

	/**
	 * Holds array of cached footer files.
	 * @var array
	 */
	protected $footers;

	/**
	 * Holds an array of data to be passed to views.
	 * @var array
	 */
	protected $data = array();

	/**
	 * Holds the array of <html> tag classes.
	 * @var array
	 */
	protected $html_classes = array();

	/**
	 * Holds the array of <body> tag classes.
	 * @var array
	 */
	protected $body_classes = array();

	/**
	 * Holds the current page's title.
	 * @var string
	 */
	protected $title;

	/**
	 * Holds the page's title parts separator.
	 * @var string
	 */
	public $title_separator = ' &#8212; ';

	/**
	 * Holds the current page's description.
	 * @var string
	 */
	protected $description;

	/**
	 * Holds the current page's keywords.
	 * @var string
	 */
	protected $keywords;

	/**
	 * Holds the current page's author.
	 * @var string
	 */
	protected $author;

	/**
	 * Holds current page copyright.
	 * @var string
	 */
	protected $copyright;

	/**
	 * Translatable site info.
	 * @var string
	 */
	public $site_name;
	public $site_description;
	public $site_keywords;
	public $site_author;

	/**
	 * Holds the current page's schema.
	 * @var array
	 */
	protected $schema = array();

	/**
	 * Holds an array of all meta tags.
	 * @var array
	 */
	protected $meta_tags = array();

	/**
	 * Flag used to prevent meta double-set.
	 * @var bool
	 */
	protected $meta_set = false;

	/**
	 * Holds extra content to be put before the closing </head> tag.
	 * @var string
	 */
	protected $extra_head = null;

	/**
	 * Holds the current view content.
	 * @var string
	 */
	protected $content;

	/**
	 * Holds the time for which content is cached. Default: 0
	 * @var integer
	 */
	protected $cache_lifetime = 0;

	/**
	 * Whether to compress the final output or not.
	 * @var boolean
	 */
	protected $compress = false;

	/**
	 * What to search for when compressing.
	 * Only set when compress is on.
	 * @var array
	 */
	protected $compress_from;

	/**
	 * With what to replace for when compressing.
	 * Only set when compress is on.
	 * @var array
	 */
	protected $compress_to;

	/**
	 * Whether to beautify the final output.
	 * @since   2.16
	 * @var     boolean
	 */
	protected $beautify = false;

	/**
	 * Holds instance of Beautify_Html object.
	 * @var object
	 */
	protected $beautifier;

	/**
	 * Holds the array of enqueued alert messages.
	 * @var array
	 */
	protected $alerts = array();

	/**
	 * Paths used to look for files.
	 * @var array
	 */
	protected $file_paths = array(APPPATH, KBPATH);

	// --------------------------------------------------------------------

	/**
	 * Magic method for setting a property value.
	 *
	 * @param 	string 	$key 	The property key.
	 * @param 	string 	$value 	The property value.
	 */
	public function __set($key, $val)
	{
		$this->{$key} = $val;
	}

	// --------------------------------------------------------------------

	/**
	 * Initializes class preferences.
	 * @param   array   $config
	 * @return  void
	 */
	public function initialize()
	{
		// No need to do anything for AJAX or API requests.
		if (is_ajax(true) OR is_api())
		{
			return;
		}

		// Can we use hooks?
		$this->use_hooks = (function_exists('apply_filters'));

		// Store information about module, controller and method.
		$this->set('module', $this->module = $this->ci->router->module, true);
		$this->controller = $this->ci->router->class;
		$this->method = $this->ci->router->method;

		// Add module's path if found.
		if ($this->module && ($mod_path = $this->ci->router->module_path($this->module)))
		{
			$this->module_path = $mod_path;
			array_unshift($this->file_paths, $this->module_path);
		}

		// Overridden title separator.
		($title_separator = $this->ci->config->item('title_separator')) && $this->title_separator = $title_separator;
		$this->title_separator = ' '.trim($this->title_separator).' ';

		// Overridden output compression.
		$this->compress = $this->ci->config->item('theme_compress', null, $this->_parent->is_live);

		/**
		 * Beautify output.
		 *
		 * Priority is to compression, if set to true, the output
		 * won't be beautified. If the output isn't set to be compressed
		 * we only beautify output that's not dashboard.
		 *
		 * @since 2.16
		 */
		$this->beautify = $this->ci->config->item('theme_beautify', null, false);
		($this->beautify) && $this->beautify = ( ! $this->compress);

		// Overridden cache lifetime.
		if ($cache_lifetime = $this->ci->config->item('cache_lifetime'))
		{
			$this->cache_lifetime = (int) $cache_lifetime;
		}

		// Set dashboard and current theme properties.
		$this->is_dashboard = $this->ci->uri->is_dashboard;

		// Load the current theme's functions.php file.
		if ( ! is_file($func_file = $this->ci->config->theme_path('functions.php')))
		{
			$_err = 'Unable to locate the theme\'s "functions.php" file: '.$this->current();
			log_message('error', $_err);

			if ($this->is_dashboard)
			{
				$this->set_alert($_err, 'error');
			}
			else
			{
				exit($_err);
			}
		}
		else
		{
			include_once $func_file;
		}

		// make the currently active them available for views.
		$this->set('current_theme', $this->current(), true);

		// Things we do when not on dashboard.
		if ( ! $this->is_dashboard)
		{
			// Load theme's translations.
			$this->load_translation();

			// See if the theme has custom image sizes.
			if (has_action('theme_images'))
			{
				$this->do_action('theme_images');
				$this->_parent->files->_set_images_sizes($this->current());
			}
		}

		// Translated site name and description?
		$this->site_name        = $this->ci->lang->line('config:site_name');
		$this->site_description = $this->ci->lang->line('config:site_description');
		$this->site_keywords    = $this->ci->lang->line('config:site_keywords');
		$this->site_author      = $this->ci->lang->line('config:site_author');

		// Make them globally available.
		$this->set('site_name', $this->site_name, true);
		$this->set('site_description', $this->site_description, true);

		// Replace credit URL.
		$this->skeleton_copyright = str_replace(
			array('{skeleton}', '{ianhub_url}', '{author}', '{author_url}'),
			array(KPlatform::LABEL, KPlatform::SITE_URL, KPlatform::AUTHOR, KPlatform::AUTHOR_URL),
			$this->skeleton_copyright
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Sets current view language.
	 *
	 * @param 	string 	$idiom
	 * @return 	void
	 */
	private function set_idiom($idiom = null)
	{
		$this->idiom = $this->ci->lang->idiom;
		$this->temp_idiom = ($idiom && $this->_parent->lang->exists($idiom)) ? $idiom : $this->idiom;
		$this->set('idiom', $this->idiom, true);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of available themes with optional details.
	 * @param   bool    $details    Whether to retrieve themes details.
	 * @return  array   Array of themes folder if $details is set to false, else
	 * array of available themes with their details.
	 */
	public function get_themes($details = false)
	{
		if ( ! isset($this->themes))
		{
			$this->themes = array();
			$themes_path = $this->ci->config->themes_path();

			if ($handle = opendir($themes_path))
			{
				$ignored = array('.', '..', 'index.html', 'index.php', '.htaccess', '__MACOSX');

				while($file = readdir($handle))
				{
					if (is_dir($themes_path.'/'.$file) && ! in_array($file, $ignored))
					{
						$this->themes[] = $file;
					}
				}
			}
		}

		if ($details && ! empty($themes = $this->themes))
		{
			foreach ($themes as $i => $folder)
			{
				if ($details = $this->get_theme_details($folder))
				{
					$themes[$folder] = $details;
				}

				unset($themes[$i]);
			}

			return $themes;
		}

		return $this->themes;
	}

	// --------------------------------------------------------------------
	// Current theme methods.
	// --------------------------------------------------------------------

	/**
	 * Returns the currently active theme depending on the site area.
	 * @param   none
	 * @return  string
	 */
	public function current()
	{
		(isset($this->current)) OR $this->current = $this->ci->config->item('theme', null, 'default');

		return $this->current;
	}

	// --------------------------------------------------------------------

	/**
	 * Dynamically sets the current theme.
	 * @param   string  $theme  The theme's folder name.
	 * @return  Theme
	 */
	public function set_theme($theme = null)
	{
		($theme && $theme !== $this->current() && ! $this->is_dashboard) && $this->current = $theme;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the currently active theme (For backward compatibility).
	 * @param   none
	 * @return  string
	 */
	public function get_theme()
	{
		return $this->current();
	}

	// --------------------------------------------------------------------
	// Theme details.
	// --------------------------------------------------------------------

	/**
	 * Returns details about the given theme.
	 * @param   string  $folder     The theme's folder name.
	 * @return  mixed   Array of details if valid, else false.
	 */
	public function get_theme_details($folder = null)
	{
		(empty($folder)) && $folder = $this->current();

		if ( ! isset($this->theme_details, $this->theme_details[$folder]))
		{
			if ( ! $folder)
			{
				return false;
			}

			if ( ! is_file($info_file = $this->ci->config->themes_path($folder.'/info.php'))
				OR empty($info = include_once $info_file)
				OR ! is_array($info))
			{
				return false;
			}

			$details = array_merge_exists($this->_theme_headers(), $info);

			if (empty($details['screenshot']))
			{
				$details['screenshot'] = $this->ci->config->common_url('img/theme-blank.png');

				foreach ($this->screenshot_ext as $ext)
				{
					if (is_file($this->ci->config->themes_path($folder.'/screenshot'.$ext)))
					{
						$details['screenshot'] = $this->ci->config->themes_url($folder.'/screenshot'.$ext);
						break;
					}
				}
			}

			// Allow translations.
			if (isset($info['translations'], $info['translations'][$this->ci->lang->idiom]))
			{
				foreach ($this->_localized_headers() as $key)
				{
					if (isset($info['translations'][$this->ci->lang->idiom][$key]))
					{
						$details[$key] = $info['translations'][$this->ci->lang->idiom][$key];
					}
				}
			}

			// Add extra stuff.
			$details['folder'] = $folder;
			$details['full_path'] = $this->ci->config->themes_path($folder);

			// Cache it first.
			return $this->theme_details[$folder] = array_clean_keys($details);
		}

		return $this->theme_details[$folder];
	}

	// --------------------------------------------------------------------
	// Layout methods.
	// --------------------------------------------------------------------

	/**
	 * Changes the currently used layout.
	 * @param   string  $layout     the layout's name.
	 * @return  object
	 */
	public function set_layout($layout = 'default')
	{
		$this->layout = (empty($layout)) ? 'default' : $layout;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current layout's name.
	 * @param   none
	 * @return  string.
	 */
	public function get_layout($layout = 'default')
	{
		$this->layout = $this->apply_filters($this->is_dashboard ? 'admin_layout' : 'theme_layout', $layout);

		return $this->layout;
	}

	// --------------------------------------------------------------------

	/**
	 * layout_exists
	 *
	 * Method for checking the existence of the layout.
	 *
	 * @param   string  $layout     The layout to check (Optional).
	 * @return  bool    true if the layout exists, else false.
	 */
	public function layout_exists($layout = null)
	{
		$layout = normalize_file((empty($layout) ? $this->get_layout('default') : $layout), false);

		$full_path = $this->apply_filters(
			$this->is_dashboard ? 'admin_layouts_path' : 'theme_layouts_path',
			$this->ci->config->theme_path()
		);

		return (is_file($full_path.'/'.$layout));
	}

	// --------------------------------------------------------------------
	// Partials methods.
	// --------------------------------------------------------------------

	/**
	 * Adds partial view
	 * @param   string  $view   view file to load
	 * @param   array   $data   array of data to pass
	 * @param   string  $name   name of the variable to use
	 */
	public function add_partial($view, $data = array(), $name = null)
	{
		if (is_string($data))
		{
			$name = $data;
			$data = array();
		}
		elseif (empty($name))
		{
			$name = basename($view);
		}

		(isset($this->partials[$name])) OR $this->partials[$name] = $this->_load_file($view, $data, 'partials');

		return $this;
	}
	// --------------------------------------------------------------------

	/**
	 * Loads a partial view and returns it to be echoed later.
	 * @param   string  $view   view file to load
	 * @param   array   $data   array of data to pass
	 * @param   string  $name   name of the variable to use
	 */
	public function load_partial($view, $data = array(), $name = null)
	{
		if (is_string($data))
		{
			$name = $data;
			$data = array();
		}
		elseif (empty($name))
		{
			$name = basename($view);
		}

		(isset($this->partials[$name])) OR $this->partials[$name] = $this->_load_file($view, $data, 'partials');

		return $this->partials[$name];
	}

	// --------------------------------------------------------------------

	/**
	 * Returns true if a partial with the given name exists.
	 * @since 	2.16
	 * @access 	public
	 * @param 	string 	$name 	the partial's name
	 * @return 	boolean true if found, else false.
	 */
	public function has_partial($name)
	{
		return (isset($this->partials[basename($name)]));
	}

	// --------------------------------------------------------------------

	/**
	 * Displays a partial view alone.
	 * @param   string  $view   the partial view name
	 * @param   array   $data   array of data to pass
	 * @param   bool    $load   load it if not cached?
	 * @return  mixed
	 */
	public function partial($view, $data = array(), $load = true)
	{
		$name = basename($view);

		$this->do_action('get_partial_'.$name);

		if (isset($this->partials[$name]))
		{
			return $this->partials[$name];
		}

		return ($load) ? null : $this->_load_file($view, $data, 'partials');
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the partial file exists or not.
	 * @param   string  $partial    The partial file to check.
	 * @return  bool    true if the view is found, else false.
	 */
	public function partial_exists($partial = null)
	{
		if ( ! empty($partial))
		{
			$partial = normalize_file($partial, false);

			$full_path = $this->apply_filters(
				$this->is_dashboard ? 'admin_partials_path' : 'theme_partials_path',
				$this->ci->config->theme_path()
			);

			return (is_file($full_path.'/'.$partial));
		}

		return false;
	}

	// --------------------------------------------------------------------
	// Widgets methods.
	// --------------------------------------------------------------------

	/**
	 * Adds widget view
	 * @param   string  $view   view file to load
	 * @param   array   $data   array of data to pass
	 * @param   string  $name   name of the variable to use
	 */
	public function add_widget($view, $data = array(), $name = null)
	{
		if (is_string($data))
		{
			$name = $data;
			$data = array();
		}
		elseif (empty($name))
		{
			$name = basename($view);
		}

		(isset($this->widgets[$name])) OR $this->widgets[$name] = $this->_load_file($view, $data, 'widgets');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a widget and returns the view to be echoed later.
	 * @param   string  $view   view file to load
	 * @param   array   $data   array of data to pass
	 * @param   string  $name   name of the variable to use
	 */
	public function load_widget($view, $data = array(), $name = null)
	{
		if (is_string($data))
		{
			$name = $data;
			$data = array();
		}
		elseif (empty($name))
		{
			$name = basename($view);
		}

		(isset($this->widgets[$name])) OR $this->widgets[$name] = $this->_load_file($view, $data, 'widgets');

		return $this->widgets[$name];
	}

	// --------------------------------------------------------------------

	/**
	 * Returns true if a widget with the given name exists.
	 * @since 	2.16
	 * @access 	public
	 * @param 	string 	$name 	the widget's name
	 * @return 	boolean true if found, else false.
	 */
	public function has_widget($name)
	{
		return (isset($this->widgets[basename($name)]));
	}

	// --------------------------------------------------------------------

	/**
	 * Displays a widget view alone.
	 * @param   string  $view   the widget view name
	 * @param   array   $data   array of data to pass
	 * @param   bool    $load   load it if not cached?
	 * @return  mixed
	 */
	public function widget($view, $data = array(), $load = true)
	{
		$name = basename($view);

		$this->do_action('get_widget_'.$name);

		if (isset($this->widgets[$name]))
		{
			return $this->widgets[$name];
		}

		return ($load) ? null : $this->_load_file($view, $data, 'widgets');
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the widget file exists or not.
	 * @param   string  $widget     The widget file to check.
	 * @return  bool    true if the view is found, else false.
	 */
	public function widget_exists($widget = null)
	{
		if ( ! empty($widget))
		{
			$widget = normalize_file($widget, false);

			$full_path = $this->apply_filters(
				$this->is_dashboard ? 'admin_widgets_path' : 'theme_widgets_path',
				$this->ci->config->theme_path()
			);

			return (is_file($full_path.'/'.$widget));
		}

		return false;
	}

	// --------------------------------------------------------------------
	// Inline view methods.
	// --------------------------------------------------------------------

	/**
	 * Adds a file that's output right after <body> tag.
	 * @param 	string 	$file 	view file to load
	 * @param 	array 	$data 	array of data to pass
	 * @param 	bool 	$both 	whether to add file to dashboard as well
	 */
	public function add_view($view, $data = array(), $both = false)
	{
		if (($both && $this->is_dashboard) OR ! $this->is_dashboard)
		{
			(is_array($data)) && $this->ci->load->vars($data);

			$this->inline_views[] = $this->ci->load->file($view, true);
		}

		return $this;
	}

	// --------------------------------------------------------------------
	// Views methods.
	// --------------------------------------------------------------------

	/**
	 * Changes the currently used view.
	 * @param   string  $view   the view's name.
	 * @return  object
	 */
	public function set_view($view = null)
	{
		$this->view = (empty($view)) ? $this->_guess_view() : $view;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current view's name.
	 * @param   none
	 * @return  string.
	 */
	public function get_view()
	{
		(isset($this->view)) OR $this->view = $this->_guess_view();

		// Front-end view.
		if ( ! $this->is_dashboard)
		{
			return $this->view = $this->apply_filters('theme_view', $this->view);
		}

		if ($this->module)
		{
			return $this->view = preg_replace("/{$this->module}\//", '', $this->view);
		}

		if (has_filter('admin_view'))
		{
			return $this->view = $this->apply_filters('admin_view', $this->view);
		}

		if ($this->is_dashboard)
		{
			$view = str_replace(array(KB_ADMIN.'/', 'admin/'), '', isset($this->view) ? $this->view : $this->method);
			$this->view = ($this->module && ($modpath = $this->ci->router->module_path($this->module)))
				? KB_ADMIN.'/'.$view : $view;
		}

		return $this->view;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the view file exists or not.
	 * @param   string  $view   The view file to check.
	 * @return  bool    true if the view is found, else false.
	 */
	public function view_exists($view = null)
	{
		$view = normalize_file((empty($view)) && $view = $this->get_view(), false);

		$full_path = $this->apply_filters(
			$this->is_dashboard ? 'admin_views_path' : 'theme_views_path',
			$this->ci->config->theme_path()
		);

		return (is_file($full_path.'/'.$view));
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to guess the view load.
	 * @param   none
	 * @return  string
	 */
	protected function _guess_view()
	{
		$view = array();

		($this->is_dashboard) && $view[] = 'admin';

		isset($this->module) OR $this->module = $this->ci->router->module;

		isset($this->controller) OR $this->controller = $this->ci->router->class;

		if ($this->module !== $this->controller)
		{
			(empty($this->module)) OR $view[] = $this->module;

			$view[] = $this->controller;
		}

		$view[] = $this->method;

		return implode('/', array_clean($view));
	}

	// --------------------------------------------------------------------
	// Data setter
	// --------------------------------------------------------------------

	/**
	 * Add variables to views.
	 * @param   string  $name   Variable's name.
	 * @param   mixed   $value  Variable's value.
	 * @param   bool    $global Whether to make it global or not.
	 * @return  object
	 */
	public function set($name, $value = null, $global = false)
	{
		if (is_array($name) OR is_object($name))
		{
			$global = (is_bool($value)) ? $value : $global;

			foreach ($name as $key => $val)
			{
				if ( ! $global)
				{
					$this->data[$key] = $val;
				}
				else
				{
					$this->ci->load->vars($key, $val);
				}
			}
		}
		elseif ( ! $global)
		{
			$this->data[$name] = $value;
		}
		else
		{
			$this->ci->load->vars($name, $value);
		}

		return $this;
	}

	// --------------------------------------------------------------------
	// Title & Description methods.
	// --------------------------------------------------------------------

	/**
	 * Sets the page title.
	 * @param   string  $title
	 * @return  object
	 */
	public function set_title()
	{
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];

			$args[] = empty($this->site_name) ? KB_LABEL : $this->site_name;

			$this->title = array_unique(array_filter(array_map('trim', $args)));
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current page's title.
	 * @param   string  $before     string to be prepended.
	 * @param   string  $after      string to be appended.
	 * @return  string
	 */
	public function get_title($before = null, $after = null)
	{
		// Already set?
		if (is_string($this->title))
		{
			return $this->title;
		}

		(isset($this->title)) OR $this->title = $this->_guess_title();

		(is_array($this->title)) OR $this->title = (array) $this->title;

		$this->title = $this->apply_filters(
			$this->is_dashboard ? 'admin_title' : 'the_title',
			$this->title
		);

		(empty($before)) OR array_unshift($this->title, $before);

		(empty($after)) OR array_push($this->title, $after);

		$this->title = implode($this->title_separator, array_clean($this->title));

		if ($this->is_dashboard)
		{
			$skeleton_title = $this->apply_filters('skeleton_title', ' &lsaquo; '.$this->ci->lang->line('dashboard'));

			(empty($skeleton_title)) OR $this->title .= $skeleton_title;
		}

		return $this->title;
	}

	// --------------------------------------------------------------------

	/**
	 * Attempt to guess the title if it's not set.
	 * @param   none
	 * @return  array
	 */
	protected function _guess_title()
	{
		$title = array();

		// Use site name and description if on the homepage.
		if ($this->ci->router->is_homepage())
		{
			$title[] = $this->site_name;

			(empty($this->site_description)) OR $title[] = $this->site_description;
		}

		// Not on the homepage?
		else
		{
			$title[] = $this->module;
			$title[] = $this->controller;

			($this->is_dashboard) && $title[] = KB_ADMIN;

			($this->method !== 'index') && $title[] = $this->method;

			in_array($this->site_name, $title) OR $title[] = $this->site_name;
		}

		$title = array_map('ucwords', array_clean($title));

		return $this->apply_filters('guess_title', $title);
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the page's description.
	 * @access 	public
	 * @param 	string 	$description
	 * @return 	object
	 */
	public function set_description($description = null)
	{
		(empty($description)) OR $this->add_meta('description', $this->description = $description);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the description of the page if found and fallbacks to site's.
	 * @access 	public
	 * @return 	string
	 */
	public function get_description()
	{
		return (empty($this->description)) ? $this->ci->config->item('site_description') : $this->description;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the page's keywords.
	 * @access 	public
	 * @param 	string 	$keywords
	 * @return 	object
	 */
	public function set_keywords($keywords = null)
	{
		(empty($keywords)) OR $this->add_meta('keywords', $this->keywords = $keywords);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the keywords of the page if found and fallbacks to site's.
	 * @access 	public
	 * @return 	string
	 */
	public function get_keywords()
	{
		return (empty($this->keywords)) ? $this->ci->config->item('site_keywords') : $this->keywords;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the page's author.
	 * @access 	public
	 * @param 	string 	$author
	 * @return 	object
	 */
	public function set_author($author = null)
	{
		(empty($author)) OR $this->add_meta('author', $this->author = $author);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the author of the page if found and fallbacks to site's.
	 * @access 	public
	 * @return 	string
	 */
	public function get_author()
	{
		return (empty($this->author)) ? $this->ci->config->item('site_author') : $this->author;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the page's copyright.
	 * @access 	public
	 * @param 	string 	$copyright
	 * @return 	object
	 */
	public function set_copyright($copyright = null)
	{
		(empty($copyright)) OR $this->add_meta('copyright', $this->copyright = $copyright);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the copyright of the page if found and fallbacks to site's.
	 * @access 	public
	 * @return 	string
	 */
	public function get_copyright()
	{
		return (empty($this->copyright)) ? $this->site_name : $this->copyright;
	}

	// --------------------------------------------------------------------
	// Schema Methods
	// --------------------------------------------------------------------

	/**
	 * Sets JSON-LD Structured Data
	 *
	 * @param 	string 	$type
	 * @param 	mixed 	$data 	Object or array of data
	 * @return 	Kbcore_theme
	 */
	public function set_schema($type, $data = null)
	{
		if (is_object($data))
		{
			// Defaults.
			$schema_data['@context'] = 'https://schema.org';
			$schema_data['@type'] = $type;

			// Headline/Title and Description
			$schema_data['headline'] = empty($data->name) ? $this->site_name : $data->name;
			$schema_data['description'] = empty($data->description) ? $this->site_description : $data->description;

			// Entity URL.
			$schema_data['url'] = empty($data->username)
				? $this->ci->config->current_url()
				: $this->ci->config->site_url($data->subtype.'/'.$data->username);

			// Schema image.
			$site_logo = $this->ci->config->common_url('img/apple-touch-icon.png');
			$schema_data['image'] = empty($data->thumbnail) ? $site_logo : $data->thumbnail;

			// Date published and modified.
			$schema_data['datePublished'] = date('c', $data->created_at);
			$schema_data['dateModified'] = date('c', $data->updated_at);

			// Schema author.
			if ($data->owner_id > 0 && ($author = $this->_parent->users->get($data->owner_id)))
			{
				$schema_data['author'] = array(
					'@type' => 'Person',
					'name' => $author->full_name
				);
			}

			// Schema publisher.
			$schema_data['publisher'] = array(
				'@type' => 'Organization',
				'name' => $this->site_name,
				'logo' => array(
					'@type' => 'ImageObject',
					'url' => $site_logo
				),
			);

			// Set final schema.
			$this->schema = $schema_data;
		}
		elseif (is_array($data) && ! empty($data))
		{
			$this->schema = array_merge(array('@type' => $type), $data);
		}

		return $this;
	}

	// --------------------------------------------------------------------
	// Meta tags methods.
	// --------------------------------------------------------------------

	/**
	 * Quick action to add meta tags to given page.
	 *
	 * @since   1.0
	 * @since   1.33   Removed the favicon to let themes decide what to use.
	 * @since 	2.16 	Moved from Kbcore to Theme
	 *
	 * @access  public
	 * @param   mixed   $entity 	The entity
	 * @author  Kader Bouyakoub
	 * @return  object
	 */
	public function set_meta($entity = null)
	{
		// Meta already set?
		if ($this->meta_set)
		{
			return $this;
		}

		$this->meta_set = true;

		// Static URL 'dns-prefetch'
		if ( ! empty($val = $this->ci->config->slash_item('static_url'))
			&& $val !== $this->ci->config->slash_item('base_url'))
		{
			$this->add_meta('dns-prefetch', $val, 'rel');
		}

		// We add favicon (fixed for the dashboard).
		$favicon = $this->ci->config->common_url('img/favicon.ico');

		// changeable for public area.
		if ( ! $this->is_dashboard)
		{
			if ( ! empty($val = $this->ci->config->item('site_favicon')))
			{
				$favicon = $val;
			}
			elseif (is_file(FCPATH.'favicon.ico'))
			{
				$favicon = $this->ci->config->base_url('favicon.ico');
			}
		}

		$this->add_meta('icon', $favicon, 'rel', 'type="image/x-icon"');

		// Site name and default title.
		$this->add_meta('application-name', $this->site_name);

		// Site description only if not forced.
		if (! empty($description = $this->get_description()))
		{
			$this->add_meta('description', $description .= $this->title_separator.$this->site_name);
		}

		// Site keywords.
		if ( ! empty($val = $this->get_keywords()))
		{
			$this->add_meta('keywords', $val);
		}

		// Language meta
		$this->add_meta('language', strtoupper($this->_parent->lang->current('code')));

		// Add site's author and copyright if found.
		if ( ! empty($val = $this->get_author()))
		{
			$this->add_meta('author', $val);
		}
		if ( ! empty($val = $this->get_copyright()))
		{
			$this->add_meta('copyright', $val);
		}

		// Add facebook app id
		if ( ! empty($val = $this->ci->config->item('facebook_app_id')))
		{
			$this->add_meta('fb:app_id', $val);
		}

		// Add google site verification IF found.
		if ( ! empty($val = $this->ci->config->item('google_site_verification')))
		{
			$this->add_meta('google-site-verification', $val);
		}

		// Social stuff
		$this->add_meta('title', $this->get_title());

		// no more for dashboard
		if ($this->is_dashboard)
		{
			return $this;
		}
		else
		{
			$this->set_alternate_meta($this->ci->uri->uri_string(true));
		}

		// Canonical URL.
		$this->add_meta('canonical', $this->ci->config->current_url(true), 'rel');

		// Site image.
		$val = $this->ci->config->common_url('img/apple-touch-icon.png');
		if ( ! empty($val = apply_filters('site_image', $val)))
		{
			$this->add_meta('apple-touch-icon', $val, 'rel');
			$this->add_meta('og:image', $val, 'meta', 'type="image/x-icon"');
			$this->add_meta('og:image:alt', $this->get_title());
			$this->add_meta('og:image:width', '316', 'meta');
			$this->add_meta('og:image:height', '316', 'meta');
		}

		$this->add_meta('og:url', $this->ci->config->current_url(true));
		$this->add_meta('og:type', 'website');
		$this->add_meta('og:site_name', $this->site_name);
		$this->add_meta('og:title', $this->get_title(), 'meta', 'itemprop="name"');
		$this->add_meta('twitter:card', 'summary');
		$this->add_meta('twitter:domain', parse_url($this->ci->config->base_url(), PHP_URL_HOST));

		// Setting 'og:description'
		(isset($description)) && $this->add_meta('og:description', $description, 'meta', 'itemprop="description"');

		// Is it a user's profile page?
		if ($entity instanceof KB_User)
		{
			// Open Graph Type
			$this->add_meta('og:type', 'profile');

			// Set meta if possible.
			(method_exists($entity, 'set_meta')) && $entity->set_meta();

			// Translate if possible.
			(method_exists($entity, 'translate')) && $entity->translate($this->ci, $this->temp_idiom);
		}
		// An object or group?
		elseif ($entity instanceof KB_Object OR $entity instanceof KB_Group)
		{
			// Open Graph Type
			$this->add_meta('og:type', 'website');

			// Set meta if possible.
			(method_exists($entity, 'set_meta')) && $entity->set_meta();

			// Translate if possible.
			(method_exists($entity, 'translate')) && $entity->translate($this->ci, $this->temp_idiom);

			// Parse name, description and content.
			(method_exists($entity, 'parse')) && $entity->parse($this->ci);

			// Revised tag.
			if ($entity->updated_at > 0 && $entity->updated_at <> $entity->created_at)
			{
				$this->add_meta('revised', date('l, F jS, Y, g:i a', $entity->updated_at));
			}

			// Entity's meta title.
			if ( ! empty($val = $entity->data->meta_title))
			{
				$val = $this->_parent->lang->parse($val);
				$this->add_meta('title', $val);
				$this->add_meta('og:title', $val);
			}

			// Entity's meta description.
			if ( ! empty($val = $entity->data->meta_description))
			{
				$val = $this->_parent->lang->parse($val);
				$this->add_meta('description', $val);
				$this->add_meta('og:description', $val, 'meta', 'itemprop="description"');
			}

			// Entity's meta keywords.
			empty($val = $entity->data->meta_keywords) OR $this->add_meta('keywords', $this->_parent->lang->parse($val));

			// Entity's image.
			if ( ! empty($val = $entity->data->opengraph))
			{
				$this->add_meta('og:image', $val, 'meta', 'type="image/x-icon"');
				$this->add_meta('og:image:width', '1200', 'meta');
				$this->add_meta('og:image:height', '630', 'meta');
			}
			elseif ( ! empty($val = $entity->data->medium))
			{
				$this->add_meta('og:image', $val, 'meta', 'type="image/x-icon"');
				$this->add_meta('og:image:width', '300', 'meta');
				$this->add_meta('og:image:height', '300', 'meta');
			}
			elseif ( ! empty($val = $entity->data->thumbnail))
			{
				$this->add_meta('og:image', $val, 'meta', 'type="image/x-icon"');
				$this->add_meta('og:image:width', '150', 'meta');
				$this->add_meta('og:image:height', '150', 'meta');
			}
		}
		else
		{
			// Open Graph Type
			$this->add_meta('og:type', 'website');
		}

		return $this;
	}

	/**
	 * Appends meta tags
	 * @param   mixed   $name   meta tag's name
	 * @param   mixed   $content
	 * @return  object
	 */
	public function add_meta($name, $content = null, $type = 'meta', $attrs = array())
	{
		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				$this->add_meta($key, $val, $type, $attrs);
			}

			return $this;
		}

		$i = 0;
		do {
			$index = $type.'::'.$name.'::'.$i;
			++$i;
		} while (isset($this->meta_tags[$index]));

		$this->meta_tags[$index] = array('content' => $content);

		(empty($attrs)) OR $this->meta_tags[$index]['attrs'] = $attrs;

		return $this;
	}

	/**
	 * Sets alternate meta tag.
	 *
	 * @param 	string
	 * @return 	void
	 */
	private function set_alternate_meta($uri_string = '')
	{
		if ( ! $this->_parent->lang->polylang)
		{
			return;
		}

		$next = empty($uri_string) ? '' : '?next='.rawurlencode($uri_string);

		foreach ($this->_parent->lang->others() as $folder => $info)
		{
			$this->add_meta(
				'alternate',
				$this->ci->config->site_url("switch-language/{$folder}{$next}"),
				'rel',
				"hreflang=\"{$info['locale']}\""
			);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all cached meta_tags.
	 * @return  array
	 */
	public function get_meta()
	{
		return $this->meta_tags;
	}

	// --------------------------------------------------------------------

	/**
	 * Takes all site meta tags and prepare the output string.
	 * @return  string
	 */
	private function print_meta_tags()
	{
		$before_filter = 'before_meta';
		$after_filter  = 'after_meta';

		if ($this->is_dashboard)
		{
			$before_filter = 'before_admin_meta';
			$after_filter  = 'after_admin_meta';
		}

		$meta_tags = $this->apply_filters($before_filter, '');
		$meta_tags .= $this->_render_meta_tags();
		$meta_tags = $this->apply_filters($after_filter, $meta_tags);

		return $meta_tags;
	}

	// --------------------------------------------------------------------

	/**
	 * Collects all additional meta_tags and prepare them for output
	 * @param   none
	 * @return  string
	 */
	protected function _render_meta_tags()
	{
		$action = 'enqueue_admin_meta';
		$filter = 'render_admin_meta_tags';

		if ( ! $this->is_dashboard)
		{
			$action = 'enqueue_meta';
			$filter = 'render_meta_tags';

			$generator = $this->apply_filters('skeleton_generator', KPlatform::LABEL.' '.KB_VERSION);
			empty($generator) OR $this->add_meta('generator', $generator);
		}

		$this->do_action($action);

		$output = '';

		if ( ! empty($this->meta_tags))
		{
			$i = 1;
			$j = count($this->meta_tags);

			foreach ($this->meta_tags as $key => $val)
			{
				[$type, $name] = explode('::', $key);
				$content = isset($val['content']) ? deep_htmlentities($val['content']) : null;
				$attrs   = isset($val['attrs']) ? $val['attrs'] : null;

				$output .= meta_tag($name, $content, $type, $attrs).($i === $j ? '' : "\n\t");

				$i++;
			}
		}

		return $this->apply_filters($filter, $output);
	}

	// --------------------------------------------------------------------
	// Assets handlers.
	// --------------------------------------------------------------------

	/**
	 * Quick add styles.
	 *
	 * @deprecated	kept for backwards compatibility.
	 * @uses 	Kbcore_assets::css
	 * @return 	Kbcore_assets
	 */
	public function add_style($file, $handle = null, $ver = null, $prepend = false, array $attrs = array())
	{
		return $this->_parent->assets->css($file, $handle, $ver, $prepend, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Quick add scripts.
	 *
	 * @deprecated	kept for backwards compatibility.
	 * @uses 	Kbcore_assets::js
	 * @return 	Kbcore_assets
	 */
	public function add_script($file, $handle = null, $ver = null, $prepend = false, array $attrs = array())
	{
		return $this->_parent->assets->js($file, $handle, $ver, $prepend, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs all additional head string.
	 * @param   string  $content
	 * @return  string
	 */
	private function print_extra_head($content = "\n")
	{
		return $this->apply_filters($this->is_dashboard ? 'admin_head' : 'extra_head', $content);
	}

	// --------------------------------------------------------------------

	/**
	 * analytics
	 *
	 * Generates and injects tracking codes for analytics and advertising.
	 *
	 * This method returns a modified array of placeholders containing scripts
	 * or empty strings depending on configuration and environment.
	 * It currently supports:
	 * - Google Tag Manager (preferred if set)
	 * - Google Analytics (fallback if GTM not set)
	 * - Facebook Pixel (independent of Google Services)
	 *
	 * It also checks for development environment (KB_LOCAHOST) and cookie
	 * consent settings, and avoids injecting any scripts unless conditions
	 * are met.
	 *
	 * @param 	array 	$replace 	The array of placeholders.
	 * @return 	array 	The modified array containing the analytics and pixel.
	 */
	private function analytics(array $replace)
	{
		// Do not include any tracking scripts if:
		// - The application is running on local development environment.
		// - The user has not accepted cookies (e.g., via cookie consent dialog).
		if (defined('KB_LOCALHOST') OR ! $this->ci->config->item('accept_cookies'))
		{
			$replace['analytics'] = '';
			$replace['google_tagmanager'] = '';
			$replace['facebook_pixel_head'] = '';
			$replace['facebook_pixel_body'] = '';

			// Early return: nothing to track, skip further checks.
			return $replace; // Early return.
		}
		// Check for Google Tag Manager ID first — preferred over Analytics.
		elseif ( ! empty($id = $this->ci->config->item('google_tagmanager_id')))
		{
			$replace['analytics'] = sprintf($this->_html_google_tagmanager_head, $id);
			$replace['google_tagmanager'] = sprintf($this->_html_google_tagmanager_body, $id);
		}
		// If GTM ID is not present, fallback to classic Google Analytics.
		elseif ( ! empty($id = $this->ci->config->item('google_analytics_id')))
		{
			$replace['analytics'] = sprintf($this->_html_google_analytics, $id);
			$replace['google_tagmanager'] = '';
		}
		// If neither GTM nor GA is set, clear the placeholders.
		else
		{
			$replace['analytics'] = '';
			$replace['google_tagmanager'] = '';
		}

		// Facebook Pixel is handled separately and can be used
		// alongside Google services.
		if ( ! empty($id = $this->ci->config->item('facebook_pixel_id')))
		{
			$replace['facebook_pixel_head'] = sprintf($this->_html_facebook_pixel_head, $id);
			$replace['facebook_pixel_body'] = sprintf($this->_html_facebook_pixel_body, $id);
		}
		else
		{
			$replace['facebook_pixel_head'] = '';
			$replace['facebook_pixel_body'] = '';
		}

		return $replace;
	}

	// --------------------------------------------------------------------
	// HTML and Body classes methods.
	// --------------------------------------------------------------------

	/**
	 * Return the string to use for html_class()
	 * @param   string  $class to add.
	 * @return  string
	 */
	public function html_class($class = null)
	{
		$this->html_classes = $this->apply_filters(
			$this->is_dashboard ? 'admin_html_class' : 'html_class',
			$this->html_classes
		);

		(is_array($this->html_classes)) OR $this->html_classes = (array) $this->html_classes;

		(empty($class)) OR array_unshift($this->html_classes, $class);

		$this->html_classes = array_clean($this->html_classes);

		return (empty($this->html_class)) ? '' : ' class="'.implode(' ', $this->html_classes).'"';
	}

	// --------------------------------------------------------------------

	/**
	 * Return the string to use for get_body_class()
	 * @param   string  $class  class to add.
	 * @return  string
	 */
	public function body_class($class = null)
	{
		(is_array($this->body_classes)) OR $this->body_classes = (array) $this->body_classes;

		$class && array_unshift($this->body_classes, $class);

		if ($this->is_dashboard)
		{
			$this->body_classes[] = 'csk-admin';
			$this->body_classes[] = 'ver-'.str_replace('.', '-', KB_VERSION);
			$this->body_classes[] = 'locale-'.strtolower($this->_parent->lang->current('locale'));
		}
		else
		{
			$this->_parent->auth->online() && $this->body_classes[] = 'is-logged';
			$this->_parent->auth->is_admin() && $this->body_classes[] = 'is-admin';
			$this->_parent->auth->has_dashboard() && $this->body_classes[] = 'has-dashboard';
		}

		(empty($this->module)) OR $this->body_classes[] = 'csk-'.$this->module;

		$this->body_classes[] = 'csk-'.$this->controller;

		('index' !== $this->method) && $this->body_classes[] = 'csk-'.$this->method;

		if ('login' !== $this->controller && $this->_parent->lang->is_rtl())
		{
			$this->body_classes[] = 'rtl';
		}

		$this->body_classes = array_clean($this->body_classes);

		$this->body_classes = $this->apply_filters(
			$this->is_dashboard ? 'admin_body_class' : 'body_class',
			$this->body_classes
		);

		return (empty($this->body_classes)) ? '' : ' class="'.implode(' ', $this->body_classes).'"';
	}

	// --------------------------------------------------------------------

	/**
	 * Quick add classes to <html> tag.
	 * @param   mixed
	 * @return  Theme
	 */
	public function set_html_class()
	{
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];

			$this->html_classes = array_clean(array_merge($this->html_classes, $args));
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Quick add classes to <body> tag.
	 * @param   mixed
	 * @return  Theme
	 */
	public function set_body_class()
	{
		if ( ! empty($args = func_get_args()))
		{
			is_array($args[0]) && $args = $args[0];

			$this->body_classes = array_clean(array_merge($this->body_classes, $args));
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the array of html classes.
	 * @param   none
	 * @return  array
	 */
	public function get_html_class()
	{
		return $this->html_classes;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the array of body classes.
	 * @param   none
	 * @return  array
	 */
	public function get_body_class()
	{
		return $this->body_classes;
	}

	// --------------------------------------------------------------------
	// Language method.
	// --------------------------------------------------------------------

	/**
	 * Set <html> language attributes.
	 *
	 * @return  string
	 */
	protected function language_attributes()
	{
		// Grab info about the language to use.
		$info = ($this->ci->uri->is_dashboard && $this->ci->router->class === 'login')
			? $this->_parent->lang->default()
			: $this->_parent->lang->current();

		// Default language attributes to use.
		$attrs = array('xml:lang' => $info['code'], 'lang' => $info['code'], 'dir' => $info['direction']);

		// Only apply filters for front-end language attributes.
		$this->ci->uri->is_dashboard OR $attrs = $this->apply_filters('language_attributes', $attrs);

		// Add "Content-Language" meta.
		$this->add_meta('Content-Language', $info['code']);

		// Return final output.
		return empty($attrs = array_clean_keys($attrs)) ? '' : ' '.array_to_attr($attrs);
	}

	// --------------------------------------------------------------------
	// Rendering methods.
	// --------------------------------------------------------------------

	/**
	 * Instead of chaining this class methods or calling them one by one,
	 * this method is a shortcut to do anything you want in a single call.
	 * @param   array   $data       array of data to pass to view
	 * @param   string  $title      page's title
	 * @param   string  $options    associative array of options to apply first
	 * @param   bool    $return     whether to output or simply build
	 */
	public function render($data = array(), $title = null, $options = array(), $return = false)
	{
		// Never render for AJAX or CLI.
		if (is_ajax(true) OR is_api())
		{
			return $title;
		}
		// Debatable check.
		elseif ( ! $this->is_dashboard && ! is_dir($this->ci->config->theme_path()))
		{
			return $this;
		}

		$this->ci->benchmark->mark('theme_render_start');

		if (is_array($title))
		{
			$return  = (bool) $options;
			$options = $title;
			$title   = null;
		}
		elseif ( ! empty($title))
		{
			$options['title'] = $title;
		}

		// Add manifest.json meta tag.
		if ($this->ci->config->item('use_manifest'))
		{
			$this->add_meta('manifest', $this->ci->config->site_url('manifest.json'), $type = 'rel');
			$this->add_meta('theme-color', '#'.$this->ci->config->item('site_theme_color', null, '134d78'));
			$this->add_meta('mobile-web-app-capable', 'yes');
			$this->add_meta('apple-mobile-web-app-capable', 'yes');
			$this->add_meta('apple-mobile-web-app-status-bar-style', 'default');
			$this->add_meta('apple-mobile-web-app-title', $this->site_name);
		}

		// Set all meta now.
		$this->set_meta();

		foreach ($options as $key => $val)
		{
			if ($key === 'css' OR $key === 'js')
			{
				$this->add($key, $val);
				continue;
			}

			elseif (method_exists($this, 'set_'.$key))
			{
				$this->{'set_'.$key}($val);
				continue;
			}

			$this->set($key, $val);
		}

		$output = $this->_load($this->get_view(), empty($data) ? $this->data : $data);

		if ($this->ci->output->parse_exec_vars && ! empty($output))
		{
			$output = str_replace(
				'{theme_time}',
				$this->ci->benchmark->elapsed_time('theme_render_start', 'theme_render_end'),
				$output
			);
		}

		$this->ci->benchmark->mark('theme_render_end');

		if ($return)
		{
			return $output;
		}

		$this->ci->output->set_output($output);
	}

	// --------------------------------------------------------------------

	/**
	 * Loads view file
	 * @param   string  $view       view to load
	 * @param   array   $data       array of data to pass to view
	 * @param   bool    $return     whether to output view or not
	 * @param   string  $master     in case you use a distinct master view
	 * @return  void
	 */
	protected function _load($view, $data = array())
	{
		if ($this->is_dashboard)
		{
			$this->do_action('enqueue_admin_partials');
		}
		else
		{
			$this->do_action('after_theme_setup');
			$this->do_action('theme_menus');
			$this->do_action('enqueue_partials');
		}

		$layout = array();

		if ( ! empty($this->partials))
		{
			foreach ($this->partials as $name => $content)
			{
				$layout[$name] = $content;
			}
		}

		$this->content = $this->_load_file($view, $data, 'views');

		$this->content = $this->apply_filters(
			$this->is_dashboard ? 'admin_content' : 'the_content',
			$this->content
		);

		$layout['content'] = $this->content;

		isset($this->layout) OR $this->layout = $this->get_layout('default');

		$this->ci->output
			->set_header('HTTP/1.0 200 OK')
			->set_header('HTTP/1.1 200 OK')
			->set_header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate')
			->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT')
			->set_header('Pragma: no-cache')
			->set_header('Expires: 0');

		($this->cache_lifetime > 0) && $this->ci->output->cache($this->cache_lifetime);

		$output = $this->_load_file($this->layout, $layout, 'layouts');
		$output = $this->apply_filters($this->is_dashboard ? 'admin_output' : 'theme_output', $output);

		// Start with footer first then add the header.
		$output = $output.$this->footer();
		$output = $this->header().$output;

		/**
		 * Return the final output after being either
		 * compressed or beautified.
		 * @since   2.16
		 */
		return $this->compress
			? $this->compress_output($output)
			: ($this->beautify ? $this->beautify_output($output) : $output);
	}

	// --------------------------------------------------------------------

	/**
	 * Loads a file.
	 * @param   string  $file
	 * @param   array   $data
	 * @param   string  $type
	 * @return  string if found, else false.
	 */
	protected function _load_file($file, $data = array(), $type = 'views')
	{
		// Prepare file name.
		$file = normalize_file($file, false);

		// Prepend 'admin' for dashboard files.
		$this->is_dashboard && $file = 'admin/'.trim(str_replace('admin', '', $file), '/');

		// Do stuff depending on the type
		switch ($type)
		{
			// Partials
			case 'partials':

				$folder = 'views/_partials/';
				$filter = $this->is_dashboard ? 'admin_partials_path' : 'theme_partials_path';

				break;

			// Widgets
			case 'widgets':

				$folder = 'views/_widgets/';
				$filter = $this->is_dashboard ? 'admin_widgets_path' : 'theme_widgets_path';

				break;

			// Layouts
			case 'layouts':

				$folder = 'views/_layouts/';
				$filter = $this->is_dashboard ? 'admin_layouts_path' : 'theme_layouts_path';

				break;

			// Views
			case 'views':

				$folder = 'views/';
				$filter = $this->is_dashboard ? 'admin_views_path' : 'theme_views_path';

				break;
		}

		$file_path = $folder.$file;

		/**
		 * If the file is guessed to "index.php", we use
		 * an alternative file without it.
		 */
		if (preg_match('/\/index/i', $file))
		{
			$alt_file = preg_replace('/\/index/i', '', $file);
			$alt_path = $folder.$alt_file;
		}

		/**
		 * If the 'filter' was set, this means we are not
		 * on the dashboard, so we prioritize theme path.
		 */
		if ($filter)
		{
			// example: home.php
			if (isset($alt_file) && is_file($theme_file = $this->apply_filters($filter, $folder).$alt_file))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($theme_file, true);
			}

			// example: home/index.php
			if (is_file($theme_file = $this->apply_filters($filter, $folder).$file))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($theme_file, true);
			}
		}

		// Loading a view file?
		if ($type === 'views')
		{
			/**
			 * $filter is only available on the front-end section of the site.
			 * So we give priority to theme files first, then we fallback to
			 * using module's default files.
			 */
			if ($filter
				&& $this->module
				&& is_file($_file_path = $this->apply_filters($filter, $folder).$this->module.'/'.$file))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($_file_path, true);
			}
			elseif (is_file($_file_path = $this->module_path.$folder))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($_file_path, true);
			}
		}

		/**
		 * Arriving at this point means no file was found. What a shame!
		 * Now we have to loop through all paths and attempt to locate
		 * the file, hoping we can find it.
		 */
		foreach ($this->file_paths as $path)
		{
			if (is_file($found = $path.$file_path))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($found, true);
			}
			elseif (isset($alt_path) && is_file($found = $path.$alt_path))
			{
				(empty($data)) OR $this->ci->load->vars($data, true);

				return $this->ci->load->file($found, true);
			}
		}

		/* Nothing found! */
		$_err = 'Unable to load the requested theme file: '.$file_path;
		log_message('critical', $_err);
		show_error($_err);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current view file content.
	 * @param   none
	 * @return  string
	 */
	public function content()
	{
		return $this->content;
	}

	// --------------------------------------------------------------------
	// Header and Footer methods.
	// --------------------------------------------------------------------

	/**
	 * Returns or outputs the header file or provided template.
	 * @param   string  $name   The name of the file to use (Optional).
	 * @return  string
	 */
	protected function header($name = null)
	{
		if (isset($this->headers, $this->headers[$name]))
		{
			return $this->headers[$name];
		}

		$this->do_action('get_header', $name);

		$file = $backup_file = 'header.php';

		(empty($name)) OR $file = 'header-'.$name;

		$file = normalize_file($file, false);

		if (is_file($header_file = $this->ci->config->theme_path($file)))
		{
			return $this->headers[$name] = $this->ci->load->file($header_file, true);
		}

		$replace['doctype']             = $this->apply_filters('the_doctype', '<!DOCTYPE html>');
		$replace['html_class']          = $this->html_class();
		$replace['skeleton_copyright']  = $this->apply_filters('skeleton_copyright', $this->skeleton_copyright);
		$replace['language_attributes'] = $this->language_attributes();
		$replace['charset']             = $this->ci->config->item('charset');
		$replace['title']               = $this->get_title();
		$replace['meta_tags']           = $this->print_meta_tags();
		$replace['stylesheets']         = $this->_parent->assets->styles();
		$replace['extra_head']          = $this->print_extra_head();
		$replace['body_class']          = $this->body_class();
		$replace['extra_views']         = implode("\n", $this->inline_views);

		// Google Tag Manager/Analytics.
		$replace = $this->analytics($replace);

		$output = $this->_html_header;
		foreach ($replace as $key => $val)
		{
			$output = str_replace('{'.$key.'}', $val, $output);
		}

		return $this->headers[$name] = $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns or outputs the footer file or provided template.
	 * @param   string  $name   The name of the file to use (Optional).
	 * @return  string
	 */
	protected function footer($name = null)
	{
		if (isset($this->footers[$name]))
		{
			return $this->footers[$name];
		}

		// Handle removed scripts.
		$this->_parent->assets->removed_scripts();

		$this->do_action('get_footer', $name);

		$file = $backup_file = 'footer.php';

		(empty($name)) OR $file = 'footer-'.$name;

		$file = normalize_file($file, false);

		if (is_file($footer_file = $this->ci->config->theme_path($file)))
		{
			return $this->footers[$name] = $this->ci->load->file($footer_file, true);
		}

		$output = str_replace('{javascripts}', $this->_parent->assets->scripts(), $this->_html_footer);

		if ( ! empty($this->schema))
		{
			$schema_json_ld = json_encode($this->schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			$output = "<script type=\"application/ld+json\">{$schema_json_ld}</script>\n".$output;
		}

		return $this->footers[$name] = $output;
	}

	// --------------------------------------------------------------------
	// Alerts methods.
	// --------------------------------------------------------------------

	/**
	 * Sets alert message by storing them in $alerts property and session.
	 * @param   mixed   $text 	Message string or associative array.
	 * @return  object
	 */
	public function set_alert($text, $type = 'info')
	{
		if (empty($text))
		{
			return $this;
		}
		elseif (is_array($text))
		{
			foreach ($text as $key => $value)
			{
				$this->set_alert($value, $key);
			}

			return $this;
		}
		else
		{
			if (is_a($text, 'Exception'))
			{
				$text = $text->getMessage();
				$type = 'error';
			}

			(isset($this->alerts[$type]) && in_array($text, $this->alerts[$type])) OR $this->alerts[$type][] = $text;

			empty($this->alerts) OR $this->ci->session->set_flashdata(SESS_ALERT, $this->alerts);

			return $this;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all registered alerts.
	 * @param   none
	 * @return  string
	 */
	public function alert($before = null, $after = null)
	{
		empty($this->alerts) && $this->alerts = $this->ci->session->flashdata(SESS_ALERT);

		if (empty($this->alerts))
		{
			return '';
		}
		elseif ( ! $this->is_dashboard)
		{
			$this->_html_alert = $this->apply_filters('alert_template', $this->_html_alert);
			$this->alert_classes  = $this->apply_filters('alert_classes', $this->alert_classes);
		}

		$this->_html_alert = sprintf($this->_html_alert, $this->ci->lang->line('close'));

		$output = '';

		foreach ($this->alerts as $type => $alerts)
		{
			foreach ($alerts as $text)
			{
				$output .= str_replace(
					array('{class}', '{message}'),
					array($this->alert_classes[$type], $text),
					$this->_html_alert
				);
			}
		}

		empty($before) OR $output = $before.$output;
		empty($after) OR $output .= $after;

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Displays an alert.
	 * @param   string  $text 	The message to display.
	 * @param   string  $type 	The type of the alert.
	 * @param   bool    $js 	Whether to use the JS template.
	 * @return  string
	 */
	public function js_alert($text, $type = 'info', $js = false)
	{
		if (empty($text))
		{
			return '';
		}

		$template = ($js) ? $this->_html_alert_js : $this->_html_alert;

		if ( ! $this->is_dashboard)
		{
			$template = ($js)
				? $this->apply_filters('alert_template_js', $this->_html_alert_js)
				: $this->apply_filters('alert_template', $this->_html_alert);

			$this->alert_classes = $this->apply_filters('alert_classes', $this->alert_classes);
		}

		return str_replace(
			array('{class}', '{message}'),
			array($this->alert_classes[$type], $text),
			sprintf($template, $this->ci->lang->line('close'))
		);
	}

	// --------------------------------------------------------------------
	// Theme translation methods.
	// --------------------------------------------------------------------

	/**
	 * Allows themes to be translatable by loading their language files.
	 * @param   string  $path   The path to the theme's folder.
	 * @param   string  $index  Unique identifier to retrieve language lines.
	 * @return  void
	 */
	public function load_translation($path = null, $index = null)
	{
		// Dashboard or already loaded? Nothing to do.
		if ($this->translation_loaded)
		{
			return;
		}

		$this->translation_loaded = true;

		// Set idiom because we need it to load theme's language files.
		$this->set_idiom($this->ci->input->get('lang', true));

		(empty($path)) && $path = $this->apply_filters('theme_translation', $this->ci->config->theme_path('language/'));

		if ( ! create_htaccess($path))
		{
			return;
		}

		// Prepare our array of language lines.
		$full_lang = array();

		// We make sure the check the english version.
		if (is_file($file = $path.'/'.$this->ci->lang->fallback.'.php'))
		{
			require_once($file);

			if (isset($lang))
			{
				$full_lang = array_replace_recursive($full_lang, $lang);
				unset($lang);
			}
		}

		if ($this->ci->lang->fallback !== $this->idiom && is_file($file = $path.'/'.$this->idiom.'.php'))
		{
			require_once($file);

			if (isset($lang))
			{
				$full_lang = array_replace_recursive($full_lang, $lang);
				unset($lang);
			}
		}

		if ( ! empty($full_lang = array_clean_keys($full_lang)))
		{
			$this->theme_language_index = $this->apply_filters('theme_translation_index', $index);

			(empty($this->theme_language_index)) && $this->theme_language_index = $this->current();

			$this->ci->lang->language[$this->theme_language_index] = $full_lang;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current theme's language index/text domain.
	 *
	 * @since   2.16
	 *
	 * @param   none
	 * @return  string
	 */
	public function theme_domain()
	{
		return $this->theme_language_index;
	}

	// --------------------------------------------------------------------
	// Cache methods.
	// --------------------------------------------------------------------

	/**
	 * Dynamically sets cache time.
	 * @param   int     $minutes
	 * @return  object
	 */
	public function set_cache($minutes = 0)
	{
		$this->cache_lifetime = $minutes;
		return $this;
	}

	// --------------------------------------------------------------------
	// Output compression.
	// --------------------------------------------------------------------

	/**
	 * Compresses the HTML output
	 *
	 * @since   1.0
	 * @since   1.41   All HTML is compressed except for <pre> tags content.
	 *
	 * @param   string  $output     the html output to compress
	 * @return  string  the minified version of $output
	 */
	private function compress_output($output)
	{
		// Make sure $output is always a string
		(is_string($output)) OR $output = (string) $output;

		// Nothing? Don't process.
		if (empty(trim($output)))
		{
			return '';
		}

		// Conserve <pre> tags.
		$pre_tags = array();

		if (str_contains($output, '<pre'))
		{
			// We explode the output and always keep the last part.
			$parts     = explode('</pre>', $output);
			$last_part = array_pop($parts);

			// Reset output.
			$output = '';

			// Marker used to identify <pre> tags.
			$i = 0;

			foreach ($parts as $part)
			{
				$start = strpos($part, '<pre');

				// Malformed? Add it as it is.
				if (false === $start)
				{
					$output .= $part;
					continue;
				}

				// Identify the pre tag and keep it.
				$name = "<pre csk-pre-tag-{$i}></pre>";
				$pre_tags[$name] = substr($part, $start).'</pre>';
				$output .= substr($part, 0, $start).$name;
				$i++;
			}

			// Always add the last part.
			$output .= $last_part;
		}

		// Conserve <!--nocompress--> tags.
		$nocompress = array();

		if (str_contains($output, '<!--nocompress'))
		{
			// We explode the output and always keep the last part.
			$parts     = explode('<!--/nocompress-->', $output);
			$last_part = array_pop($parts);

			// Reset output.
			$output = '';

			// Marker used to identify <!--nocompress--> tags.
			$i = 0;

			foreach ($parts as $part)
			{
				$start = strpos($part, '<!--nocompress');

				// Malformed? Add it as it is.
				if (false === $start)
				{
					$output .= $part;
					continue;
				}

				// Identify the nocompress tag and keep it.
				$name = "<nocompress {$i}></nocompress>";
				$nocompress[$name] = substr($part, $start).'<!--/nocompress-->';
				$output .= substr($part, 0, $start).$name;
				$i++;
			}

			// Always add the last part.
			$output .= $last_part;
		}

		// Compress the final output.
		$output = $this->_compress_output($output);

		// If we have <pre> tags, add them.
		(empty($pre_tags)) OR $output = str_replace(array_keys($pre_tags), array_values($pre_tags), $output);

		// If we have <!--nocompress--> tags, add them.
		(empty($nocompress)) OR $output = str_replace(array_keys($nocompress), array_values($nocompress), $output);

		// Return the final output.
		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Beatifies the HTML output using the Beautify_Html
	 *
	 * @since   2.16
	 *
	 * @param   string  $output     the html output to beautify
	 * @return  string  the beautified htmk output
	 */
	private function beautify_output($output)
	{
		isset($this->beautifier) OR $this->beautifier = new Beautify_Html();

		return $this->beautifier->beautify($output);
	}

	// --------------------------------------------------------------------

	/**
	 * _compress_output
	 *
	 * The real method behind final output compression.
	 *
	 * @author  Kader Bouyakoub
	 * @link    https://github.com/bkader
	 * @since   1.41
	 *
	 * @param   string  $output     The final output.
	 * @return  string  The final output after compression.
	 */
	protected function _compress_output($output)
	{
		// In orders, we are searching for
		if ( ! isset($this->compress_from))
		{
			$this->compress_from = array(
				'/\>[^\S ]+/s', // 1. White-spaces after tags, except space.
				'/[^\S ]+\</s', // 2. White-spaces before tags, except space.
				'/(\s)+/s', // 3. Multiple white-spaces sequences.
				'/<!--(?!<!)[^\[>].*?-->/s', // 4. HTML comments
				'#(?://)?<!\[CDATA\[(.*?)(?://)?\]\]>#s' // 5. CDATA
			);
		}

		if ( ! isset($this->compress_to))
		{
			$this->compress_to = array('>', '<', '\\1', '', "//&lt;![CDATA[\n".'\1'."\n//]]>");
		}

		// We return the minified $output
		return preg_replace($this->compress_from, $this->compress_to, $output);
	}

	// --------------------------------------------------------------------

	/**
	 * Allow using any of the template provided by the lib.
	 * @access 	public
	 * @param 	mixed
	 */
	public function template()
	{
		if (empty($args = func_get_args()))
		{
			return '';
		}

		$template = 'template_'.array_shift($args);

		// Fix some alert stuff
		if ($template === 'template_alert' OR $template === 'template_alert_js')
		{
			// Add alert type if missing.
			(count($args) === 1) && array_push($args, 'info');

			// Translate 'aria-label'
			array_push($args, $this->ci->lang->line('close'));
		}

		return (isset($this->$template)) ? vsprintf($this->$template, $args) : '';
	}

	// --------------------------------------------------------------------
	// Private methods.
	// --------------------------------------------------------------------

	/**
	 * Returns array of default themes details.
	 * @param   none
	 * @return  array
	 */
	protected function _theme_headers()
	{
		static $headers;

		if ( ! isset($headers))
		{
			// Default theme headers.
			$defaults = array(
				'name'         => null,
				'theme_uri'    => null,
				'description'  => null,
				'version'      => null,
				'license'      => null,
				'license_uri'  => null,
				'author'       => null,
				'author_uri'   => null,
				'author_email' => null,
				'tags'         => null,
				'screenshot'   => null
			);

			/**
			 * Allow users to filter default themes headers.
			 * @since   2.12
			 */
			$headers = $this->apply_filters('themes_headers', $defaults);

			// We fall-back to default headers if empty.
			empty($headers) && $headers = $defaults;
		}

		return $headers;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of theme info that can be translated.
	 *
	 * @return 	array
	 */
	protected function _localized_headers()
	{
		static $headers;

		if ( ! isset($headers))
		{
			$defaults = array(
				'name',
				'description',
				'license',
				'author',
				'tags',
				'screenshot'
			);

			$headers = apply_filters('theme_localized_headers', $defaults);

			empty($headers) && $headers = $defaults;
		}

		return $headers;
	}

	// --------------------------------------------------------------------
	// Filters and actions.
	// --------------------------------------------------------------------

	/**
	 * Because this package can be used elsewhere, not only on Skeleton,
	 * we make sure to check if we are using the custom hooks system or not.
	 * If it is found, we use, otherwise, we see if the filter is a callable.
	 * @param   string  $filter     The filter or the function to use instead.
	 * @param   mixed   $args       Arguments on which we apply filters.
	 * @return  mixed
	 */
	protected function apply_filters($filter, $args = null)
	{
		if ($this->use_hooks)
		{
			return apply_filters($filter, $args);
		}

		(isset($this->_valid_filters[$filter])) OR $this->_valid_filters[$filter] = is_callable($filter);

		return ($this->_valid_filters[$filter]) ? $args = call_user_func($filter, $args) : $args;
	}

	// --------------------------------------------------------------------

	/**
	 * Do actions if found.
	 * @param   mixed   $action     THe action or callback.
	 * @return  void
	 */
	protected function do_action($action)
	{
		if ($this->use_hooks)
		{
			do_action($action);
			return;
		}

		(is_callable($action)) && call_user_func($action);
	}

	// --------------------------------------------------------------------

	/**
	 * Displays admin dashboard menu.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_themes()
	{
		echo admin_anchor('themes', $this->ci->lang->line('admin_themes'), 'class="dropdown-item"');
	}

	// --------------------------------------------------------------------

	/**
	 * Counts users and displays the info box on the dashboard index.
	 * @since 	2.54
	 *
	 * @return 	void
	 */
	public function _stats_admin()
	{
		echo info_box(
			count($this->get_themes()), $this->ci->lang->line('admin_themes'),
			'paint-brush', $this->ci->config->admin_url('themes'),
			'orange', 'div', 'class="col"'
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Displays menus and other stuff on dashboard.
	 * @since 	2.53
	 *
	 * @param 	bool 	$is_homepage 	Whether we are on dashboard index.
	 * @return 	void
	 */
	public function for_dashboard($is_homepage = false)
	{
		if ( ! $this->_parent->auth->is_level(KB_LEVEL_ADMIN))
		{
			return;
		}

		add_action('extensions_menu', array($this, '_menu_themes'), 98);

		($is_homepage) && add_action('admin_index_stats', array($this, '_stats_admin'), 90);
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('img_alt'))
{
	/**
	 * Displays an alternative image using placehold.it website.
	 *
	 * @return  string
	 */
	function img_alt($width, $height = null, $text = null, $background = null, $foreground = null)
	{
		$params = array();
		if (is_array($width))
		{
			$params = $width;
		}
		else
		{
			$params['width']      = $width;
			$params['height']     = $height;
			$params['text']       = $text;
			$params['background'] = $background;
			$params['foreground'] = $foreground;
		}

		$params['height']     = (empty($params['height'])) ? $params['width'] : $params['height'];
		$params['text']       = (empty($params['text'])) ? $params['width'].' x '.$params['height'] : $params['text'];
		$params['background'] = (empty($params['background'])) ? 'CCCCCC' : $params['height'];
		$params['foreground'] = (empty($params['foreground'])) ? '969696' : $params['foreground'];
		return '<img src="http://placehold.it/'.$params['width'].'x'.$params['height'].'/'.$params['background'].'/'.$params['foreground'].'&text='.$params['text'].'" alt="Placeholder">';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('add_ie9_support'))
{
	/**
	 * This function is used alongside the "extra_head" filter in order
	 * to add support for old browsers (Internet Explorer)
	 * @param   string  $output     The extra head content.
	 * @param   bool    $remote     Whether to load from CDN or use local files.
	 * @return  void
	 */
	function add_ie9_support(&$output, $remote = true)
	{
		$html5shiv = '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js';
		$respond = '//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js';
		$json2 = '//cdnjs.cloudflare.com/ajax/libs/json2/20150503/json2.min.js';

		if ($remote === false)
		{
			$html5shiv = common_url('js/vendor/html5shiv.min.js');
			$respond   = common_url('js/vendor/respond.min.js');
			$json2     = common_url('js/vendor/json2.min.js');
		}
		$output .= <<<HTML
<!--[if lt IE 9]>
\t<script id="html5shiv-js" type="text/javascript" src="{$html5shiv}"></script>
\t<script id="respond-js" type="text/javascript" src="{$respond}"></script>
\t<![endif]-->
<!--[if lt IE 8]>
\t<script id="json2-js" type="text/javascript" src="{$json2}"></script>
\t<![endif]-->
HTML;
	}
}

/*==========================================
=            METADATA FUNCTIONS            =
==========================================*/

if ( ! function_exists('meta_tag')):
	/**
	 * Output a <meta> tag of almost any type.
	 * @param   mixed   $name   the meta name or array of meta.
	 * @param   string  $content    the meta tag content.
	 * @param   string  $type       the type of meta tag.
	 * @param   mixed   $attrs      array of string of attributes.
	 * @return  string
	 */
	function meta_tag($name, $content = null, $type = 'meta', $attrs = array())
	{
		// Loop through multiple meta tags
		if (is_array($name))
		{
			$meta = array();
			foreach ($name as $key => $val)
			{
				$meta[] = meta_tag($key, $val, $type, $attrs);
			}

			return implode("\n\t", $meta);
		}

		$attributes = array();
		switch ($type)
		{
			case 'rel':
				$tag                = 'link';
				$attributes['rel']  = $name;
				$attributes['href'] = $content;
				break;

			// In case of a meta tag.
			case 'meta':
			default:
				if ($name == 'charset')
				{
					return "<meta charset=\"{$content}\" />";
				}

				if ($name == 'base')
				{
					return "<base href=\"{$content}\" />";
				}

				// The tag by default is "meta"

				$tag = 'meta';

				// In case of using Open Graph tags,
				// we use 'property' instead of 'name'.
				if (str_starts_with($name, 'og:') OR str_starts_with($name, 'fb:'))
				{
					$type = 'property';
					if ( ! empty($attrs))
					{
						$attributes['name'] = str_replace('og:', 'twitter:', $name);
					}
				}
				else
				{
					$type = 'name';
				}

				if ($content === null)
				{
					$attributes[$type] = $name;
				}
				else
				{
					$attributes[$type]     = e($name);
					$attributes['content'] = e($content);
				}

				break;
		}

		$attributes = (is_array($attrs)) ? array_to_attr(array_merge($attributes, $attrs)) : array_to_attr($attributes).' '.$attrs;

		return "<{$tag}{$attributes}/>";
	}
endif;

/*==============================================
=            FLASH ALERTS FUNCTIONS            =
==============================================*/

if ( ! function_exists('set_alert'))
{
	/**
	 * Sets a flash alert.
	 * @param   mixed   $text 	message or array of $type => $text
	 * @param   string  $type 	type to use for a single message.
	 * @return  void.
	 */
	function set_alert($text, $type = 'info')
	{
		return get_instance()->theme->set_alert($text, $type);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('print_alert'))
{
	/**
	 * Displays a flash alert.
	 * @param  string 	$text 	the message to display.
	 * @param  string 	$type 	the message type.
	 * @param  bool 	$js 	html or js
	 * @return string
	 */
	function print_alert($text = null, $type = 'info', $js = false, $echo = true)
	{
		$alert = get_instance()->theme->js_alert($text, $type, $js);
		if ($echo === false)
		{
			return $alert;
		}

		echo $alert;
	}
}

// --------------------------------------------------------------------
// Utilities.
// --------------------------------------------------------------------

if ( ! function_exists('fa_icon'))
{
	/**
	 * fa_icon
	 *
	 * Function for generating FontAwesome icons.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @access  public
	 * @param   none
	 * @param   string $after
	 * @param   string $before
	 * @return  string
	 */
	function fa_icon($class = '', string $after = null, string $before = null)
	{
		static $template; // remember it.

		(empty($template)) && $template = '<i class="%s fa-fw fa-%s"></i>';

		if (sscanf($class, 'fab:%s', $_class) === 1)
		{
			empty($rest = str_replace('fab:'.$_class, '', $class)) OR $_class .= $rest;
			$icon = sprintf($template, 'fab', $_class);
		}
		else
		{
			$icon = sprintf($template, 'fa', $class);
		}

		empty($after) OR $icon .= $after;

		empty($before) OR $icon = $before.$icon;

		return $icon;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('label_condition'))
{
	/**
	 * This is a dummy function used to display Boostrap labels
	 * depending on a given condition.
	 *
	 * @since   1.0
	 * @since   1.33   Fixed issue with translation.
	 *
	 * @param   bool    $cond   The conditions result.
	 * @param   string  $true   String to output if true.
	 * @param   string  $false  String to output if false.
	 * @param   string  $sucess
	 * @param   string  $danger
	 * @return  string
	 */
	function label_condition($cond, $true = null, $false = null, $success = null, $danger = null)
	{
		static $template; // remember it.

		(empty($template)) && $template = '<span class="badge bg-%s text-white">%s</span>';

		return ($cond)
			? sprintf($template, (empty($success)) ? 'success' : $success, _translate(empty($true) ? 'yes' : $true))
			: sprintf($template, (empty($danger)) ? 'danger' : $danger, _translate(empty($false) ? 'no' : $false));
	}
}
