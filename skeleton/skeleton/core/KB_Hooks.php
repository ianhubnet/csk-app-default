<?php
defined('BASEPATH') OR die;

/**
 * KB_Hooks Class
 *
 * This file extends CI_Hooks class in order to make hooks available
 * to be loaded from the Skeleton folder.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.0
 */
class KB_Hooks extends CI_Hooks
{
	/**
	 * Instance of CI_Events.
	 *
	 * @var object
	 */
	protected $events;

	/**
	 * Class constructor
	 *
	 * @return  void
	 */
	public function __construct(CI_Config &$config, CI_Events &$events)
	{
		$this->events = $events;

		array_unshift($this->_hook_paths, KBPATH);

		parent::__construct($config, $events);
	}

	// --------------------------------------------------------------------

	/**
	 * register
	 *
	 * This method is used to register CodeIgniter hooks.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   string  $hook   The hook name.
	 * @param   array   $data   The hooks details array.
	 * @return  void
	 */
	public function register($hook, $data = array())
	{
		isset($hook, $this->hooks) OR $this->hooks[$hook] = array();
		$this->hooks[$hook][] = $data;
	}

	// --------------------------------------------------------------------

	/**
	 * call_hook
	 *
	 * Calls a particular hook. Added for Skeleton in order to execute action
	 * using the Plugins class.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.5.2
	 *
	 * @access  public
	 * @param   string  $which  The hook's name.
	 * @return  bool    true on success, else false.
	 */
	public function call_hook($which = '')
	{
		// Trigger events first.
		$this->events->trigger($which);

		// We do any action first.
		do_action($which);

		// Then we let the parent do the rest.
		return parent::call_hook($which);
	}

}
