<?php
defined('BASEPATH') OR die;

/**
 * OAuth Library
 *
 * Handles third-party authentication services.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
final class Oauth extends KB_Driver_Library
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	public $ci;

	/**
	 * Array of valid drivers.
	 * @var array
	 */
	protected $valid_drivers;

	/**
	 * Array of available oauth providers.
	 * @var array
	 */
	public $providers = array();

	/**
	 * Stores alert messages.
	 * @var string
	 */
	public $message = '';

	/**
	 * Flag to check if enabled.
	 * @var bool
	 */
	public $enabled = false;

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();

		$this->valid_drivers = array(
			'discord',
			'facebook',
			'github',
			'google',
			'linkedin'
		);

		/**
		 * Here are the conditions for this to be enabled:
		 * 	1. The user must be logged out of the site.
		 * 	2. The service must be enabled on the settings.
		 * 	3. cURL must be enabled on the system.
		 */
		$this->enabled = ( ! $this->ci->auth->online());
		$this->enabled && $this->enabled = $this->ci->config->item('allow_oauth', null, false);
		$this->enabled && $this->enabled = function_exists('curl_init');
		$this->enabled && $this->enabled = ! $this->ci->config->item('site_offline');

		// Register events to be triggered when oauth login is changed.
		$this->ci->events->register('option_updated_allow_oauth', array($this, 'on_toggle'));
	}

	// --------------------------------------------------------------------

	/**
	 * Event triggered when 'allow_oauth' option is changed.
	 *
	 * @param 	string 	$status
	 * @return 	void
	 */
	public function on_toggle(string $status)
	{
		// Enabled?
		if ($status = str2bool($status))
		{
			// Trigger special event.
			$this->ci->events->trigger('oauth_enabled');

			// Move some options to their respective tabs.
			$this->ci->db->update_batch('options', array(
				array('name' => 'google_analytics_id', 'tab' => 'google'),
				array('name' => 'google_site_verification', 'tab' => 'google'),
				array('name' => 'facebook_app_id', 'tab' => 'facebook')
			), 'name');
		}
		// Disabled?
		else
		{
			// Trigger special event.
			$this->ci->events->trigger('oauth_disabled');

			// Move some options back to general tab.
			$this->ci->db->update_batch('options', array(
				array('name' => 'google_analytics_id', 'tab' => 'general'),
				array('name' => 'google_site_verification', 'tab' => 'general'),
				array('name' => 'facebook_app_id', 'tab' => 'general')
			), 'name');

			// Unset any possible session keys.
			foreach ($this->valid_drivers as $driver)
			{
				if ($this->ci->session->userdata('__ci_oauth_'.$driver))
				{
					$this->ci->session->unset_userdata('__ci_oauth_'.$driver);
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initializes class.
	 *
	 * @return 	void
	 */
	public function initialize()
	{
		// Only initialize drivers if service is enabled.
		if ($this->enabled)
		{
			foreach ($this->valid_drivers as $driver)
			{
				if (method_exists($this->$driver, 'initialize'))
				{
					$this->$driver->initialize($this->enabled);
				}
			}

			if (empty($this->providers))
			{
				$this->enabled = false;
				$this->ci->config->set_item('allow_oauth', false);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * State checker used as a CSRF protection layer.
	 *
	 * @param 	string 	$provider
	 * @param 	string 	$state
	 * @return 	bool
	 */
	public function check_state(string $provider, ?string $state)
	{
		// 1. Make sure the provider is valid.
		if ( ! isset($this->providers[$provider]))
		{
			$this->message = $this->ci->lang->line('error_unknown');
			return false;
		}
		// 2. The provider didn't really provide a scope? nothing to do...
		elseif (empty($sess_state = $this->ci->session->userdata('__ci_oauth_'.$provider)))
		{
			$this->message = ''; // reset message.
			return true;
		}
		// 3. The provided state is missing or wrong?
		elseif (empty($state) OR ! hash_equals($state, $sess_state))
		{
			$this->message = $this->ci->lang->line('error_csrf');
			return false;
		}
		// Everything passed.
		else
		{
			$this->message = ''; // reset message.
			return true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Process user authentication, called from drivers.
	 *
	 * @param 	array 	$data 		array of user info.
	 * @param 	string 	$provider 	provider used to sign in.
	 * @return 	bool
	 */
	public function _process(array $data, string $provider)
	{
		// Failed to register user?
		if ( ! ($guid = $this->ci->auth->register($data)))
		{
			$this->message = $this->ci->auth->message;
			return false;
		}
		// Attempt to login the user.
		elseif ($data['enabled'] == 1)
		{
			$status = $this->ci->auth->quick_login($guid, null, $provider);
			$this->message = $this->ci->auth->message;
			return $status;
		}
		else
		{
			$this->message = $this->ci->auth->message;
			return true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Magic __call function.
	 *
	 * @param 	string 	$provider
	 * @param 	array 	$params
	 * @return 	bool
	 */

	public function __call($provider, $params = array())
	{
		// Service disabled?
		if ( ! $this->enabled OR ! in_array($provider, $this->valid_drivers))
		{
			$this->message = $this->ci->lang->line(array('error_unknown', 'try_again_later'));
			return false;
		}
		elseif ( ! call_user_func_array(array($this->$provider, '_process'), $params))
		{
			return false;
		}
		else
		{
			$this->ci->session->unset_userdata('__ci_oauth_'.$provider);
			return true;
		}
	}
}
