<?php
defined('BASEPATH') OR die;

/**
 * User_Controller Class
 *
 * Controllers extending this class require a logged in user.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	1.0
 */
class User_Controller extends KB_Controller
{
	/**
	 * Array of method that don't require a logged-in
	 * user even if the controller extends this class.
	 * @var 	array
	 */
	protected $open_methods = array();

	/**
	 * Some remapping mainly to handle methods that don't really require a
	 * logged-in user.
	 *
	 * @since 	2.72
	 *
	 * @param 	string 	$method
	 * @param 	array 	$params
	 * @return 	mixed
	 */
	public function __construct()
	{
		parent::__construct();

		/**
		 * Nothing to do if:
		 * 	1. The user is already logged in.
		 * 	2. The method doesn't really require being logged.
		 */
		if ($this->auth->online()
			OR in_array($this->router->method, $this->open_methods))
		{
			return;
		}

		// Set alert about required login.
		$this->theme->set_alert($this->lang->line('error_logged_out'), 'error');

		// Prepare redirection.
		$uri = $this->uri->is_dashboard ? 'admin-login' : 'login';
		empty($next = $this->uri_string) OR $uri .= '?next='.rawurlencode($next);
		redirect($uri);
	}

}
