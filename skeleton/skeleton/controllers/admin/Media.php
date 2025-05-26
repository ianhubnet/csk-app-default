<?php
defined('BASEPATH') OR die;

/**
 * Media Controller
 *
 * Allows users to upload, view and edit files.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.16
 */
class Media extends Admin_Controller
{
	/**
	 * Access reversed for managers+
	 * @var int
	 */
	protected $access_level = KB_LEVEL_ACP;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array('_delete' => 'admin/media');

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();
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
			// Load dependencies.
			$this->assets
				->dropzone()
				->handlebars()
				->zoom();

			// Page title & icon.
			$this->page_title = $this->lang->line('admin_media');
			$this->page_icon = 'image';
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * List all media files.
	 *
	 * @return 	void
	 */
	public function index()
	{
		$this->load->helper('number');

		$files = $this->auth->is_level(KB_LEVEL_MANAGER)
			? $this->files->get_many()
			: $this->files->get_many('owner_id', $this->user->id);

		if ( ! empty($files) && 0 < ($count = count($files)))
		{
			[$limit, $offset] = $this->paginate($this->config->admin_url('media'), $count);

			($count >= $limit) && $files = array_splice($files, $offset, $limit);
		}

		$this->data['files'] = $files;

		if ( ! empty($id = $this->input->get('item', true)) && ($item = $this->files->get($id)))
		{
			$this->data['item'] = $item;

			$this->prep_form(array(
				array(	'field' => 'name',
						'label' => $this->lang->line('name'),
						'rules' => 'trim|required|min_length[3]|max_length[100]')
			), '#media-update');
		}

		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes selected media files.
	 *
	 * @return 	void
	 */
	public function _delete()
	{
		if ($this->nonce->verify_request('media-delete') === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/media');
		}

		$id = explode(',', $this->input->post('id', true));

		if ($this->files->delete_by('id', $id))
		{
			$this->theme->set_alert($this->files->message, 'success');
		}
		else
		{
			$this->theme->set_alert($this->files->message, 'error');
		}

		redirect('admin/media');
	}

	// --------------------------------------------------------------------

	/**
	 * Update media file.
	 *
	 * @return 	void
	 */
	public function _update($id = 0)
	{
		if ($this->files->update($id, $this->input->post(null, true)))
		{
			$this->theme->set_alert($this->files->message, 'success');
		}
		else
		{
			$this->theme->set_alert($this->files->message, 'error');
		}
		redirect('admin/media');
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a small portion of script.
	 *
	 * @return 	void
	 */
	public function _admin_head($output)
	{
		$lines = array('copied' => $this->lang->line('admin_media_url_copied'));

		$output .= "\n\t<script type=\"text/javascript\">";
		$output .= 'var csk = window.csk = window.csk || {};';
		$output .= ' csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.media = '.json_encode($lines).';';
		$output .= "</script>\n";

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
		// Upload media button.
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('upload'),
			'success btn-sm me-2',
			'upload',
			array_to_attr(array(
				'role' => 'button',
				'id' => 'upload'
			))
		),

		// Delete media files.
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('delete'),
			'danger btn-sm bulk-action disabled', 'trash',
			array_to_attr(array(
				'role' => 'button',
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('media/delete', 'media-delete')),
				'data-confirm' => $this->lang->line('admin_media_delete_confirm'),
			))
		);
	}

}
