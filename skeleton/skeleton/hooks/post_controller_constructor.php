<?php
defined('BASEPATH') OR die;

/**
 * Skeleton_post_controller_constructor
 *
 * Register post controller constructor hooks.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Hooks
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2025, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.130
 */
class Skeleton_post_controller_constructor
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $CI;

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();

		// Several methods need it, so we load it.
		isset($this->CI->session) OR $this->CI->load->library('session');
	}

	// --------------------------------------------------------------------

	/**
	 * Redirect to SSL
	 *
	 * This hook will automatically redirect to the HTTPS version
	 * of your  website and set the appropriate headers.
	 *
	 * @return 	void
	 */
	public function redirect_ssl()
	{
		/**
		 * We ignore if:
		 * 	1. On localhost.
		 * 	2. is a CLI command.
		 * 	3. Base URL doesn't contain 'https'
		 */
		// Are we using CLI?
		if (defined('KB_LOCALHOST') OR is_cli() OR substr($this->CI->config->base_url(), 0, 5) !== 'https')
		{
			return;
		}
		// We are not using HTTPS? redirect to https.
		elseif ( ! is_https())
		{
			redirect($this->CI->config->site_url($this->CI->uri->uri_string()), false, 301);
		}

		// We only allow HTTPS cookies (no JS).
		$this->CI->config->set_item('cookie_secure', true);
		$this->CI->config->set_item('cookie_httponly', true);

		$this->CI->output

			/**
			 * Permissions-Policy
			 * Formerly Feature-Policy, allows you to control access to browser features and APIs
			 * (e.g.: geolocation, microphone, camera).
			 */
			->set_header('Permissions-Policy: geolocation=("self"), camera=("self"), microphone=("self")')

			/**
			 * Content-Language
			 * Indicates the language of the response content, useful for localization.
			 */
			->set_header('Content-Language: '.$this->CI->i18n->current('code'))

			/**
			 * Content-Security-Policy (CSP)
			 * Helps protect against XSS attacks by defining which
			 * resources are allowed to load on the page.
			 */
			->set_header("Content-Security-Policy: frame-ancestors 'self'")
			// ->set_header("Content-Security-Policy:
			// 	default-src 'self';
			// 	script-src 'self' cdnjs.cloudflare.com maxcdn.bootstrapcdn.com;
			// 	style-src 'self' fonts.googleapis.com cdnjs.cloudflare.com;
			// 	font-src 'self' fonts.gstatic.com cdnjs.cloudflare.com;
			// 	img-src 'self' *.googleusercontent.com www.googletagmanager.com;
			// 	connect-src www.googletagmanager.com
			// ")

			/**
			 * Cross-Origin-Resource-Policy (CORP)
			 * Prevents unauthorized sites from accessing resources.
			 */
			->set_header('Cross-Origin-Resource-Policy: same-origin')

			/**
			 * Access-Control-Allow-Origin
			 * Controls which domains can access resources on the server.
			 * Set it to * for all or specify domains as needed.
			 */
			->set_header('Access-Control-Allow-Origin: '.rtrim($this->CI->config->base_url(), '/'))

			/**
			 * Access-Control-Allow-Methods
			 * Specifies allowed HTTP methods (e.g., GET, POST).
			 */
			->set_header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS')

			/**
			 * Access-Control-Allow-Headers
			 * used in response to a preflight request to indicate the
			 * HTTP headers that can be used during the actual request.
			 */
			->set_header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With')

			/**
			 * Access-Control-Allow-Credentials
			 * Tells browsers whether the server allows credentials to be
			 * included in cross-origin HTTP requests.
			 */
			->set_header('Access-Control-Allow-Credentials: true')

			/**
			 * Strict-Transport-Security
			 * Forces the browser to use HTTPS, improving security (max-age is set to 1 month.
			 */
			->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains')

			/**
			 * Referrer-Policy
			 * Controls the amount of referrer information sent with requests.
			 * Only allow referrers to be sent withing the website.
			 */
			->set_header('Referrer-Policy: strict-origin')

			/**
			 * Timing-Allow-Origin
			 * Controls which origins can access timing information of a resource.
			 * Useful if you want to analyze performance.
			 */
			->set_header('Timing-Allow-Origin: *')

			/**
			 * Accept-CH
			 * Allows the server to specify which client hints
			 * (e.g., viewport width, device memory) are acceptable,
			 * helping with responsive and adaptive design.
			 */
			->set_header('Accept-CH: Width, Viewport-Width')

			/**
			 * CI Skeleton Info Headers.
			 * @since 2.95
			 */
			->set_header('X-Request-ID: '.KB_REQUEST_ID)
			->set_header('X-Generator: '.KPlatform::LABEL)
			->set_header('X-Generator-Version: '.KB_VERSION)

			/**
			 * X-Content-Type-Options
			 * Prevents the browser from MIME-sniffing content, reducing
			 * risk for certain types of attacks.
			 */
			->set_header('X-Content-Type-Options: nosniff')

			/**
			 * X-Debug-Info
			 * Include debugging information like the environment to
			 * help diagnose issues.
			 */
			->set_header('X-Debug-Info: '.ENVIRONMENT)

			/**
			 * X-Frame-Options
			 * Prevents the page from being embedded in an iframe, protecting against clickjacking.
			 */
			->set_header('X-Frame-Options: SAMEORIGIN')

			/**
			 * X-UA-Compatible
			 * Specifies how pages are rendered in Internet Explorer, useful
			 * for compatibility with older IE versions.
			 */
			->set_header('X-UA-Compatible: IE=edge')

			/**
			 * X-XSS-Protection
			 * a security feature used to control the browser's built-in
			 * Cross-Site Scripting (XSS) protection. While it was useful
			 * in the past, it’s now considered less effective due to modern
			 * security practices, and many browsers are moving away from
			 * supporting it.
			 */
			->set_header('X-XSS-Protection: 1; mode=block');
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares Previous URI
	 *
	 * This method makes sure to store the current URI if it is not of
	 * language switch or the current URI so we can use it to redirect
	 * to previous URI.
	 *
	 * @return 	void
	 */
	public function prep_previous_uri()
	{
		/**
		 * We never execute this method if:
		 * 	1. The request is not a GET request.
		 * 	2. The request is either AJAX or CLI
		 * 	3. We are calling the switch language method.
		 */
		if ( ! $this->CI->input->is_get_request()
			OR is_ajax(true)
			OR $this->CI instanceof API_Controller
			OR $this->CI->router->method === 'switch_language')
		{
			return;
		}

		// We need the Session library.
		isset($this->CI->session) OR $this->CI->load->library('session');

		// We don't execute the rest of the code if we do not have a previous
		// URL or the one we have is the same as the current one.
		if (empty($current_url = $this->CI->session->userdata(SESS_PREV_URI))
			OR $current_url === $this->CI->config->current_url(true))
		{
			return;
		}

		$this->CI->session->set_userdata(SESS_PREV_URI, $current_url);
	}

	// --------------------------------------------------------------------

	/**
	 * Prepare Next URI
	 *
	 * If the 'next' request is provided, this function makes sure to
	 * store it in session so that it can be used for redirections.
	 *
	 * @return 	void
	 */
	public function prep_next_uri()
	{
		// Nothing provided?
		if (empty($next_uri = $this->CI->input->get_post('next')))
		{
			return;
		}
		elseif ((bool) preg_match('#^('.KB_LOGIN.'|'.KB_REGISTER.'|'.KB_LOGOUT.'|'.KB_OFFLINE.')(/\s+)?$#', $next_uri))
		{
			return;
		}
		// ALERT: Open Redirection.
		elseif ( ! is_internal_url($next_uri))
		{
			log_message('critical', sprintf('Open Redirection Detected: %s | IP: %s', rawurldecode($next_uri), ip_address()));
			redirect($this->CI->uri->uri_string());
		}

		$this->CI->session->set_flashdata(SESS_NEXT_URI, rawurldecode($next_uri));
	}

	// --------------------------------------------------------------------

	/**
	 * This hook allows us to display the site's offline page.
	 *
	 * @return 	void
	 */
	public function offline()
	{
		// Already on the offline page.
		if (preg_match('#^'.KB_OFFLINE.'(/?)$#', $uri = uri_path(false)))
		{
			// The site isn't offline?
			if (true !== $this->CI->config->item('site_offline'))
			{
				redirect('', true, 301);
			}
		}
		/**
		 * We do not redirect to offline page when all conditions are met:
		 * 	1. The site if on offline mode.
		 * 	2. The user is not switching language.
		 * 	3. The user is not of level MANAGER.
		 * 	4. The user is not on the login page.
		 */
		elseif ($this->CI->config->item('site_offline')
			&& $this->CI->router->class !== 'seo'
			&& $this->CI->uri->segment(1) !== Route::named('switch-language')
			&& ! $this->CI->auth->is_level($this->CI->config->item('offline_access_level', null, KB_LEVEL_MANAGER))
			&& ! preg_match('#^'.KB_LOGIN.'(/(verify|link)(/[\w\-]+)?)?$#', $uri))
		{
			redirect(KB_OFFLINE, false, 302);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Session Security Hook
	 *
	 * Validate sessions and detect unusual behavior, such as session
	 * hijacking attempts.
	 *
	 * @return 	void
	 */
	public function validate_session()
	{
		// Only for logged in users.
		if ( ! $this->CI->auth->online())
		{
			return;
		}
		// Unusual User Agent behavior?
		elseif (empty($user_agent = $this->CI->session->userdata(SESS_USER_AGENT))
			OR $user_agent !== $this->CI->input->server('HTTP_USER_AGENT'))
		{
			log_message('critical', sprintf('Unusual Session Behavior: User-Agent (%s)', ip_address()));
			$this->CI->auth->logout($this->CI->session->userdata(SESS_USER_ID));
			redirect($this->CI->uri->is_dashboard ? 'admin-login' : 'login');
		}
		// Unusual IP Address behavior?
		elseif (empty($ip_address = $this->CI->session->userdata(SESS_IP_ADDRESS))
			OR $ip_address !== $this->CI->input->server('REMOTE_ADDR'))
		{
			log_message('critical', sprintf('Unusual Session Behavior: IP Address (%s)', ip_address()));
			$this->CI->auth->logout($this->CI->session->userdata(SESS_USER_ID));
			redirect($this->CI->uri->is_dashboard ? 'admin-login' : 'login');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Registers PHP Exception event listener.
	 *
	 * @return 	void
	 */
	public function register_exception_listener()
	{
		$this->CI->events->register('php_exception', array($this, 'send_exception_email'));
	}

	// --------------------------------------------------------------------

	/**
	 * Handles sending PHP Exception emails to server email address.
	 *
	 * @param 	array 	$params
	 * @return 	void
	 */
	public function send_exception_email($params)
	{
		// Make sure we have provided params.
		if (empty($params) OR ! isset($params['message']))
		{
			return;
		}

		// --- Start of [Duplicate Errors] ---
		// Generate error hash:
		$error_string = $params['severity'].'|'.$params['message'].'|'.$params['filepath'].'|'.$params['line'];
		$error_hash = md5($error_string);
		$error_cache_key = 'exception_email_'.$error_hash;

		// Check for duplicate error:
		if ($this->CI->cache->get($error_cache_key))
		{
			// Instead of sending again, log the repetition.
			log_message('error', 'Duplicate exception detected and skipped: '.$params['message']);
			return;
		}
		else
		{
			// Save this error hash for 1 hour.
			$this->CI->cache->save($error_cache_key, true, 3600);
		}
		// --- End of [Duplicate Errors] ---

		// --- Start of [Throttle mechanism] ---
		$throttle_key = 'exception_email_throttle';
		$max_emails_per_hour = 10;

		$current = $this->CI->cache->get($throttle_key);
		if ($current !== false)
		{
			if ($current >= $max_emails_per_hour)
			{
				log_message('error', 'Exception email skipped due to throttle limit.');
				return;
			}
			$this->CI->cache->save($throttle_key, $current + 1, 3600); // 1 hour
		}
		else
		{
			$this->CI->cache->save($throttle_key, 1, 3600);
		}
		// --- End of [Throttle mechanism] ---

		// Make sure to load "email" library if not already loaded.
		isset($this->CI->email) OR $this->CI->load->library('email');

		// Start of [Smart subject]
		$params['datetime'] = date('Y-m-d H:i:s', TIME);
		$short_message = trim(str_replace(["\n", "\r"], ' ', $params['message']));
		$short_message = wordwrap($short_message, 50);
		$short_message = strtok($short_message, "\n"); // Take only first line
		$subject = 'PHP Error: '.(strlen($short_message) > 50 ? substr($short_message, 0, 50).'...' : $short_message);
		// End of [Smart subject]

		// Pretty error content
		$content = <<<EOT
Date: {$params['datetime']}
Severity: {$params['severity']}
Message: {$params['message']}
File Path: {$params['filepath']}
Line: {$params['line']}
User IP: {$this->CI->input->ip_address()}
User Agent: {$this->CI->input->user_agent()}
Request URL: {$this->CI->input->server('REQUEST_URI')}
EOT;

		// Proceed with email info.
		$this->CI->email
			->from($this->CI->config->item('server_email'))
			->to($this->CI->config->item('admin_email'))
			->subject($subject)
			->message($content);

		// Failed to send email?
		if ( ! $this->CI->email->send())
		{
			log_message('error', 'Error sending exception email: '.$this->CI->email->print_debugger(['headers']));
		}
	}

}
