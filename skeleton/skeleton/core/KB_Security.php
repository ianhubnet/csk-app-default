<?php
defined('BASEPATH') OR die;

/**
 * KB_Security
 *
 * @package 	CodeIgniter
 * @subpackage 	Subpackage
 * @category 	Category
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.124
 */
class KB_Security extends CI_Security
{
	/**
	 * Show CSRF Error
	 *
	 * @return  void
	 */
	public function csrf_show_error()
	{
		show_error('The action you have requested is not allowed. You either do not have access, or your login session has expired and you need to sign in again.', 403);
	}

	// --------------------------------------------------------------------

	/**
	 * CSRF Verify
	 *
	 * @return  CI_Security
	 */
	public function csrf_verify()
	{
		// If it's not a POST request we will set the CSRF cookie
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
		{
			return $this->csrf_set_cookie();
		}
		// User already provided a 'COOK_CSRF'?
		elseif (isset($_POST[COOK_CSRF]))
		{
			return $this;
		}
		// Check excluded controllers?
		elseif ( ! empty($exclude_controllers = $this->config->item('csrf_exclude_controllers')))
		{
			$path = $this->router->class;
			empty($module = $this->router->module) OR $path = $module.'/'.$path;

			if (in_array($path, $exclude_controllers))
			{
				return $this;
			}
		}

		// Let the parent do the rest.
		return parent::csrf_verify();
	}

}
