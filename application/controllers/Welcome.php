<?php

/**
 * Welcome Controller
 *
 * Nothing fancy about this controller, it came with CodeIgniter
 * and we simply rendered a dummy view file. Add your own default
 * controller/module when you start developing.
 *
 * @package    Application\Controllers
 * @author     Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright  Copyright (c) 2025, Kader Bouyakoub
 * @since      1.0
 */

final class Welcome extends Public_Controller
{
	/**
	 * Layout file to use for method that have output render.
	 *
	 * @var string|null
	 */
	protected $layout = 'double';

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->render();
	}

}
