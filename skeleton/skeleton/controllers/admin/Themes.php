<?php
defined('BASEPATH') OR die;

/**
 * Themes Module - Admin Controller
 *
 * This module allow admins to manage site themes.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Modules\Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.16
 */
class Themes extends Admin_Controller
{
	/**
	 * Access reserved for admins and above.
	 * @since   2.16
	 * @var     integer
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array(
		'_delete'  => 'admin/modules',
		'_enable'  => 'admin/modules',
		'upload'   => 'admin/modules/install'
	);

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
		$this->page_help  = 'http://bit.ly/CSKThemesDev';
		$this->page_title = $this->lang->line('admin_themes');
		$this->page_icon  = 'paint-brush';

		if ($method === 'index')
		{
			$this->assets->zoom();
		}
		elseif ($method === 'install')
		{
			$this->page_title = $this->lang->line('admin_themes_add');
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * Display available themes.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * 
	 * @since   1.0
	 * @since   1.33   Rewritten for better code readability and performance.
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function index()
	{
		// Get themes stored in database and in folder.
		$themes = $this->theme->get_themes(true);

		// Format some elements before final output.
		foreach ($themes as $folder => &$t)
		{
			// Activation button.
			if ($folder !== $this->theme->current())
			{
				$t['actions'][] = $this->theme->template(
					'button_icon_attrs',
					'javascript:void(0);',
					$this->lang->line('activate'),
					'success',
					'check-circle',
					 array_to_attr(array(
						'role' => 'button',
						'aria-label' => $t['name'],
						'data-form' => esc_url(nonce_admin_url('themes/enable', 'themes-enable_'.$folder)),
						'data-confirm' => 'lang:themes.enable',
						'data-fields' => 'id:'.$folder
					))
				);
			}

			$t['actions'][] = $this->theme->template(
				'button_icon', $this->config->admin_url('themes?theme='.$folder), $this->lang->line('details'),
				'default', 'info-circle text-info'
			);
		}

		// Displaying a single theme details?
		if (null !== ($theme = $this->input->get('theme', true)) && isset($themes[$theme]))
		{
			$get   = $theme;
			$theme = $themes[$theme];

			// Is the theme enabled?
			$theme['enabled'] = ($get === $this->config->item('theme', null, 'default'));

			// The theme has a URI?
			$theme['name_uri'] = $theme['name'];
			if ( ! empty($theme['theme_uri'])) {
				$theme['name_uri'] = anchor($theme['theme_uri'], $theme['name'], array(
					'rel' => 'nofollow',
					'target' => '_blank',
				));
			}

			// Does the license have a URI?
			if ( ! empty($theme['license_uri'])) {
				$theme['license'] = anchor($theme['license_uri'], $theme['license'], array(
					'rel'    => 'nofollow',
					'target' => '_blank',
				));
			}

			// Does the author have a URI?
			if ( ! empty($theme['author_uri'])) {
				$theme['author'] = anchor($theme['author_uri'], $theme['author'], array(
					'rel'    => 'nofollow',
					'target' => '_blank',
				));
			}

			// Did the user provide a support email address?
			if ( ! empty($theme['author_email'])) {
				$theme['author_email'] = mailto(
					$theme['author_email'].'?subject='.rawurlencode('Theme Support: '.$theme['name']),
					$theme['author_email'],
					array('target' => '_blank', 'rel' => 'nofollow')
				);
			}

			// Actions buttons.
			$theme['action_activate'] = null;
			$theme['action_delete'] = null;
			if (true !== $theme['enabled'])
			{
				$theme['action_activate'] = $this->theme->template(
					'button_icon_attrs',
					'javascript:void(0);',
					$this->lang->line('activate'),
					'success btn-sm',
					'check-circle',
					array_to_attr(array(
						'role' => 'button',
						'aria-label' => $theme['name'],
						'data-form' => esc_url(nonce_admin_url('themes/enable', 'themes-enable_'.$get)),
						'data-confirm' => 'lang:themes.enable',
						'data-fields' => 'id:'.$get
					))
				);

				$theme['action_delete'] = $this->theme->template(
					'button_icon_attrs',
					'javascript:void(0);',
					$this->lang->line('delete'),
					'danger btn-sm float-end',
					'trash',
					array_to_attr(array(
						'role' => 'button',
						'aria-label' => $theme['name'],
						'data-form' => esc_url(nonce_admin_url('themes/delete', 'themes-delete_'.$get)),
						'data-confirm' => 'lang:themes.delete',
						'data-fields' => 'id:'.$get
					))
				);
			}
		}
		else
		{
			$theme = null;
		}

		// Pass all variables to view.
		$this->data['themes'] = $themes;
		$this->data['theme']  = $theme;

		// Set page title and render view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * install
	 *
	 * Method for installing themes from future server or upload ZIP themes.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * 
	 * @since   1.34
	 * @since   1.4   Updated to use newly created nonce system.
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function install()
	{
		// We prepare form validation.
		$this->prep_form();

		$this->data['hidden'][COOK_CSRF] = $this->nonce->create('upload-theme');

		// Set page title and load view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * upload
	 *
	 * Method for uploading themes using ZIP archives.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * 
	 * @since   1.34
	 * @since   1.4   Updated to use newly created nonce system.
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function upload()
	{
		if ($this->nonce->verify_request('upload-theme') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/themes/install');
		}

		// Did the user provide a valid file?
		elseif (empty($_FILES['themezip']['name']))
		{
			$this->theme->set_alert($this->lang->line('admin_themes_upload_error'), 'error');
			redirect('admin/themes/install');
		}

		// Load our file helper and make sure the "unzip_file" function exists.
		$this->load->helper('file');
		if ( ! function_exists('unzip_file'))
		{
			$this->theme->set_alert($this->lang->line('admin_themes_upload_error'), 'error');
			redirect('admin/themes/install');
		}

		// Load upload library.
		$this->load->library('upload', array(
			'upload_path'   => FCPATH.'content/uploads/temp/',
			'allowed_types' => 'zip',
		));

		// Error uploading?
		if (false === $this->upload->do_upload('themezip') OR ! class_exists('ZipArchive', false))
		{
			$this->theme->set_alert($this->lang->line('admin_themes_upload_error'), 'error');
			redirect('admin/themes/install');
		}

		// Prepare data for later use.
		$data = $this->upload->data();

		// Catch the upload status and delete the temporary file anyways.
		$status = unzip_file($data['full_path'], FCPATH.'content/themes/');
		@unlink($data['full_path']);
		
		// Successfully installed?
		if (true === $status)
		{
			$this->theme->set_alert($this->lang->line('admin_themes_upload_success'), 'success');
			redirect('admin/themes');
		}

		// Otherwise, the theme could not be installed.
		$this->theme->set_alert($this->lang->line('admin_themes_upload_error'), 'error');
		redirect('admin/themes/install');
	}

	// --------------------------------------------------------------------
	// Quick-access methods.
	// --------------------------------------------------------------------

	/**
	 * Method for activating the given theme.
	 *
	 * @since   2.11
	 *
	 * @access  protected
	 * @param   string  $folder
	 * @return  void
	 */
	protected function _enable()
	{
		// Make sure the theme is provided.
		if (empty($folder = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('admin_themes_enable_error'), 'error');
			redirect('admin/themes');
		}

		// Security check
		elseif ($this->nonce->verify_request('themes-enable_'.$folder) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/themes');
		}

		$themes = $this->theme->get_themes();

		// Successfully updated?
		if (false !== $this->options->set_item('theme', $folder))
		{
			// Delete other themes stored options.
			foreach (array_keys($themes) as $_name)
			{
				if ($folder !== $_name)
				{
					$this->options->delete('theme_images_'.$_name);
					$this->options->delete('theme_menus_'.$_name);
				}
			}

			$this->theme->set_alert($this->lang->line('admin_themes_enable_success'), 'success');
			redirect('admin/themes');
		}
		
		$this->theme->set_alert($this->lang->line('admin_themes_enable_error'), 'error');
		redirect('admin/themes');
	}

	// --------------------------------------------------------------------

	/**
	 * Method for deleting the given theme.
	 *
	 * @since   2.11
	 *
	 * @access  protected
	 * @param   string  $folder
	 * @return  void
	 */
	protected function _delete()
	{
		// Make sure the theme is provided.
		if (empty($folder = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('admin_themes_delete_error'), 'error');
			redirect('admin/themes');
		}

		// Security check
		elseif ($this->nonce->verify_request('themes-delete_'.$folder) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/themes');
		}

		// We cannot delete the current theme.
		if ($folder === $this->theme->current())
		{
			$this->theme->set_alert($this->lang->line('admin_themes_delete_error_active'), 'error');
			redirect('admin/themes');
			return;
		}

		// Load directory helper if needed.
		(function_exists('directory_delete')) OR $this->load->helper('directory');

		// Successful delete?
		if (false !== directory_delete($this->config->themes_path($folder)))
		{
			$this->options->delete('theme_images_'.$folder);
			$this->options->delete('theme_menus_'.$folder);

			$this->theme->set_alert($this->lang->line('admin_themes_delete_success'), 'success');
			redirect('admin/themes');
		}

		// Something went wrong.
		$this->theme->set_alert($this->lang->line('admin_themes_delete_error'), 'error');
		redirect('admin/themes');
	}

	// --------------------------------------------------------------------
	// Private methods.
	// --------------------------------------------------------------------

	/**
	 * Method for adding some JS lines to the head part.
	 *
	 * @since   1.33
	 * @since   1.4   Update because CSK object was updated.
	 *
	 * @access  public
	 * @param   string
	 * @return  string
	 */
	public function _admin_head($output)
	{
		// Add lines.
		$lines = array(
			'enable' => $this->lang->line('admin_themes_enable_confirm'),
			'delete' => $this->lang->line('admin_themes_delete_confirm')
		);
		$output .= '<script type="text/javascript">';
		$output .= 'csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.themes = '.json_encode($lines).';';
		$output .= '</script>';
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
		// Add theme button.
		echo $this->theme->template(
			'button_icon',
			$this->config->admin_url('themes/install'),
			$this->lang->line('admin_themes_add'),
			'success btn-sm', 'plus-circle'
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu (install).
	 *
	 * @return 	void
	 */
	public function _submenu_install()
	{
		// Upload theme button.
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('admin_themes_upload'),
			'success btn-sm me-2',
			'upload',
			array_to_attr(array(
				'role' => 'button',
				'data-bs-toggle' => 'collapse',
				'data-bs-target' => '#theme-install'
			))
		),

		// Back button.
		$this->back_button('themes');
	}

}
