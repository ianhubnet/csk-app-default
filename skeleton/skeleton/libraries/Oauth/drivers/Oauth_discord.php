<?php
defined('BASEPATH') OR die;

/**
 * Discord OAuth
 *
 * Handles Discord login.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
final class Oauth_discord extends KB_Driver
{
	/**
	 * Provider OAuth client_id.
	 * @var string
	 */
	protected $client_id;

	/**
	 * Provider OAuth client_secret.
	 * @var string
	 */
	protected $client_secret;

	/**
	 * Provider OAuth redirect_uri.
	 * @var string
	 */
	protected $redirect_uri;

	/**
	 * Provider OAuth access token endpoint.
	 * @var string
	 */
	protected $access_token_url;

	/**
	 * Provider OAuth user info endpoint.
	 * @var string
	 */
	protected $user_info_url;

	/**
	 * Flag to set this provider as enabled.
	 * @var bool
	 */
	public $enabled = false;

	// --------------------------------------------------------------------

	/**
	 * Initialize class preferences.
	 *
	 * @var 	bool 	$enabled
	 * @return 	void
	 */
	public function initialize(bool $enabled)
	{
		// Provider globally or individually disabled?
		if ( ! ($this->enabled = $enabled && $this->ci->config->item('discord_auth', null, false)))
		{
			return;
		}
		// Missing client id?
		elseif (empty($this->client_id = $this->ci->config->item('discord_client_id')))
		{
			$this->enabled = false;
			return;
		}
		// Missing client secret?
		elseif (empty($this->client_secret = $this->ci->config->item('discord_client_secret')))
		{
			$this->enabled = false;
			return;
		}

		// Set things we need:
		$this->redirect_uri = $this->ci->config->site_url('oauth/discord');
		$this->access_token_url = 'https://discord.com/api/oauth2/token';
		$this->user_info_url = 'https://discord.com/api/users/@me';

		// Add provider to parent:
		$this->_parent->providers['discord'] = 'https://discord.com/oauth2/authorize?'.http_build_query(array(
			'client_id'     => $this->client_id,
			'response_type' => 'code',
			'redirect_uri'  => $this->redirect_uri,
			'scope' => 'identify email'
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Exchanges Providers code for access token.
	 *
	 * @param 	string 	$code
	 * @return 	mixed 	object if successful, else null
	 */
	private function get_access_token(string $code)
	{
		$post_fields = array(
			'grant_type'    => 'authorization_code',
			'code'          => $code,
			'redirect_uri'  => $this->redirect_uri,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret
		);

		$cURL = curl_init();

		curl_setopt($cURL, CURLOPT_URL, $this->access_token_url);
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, http_build_query($post_fields));
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($cURL);
		curl_close($cURL);

		$response_data = json_decode($response, true);

		return isset($response_data['access_token']) ? $response_data['access_token'] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Exchanges access token for user info.
	 *
	 * @param 	string 	$access_token
	 * @return 	array 	array of user info
	 */
	private function get_user_info(string $access_token)
	{
		$cURL = curl_init();

		curl_setopt($cURL, CURLOPT_URL, $this->user_info_url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$access_token));
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($cURL);
		curl_close($cURL);

		return json_decode($response, true);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the user if found on database.
	 *
	 * @param 	array 	$info
	 * @return 	mixed 	user object if found, else false.
	 */
	private function exists(array $info)
	{
		// Attempt to retrieve the user by Discord ID.
		$meta = $this->ci->db
			->where('name', 'discord_id')
			->where('value', $info['id'])
			->get('metadata')
			->row();

		// Fond? proceed...
		if ($meta)
		{
			return $this->ci->users->get($meta->guid);
		}

		// Attempt to get user by email address
		return $this->ci->users->get_by('email', $info['email']);
	}
	// --------------------------------------------------------------------

	/**
	 * Process to user authentication.
	 *
	 * @param 	string 	$code
	 * @return 	bool
	 */
	public function _process(?string $code)
	{
		// Disabled?
		if ( ! $this->enabled)
		{
			return false;
		}
		// No authorization code received?
		elseif (empty($code))
		{
			$this->_parent->message = $this->ci->lang->line('oauth_error_code');
			return false;
		}
		// No access code received?
		elseif (empty($access_token = $this->get_access_token($code)))
		{
			$this->_parent->message = $this->ci->lang->line('oauth_error_token');
			return false;
		}
		// No user info received?
		elseif (empty($info = $this->get_user_info($access_token)))
		{
			$this->_parent->message = $this->ci->lang->line('oauth_error_info');
			return false;
		}
		// Existing user? Login...
		elseif ($user = $this->exists($info))
		{
			// Store user id.
			(empty($user->discord_id) OR $user->discord_id !== $info['id']) && $user->update('discord_id', $info['id']);
			$status = $this->ci->auth->quick_login($user, $user->language, 'discord');
			$this->_parent->message = $this->ci->auth->message;
			return $status;
		}
		// Registrations disabled?
		elseif ( ! $this->ci->config->item('allow_registration'))
		{
			$this->_parent->message = $this->ci->lang->line(array('error_register_off', 'try_again_later'));
			return false;
		}
		// Process to account creation/login.
		else
		{
			$full_name = explode(' ', $info['global_name']);

			/**
			 * Let's now collect info we need.
			 * Note: user is enabled if they have a verified provider email.
			 */
			$data = array(
				'username'   => $info['username'],
				'enabled'    => $info['verified'] ? 1 : 0,
				'email'      => $info['email'],
				'last_name'  => array_pop($full_name),
				'first_name' => implode(' ', $full_name)
			);

			// Set user language if defined.
			foreach ($this->ci->i18n->languages() as $folder => $details)
			{
				if ($details['locale'] == $info['locale'])
				{
					$data['language'] = $folder;
					break;
				}
			}

			// Let the parent handle the rest.
			return $this->_parent->_process($data, 'discord');
		}
	}

}
