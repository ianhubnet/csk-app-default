<?php
defined('BASEPATH') OR die;

/**
 * Relations_model Class
 *
 * Handles all operations done with and to 'relations' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Relations_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'relations';

	/**
	 * Parameters to pass to callbacks.
	 * @var array
	 */
	protected $callback_parameters = array('params');

	/**
	 * Array of callbacks to use after get.
	 * @var array
	 */
	protected $after_get = array('prepare_output');

	/**
	 * Array of callbacks to user before create or update.
	 * @var array
	 */
	protected $before_create = array('prepare_input');
	protected $before_update = array('prepare_input');
}
