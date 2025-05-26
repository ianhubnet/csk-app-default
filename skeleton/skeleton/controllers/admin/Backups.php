<?php
defined('BASEPATH') OR die;

/**
 * Backup Controller
 *
 * This controller allows owners to backup their databases.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        https://github.com/bkader
 * @copyright   Copyright (c) 2022, Kader Bouyakoub (https://github.com/bkader)
 * @since       2.16
 * @version     1.0.0
 */
class Backups extends Admin_Controller
{
	/**
	 * Access reserved for owners.
	 * @var 	int
	 */
	protected $access_level = KB_LEVEL_ADMIN;

	/**
	 * When next purge happens.
	 * @var int
	 */
	protected $next_purge;

	/**
	 * Array of methods to redirect URI that are used to redirect
	 * users and print demo alert message.
	 * @var 	array
	 */
	protected $demo_protected = array('_action' => 'admin/backups');

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
			// Page title and icon.
			$this->page_title = $this->lang->line('admin_database_backup');
			$this->page_icon = 'database';

			// Date of next purge.
			$this->next_purge = date_formatter($this->config->item('next_db_purge', null, TIME));
		}

		// Always return parent's '_remap'.
		return parent::_remap($method, $params);
	}

	// --------------------------------------------------------------------

	/**
	 * List all available backups.
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		$this->data['files'] = $this->backups->list();
		$this->render();
	}

	// --------------------------------------------------------------------

	/**
	 * Allowed actions.
	 * @var 	array
	 */
	private $_actions = array(
		'lock'   => KB_LEVEL_ADMIN,
		'unlock' => KB_LEVEL_ADMIN,
		'delete' => KB_LEVEL_OWNER,
		'create' => KB_LEVEL_OWNER,
		'purge'  => KB_LEVEL_OWNER,
	);

	/**
	 * Performs bulk actions on files.
	 * @access 	public 	via $_POST
	 * @param 	string 	$action
	 * @return 	void
	 */
	public function _action($action = null)
	{
		// No action provided?
		if (empty($action))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			redirect('admin/backups');
		}

		// No permission?
		elseif ( ! isset($this->_actions[$action]) OR ! $this->auth->is_level($this->_actions[$action]))
		{
			$this->theme->set_alert($this->lang->line('permission_error_action'), 'error');
			redirect('admin/backups');
		}

		// Security check.
		elseif ($this->nonce->verify_request("backup-{$action}") === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			redirect('admin/backups');
		}

		// Create action?
		elseif ('create' === $action)
		{
			if (false !== $this->backups->create('zip', array('locked' => true)))
			{
				$this->theme->set_alert($this->backups->message, 'success');
				redirect('admin/backups');
			}
		}

		// Prune action?
		elseif ('purge' === $action)
		{
			if (($count = $this->purge->run()) > 0)
			{
				$this->theme->set_alert($this->lang->line('admin_database_prune_success'), 'success');
				redirect('admin/backups');
			}

			$this->theme->set_alert($this->lang->line('admin_database_prune_error'), 'error');
			redirect('admin/backups');
		}

		// Perform the action.
		elseif (false !== $this->backups->$action($this->input->post_get('id', true), true))
		{
			$this->theme->set_alert($this->backups->message, 'success');
			redirect('admin/backups');
		}

		$this->theme->set_alert($this->backups->message, 'error');
		redirect('admin/backups');
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the left side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_index()
	{
		// Create backup
		echo $this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('admin_database_backup_create'),
			'success btn-sm me-4',
			'plus-circle',
			array_to_attr(array(
				'data-form' => esc_url(nonce_admin_url('backups/action/create', 'backup-create')),
				'data-confirm' => $this->lang->line('admin_database_backup_create_confirm'),
			))
		),

		// Lock/Unlock backups
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('lock'),
			'default btn-sm bulk-action disabled',
			'lock text-success',
			array_to_attr(array(
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('backups/action/lock', 'backup-lock')),
				'data-confirm' => $this->lang->line('admin_database_backup_lock_confirm')
			))
		),
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('unlock'),
			'default btn-sm bulk-action disabled ms-1',
			'unlock text-warning',
			array_to_attr(array(
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('backups/action/unlock', 'backup-unlock')),
				'data-confirm' => $this->lang->line('admin_database_backup_unlock_confirm')
			))
		),
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('delete'),
			'danger btn-sm bulk-action disabled ms-1',
			'trash',
			array_to_attr(array(
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('backups/action/delete', 'backup-delete')),
				'data-confirm' => $this->lang->line('admin_database_backup_delete_confirm')
			))
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Things to display on the right side of the sub-menu.
	 *
	 * @return 	void
	 */
	public function _submenu_right_index()
	{
		// Next prune date.
		echo html_tag(
			'span',
			'class="d-none d-md-inline me-2 lh-lg"',
			fa_icon('info-circle text-primary me-1', $this->lang->sline('admin_database_prune_next', $this->next_purge))
		),

		// Prune action.
		$this->theme->template(
			'button_icon_attrs',
			'javascript:void(0);',
			$this->lang->line('admin_database_prune'),
			'danger btn-sm bulk-action ms-1',
			'database',
			array_to_attr(array(
				'disabled' => 'disabled',
				'data-form' => esc_url(nonce_admin_url('backups/action/purge', 'backup-purge')),
				'data-confirm' => $this->lang->line('admin_database_prune_confirm')
			))
		);
	}

}
