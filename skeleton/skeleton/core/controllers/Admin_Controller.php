<?php
defined('BASEPATH') OR die;

/**
 * Define dashboard constant in case if wasn't defined.
 * @since 2.94
 */
defined('KB_DASHBOARD') OR define('KB_DASHBOARD', true);

/**
 * Admin_Controller Class
 *
 * Controllers extending this class requires a logged in user of rank "admin".
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.11
 */
class Admin_Controller extends KB_Controller
{
	/**
	 * Access reserved for users with access to admin.
	 * @var int
	 */
	protected $access_level = KB_LEVEL_ACP;

	/**
	 * Layout to use. Can be used by modules.
	 * @var string
	 */
	protected $layout = 'default';

	/**
	 * Default dashboard title, icon, help & donate links.
	 * @since   2.16
	 */
	public $page_icon;
	public $page_help;
	public $page_donate;

	/**
	 * Class constructor
	 *
	 * @since   1.0
	 * @since   1.33   Added favicon to dashboard, removed loading admin language file
	 *                  and move some actions to "_remap" method.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load dashboard language.
		$this->lang->load('admin_core');

		// Everything but AJAX requests.
		if ( ! is_ajax(true))
		{
			// Add DNS-Prefetch.
			if ($this->core->is_live)
			{
				$this->theme
					->add_meta('dns-prefetch', '//fonts.googleapis.com/', 'rel')
					->add_meta('dns-prefetch', '//cdnjs.cloudflare.com/', 'rel');
			}

			// Load admin helper.
			$this->load->helper('admin', 'date');

			// Add needed stuff.
			add_filter('admin_head', array($this, '__main'), 0);
			add_filter('admin_head', array($this, '__head'), 99);

			// Apply admin header filter.
			method_exists($this, '_admin_head') && add_filter('admin_head', array($this, '_admin_head'));

			// Add fonts we need.
			$this->assets->css(
				'//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Noto+Kufi+Arabic:wght@100..900&&display=swap',
				'google-fonts',
				null,
				true
			);

			// Enqueue assets we need.
			$this->assets
				->sprintf()
				->handlebars()
				->fontawesome()
				->bootstrap()
				->toastr()
				->bootbox();

			$this->page_title = $this->lang->line('dashboard');
			$this->page_icon = 'dashboard';
		}
	}

	// --------------------------------------------------------------------

	/**
	 * We remap methods so we can do extra actions when we are not on methods
	 * that required AJAX requests.
	 *
	 * @since   1.33
	 *
	 * @access  public
	 * @param   string  $method     The method's name.
	 * @param   array   $params     Arguments to pass to the method.
	 * @return  mixed   Depends on the called method.
	 */
	public function _remap($method, $params = array())
	{
		// If we have a sub-menu method, use it.
		if (method_exists($this, "_submenu_{$method}"))
		{
			add_action('admin_submenu', array($this, "_submenu_{$method}"));
		}

		// If we have a right sub-menu method, use it.
		if (method_exists($this, "_submenu_right_{$method}"))
		{
			add_action('admin_submenu_right', array($this, "_submenu_right_{$method}"));
		}

		/**
		 * Admin menu is called only of method that load views.
		 * Brought back from 2.1
		 * @since 	2.105
		 */
		$this->build_admin_menu();

		/**
		 * Add assets we need.
		 * @since 	2.16
		 */

		// in production mode.
		if ($this->core->is_live)
		{
			$this->i18n->polylang && $this->assets->flags();
			$this->assets->css('admin.min.css', 'admin');
			$this->assets->js('admin.min.js', 'admin');
		}
		// in non-production modes.
		else
		{
			$this->i18n->polylang && $this->assets->flags();
			$this->assets->css('admin.css', 'admin');
			$this->assets->js('admin.js', 'admin');
		}

		/**
		 * Separated dashboard header and footer to allow different layouts.
		 * @since   2.12
		 */
		$this->theme
			->set_layout(empty($this->layout) ? 'default' : $this->layout)
			->add_partial('header')
			->add_partial('footer');

		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * Generates back button.
	 * @access 	protected
	 * @param 	string 	$uri
	 * @param 	mixed 	$button_attrs
	 * @return 	string
	 */
	protected function back_button($uri = '', $title = '', $button_attrs = '', $icon_attrs = '')
	{
		return $this->theme->template(
			'button_icon',
			$this->config->admin_url($uri),
			$title ?: $this->lang->line('back'),
			'default btn-sm'.array_to_attr($button_attrs),
			'arrow-circle-left'.array_to_attr($icon_attrs)
		);
	}

	// --------------------------------------------------------------------
	// Private methods.
	// --------------------------------------------------------------------

	/**
	 * __main
	 *
	 * Method for adding JS global before anything else.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @access  public
	 * @param   string  $output     StyleSheets output.
	 * @return  void
	 */
	public function __main($output)
	{
		// Default configuration.
		$config = array(
			'siteURL'    => $this->config->site_url(),
			'baseURL'    => $this->config->base_url(),
			'adminURL'   => $this->config->admin_url(),
			'currentURL' => $this->config->current_url(),
			'ajaxURL'    => ajax_url(),
			'lang'       => $this->i18n->current()
		);

		// Generic language lines.
		$lines = array();

		if ($this->user->admin && $this->router->is_dashboard('users', 'index'))
		{
			$lines['switch'] = $this->lang->line('switch_account_confirm');
		}

		$output .= "\n\t<script type=\"text/javascript\">";
		$output .= 'var csk = window.csk = window.csk || {};';
		$output .= ' csk.user_id = '.$this->user->id.';';
		$output .= ' csk.config = '.json_encode($config).';';
		$output .= ' csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.default = '.json_encode($lines).';';
		$output .= "</script>\n";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * __head
	 *
	 * Method for adding extra stuff to admin output before closing </head> tag.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.33
	 *
	 * @since   1.4   Left only iE9 support and other things moved to "__main".
	 *
	 * @access  public
	 * @param   string  $output     The admin head output.
	 * @return  void
	 */
	public function __head($output)
	{
		add_ie9_support($output, (ENVIRONMENT !== 'development'));
		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Handles building admin menu sections for activated modules.
	 *
	 * @return 	void
	 */
	private function build_admin_menu()
	{
		if (empty($modules = $this->modules->active(true)))
		{
			return;
		}

		foreach ($modules as $folder => $info)
		{
			// For some reason it is not enabled?
			if ( ! $info['enabled'])
			{
				continue;
			}

			// General access level set?
			elseif (isset($info['access_level']) && ! $this->auth->is_level($info['access_level']))
			{
				continue;
			}

			// Has action?
			elseif (has_action('admin_navbar-'.$folder) OR has_action('admin_navbar_right-'.$folder))
			{
				// Left admin navbar action?
				if (has_action('admin_navbar-'.$folder))
				{
					add_action('admin_navbar', function() use ($folder) {
						do_action('admin_navbar-'.$folder);
					});
				}

				// Right admin navbar action?
				if (has_action('admin_navbar_right-'.$folder))
				{
					add_action('admin_navbar_right', function() use ($folder) {
						do_action('admin_navbar_right-'.$folder);
					});
				}

				continue;
			}
			else
			{
				foreach ($info['contexts'] as $context => $status)
				{
					// Context unavailable?
					if ( ! $status)
					{
						continue;
					}
					// Context with access level
					elseif (isset($info[$level = 'access_level_'.$context]) && ! $this->auth->is_level($level))
					{
						continue;
					}
					// Now we build the context.
					else
					{
						$action_tag = $this->prep_context_action($context, $info);
						add_action($action_tag, function() use ($folder, $info, $context) {
							// prepare module URI.
							$uri = isset($info["{$context}_uri"])
								? $info["{$context}_uri"]
								: (($context !== 'admin') ? $folder.'/'.$context : $folder);

							// Prepare the text to use.
							$line = $context.'_menu';

							// See if the line was translate.
							$title = isset($info[$line]) ? $this->lang->_translate($info[$line]) : $info['name'];

							echo html_tag('a', array(
								'href' => $this->config->admin_url($uri),
								'class' => 'dropdown-item',
							), $title);

						});
					}
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * prep_context_action
	 *
	 * Since admin contexts are automatic, this method allows overriding
	 * them but choosing is which context menu a module's menu items should
	 * go. For example:
	 *
	 * - If you wish to move a module's admin item from the `Components`
	 * dropdown to `Content` dropdown while having `Admin.php` controller,
	 * you add `'admin_context' => 'content_menu'`, which means its menu goes
	 * under `content_menu` dropdown.
	 *
	 * - If you wish to move a module's admin item from the `Content`
	 * dropdown to `Component` dropdown while having `admin/Content.php` controller,
	 * you add `'content_context' => 'admin_menu'`, which means its menu goes
	 * under `admin_menu` dropdown.
	 *
	 * @param 	string 	$context 	The original context.
	 * @param 	array 	$info 		Module's info array.
	 * @return 	string 	The new context action tag.
	 */
	private function prep_context_action(string $context, array $info): string
	{
		$action_tag = isset($info[$context.'_context']) ? $info[$context.'_context'] : $context;
		return preg_replace('/_menu$/', '', $action_tag).'_menu';
	}

}
