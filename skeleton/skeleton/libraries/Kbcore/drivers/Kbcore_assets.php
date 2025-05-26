<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_assets Class
 *
 * Handles all about assets.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.112
 */
final class Kbcore_assets extends KB_Driver
{
	/**
	 * Array of queued CSS files.
	 * @var array
	 */
	protected $styles = array();

	/**
	 * Array of queued JS files.
	 * @var array
	 */
	protected $scripts = array();

	/**
	 * Array of queued inline CSstyles.
	 * @var array
	 */
	protected $inline_styles = array();

	/**
	 * Array of queued inline scripts.
	 * @var array
	 */
	protected $inline_scripts = array();

	/**
	 * Array of CSS files to be removed before final render.
	 * @var array
	 */
	protected $removed_styles = array();

	/**
	 * Array of JS files to be removed before final render.
	 * @var array
	 */
	protected $removed_scripts = array();

	/**
	 * Array of CSS files that should be rendered before others.
	 * @var array
	 */
	protected $prepended_styles = array();

	/**
	 * Array of JS files that should be rendered before others.
	 * @var array
	 */
	protected $prepended_scripts = array();

	/**
	 * Array of default style tag attributes.
	 * @var array
	 */
	protected $css_attrs = array('rel' => 'stylesheet', 'type' => 'text/css');

	/**
	 * Array of default script tag attributes.
	 * @var array
	 */
	protected $js_attrs = array('type' => 'text/javascript');

	/**
	 * Template used for rendering inline styles.
	 * @var array
	 */
	protected $inline_style_wrapper = <<<HTML
<style type="text/css">
	%s
</style>
HTML;

	/**
	 * Template used for rendering inline scripts.
	 * @var array
	 */
	protected $inline_script_wrapper = <<<HTML
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		%s
	});
</script>'
HTML;

	/**
	 * Array of style handles used for ordering CSS files.
	 * @var array
	 */
	protected $styles_order = array(
		'google-fonts-css',
		'fontawesome-css',
		'bootstrap-css'
	);

	/**
	 * Array of script handles used for ordering JS files.
	 * @var array
	 */
	protected $scripts_order = array(
		'modernizr-js',
		'jquery-js',
		'handlebars-js'
	);

	// --------------------------------------------------------------------
	// Public Methods
	// --------------------------------------------------------------------

	/**
	 * Adds a CSS style file.
	 *
	 * @param 	mixed 	$file
	 * @param 	string 	$handle
	 * @param 	int 	$ver
	 * @param 	bool 	$prepend
	 * @param 	array 	$attrs
	 *
	 * @return 	self
	 */
	public function css($file, $handle = null, $ver = null, $prepend = false, array $attrs = array())
	{
		return $this->add($file, 'css', $handle, $ver, $prepend, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a JS script file.
	 *
	 * @param 	mixed 	$file
	 * @param 	string 	$handle
	 * @param 	int 	$ver
	 * @param 	bool 	$prepend
	 * @param 	array 	$attrs
	 *
	 * @return 	self
	 */
	public function js($file, $handle = null, $ver = null, $prepend = false, array $attrs = array())
	{
		return $this->add($file, 'js', $handle, $ver, $prepend, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Removes any added files.
	 *
	 * @param 	mixed
	 *
	 * @return 	self
	 */
	public function remove()
	{
		if (empty($args = func_get_args()))
		{
			return $this;
		}

		$type = strtolower(array_shift($args));

		if (empty($args) OR ($type !== 'css' && $type !== 'js'))
		{
			return $this;
		}

		is_array($args[0]) && $args = $args[0];

		foreach ($args as $handle)
		{
			$handle = preg_replace("/-{$type}$/", '', strtolower($handle))."-{$type}";

			if ($type === 'css')
			{
				$this->removed_styles[] = $handle;
				unset($this->styles[$handle], $this->prepended_styles[$handle]);
			}
			else
			{
				$this->removed_scripts[] = $handle;
				unset($this->scripts[$handle], $this->prepended_scripts[$handle]);
			}
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Handles removed scripts.
	 *
	 * @return 	void
	 */
	public function removed_scripts()
	{
		// Make sure to always have 'modernizr' and 'jquery'.
		in_array('modernizr-js', $this->removed_scripts) OR $this->modernizr();
		in_array('jquery-js', $this->removed_scripts) OR $this->jquery();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces any CSS file with another.
	 *
	 * @param 	string 	$file
	 * @param 	string 	$handle
	 * @param 	int 	$ver
	 * @param 	array 	$attrs
	 *
	 * @return self
	 */
	public function replace_css(string $file, $handle = null, $ver = null, array $attrs = array())
	{
		return $this->replace($file, 'css', $handle, $ver, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces any JS file with another.
	 *
	 * @param 	string 	$file
	 * @param 	string 	$handle
	 * @param 	int 	$ver
	 * @param 	array 	$attrs
	 *
	 * @return self
	 */
	public function replace_js(string $file, $handle = null, $ver = null, array $attrs = array())
	{
		return $this->replace($file, 'js', $handle, $ver, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds inline CSS.
	 *
	 * @param 	string 	$content
	 * @param 	string 	$handle
	 *
	 * @return 	self
	 */
	public function inline_css(string $content = '', $handle = null)
	{
		return $this->add_inline($content, 'css', $handle);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds inline JS.
	 *
	 * @param 	string 	$content
	 * @param 	string 	$handle
	 *
	 * @return 	self
	 */
	public function inline_js(string $content = '', $handle = null)
	{
		return $this->add_inline($content, 'js', $handle);
	}

	// --------------------------------------------------------------------
	// Rendering Methods
	// --------------------------------------------------------------------

	/**
	 * Outputs all StyleSheets and inline styles.
	 *
	 * @return 	string
	 */
	public function styles()
	{
		$before_filter = 'before_styles';
		$after_filter  = 'after_styles';

		if ($this->is_dashboard)
		{
			$before_filter = 'before_admin_styles';
			$after_filter  = 'after_admin_styles';
		}

		$output = apply_filters($before_filter, '');
		$output .= $this->render_css();
		return apply_filters($after_filter, $output);
	}

	// --------------------------------------------------------------------

	/**
	 * Outputs all Scripts and inline scripts.
	 *
	 * @return 	string
	 */
	public function scripts()
	{
		$before_filter = 'before_scripts';
		$after_filter  = 'after_scripts';

		if ($this->is_dashboard)
		{
			$before_filter = 'before_admin_scripts';
			$after_filter  = 'after_admin_scripts';
		}

		$output = apply_filters($before_filter, '');
		$output .= $this->render_js();
		return apply_filters($after_filter, $output);
	}


	// --------------------------------------------------------------------
	// Private Methods
	// --------------------------------------------------------------------

	/**
	 * Resets all asset arrays.
	 *
	 * @return 	self
	 */
	protected function reset()
	{
		$this->styles = array();
		$this->scripts = array();
		$this->inline_styles = array();
		$this->inline_scripts = array();
		$this->removed_styles = array();
		$this->removed_scripts = array();
		$this->prepended_styles = array();
		$this->prepended_scripts = array();

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds any type of asset (CSS or JS).
	 *
	 * @param 	mixed 	$file 		The file to add.
	 * @param 	string 	$type 		The type of file to add.
	 * @param 	string 	$handle 	A unique ID of the file.
	 * @param 	int 	$ver 		The version to use.
	 * @param 	bool 	$prepend 	Whether to put the file ahead or after all.
	 *
	 * @return 	self
	 */
	protected function add($file, $type = 'css', $handle = null, $ver = null, $prepend = false, array $attrs = array())
	{
		if (empty($file))
		{
			return $this;
		}
		elseif (is_array($file))
		{
			foreach ($file as $_handle => $_file)
			{
				$this->add(
					$_file,
					$type,
					(is_int($_handle) && is_string($_file)) ? null : $_handle,
					$prepend,
					$attrs
				);
			}

			return $this;
		}

		empty($handle) && $handle = preg_replace('/\./', '-', basename($file));
		$handle = strtolower(preg_replace("/-{$type}$/", '', $handle)."-{$type}");
		isset($attrs['id']) OR $attrs['id'] = $handle;

		path_is_url($file) OR $file = $this->ci->config->common_url($type.'/'.$file);

		empty($ver) OR $file .= '?ver='.$ver;

		if ($type === 'css')
		{
			isset($attrs['href']) OR $attrs['href'] = $file;
			$attrs = array_merge($this->css_attrs, $attrs);

			if ($prepend)
			{
				$this->prepended_styles[$handle] = $attrs;
				$this->styles = array_replace_recursive($this->prepended_styles, $this->styles);
				return $this;
			}

			$this->styles[$handle] = $attrs;
			return $this;
		}

		isset($attrs['src']) OR $attrs['src'] = $file;
		$attrs = array_merge($this->js_attrs, $attrs);

		if ($prepend)
		{
			$this->prepended_scripts[$handle] = $attrs;
			$this->scripts = array_replace_recursive($this->prepended_scripts, $this->scripts);
			return $this;
		}

		$this->scripts[$handle] = $attrs;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Replaces any file with another.
	 *
	 * @param 	string 	$type
	 * @param 	string 	$file
	 * @param 	string 	$handle
	 * @param 	int 	$ver
	 * @param 	array 	$attrs
	 *
	 * @return 	self
	 */
	protected function replace(string $file, $type = 'css', $handle = null, $ver = null, array $attrs = array())
	{
		return (empty($file) OR empty($handle)) ? $this : $this->add($file, $type, $handle, $ver, false, $attrs);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds inline CSS or JS.
	 *
	 * @param 	string 	$content
	 * @param 	string 	$type
	 * @param 	string 	$handle
	 *
	 * @return 	self
	 */
	protected function add_inline(string $content = '', $type = 'css', $handle = null)
	{
		if (empty($content) OR (($type = strtolower($type)) !== 'css' && $type !== 'js'))
		{
			return $this;
		}
		elseif ( ! empty($handle))
		{
			$handle = preg_replace("/-{$type}$/", '', $handle)."-{$type}";
		}

		if ($type === 'css')
		{
			if (empty($handle))
			{
				$this->inline_styles[] = sprintf(
					$this->inline_style_wrapper,
					$this->_parent->is_live ? CssMin::minify($content) : $content
				);
			}
			else
			{
				$this->inline_styles[$handle] = sprintf(
					$this->inline_style_wrapper,
					$this->_parent->is_live ? CssMin::minify($content) : $content
				);
			}
		}
		elseif (empty($handle))
		{
			$this->inline_scripts[] = sprintf(
				$this->inline_script_wrapper,
				$this->_parent->is_live ? JSMin::minify($content) : $content
			);
		}
		else
		{
			$this->inline_scripts[$handle] = sprintf(
				$this->inline_script_wrapper,
				$this->_parent->is_live ? JSMin::minify($content) : $content
			);
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Collects all CSS files and prepare them for output.
	 *
	 * @return 	string
	 */
	protected function render_css()
	{
		$action = 'enqueue_styles';
		$filter = 'print_styles';

		if ($this->is_dashboard)
		{
			$action = 'enqueue_admin_styles';
			$filter = 'admin_print_styles';
		}

		do_action($action);

		// See if the user overrode this.
		$temp_output = apply_filters($filter, array(
			'inline' => $this->inline_styles,
			'styles' => $this->styles,
			'output' => null,
		));

		if (is_string($temp_output))
		{
			return $temp_output;
		}

		$output = '';

		foreach ($this->styles_order as $handle)
		{
			// Handle not queued? nothing to do...
			if ( ! isset($this->styles[$handle]))
			{
				continue;
			}

			// Has inline styles attached to it?
			if (isset($this->inline_styles[$handle]))
			{
				$output .= $this->inline_styles[$handle]."\n";
				unset($this->inline_styles[$handle]);
			}

			// Valid array?
			(($file = $this->styles[$handle]) !== false) && $output .= '<link'.array_to_attr($file).' />';

			// Remove the handle to prevent re-rendering it.
			unset($this->styles[$handle]);
		}

		// Render all styles.
		foreach ($this->styles as $handle => $file)
		{
			if (isset($this->inline_styles[$handle]))
			{
				$output .= $this->inline_styles[$handle]."\n\t";
				unset($this->inline_styles[$handle]);
			}

			($file !== false) && $output .= '<link'.array_to_attr($file).' />';
		}

		// Render the rest of inline styles.
		empty($this->inline_styles) OR $output .= implode("\n\t", $this->inline_styles);

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Collects all JS files and prepare them for output.
	 *
	 * @return string
	 */
	protected function render_js()
	{
		$action = 'enqueue_scripts';
		$filter = 'print_scripts';

		// On dashboard?
		if ($this->is_dashboard)
		{
			$action = 'enqueue_admin_scripts';
			$filter = 'admin_print_scripts';
		}

		do_action($action);

		$temp_output = apply_filters($filter, array(
			'inline'  => $this->inline_scripts,
			'scripts' => $this->scripts,
			'output'  => null,
		));

		// An output was created? Return it
		if (is_string($temp_output))
		{
			return $temp_output;
		}

		$output = '';

		foreach ($this->scripts_order as $handle)
		{
			// Handle not queued? nothing to do...
			if ( ! isset($this->scripts[$handle]))
			{
				continue;
			}

			// Has inline script attached to it?
			if (isset($this->inline_scripts[$handle]))
			{
				$output .= $this->inline_scripts[$handle]."\n\t";
				unset($this->inline_scripts[$handle]);
			}

			// Valid array?
			if (($file = $this->scripts[$handle]) !== false)
			{
				$output .= '<script'.array_to_attr($file);

				// Only defer other files.
				($file['id'] !== 'jquery-js' && $file['id'] !== 'modernizr-js') && $output .= ' defer';
				$output .= '></script>';
			}

			// Remove the handle to prevent re-rendering it.
			unset($this->scripts[$handle]);
		}

		// Render all scripts.
		foreach ($this->scripts as $handle => $file)
		{
			if (isset($this->inline_scripts[$handle]))
			{
				$output .= $this->inline_scripts[$handle]."\n";
				unset($this->inline_scripts[$handle]);
			}

			($file !== false) && $output .= '<script'.array_to_attr($file).' defer></script>';
		}

		// Render the rest of inline scripts.
		empty($this->inline_scripts) OR $output .= implode("\n\t", $this->inline_scripts);

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Used internally to check whether the CSS file should be added or not.
	 *
	 * @param 	string 	$handle
	 * @return 	bool
	 */
	protected function should_add_css(string $handle)
	{
		return (is_ajax(true) OR is_api() OR isset($this->styles[$handle])) ? false : true;
	}

	// --------------------------------------------------------------------

	/**
	 * Used internally to check whether the JS file should be added or not.
	 *
	 * @param 	string 	$handle
	 * @return 	bool
	 */
	protected function should_add_js(string $handle)
	{
		return (is_ajax(true) OR is_api() OR isset($this->scripts[$handle])) ? false : true;
	}

	// --------------------------------------------------------------------
	// Assets Queuers.
	// --------------------------------------------------------------------

	/**
	 * Adds Modernizr JS.
	 *
	 * @return 	self
	 */
	public function modernizr()
	{
		if ( ! $this->should_add_js('modernizr-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', 'js', 'modernizr', null, true);
		}
		else
		{
			return $this->add('vendor/modernizr.min.js', 'js', 'modernizr', null, true);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds jQuery JS.
	 *
	 * @return 	self
	 */
	public function jquery()
	{
		if ( ! $this->should_add_js('jquery-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$this->_parent->theme->add_meta('preload', $file_url = '//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', 'rel', array('as' => 'script'));
			return $this->add($file_url, 'js', 'jquery', null, true);
		}
		else
		{
			return $this->add('vendor/jquery.min.js', 'js', 'jquery', null, true);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds jQuery UI CSS and JS files
	 *
	 * @param 	bool 	$touch_punch
	 * @return 	self
	 */
	public function jquery_ui($touch_punch = true)
	{
		if ( ! $this->should_add_js('jquery-ui-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js';

			if ($touch_punch)
			{
				$touch_url = '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js';
			}
		}
		else
		{
			$css_url = 'vendor/jquery-ui.min.css';
			$js_url = 'vendor/jquery-ui.min.js';

			$touch_punch && $touch_url = 'vendor/jquery.ui.touch-punch.min.js';
		}

		$this->add($css_url, 'css', 'jquery-ui');
		$this->add($js_url, 'js', 'jquery-ui');

		isset($touch_url) && $this->add($touch_url, 'js', 'jquery-ui-touch');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Add jQuery Validate JS file.
	 *
	 * @return 	self
	 */
	public function jquery_validate()
	{
		if ( ! $this->should_add_js('jquery-validate-js'))
		{
			return $this;
		}

		$this->add('vendor/jquery.validate.min.js', 'js', 'jquery-validate');

		if (($code = $this->_parent->lang->current('code')) !== 'en')
		{
			$this->add("vendor/jquery-validate/messages_{$code}.min.js", 'js', 'jquery-validate-locale');
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Add jQuery Nestable CSS and JS files.
	 *
	 * @return 	self
	 */
	public function jquery_nestable()
	{
		if ( ! $this->should_add_js('jquery-nestable-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js';
		}
		else
		{
			$css_url = 'vendor/jquery.nestable.min.css';
			$js_url = 'vendor/jquery.nestable.min.js';
		}

		$this->add($css_url, 'css', 'jquery-nestable');
		$this->add($js_url, 'js', 'jquery-nestable');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Font-Awesome CSS.
	 *
	 * @return 	self
	 */
	public function fontawesome()
	{
		if ( ! $this->should_add_css('fontawesome-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$this->_parent->theme->add_meta('preload', $file_url = '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', 'rel', array('as' => 'style'));
			return $this->add($file_url, 'css', 'fontawesome', null, true);
		}
		else
		{
			return $this->add('vendor/font-awesome.min.css', 'css', 'fontawesome', null, true);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Vue JS.
	 *
	 * @return 	self
	 */
	public function vue()
	{
		if ( ! $this->should_add_js('vue-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/vue/3.5.13/vue.global.prod.min.js', 'js', 'vue', null, true);
		}
		else
		{
			return $this->add('vendor/vue.min.js', 'js', 'vue', null, true);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Bootstrap CSS and JS files.
	 *
	 * @return self
	 */
	public function bootstrap()
	{
		if ( ! $this->should_add_css('bootstrap-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = $this->_parent->lang->is_rtl()
				? '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.rtl.min.css'
				: '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css';
			$this->_parent->theme->add_meta('preload', $css_url, 'rel', array('as' => 'style'));

			$js_url = '//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js';
		}
		else
		{
			$css_url = $this->_parent->lang->is_rtl()
				? 'vendor/bootstrap.rtl.min.css'
				: 'vendor/bootstrap.min.css';

			$js_url = 'vendor/bootstrap.min.js';
		}

		$this->add($css_url, 'css', 'bootstrap', null, true);
		$this->add($js_url, 'js', 'bootstrap', null, true);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Toastr CSS and JS files.
	 *
	 * @return 	self
	 */
	public function toastr()
	{
		if ( ! $this->should_add_css('toastr-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js';
		}
		else
		{
			$css_url = 'vendor/toastr.min.css';
			$js_url = 'vendor/toastr.min.js';
		}

		$this->add($css_url, 'css', 'toastr');
		$this->add($js_url, 'js', 'toastr');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds jQuery Sprintf JS file.
	 *
	 * @return 	self
	 */
	public function sprintf()
	{
		if ( ! $this->should_add_js('sprintf-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/sprintf/1.1.2/sprintf.min.js', 'js', 'sprintf');
		}
		else
		{
			return $this->add('vendor/sprintf.min.js', 'js', 'sprintf');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Handlebars JS File.
	 *
	 * @return 	self
	 */
	public function handlebars()
	{
		if ( ! $this->should_add_js('handlebars-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.6/handlebars.min.js', 'js', 'handlebars');
		}
		else
		{
			return $this->add('vendor/handlebars.min.js', 'js', 'handlebars');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Bootbox JS File.
	 *
	 * @return 	self
	 */
	public function bootbox()
	{
		if ( ! $this->should_add_js('bootbox-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js', 'js', 'bootbox');
		}
		else
		{
			return $this->add('vendor/bootbox.min.js', 'js', 'bootbox');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Datatables CSS and JS files;
	 *
	 * @param 	bool 	$bs4 	Whether to use Bootstrap 4.
	 * @return 	self
	 */
	public function datatables(bool $bs4 = true)
	{
		if ( ! $this->should_add_css('datatables-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			// Main files.
			$this->add('//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.min.css', 'css', 'datatables');
			$this->add('//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js', 'js', 'datatables');

			// Bootstrap 4.
			if ($bs4)
			{
				$this->add('//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css', 'css', 'datatables-bs4');
				$this->add('//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap4.min.js', 'js', 'datatables-bs4');
			}

			return $this;
		}
		else
		{
			// Main files.
			$this->add('vendor/datatables.min.css', 'css', 'datatables');
			$this->add('vendor/datatables.min.js', 'js', 'datatables');

			// Bootstrap 4.
			if ($bs4)
			{
				$this->add('vendor/datatables-bs4.min.css', 'css', 'datatables-bs4');
				$this->add('vendor/datatables-bs4.min.js', 'js', 'datatables-bs4');
			}

			return $this;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Dropzone CSS and JS files.
	 *
	 * @return 	self
	 */
	public function dropzone()
	{
		if ( ! $this->should_add_css('dropzone-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js';
		}
		else
		{
			$css_url = 'vendor/dropzone.min.css';
			$js_url = 'vendor/dropzone.min.js';
		}

		$this->add($css_url, 'css', 'dropzone');
		$this->add($js_url, 'js', 'dropzone');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Zoom CSS and JS files.
	 *
	 * @return 	self
	 */
	public function zoom()
	{
		if ( ! $this->should_add_css('zoom-css'))
		{
			return $this;
		}

		$this->add('vendor/zoom.min.css', 'css', 'zoom');
		$this->add('vendor/zoom.min.js', 'js', 'zoom');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Axios JS file.
	 *
	 * @return 	self
	 */
	public function axios()
	{
		if ( ! $this->should_add_js('axios-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js', 'js', 'axios');
		}
		else
		{
			return $this->add('vendor/axios.min.js', 'js', 'axios');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Garlic JS file.
	 *
	 * @return 	self
	 */
	public function garlic()
	{
		if ( ! $this->should_add_js('garlic-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			return $this->add('//cdnjs.cloudflare.com/ajax/libs/garlic.js/1.4.2/garlic.min.js', 'js', 'garlic');
		}
		else
		{
			return $this->add('vendor/garlic.min.js', 'js', 'garlic');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns all available Highlight JS styles.
	 *
	 * @return 	array
	 */
	private function highlight_styles()
	{
		static $styles = array(
			'1c-light',
			'a11y-dark',
			'a11y-light',
			'agate',
			'an-old-hope',
			'androidstudio',
			'arduino-light',
			'arta',
			'ascetic',
			'atom-one-dark-reasonable',
			'atom-one-dark',
			'atom-one-light',
			'brown-paper',
			'codepen-embed',
			'color-brewer',
			'dark',
			'default',
			'devibeans',
			'docco',
			'far',
			'felipec',
			'foundation',
			'github-dark-dimmed',
			'github-dark',
			'github',
			'gml',
			'googlecode',
			'gradient-dark',
			'gradient-light',
			'grayscale',
			'hybrid',
			'idea',
			'intellij-light',
			'ir-black',
			'isbl-editor-dark',
			'isbl-editor-light',
			'kimbie-dark',
			'kimbie-light',
			'lightfair',
			'lioshi',
			'magula',
			'mono-blue',
			'monokai-sublime',
			'monokai',
			'night-owl',
			'nnfx-dark',
			'nnfx-light',
			'nord',
			'obsidian',
			'panda-syntax-dark',
			'panda-syntax-light',
			'paraiso-dark',
			'paraiso-light',
			'pojoaque',
			'purebasic',
			'qtcreator-dark',
			'qtcreator-light',
			'rainbow',
			'routeros',
			'school-book',
			'shades-of-purple',
			'srcery',
			'stackoverflow-dark',
			'stackoverflow-light',
			'sunburst',
			'tokyo-night-dark',
			'tokyo-night-light',
			'tomorrow-night-blue',
			'tomorrow-night-bright',
			'vs',
			'vs2015',
			'xcode',
			'xt256'
		);

		return $styles;
	}

	/**
	 * Adds Highlight CSS and JS files.
	 *
	 * @param 	string 	$style 		The style to use.
	 * @param 	string 	$inline 	Inline JS to override default one.
	 * @return 	self
	 */
	public function highlight(string $style = 'default', string $inline = null)
	{
		if ( ! $this->should_add_js('highlight-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			in_array($style, $this->highlight_styles()) OR $style = 'default';
			$css_url = "//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/{$style}.min.css";
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js';
		}
		else
		{
			in_array($style, $this->highlight_styles()) OR $style = 'default';
			$css_url = "vendor/highlight/{$style}.min.css";
			$js_url = 'vendor/highlight.min.js';
		}

		$this->add($css_url, 'css', 'highlight');
		$this->add($js_url, 'js', 'highlight');

		if (empty($inline))
		{
			$inline = '$(document).ready(function(){document.querySelectorAll("pre code").forEach(function(e){hljs.highlightElement(e)})});';
		}

		return $this->add_inline($inline, 'js', 'highlight-inline');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Select2 CSS and JS files.
	 *
	 * @param 	bool 	$bootstrap 	Whether to load bootstrap style.
	 * @return 	self
	 */
	public function select2($bootstrap = true)
	{
		if ( ! $this->should_add_js('select2-js'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js';

			if ($bootstrap)
			{
				$bootstrap_url = '//cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css';
			}

			if (($code = $this->_parent->lang->current('code')) !== 'en')
			{
				$i18n_url = "//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/{$code}.min.js";
			}
		}
		else
		{
			$css_url = 'vendor/select2.min.css';
			$js_url = 'vendor/select2.min.js';

			if ($bootstrap)
			{
				$bootstrap_url = 'vendor/select2-bootstrap.min.css';
			}

			if (($code = $this->_parent->lang->current('code')) !== 'en')
			{
				$i18n_url = "vendor/select2/{$code}.js";
			}
		}

		$this->add($css_url, 'css', 'select2');
		$this->add($js_url, 'js', 'select2');

		isset($bootstrap_url) && $this->add($bootstrap_url, 'css', 'select2-bootstrap');
		isset($i18n_url) && $this->add($i18n_url, 'js', 'select2-locale');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Summernote CSS and JS files.
	 *
	 * @param 	bool 	$bootstrap
	 * @return 	self
	 */
	public function summernote($bootstrap = false)
	{
		if ( ! $this->should_add_js('summernote-js'))
		{
			return $this;
		}
		// Live mode, with Bootstrap.
		elseif ($this->_parent->is_live && $bootstrap)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.js';
		}
		// Live mode, no Bootstrap.
		elseif ($this->_parent->is_live)
		{
			$css_url = '//cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-lite.min.css';
			$js_url = '//cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-lite.min.js';
		}
		// Locally, with Bootstrap.
		elseif ($bootstrap)
		{
			$css_url = 'vendor/summernote-bs5.min.css';
			$js_url = 'vendor/summernote-bs5.min.js';
		}
		// Locally, no Bootstrap.
		else
		{
			$css_url = 'vendor/summernote-lite.min.css';
			$js_url = 'vendor/summernote-lite.min.js';
		}

		// Add files.
		$this->add($css_url, 'css', 'summernote');
		$this->add($js_url, 'js', 'summernote');

		// Localization.
		if (($locale = $this->_parent->lang->current('locale')) !== 'en-US')
		{
			$i18n_url = $this->_parent->is_live
				? "//cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/lang/summernote-{$locale}.min.js"
				: "vendor/summernote/summernote-{$locale}.min.js";

			$this->add($i18n_url, 'js', 'summernote-locale');
		}

		$inline = <<<JS
\$(document).ready(function() {
	\$(".summernote").each(function() {
		var \$that = \$(this);

		\$that.summernote({
			lang: csk.config.lang.locale,
			height: \$that.attr("data-height") || 275,
			inheritPlaceholder: true,
			prettifyHtml: true,
			toolbar: [
				['style', ['style']],
				['edit', ['undo', 'redo']],
				['font', ['clear', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript']],
				['color', ['forecolor', 'backcolor']],
				['para', ['ul', 'ol', 'paragraph']],
				['insert', ['table', 'link', 'picture', 'video', 'hr']],
				['view', ['fullscreen', 'codeview', 'help']],
			],
			popover: {
				image: [
					['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
					['float', ['floatLeft', 'floatRight', 'floatNone']],
					['remove', ['removeMedia']]
					],
				link: [
					['link', ['linkDialogShow', 'unlink']]
				],
				table: [
					['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
					['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
				],
				air: [
					['color', ['color']],
					['font', ['bold', 'underline', 'clear']],
					['para', ['ul', 'paragraph']],
					['table', ['table']],
					['insert', ['link', 'picture']]
				]
			},
			callbacks: {
				onPaste: function (e) {
					var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
					e.preventDefault();
					document.execCommand('insertText', false, bufferText);
				},
				onImageUpload: function(files) {
					\$that
						.closest('form')
						.find("[type=submit]")
						.prop("disabled", true)
						.addClass("disabled");

					for (var i = 0; i <= files.length; i++) {
						var file = files[i];
						if (typeof file !== "undefined") {
							csk.sendFile(file, this);
						}
					}
				}
			}
		});

		if (\$that.hasClass("summernote-no-upload")) {
			\$('.note-group-select-from-files').first().remove();
		}
	});
});
JS;

		return $this->add_inline($inline, 'js', 'summernote-inline');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Flags CSS file.
	 *
	 * @return 	self
	 */
	public function flags()
	{
		if ( ! $this->should_add_css('flags-css'))
		{
			return $this;
		}

		return $this->add($this->_parent->is_live ? 'flags.min.css' : 'flags.css', 'css', 'flags');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Social Networks colors (buttons, texts ... etc).
	 *
	 * @return 	self
	 */
	public function social()
	{
		if ( ! $this->should_add_css('social-css'))
		{
			return $this;
		}

		return $this->add($this->_parent->is_live ? 'social.min.css' : 'social.css', 'css', 'social');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds Animate CSS file.
	 *
	 * @param 	bool 	$compat 	Whether to use no-prefix version.
	 * @return 	self
	 */
	public function animate(bool $compat = false)
	{
		if ( ! $this->should_add_css('animate-css'))
		{
			return $this;
		}
		elseif ($this->_parent->is_live)
		{
			$file_url = $compat
				? '//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.compat.min.css'
				: '//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';
		}
		else
		{
			$file_url = $compat ? 'vendor/animate.compat.min.css' : 'vendor/animate.min.css';
		}

		return $this->add($file_url, 'css', 'animate');
	}

}

/*=======================================================
=            STYLES AND STYLSHEETS FUNCTIONS            =
=======================================================*/

if ( ! function_exists('css'))
{
	/**
	 * Outputs a full CSS <link> tag.
	 * @param   string  $file   the file name.
	 * @param   string  $cdn    the cdn file to use.
	 * @param   array   $attrs  array of additional attributes.
	 * @param   bool    $common in case of a js file in the common folder.
	 */
	function css($file, $cdn = null, $attrs = array(), $common = false)
	{
		if ($file)
		{
			$attributes = array(
				'rel' => 'stylesheet',
				'type' => 'text/css'
			);

			$file = ($common === true) ? common_url($file) : $this->ci->config->theme_url($file);

			$file               = preg_replace('/.css$/', '', $file).'.css';
			$attributes['href'] = $file;

			// Are there any other attributes to use?
			if (is_array($attrs))
			{
				$attributes = array_replace_recursive($attributes, $attrs);
				return '<link'.array_to_attr($attributes).'/>'."\n";
			}

			$attributes = array_to_attr($attributes)." {$attrs}";
			return '<link'.$attributes.' />'."\n\t";
		}

		return null;
	}
}

/*=============================================
=            JAVASCRIPTS FUNCTIONS            =
=============================================*/

if ( ! function_exists('js'))
{
	/**
	 * Outputs a full <script> tag.
	 * @param   string  $file   the file name.
	 * @param   string  $cdn    the cdn file to use.
	 * @param   array   $attrs  array of additional attributes.
	 * @param   bool    $common in case of a js file in the common folder.
	 */
	function js($file, $cdn = null, $attrs = array(), $common = false)
	{
		if ($file)
		{
			$attributes['type'] = 'text/javascript';

			$file = ($common === true) ? common_url($file) : theme_url($file);

			$file              = preg_replace('/.js$/', '', $file).'.js';
			$attributes['src'] = $file;

			// Are there any other attributes to use?
			if (is_array($attrs))
			{
				$attributes = array_replace_recursive($attributes, $attrs);
				return '<link'.array_to_attr($attributes).'/>'."\n";
			}

			$attributes = array_to_attr($attributes)." {$attrs}";
			return '<script'.$attributes.'></script>'."\n";
		}

		return null;
	}
}
