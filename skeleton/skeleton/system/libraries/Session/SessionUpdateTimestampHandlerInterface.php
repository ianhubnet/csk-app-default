<?php
defined('BASEPATH') OR die;

/**
 * SessionUpdateTimestampHandlerInterface
 *
 * PHP 7 compatibility interface
 *
 * @package	CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author	Andrey Andreev
 * @link	https://codeigniter.com/userguide3/libraries/sessions.html
 */
interface SessionUpdateTimestampHandlerInterface
{
	public function updateTimestamp($session_id, $data);
	public function validateId($session_id);
}
