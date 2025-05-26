<?php
defined('BASEPATH') OR die;

/**
 * Reports_Controller Class
 *
 * Only "Reports.php" controllers should extend this class.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.0
 */
class Reports_Controller extends Admin_Controller
{
	/**
	 * Access level reserved for managers and above.
	 * @var int
	 */
	protected $access_level = KB_LEVEL_MANAGER;

	/**
	 * __construct
	 *
	 * Load needed resources only.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		add_action('admin_head', array($this, '__head'), 0);

		$this->page_help  = 'http://bit.ly/CSKContextReports';
		$this->page_title = $this->lang->line('admin_reports');
		$this->page_icon  = 'history';
	}

	// --------------------------------------------------------------------

	/**
	 * __head
	 *
	 * Add some JS lines.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   string
	 * @return  string
	 */
	public function __head($output)
	{
		$output .= '<script type="text/javascript">';
		$output .= 'csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.reports = csk.i18n.reports || {};';
		$output .= ' csk.i18n.reports.clear = "'.$this->lang->line('admin_reports_clear_confirm').'";';
		$output .= '</script>';

		return $output;
	}

}
