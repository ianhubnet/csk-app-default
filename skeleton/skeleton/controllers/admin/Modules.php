<?php
defined('BASEPATH') OR die;

/**
 * Modules Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.0
 * @version 	2.0
 */
class Modules extends Admin_Controller
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
	protected $demo_protected = array(
		'_delete'  => 'admin/modules',
		'_disable' => 'admin/modules',
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
	   // Page title, icon and help
		$this->page_title = ($method === 'install') ? $this->lang->line('admin_modules_add') : $this->lang->line('admin_modules');
		$this->page_icon  = 'cubes';
		$this->page_help  = 'http://bit.ly/CSKModulesDev';

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * List all available modules.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		$modules = $this->modules->list(true);
		$this->data['modules'] =& $modules;

		foreach ($modules as $folder => &$m)
		{
			if (false === $m)
			{
				unset($modules[$folder]);
				continue;
			}

			// Add module actions.
			$m['actions'] = array();

			if ($m['enabled'])
			{
				$m['actions'][] = $this->theme->template(
					'button_icon_attrs', '#', $this->lang->line('disable'),
					'default', 'times-circle text-danger', array_to_attr(array(
						'role'         => 'button',
						'aria-label'   => $m['name'],
						'data-form'    => esc_url(nonce_admin_url('modules/disable', "modules-disable_$folder")),
						'data-confirm' => 'lang:modules.disable',
						'data-fields'  => "id:$folder"
					))
				);
			}
			else
			{
				$m['actions'][] = $this->theme->template(
					'button_icon_attrs', '#', $this->lang->line('enable'),
					'default', 'check-circle text-success',
					array_to_attr(array(
						'role'         => 'button',
						'aria-label'   => $m['name'],
						'data-form'    => esc_url(nonce_admin_url('modules/enable', "modules-enable_$folder")),
						'data-confirm' => 'lang:modules.enable',
						'data-fields'  => "id:$folder"
					))
				);

				if ( ! $m['protected'])
				{
					$m['actions'][] = $this->theme->template(
						'button_icon_attrs', '#', $this->lang->line('delete'),
						'danger', 'trash',
						array_to_attr(array(
							'role'         => 'button',
							'aria-label'   => $m['name'],
							'data-form'    => esc_url(nonce_admin_url('modules/delete', "modules-delete_$folder")),
							'data-confirm' => 'lang:modules.delete',
							'data-fields'  => "id:$folder"
						))
					);
				}
			}

			// Module details.
			// $details = array();

			if ($m['protected'])
			{
				$m['details'][] = '<span class="badge bg-primary">'.$this->lang->line('protected').'</span>';
			}
			if ( ! empty($m['version']))
			{
				$m['details'][] = $this->lang->sline('version_num', $m['version']);
			}
			if ( ! empty($m['author']))
			{
				$author = (empty($m['author_uri']))
					? $m['author']
					: anchor($m['author_uri'], $m['author'], 'rel="nofollow" target="_blank"');
				$m['details'][] = sprintf('%s: %s', $this->lang->line('author'), $author);
			}
			if ( ! empty($m['license']))
			{
				$license = empty($m['license_uri'])
					? $m['license']
					: anchor($m['license_uri'], $m['license'], 'rel="nofollow" target="_blank"');
				$m['details'][] = $this->lang->sline('license_name', $license);
				// Reset license.
				$license = null;
			}
			if ( ! empty($m['module_uri']))
			{
				$m['details'][] = anchor(
					$m['module_uri'],
					$this->lang->line('website'),
					'rel="nofollow" target="_blank"'
				);
			}
			if ( ! empty($m['author_email']))
			{
				$m['details'][] = sprintf('%s: %s', $this->lang->line('email'), safe_mailto(
					$m['author_email'].'?subject='.rawurlencode('Module Support: '.$m['name']),
					$m['author_email'],
					array('target' => '_blank', 'rel' => 'nofollow')
				));
			}
		}

		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * install
	 *
	 * Method for installing modules from future server or upload ZIP modules.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function install()
	{
		// We prepare form validation.
		$this->prep_form();

		// Form CSRF.
		$this->data['hidden'][COOK_CSRF] = $this->nonce->create('upload-module');

		// Set page title and load view.
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * upload
	 *
	 * Method for uploading modules using ZIP archives.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function upload()
	{
		// We check CSRF token validity.
		if ($this->nonce->verify_request('upload-module') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/modules/install');
		}

		// Did the user provide a valid file?
		elseif (empty($_FILES['modulezip']['name']))
		{
			$this->theme->set_alert($this->lang->line('admin_modules_upload_error'), 'error');
			redirect('admin/modules/install');
		}

		// Load our file helper and make sure the "unzip_file" function exists.
		$this->load->helper('file');
		if ( ! function_exists('unzip_file'))
		{
			$this->theme->set_alert($this->lang->line('admin_modules_upload_error'), 'error');
			redirect('admin/modules/install');
		}

		// Load upload library.
		$this->load->library('upload', array(
			'upload_path'   => FCPATH.'content/uploads/temp/',
			'allowed_types' => 'zip',
		));

		// Error uploading?
		if (false === $this->upload->do_upload('modulezip')
			OR ! class_exists('ZipArchive', false))
		{
			$this->theme->set_alert($this->lang->line('admin_modules_upload_error'), 'error');
			redirect('admin/modules/install');
		}

		// Prepare data for later use.
		$data = $this->upload->data();

		$location = $this->input->post('location', true);
		$location = $this->config->item('modules_locations')[($location === '-1') ? '0' : $location];

		// Catch the upload status and delete the temporary file anyways.
		$status = unzip_file($data['full_path'], $location);
		@unlink($data['full_path']);

		// Successfully installed?
		if ($status)
		{
			$this->activities->log($this->user->id, 'report_module_install:'.$_FILES['modulezip']['name']);
			$this->theme->set_alert($this->lang->line('admin_modules_upload_success'), 'success');
			redirect('admin/modules');
		}

		// Otherwise, the theme could not be installed.
		$this->theme->set_alert($this->lang->line('admin_modules_upload_error'), 'error');
		redirect('admin/modules/install');
	}

	// --------------------------------------------------------------------
	// Modules activation, deactivate and deletion.
	// --------------------------------------------------------------------

	/**
	 * Method for activating the given module.
	 *
	 * @since   2.1
	 *
	 * @param   string  $folder
	 * @return  void
	 */
	protected function _enable()
	{

		// Check the provided folder
		if (empty($folder = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/modules');
		}

		// Security check
		elseif ($this->nonce->verify_request("modules-enable_$folder") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/modules');
		}

		// Attempt to activate.
		elseif ( ! $this->modules->activate($folder))
		{
			$this->theme->set_alert($this->modules->message, 'error');
			redirect('admin/modules');
		}

		$this->activities->log($this->user->id, "report_module_enable:$folder");
		$this->theme->set_alert($this->modules->message, 'success');
		redirect('admin/modules');
	}

	// --------------------------------------------------------------------

	/**
	 * Method for deactivating the given module.
	 *
	 * @since   2.1
	 *
	 * @param   string  $folder
	 * @return  void
	 */
	protected function _disable()
	{
		// Check the provided folder
		if (empty($folder = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/modules');
		}

		// Security check
		elseif ($this->nonce->verify_request("modules-disable_$folder") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/modules');
		}

		// Attempt to activate.
		elseif ( ! $this->modules->deactivate($folder))
		{
			$this->theme->set_alert($this->modules->message, 'error');
			redirect('admin/modules');
		}

		$this->activities->log($this->user->id, "report_module_disable:$folder");
		$this->theme->set_alert($this->modules->message, 'success');
		redirect('admin/modules');
	}

	// --------------------------------------------------------------------

	/**
	 * Method for deleting the given module.
	 *
	 * @since   2.1
	 *
	 * @param   string  $folder
	 * @return  void
	 */
	protected function _delete()
	{
		// Check the provided folder
		if (empty($folder = $this->input->post('id', true)))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/modules');
		}

		// Security check
		elseif ($this->nonce->verify_request("modules-delete_$folder") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/modules');
		}

		// Attempt to activate.
		elseif ( ! $this->modules->delete($folder))
		{
			$this->theme->set_alert($this->lang->line('admin_modules_delete_error'), 'error');
			redirect('admin/modules');
		}

		$this->activities->log($this->user->id, "report_module_delete:$folder");
		$this->theme->set_alert($this->lang->line('admin_modules_delete_success'), 'success');
		redirect('admin/modules');
	}

	// --------------------------------------------------------------------
	// Private methods.
	// --------------------------------------------------------------------

	/**
	 * Add some module language lines to head section.
	 *
	 * @since 	1.33
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		$lines = array(
			'enable'  => $this->lang->line('admin_modules_enable_confirm'),
			'disable' => $this->lang->line('admin_modules_disable_confirm'),
			'delete'  => $this->lang->line('admin_modules_delete_confirm'),
		);

		$output .= '<script type="text/javascript">';
		$output .= 'csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.modules = '.json_encode($lines).';';
		$output .= '</script>';

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the right side of the sub-menu (index).
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		echo $this->theme->template(
			'button_icon',
			admin_url('modules/install'),
			$this->lang->line('admin_modules_add'),
			'success btn-sm', 'plus-circle'
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the right side of the sub-menu (install).
	 * @return 	void
	 */
	public function _submenu_install()
	{
		// Upload module button.
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('admin_modules_upload'),
			'success btn-sm me-2', 'upload',
			array_to_attr(array(
				'role' => 'button',
				'data-bs-toggle' => 'collapse',
				'data-bs-target' => '#module-install'
			))
		),

		// Back button.
		$this->back_button('modules');
	}

}
