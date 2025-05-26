<?php
defined('BASEPATH') OR die;

/**
 * CI_Session_driver_interface
 *
 * A compatibility typeless SessionHandlerInterface alias
 *
 * @package CodeIgniter
 * @subpackage  Libraries
 * @category    Sessions
 * @author  Andrey Andreev
 * @link    https://codeigniter.com/userguide3/libraries/sessions.html
 */
interface CI_Session_driver_interface
{
	public function open($save_path, $name);
	public function close();
	public function read($session_id);
	public function write($session_id, $session_data);
	public function destroy($session_id);
	public function gc($maxlifetime);
	public function updateTimestamp($session_id, $data);
	public function validateId($session_id);
}
