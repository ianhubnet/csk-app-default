<?php
defined('BASEPATH') OR die;

/**
 * Language Module - Admin Controller
 *
 * Allow administrators to manage site's languages.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Modules\Controllers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0.0
 * @version 	2.11
 */
class Languages extends Admin_Controller
{
	/**
	 * Access reserved for admins and above.
	 * @since 	2.16
	 * @var 	integer
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array('*' => 'admin/languages');

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load required language file.
		$this->lang->load('lang');
	}

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
			/**
			 * Since flags.css isn't queued in case of only a single language
			 * is enabled, we make sure to add here in order to display flags
			 * on the list of languages.
			 * @since 2.55
			 */
			$this->assets->flags();

			// Page title, icon and help URL.
			$this->page_title = $this->lang->line('languages');
			$this->page_icon  = 'language';
			$this->page_help  = 'http://bit.ly/CSKLanguages';
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Method for displaying the list of available site languages.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		// Get site's available languages.
		$available_languages = $this->config->item('languages');

		// All Skeleton languages.
		$this->data['languages'] = $this->i18n->all();

		/**
		 * We check if the language folder is available or not and set
		 * it to available if found. This way we avoid installing languages
		 * that are not really available.
		 */
		foreach ($this->data['languages'] as $folder => &$info)
		{
			$info['enabled'] = in_array($folder, $available_languages);

			/**
			 * If the language is set a default, no action should
			 * be available unless changed.
			 */
			if ($folder === $this->lang->fallback)
			{
				continue;
			}

			// Action buttons.
			$info['actions'] = array();

			// Disable language action.
			if ($info['enabled'])
			{
				// Make default action.
				$info['actions'][] = $this->theme->template(
					'button_icon_attrs', 'javascript:void(0);', $this->lang->line('make_default'),
					'default', 'star text-warning', array_to_attr(array(
						'role'         => 'button',
						'aria-label'   => $this->lang->line($info['id']),
						'data-form'    => esc_url(nonce_admin_url('languages/default', "languages-default_$folder")),
						'data-confirm' => 'lang:languages.make_default',
						'data-fields'  => "id:$folder"
					))
				);

				// Disable action.
				$info['actions'][] = $this->theme->template(
					'button_icon_attrs', 'javascript:void(0);', $this->lang->line('disable'),
					'danger', 'times-circle', array_to_attr(array(
						'role'         => 'button',
						'aria-label'   => $this->lang->line($info['id']),
						'data-form'    => esc_url(nonce_admin_url('languages/disable', "languages-disable_$folder")),
						'data-confirm' => 'lang:languages.disable',
						'data-fields'  => "id:$folder"
					))
				);
			}
			// Enable language action.
			else
			{
				$info['actions'][] = $this->theme->template(
					'button_icon_attrs', 'javascript:void(0);', $this->lang->line('enable'),
					'success', 'check-circle', array_to_attr(array(
						'role'         => 'button',
						'aria-label'   => $this->lang->line($info['id']),
						'data-form'    => esc_url(nonce_admin_url('languages/enable', "languages-enable_$folder")),
						'data-confirm' => 'lang:languages.enable',
						'data-fields'  => "id:$folder"
					))
				);
			}
		}

		// Set page title and render view.
		$this->render();
	}

	// --------------------------------------------------------------------
	// Private methods.
	// --------------------------------------------------------------------

	/**
	 * Makes the selected language by default
	 *
	 * @return 	void
	 */
	protected function _default()
	{
		// Make sure we have a language.
		if (empty($idiom = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_default_error'), 'error');
			redirect('admin/languages');
		}

		// Security check.
		elseif ($this->nonce->verify_request("languages-default_$idiom") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/languages');
		}

		// Not available?
		elseif ( ! in_array($idiom, $this->config->item('languages')))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_default_error'), 'error');
			redirect('admin/languages');
		}

		// Already by default?
		elseif ($idiom === $this->i18n->default('folder'))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_default_error_nochange'), 'error');
			redirect('admin/languages');
		}

		// Unable to change it?
		elseif ( ! $this->config->write('language', array('language' => $idiom)))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_default_error'), 'error');
			redirect('admin/languages');
		}

		// Passed
		$this->activities->log($this->user->id, "report_language_default:lang_$idiom");
		$this->theme->set_alert($this->lang->line('admin_languages_default_success'), 'success');
		redirect('admin/languages');
	}

	// --------------------------------------------------------------------

	/**
	 * Enables a language
	 *
	 * @return 	void
	 */
	protected function _enable()
	{
		// Make sure we have a language.
		if (empty($idiom = strtolower($this->input->post('id', true))))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_enable_error'), 'error');
			redirect('admin/languages');
		}

		// Security check.
		elseif ($this->nonce->verify_request("languages-enable_$idiom") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/languages');
		}

		$languages = $this->config->item('languages', null, array());

		// Enable all languages?
		if ($idiom === 'all')
		{
			$this->_save(
				array_keys($this->i18n->all()),
				$this->lang->line('admin_languages_enable_all_success'),
				$this->lang->line('admin_languages_enable_all_error'),
			);
			return;
		}

		// Already enabled?
		if (in_array($idiom, $languages) OR ! array_key_exists($idiom, $this->i18n->all()))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_enable_error_nochange'), 'error');
			redirect('admin/languages');
		}

		// Add the language
		$languages[] = $idiom;
		asort($languages);

		$this->_save(
			$languages,
			$this->lang->line('admin_languages_enable_success'),
			$this->lang->line('admin_languages_enable_error')
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Disable a language
	 *
	 * @return 	void
	 */
	protected function _disable()
	{
		// Make sure we have a language.
		if (empty($idiom = strtolower($this->input->post('id', true))))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_disable_error'), 'error');
			redirect('admin/languages');
		}

		// Security check.
		elseif ($this->nonce->verify_request("languages-disable_$idiom") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/languages');
		}

		// Cannot disable default site's language.
		elseif ($idiom === $this->lang->fallback)
		{
			if ( ! $this->config->set_item('language', array('language' => 'english')))
			{
				$this->theme->set_alert($this->lang->line('admin_languages_disable_error'), 'error');
				redirect('admin/languages');
			}
		}

		$languages = $this->config->item('languages', null, array());

		// Disable all languages?
		if ($idiom === 'all')
		{
			$this->_save(
				array($this->lang->fallback),
				$this->lang->line('admin_languages_disable_all_success'),
				$this->lang->line('admin_languages_disable_all_error')
			);
			return;
		}

		// Already enabled?
		if ( ! in_array($idiom, $languages))
		{
			$this->theme->set_alert($this->lang->line('admin_languages_disable_error_nochange'), 'error');
			redirect('admin/languages');
		}

		// Add the language
		foreach ($languages as $i => $lang)
		{
			if ($lang === $idiom)
			{
				unset($languages[$i]);
			}
		}
		asort($languages);

		$this->_save(
			$languages,
			$this->lang->line('admin_languages_disable_success'),
			$this->lang->line('admin_languages_disable_error')
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Common method used to save languages array.
	 *
	 * @since 	2.128
	 *
	 * @param 	array 	$idioms 		The array of languages to save.
	 * @param 	string 	$success_msg 	Alert message upon success.
	 * @param 	string 	$error_msg 		Alert message upon error.
	 * @return 	void
	 */
	private function _save(array $idioms, string $success_msg, string $error_msg)
	{
		if ( ! $this->config->write('language', array('languages' => array_values($idioms))))
		{
			$this->theme->set_alert($error_msg, 'error');
			redirect('admin/languages');
		}

		$this->theme->set_alert($success_msg, 'success');
		redirect('admin/languages');
	}

	// --------------------------------------------------------------------

	/**
	 * __head
	 *
	 * Add some JS lines to admin head section.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		switch ($this->router->method)
		{
			// Language main page.
			case 'index':
				$lines = array(
					'enable'       => $this->lang->line('admin_languages_enable_confirm'),
					'enable_all'   => $this->lang->line('admin_languages_enable_all_confirm'),
					'disable'      => $this->lang->line('admin_languages_disable_confirm'),
					'disable_all'  => $this->lang->line('admin_languages_disable_all_confirm'),
					'make_default' => $this->lang->line('admin_languages_default_confirm')
				);

				$output .= '<script type="text/javascript">';
				$output .= 'csk.i18n = csk.i18n || {};';
				$output .= ' csk.i18n.languages = '.json_encode($lines).';';
				$output .= '</script>';
				break;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		// Enable all button.
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('enable_all'),
			'success btn-sm', 'check-circle',
			array_to_attr(array(
				'role'         => 'button',
				'aria-label'   => $this->lang->line('enable_all'),
				'data-form'    => esc_url(nonce_admin_url('languages/enable', 'languages-enable_all')),
				'data-confirm' => 'lang:languages.enable_all',
				'data-fields'  => 'id:all'
			))
		),

		// Disable all button.
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('disable_all'),
			'danger btn-sm ms-1', 'times-circle',
			array_to_attr(array(
				'role'         => 'button',
				'aria-label'   => $this->lang->line('disable_all'),
				'data-form'    => esc_url(nonce_admin_url('languages/disable', 'languages-disable_all')),
				'data-confirm' => 'lang:languages.disable_all',
				'data-fields'  => 'id:all'
			))
		),

		// Just some info tag.
		html_tag(
			'span',
			'class="d-none d-md-inline lh-lg ms-md-2"',
			fa_icon('info-circle text-primary me-1', $this->lang->line('admin_languages_tip'))
		);
	}

}
