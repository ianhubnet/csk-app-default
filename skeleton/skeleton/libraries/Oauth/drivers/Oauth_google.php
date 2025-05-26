<?php
defined('BASEPATH') OR die;

/**
 * Google OAuth
 *
 * Handles Google login.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
final class Oauth_google extends KB_Driver
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
		if ( ! ($this->enabled = $enabled && $this->ci->config->item('google_auth', null, false)))
		{
			return;
		}
		// Missing client id?
		elseif (empty($this->client_id = $this->ci->config->item('google_client_id')))
		{
			$this->enabled = false;
			return;
		}
		// Missing client secret?
		elseif (empty($this->client_secret = $this->ci->config->item('google_client_secret')))
		{
			$this->enabled = false;
			return;
		}

		// Set things we need:
		$this->redirect_uri = $this->ci->config->site_url('oauth/google');
		$this->access_token_url = 'https://oauth2.googleapis.com/token';
		$this->user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo';

		// CSRF protection token.
		$state = (isset($_SESSION['__ci_oauth_google']))
			? $_SESSION['__ci_oauth_google']
			: bin2hex(random_bytes(8));

		// Add provider to parent:
		$query = array(
			'response_type' => 'code',
			'client_id'     => $this->client_id,
			'redirect_uri'  => $this->redirect_uri,
			'scope'         => 'email profile',
			'state'         => ($_SESSION['__ci_oauth_google'] = $state)
		);

		// Google app prompt
		$this->ci->agent->is_mobile() && $query['prompt'] = 'select_account';

		$this->_parent->providers['google'] = 'https://accounts.google.com/o/oauth2/auth?'.http_build_query($query);
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
			'code'          => $code,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'redirect_uri'  => $this->redirect_uri,
			'grant_type'    => 'authorization_code'
		);

		$cURL = curl_init($this->access_token_url);

		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $post_fields);

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
		$url = $this->user_info_url.'?access_token='.$access_token;

		$cURL = curl_init($url);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($cURL);
		curl_close($cURL);

		return json_decode($response, true);
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
		elseif ($user = $this->ci->users->get_by('email', $info['email']))
		{
			$status = $this->ci->auth->quick_login($user, $user->language, 'google');
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
			/**
			 * Let's now collect info we need.
			 * Note: user is enabled if they have a verified provider email.
			 */
			$data = array(
				'username'   => sanitize_username(str_replace('gmail.com', '', $info['email']), true),
				'enabled'    => $info['verified_email'] ? 1 : 0,
				'email'      => $info['email'],
				'first_name' => $info['given_name'],
				'last_name'  => $info['family_name']
			);

			// User avatar.
			empty($info['picture']) OR $data['avatar_url'] = $info['picture'];

			// Let the parent handle the rest.
			return $this->_parent->_process($data, 'google');
		}
	}

}
