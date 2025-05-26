<?php
defined('BASEPATH') OR die;

/**
 * Process_Controller Class
 *
 * Controllers extending this class require a GET request only and should never
 * output anything. Methods should perform a server-side action then redirects
 * users to the given URL.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Core Extension
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.33
 * @version 	1.33
 */
class Process_Controller extends KB_Controller
{
	/**
	 * __construct
	 *
	 * Simply call parent's constructor and make sure the request is only
	 * a GET request.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.33
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// We make sure the request is a GET.
		if (true !== $this->input->is_get_request())
		{
			redirect_back('');
		}
	}

}
