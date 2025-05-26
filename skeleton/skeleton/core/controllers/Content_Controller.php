<?php
defined('BASEPATH') OR die;

/**
 * Content_Controller Class
 *
 * Only "Content.php" controllers should extend this class.
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
class Content_Controller extends Admin_Controller
{
	/**
	 * __construct
	 *
	 * Load needed resources only.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->page_help  = 'https://bit.ly/CSKContentHelp';
		$this->page_title = $this->lang->line('admin_content');
		$this->page_icon  = 'paper-plane';
	}}
