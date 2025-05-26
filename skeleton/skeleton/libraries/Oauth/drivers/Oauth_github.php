<?php
defined('BASEPATH') OR die;

/**
 * GitHub OAuth
 *
 * Handles GitHub login.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
final class Oauth_github extends KB_Driver
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
		if ( ! $this->_parent->enabled OR ! ($this->enabled = $enabled && $this->ci->config->item('github_auth', null, false)))
		{
			return;
		}
		// Missing client id?
		elseif (empty($this->client_id = $this->ci->config->item('github_client_id')))
		{
			$this->enabled = false;
			return;
		}
		// Missing client secret?
		elseif (empty($this->client_secret = $this->ci->config->item('github_client_secret')))
		{
			$this->enabled = false;
			return;
		}

		// Set things we need:
		$this->redirect_uri = $this->ci->config->site_url('oauth/github');
		$this->access_token_url = 'https://github.com/login/oauth/access_token';
		$this->user_info_url = 'https://api.github.com/user';

		// CSRF protection token.
		$state = (isset($_SESSION['__ci_oauth_github']))
			? $_SESSION['__ci_oauth_github']
			: bin2hex(random_bytes(8));

		// Add provider to parent:
		$this->_parent->providers['github'] = 'https://github.com/login/oauth/authorize?'.http_build_query(array(
			'client_id'    => $this->client_id,
			'redirect_uri' => $this->redirect_uri,
			'scope'        => 'read:user user:email',
			'state'        => ($_SESSION['__ci_oauth_github'] = $state)
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
			'code'          => $code,
			'client_id'     => $this->client_id,
			'client_secret' => $this->client_secret,
			'redirect_uri'  => $this->redirect_uri
		);

		$cURL = curl_init();

		curl_setopt($cURL, CURLOPT_URL, $this->access_token_url);
		curl_setopt($cURL, CURLOPT_POST, true);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($cURL);
		curl_close($cURL);

		$data = json_decode($response, true);
		return isset($data['access_token']) ? $data['access_token'] : null;	}

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
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

		// cURL HTTP headers
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$access_token,
			'User-Agent: CodeIgniter-GitHub-Auth'
		));

		$response = curl_exec($cURL);
		curl_close($cURL);

		$response_data = json_decode($response, true);

		return array_merge($response_data, $this->get_user_email($access_token));
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieve user's email since this requires a separate request.
	 *
	 * @param 	string 	$access_token
	 * @return 	array
	 */
	private function get_user_email(string $access_token)
	{
		$cURL = curl_init();

		curl_setopt($cURL, CURLOPT_URL, $this->user_info_url.'/emails');
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);

		// cURL HTTP headers
		curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer '.$access_token,
			'User-Agent: CodeIgniter-GitHub-Auth'
		));

		$response = curl_exec($cURL);
		curl_close($cURL);

		$response_data = json_decode($response, true);

		if (is_array($response_data))
		{
			foreach ($response_data as $email)
			{
				if ($email['primary'])
				{
					return array(
						'email'    => $email['email'],
						'verified' => $email['verified']
					);
				}
			}
		}

		return null;
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
		// The email address is required!
		elseif (empty($info['email']))
		{
			$this->_parent->message = $this->ci->lang->sline('required_field_error', $this->ci->lang->line('email_address'));
			return false;
		}
		// Existing user? Login...
		elseif ($user = $this->ci->users->get_by('email', $info['email']))
		{
			$status = $this->ci->auth->quick_login($user, $user->language, 'github');
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
			$full_name = explode(' ', $info['name']);

			/**
			 * Let's now collect info we need.
			 * Note: user is enabled if they have a verified provider email.
			 */
			$data = array(
				'username'   => sanitize_username($info['login'], true),
				'enabled'    => $info['verified'] ? 1 : 0,
				'email'      => $info['email'],
				'last_name'  => array_pop($full_name),
				'first_name' => implode(' ', $full_name)
			);

			// User avatar.
			empty($info['picture']) OR $data['avatar_url'] = $info['picture'];

			// Let the parent handle the rest.
			return $this->_parent->_process($data, 'github');
		}
	}

}
