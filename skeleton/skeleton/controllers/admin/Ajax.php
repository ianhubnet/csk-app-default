<?php
defined('BASEPATH') OR die;

/**
 * Main AJAX controller.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.1
 */
class Ajax extends AJAX_Controller
{
	/**
	 * Some actions are reserved for certain levels.
	 * @var array
	 */
	protected $access_levels = array();

	/**
	 * Array of available contexts.
	 * @var array
	 */
	private $_targets = array();

	/**
	 * __constructr
	 *
	 * Added safe methods.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   none
	 * @return  AJAX_Controller::response()
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * index
	 *
	 * This method handles all operation done on the reserved sections of the
	 * dashboard.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @param   mixed
	 * @return  AJAX_Controller:response()
	 */
	public function index()
	{
		$args = func_get_args();
		$method = array_shift($args);
		if (isset($method) && method_exists($this, $method))
		{
			return (true !== $this->has_permission($method))
				? $this->response()
				: call_user_func_array(array($this, $method), $args);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Handles file uploads.
	 *
	 * @param 	string 	$folder 	optional folder name
	 * @return 	void
	 */
	protected function upload($folder = null)
	{
		if (false === ($file = $this->files->upload($folder)))
		{
			$this->response->header  = HttpStatusCodes::HTTP_CONFLICT;
			$this->response->message = $this->files->message;
		}
		else
		{
			$file->is_image && $file->data->is_image = true;
			$file->is_video && $file->data->is_video = true;
			$file->is_pptx && $file->data->is_pptx = true;
			$file->is_txt && $file->data->is_txt = true;

			$this->response->header  = HttpStatusCodes::HTTP_OK;
			$this->response->message = $this->files->message;
			$this->response->results = $file->data;
		}
	}

}
