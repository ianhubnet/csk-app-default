<?php
defined('BASEPATH') OR die;

/**
 * Entities_model Class
 *
 * Handles all operations done with and to 'entities' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Entities_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'entities';

	/**
	 * Table's soft delete.
	 * @var bool
	 */
	protected $soft_delete = true;

	/**
	 * Array of callbacks to use after get.
	 * @var array
	 */
	protected $after_get = array('prepare_numeric');

	/**
	 * Parameters to pass to callbacks.
	 * @var array
	 */
	protected $callback_parameters = array('value', 'params');

	/**
	 * Datetime format.
	 * @var string
	 */
	protected $datetime_format = 'timestamp';
}
