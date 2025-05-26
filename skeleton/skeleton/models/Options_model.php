<?php
defined('BASEPATH') OR die;

/**
 * Options_model Class
 *
 * Handles all operations done with and to 'options' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.97
 */
class Options_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'options';

	/**
	 * Table's primary key.
	 * @var string
	 */
	protected $primary_key = 'name';

	/**
	 * Parameters to pass to callbacks.
	 * @var array
	 */
	protected $callback_parameters = array('value', 'options');

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
