<?php
defined('BASEPATH') OR die;

/**
 * Model Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/libraries/config.html
 */
#[AllowDynamicProperties]
class CI_Model
{
	/**
	 * __get magic
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param   string  $key
	 */
	public function __get($key)
	{
		// Debugging note:
		//  If you're here because you're getting an error message
		//  saying 'Undefined Property: system/core/Model.php', it's
		//  most likely a typo in your model code.
		return get_instance()->$key;
	}

}
