<?php
defined('BASEPATH') OR die;

/**
 * Facebook OAuth
 *
 * Handles Facebook login.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.95
 */
final class Oauth_facebook extends KB_Driver
{
	/**
	 * Provider OAuth app_id.
	 * @var string
	 */
	protected $app_id;

	/**
	 * Provider OAuth app_secret.
	 * @var string
	 */
	protected $app_secret;

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

	/**
	 * Facebook SDK thing.
	 * @var string
	 */
	protected $facebook_sdk = <<<JS
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
JS;

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
		if ( ! ($this->enabled = $enabled && $this->ci->config->item('facebook_auth', null, false)))
		{
			return;
		}
		// Missing client id?
		elseif (empty($this->app_id = $this->ci->config->item('facebook_app_id')))
		{
			$this->enabled = false;
			return;
		}
		// Missing client secret?
		elseif (empty($this->app_secret = $this->ci->config->item('facebook_app_secret')))
		{
			$this->enabled = false;
			return;
		}

		// Set things we need:
		$this->redirect_uri = $this->ci->config->site_url('oauth/facebook');
		$this->access_token_url = 'https://graph.facebook.com/v10.0/oauth/access_token';
		$this->user_info_url = 'https://graph.facebook.com/me?access_token=%s&fields=id,name,email';

		// CSRF protection token.
		$state = (isset($_SESSION['__ci_oauth_facebook']))
			? $_SESSION['__ci_oauth_facebook']
			: bin2hex(random_bytes(8));

		// Add provider to parent:
		$query = array(
			'client_id'    => $this->app_id,
			'redirect_uri' => $this->redirect_uri,
			'scope'        => 'email,public_profile',
			'state'        => ($_SESSION['__ci_oauth_facebook'] = $state)
		);

		// Facebook app prompt.
		$this->ci->agent->is_mobile() && $query['display'] = 'touch';

		$this->_parent->providers['facebook'] = 'https://www.facebook.com/v10.0/dialog/oauth?'.http_build_query($query);

		// Add Facebook SDK Javascript
		// $this->ci->uri->is_dashboard OR $this->ci->assets->inline_js(sprintf($this->facebook_sdk, $this->app_id, 'facebook'));
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
		$url = $this->access_token_url.'?'.http_build_query(array(
			'code'          => $code,
			'client_id'     => $this->app_id,
			'client_secret' => $this->app_secret,
			'redirect_uri'  => $this->redirect_uri
		));

		if ($content = @file_get_contents($url))
		{
			$response = json_decode($content, true);

			return isset($response['access_token']) ? $response['access_token'] : null;
		}

		return null;
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
		return json_decode(file_get_contents(sprintf($this->user_info_url, $access_token)), true);
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
			$status = $this->ci->auth->quick_login($user, $user->language, 'facebook');
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
				'username'   => substr($info['email'], 0, strrpos($info['email'], '@')),
				'enabled'    => $this->config->item('email_activation') ? 0 : 1,
				'email'      => $info['email'],
				'last_name'  => array_pop($full_name),
				'first_name' => implode(' ', $full_name)
			);

			// Let the parent handle the rest.
			return $this->_parent->_process($data, 'facebook');
		}
	}

}
