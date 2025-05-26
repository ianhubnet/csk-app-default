<?php
defined('BASEPATH') OR die;

/**
 * Settings Class
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.0
 */
class Settings extends Settings_Controller
{
	/**
	 * Only accessible for admins and above.
	 * @since   2.16
	 * @var     int
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * Stores the current settings tab.
	 * @var string
	 */
	protected $curr_tab = 'general';

	// --------------------------------------------------------------------

	/**
	 * Stuff to do before loading our page.
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function _remap($method, $params = array())
	{
		if ($method === 'index')
		{
			$this->prep_tabs();

			$this->assets->jquery_validate();

			$this->page_title = $this->lang->line('settings_global');

			if ($this->curr_tab !== 'general'
				&& ($this->page_title = $this->lang->_translate('settings_'.$this->curr_tab)) === null)
			{
				$this->page_title = $this->lang->sline('settings_com', $this->lang->line($this->curr_tab));
			}

			// Prepare page icon.
			switch ($this->curr_tab) {
				case 'users':
					$this->page_icon = 'users';
					break;

				case 'datetime':
					$this->page_icon = 'calendar';
					break;

				case 'email':
					$this->page_icon = 'envelope';
					break;

				case 'captcha':
					$this->page_icon = 'lock';
					break;

				case 'upload':
					$this->page_icon = 'upload';
					break;

				case 'manifest':
					$this->page_icon = 'fab:android';
					break;

				case 'google':
				case 'facebook':
				case 'github':
				case 'linkedin':
				case 'discord':
					$this->page_icon = 'fab:'.$this->curr_tab;
					break;

				case 'general':
				default:
					$this->page_icon = 'sliders';
					break;
			}
		}
		elseif ($method === 'sysinfo')
		{
			$this->page_title = $this->lang->line('settings_sysinfo');
			$this->page_icon  = 'info-circle';
			$this->page_help  = null;
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method for updating site global settings.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function index()
	{
		// Let's see what tab we are on.
		[$this->data['inputs'], $rules] = $this->_prep_settings($this->curr_tab);

		// missing inputs?
		if (false === $this->data['inputs'])
		{
			redirect('admin/settings');
		}

		// Set validation rules
		$this->prep_form($rules, '#settings-'.$this->curr_tab);
		$this->data['tab'] = $this->curr_tab;

		// Prepare form action.
		$action = '';
		('general' !== $this->curr_tab) && $action .= '?tab='.$this->curr_tab;
		$this->data['action'] = 'admin/settings'.$action;

		if ($this->form_validation->run() === false)
		{
			$this->data['hidden'][COOK_CSRF] = $this->nonce->create('settings-'.$this->curr_tab);
			$this->render();
		}
		// Failed nocne?
		elseif ($this->nonce->verify_request('settings-'.$this->curr_tab) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/settings'.$action);
		}
		else
		{
			// Disabled in admin mode!
			$this->core->redirect_demo('admin/settings'.$action);

			$this->_save_settings($this->data['inputs'], $this->curr_tab);
			redirect('admin/settings'.$action);
		}

	}

	// --------------------------------------------------------------------

	/**
	 * Prepares setting tabs.
	 *
	 * @return 	void
	 */
	private function prep_tabs()
	{
		// Add tabs and fields order.
		$this->_tabs = array(

			// Global Settings.
			'general' => array(
				'site_name',
				'site_description',
				'site_keywords',
				'site_author',
				'site_favicon',
				'base_controller',
				'per_page',
				'site_offline',
				'offline_access_level',
				'demo_mode',
				'enable_profiler'
			),

			// User Settings.
			'users' => array(
				'allow_registration',
				'email_activation',
				'manual_activation',
				'login_type',
				'allow_multi_session',
				'allow_remember',
				'allow_quick_login',
				'alert_login_success',
				'alert_login_failed',
				'allow_oauth',
				'login_fail_enabled',
				'login_fail_allowed_attempts',
				'login_fail_short_lockout',
				'login_fail_allowed_lockouts',
				'login_fail_long_lockout',
				'use_gravatar'
			),

			// Datetime Settings.
			'datetime' => array(
				'time_reference',
				'date_format',
				'time_format'
			),

			// Email Settings.
			'email' => array(
				'admin_email',
				'server_email',
				'contact_email',
				'mail_protocol',
				'sendmail_path',
				'smtp_host',
				'smtp_port',
				'smtp_crypto',
				'smtp_user',
				'smtp_pass'
			),

			// Captcha settings.
			'captcha' => array(
				'use_captcha',
				'use_recaptcha',
				'recaptcha_site_key',
				'recaptcha_private_key'
			),

			// Upload Settings.
			'upload' => array(
				'upload_path',
				'allowed_types',
				'max_size',
				'min_width',
				'min_height',
				'max_width',
				'max_height',
				'upload_year_month',
				'image_watermark'
			),

			// Manifest Settings.
			'manifest' => array(
				'use_manifest',
				'site_short_name',
				'site_background_color',
				'site_theme_color'
			)
		);

		if ($this->config->item('allow_oauth'))
		{
			// Google.
			$this->_tabs['google'] = array(
				'google_analytics_id',
				'google_tagmanager_id',
				'google_site_verification',
				'google_auth',
				'google_client_id',
				'google_client_secret'
			);

			// Facebook.
			$this->_tabs['facebook'] = array(
				'facebook_auth',
				'facebook_app_id',
				'facebook_app_secret',
				'facebook_pixel_id'
			);

			// LinkedIn.
			$this->_tabs['linkedin'] = array(
				'linkedin_auth',
				'linkedin_client_id',
				'linkedin_client_secret'
			);

			// GitHub.
			$this->_tabs['github'] = array(
				'github_auth',
				'github_client_id',
				'github_client_secret'
			);

			// Discord.
			$this->_tabs['discord'] = array(
				'discord_auth',
				'discord_client_id',
				'discord_client_secret'
			);
		}
		else
		{
			// General
			array_splice($this->_tabs['general'], 7, 0, array(
				'google_analytics_id',
				'google_tagmanager_id',
				'google_site_verification',
				'facebook_app_id',
				'facebook_pixel_id'
			));
		}

		// store current settings tab.
		(($tab = $this->input->get('tab', true)) && isset($this->_tabs[$tab])) && $this->curr_tab = $tab;
	}

	// --------------------------------------------------------------------

	/**
	 * sysinfo
	 *
	 * Method for displaying system information.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function sysinfo()
	{
		// Disabled in demo mode!
		$this->core->redirect_demo('admin');

		// System information.
		$this->data['info'] = array(
			'php_built_on'     => php_uname(),
			'php_version'      => phpversion(),
			'database_type'    => $this->db->platform(),
			'database_version' => $this->db->version(),
			'web_server'       => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE'),
			'skeleton_version' => KB_VERSION,
			'user_agent'       => $this->agent->agent_string(),
		);

		// PHP Settings.
		$this->data['php'] = array(
			'safe_mode'          => ini_get('safe_mode') == '1',
			'display_errors'     => ini_get('display_errors') == '1',
			'short_open_tag'     => ini_get('short_open_tag') == '1',
			'file_uploads'       => ini_get('file_uploads') == '1',
			'magic_quotes_gpc'   => ini_get('magic_quotes_gpc') == '1',
			'register_globals'   => ini_get('register_globals') == '1',
			'output_buffering'   => (int) ini_get('output_buffering') !== 0,
			'open_basedir'       => ini_get('open_basedir'),
			'session.save_path'  => ini_get('session.save_path'),
			'session.auto_start' => ini_get('session.auto_start'),
			'disable_functions'  => ini_get('disable_functions'),
			'xml'                => extension_loaded('xml'),
			'zlib'               => extension_loaded('zlib'),
			'zip'                => function_exists('zip_open') && function_exists('zip_read'),
			'mbstring'           => extension_loaded('mbstring'),
			'iconv'              => function_exists('iconv'),
			'max_input_vars'     => ini_get('max_input_vars'),
		);

		// PHP Info (if enabled).
		function_exists('phpinfo') && $this->data['phpinfo'] = $this->_get_phpinfo();

		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * _get_phpinfo
	 *
	 * Method for getting PHP information.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  string
	 */
	protected function _get_phpinfo()
	{
		ob_start();
		date_default_timezone_set('UTC');
		phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
		$phpInfo = ob_get_contents();
		ob_end_clean();

		preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpInfo, $output);

		$output = preg_replace('#<table[^>]*>#', '<div class="card card-sm border-top-0"><div class="card-body"><table class="table table-sm table-hover table-striped mb-0">', $output[1][0]);
		$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
		$output = preg_replace('#<hr />#', '', $output);
		$output = str_replace('<div class="center">', '', $output);
		$output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
		$output = str_replace('</table>', '</tbody></table></div></div>', $output);
		$output = str_replace('</div>', '', $output);

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (index).
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		echo '<div class="btn-group btn-group-sm">',

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings'),
			$this->lang->line('general'),
			($this->curr_tab == 'general' ? 'primary' : 'default'),
			'cog'
		),

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings?tab=users'),
			$this->lang->line('admin_users'),
			($this->curr_tab == 'users' ? 'primary' : 'default'),
			'users'
		),

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings?tab=datetime'),
			$this->lang->line('date'),
			($this->curr_tab == 'datetime' ? 'primary' : 'default'),
			'calendar'
		),

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings?tab=email'),
			$this->lang->line('email'),
			($this->curr_tab == 'email' ? 'primary' : 'default'),
			'envelope'
		),

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings?tab=captcha'),
			$this->lang->line('captcha'),
			($this->curr_tab == 'captcha' ? 'primary' : 'default'),
			'lock'
		),

		$this->theme->template(
			'button_icon',
			$this->config->admin_url('settings?tab=upload'),
			$this->lang->line('uploads'),
			($this->curr_tab == 'upload' ? 'primary' : 'default'),
			'upload'
		),

		$this->theme->template(
			'button_business_icon',
			$this->config->admin_url('settings?tab=manifest'),
			$this->lang->line('wpa'),
			($this->curr_tab == 'manifest' ? 'primary' : 'default'),
			'android'
		);

		if ($this->config->item('allow_oauth'))
		{
			echo $this->theme->template(
				'button_business_icon',
				$this->config->admin_url('settings?tab=google'),
				$this->lang->line('google'),
				($this->curr_tab == 'google' ? 'primary' : 'default'),
				'google'
			),

			$this->theme->template(
				'button_business_icon',
				$this->config->admin_url('settings?tab=facebook'),
				$this->lang->line('facebook'),
				($this->curr_tab == 'facebook' ? 'primary' : 'default'),
				'facebook-square'
			),

			$this->theme->template(
				'button_business_icon',
				$this->config->admin_url('settings?tab=github'),
				$this->lang->line('github'),
				($this->curr_tab == 'github' ? 'primary' : 'default'),
				'github'
			),

			$this->theme->template(
				'button_business_icon',
				$this->config->admin_url('settings?tab=linkedin'),
				$this->lang->line('linkedin'),
				($this->curr_tab == 'linkedin' ? 'primary' : 'default'),
				'linkedin'
			),

			$this->theme->template(
				'button_business_icon',
				$this->config->admin_url('settings?tab=discord'),
				$this->lang->line('discord'),
				($this->curr_tab == 'discord' ? 'primary' : 'default'),
				'discord'
			);
		}

		echo '</div>';
	}

}
