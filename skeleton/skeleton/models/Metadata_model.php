<?php
defined('BASEPATH') OR die;

/**
 * Metadata_model Class
 *
 * Handles all operations done with and to 'metadata' table.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Models
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
class Metadata_model extends KB_Model
{
	/**
	 * The table used for this model.
	 * @var string
	 */
	protected $table = 'metadata';

	/**
	 * Parameters to pass to callbacks.
	 * @var array
	 */
	protected $callback_parameters = array('value');

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
