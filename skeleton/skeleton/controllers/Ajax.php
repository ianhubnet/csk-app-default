<?php
defined('BASEPATH') OR die;

/**
 * Main Ajax Controller
 *
 * File Description
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.100
 */
class Ajax extends AJAX_Controller
{
	/**
	 * Keeps sessions alive.
	 * @return 	void
	 */
	public function keep_alive()
	{
		$this->response->header = HttpStatusCodes::HTTP_NO_CONTENT;

		if ($this->config->item('sess_driver') === 'files')
		{
			$this->response->header = HttpStatusCodes::HTTP_OK;

			$time_to_update = $this->config->item('sess_time_to_update');
			$last_regenerate = $this->session->userdata('__ci_last_regenerate');

			if ($time_to_update > 0 && $last_regenerate !== null && ($last_regenerate + $time_to_update) < TIME)
			{
				$this->session->sess_regenerate(false);
			}
		}
	}
}
