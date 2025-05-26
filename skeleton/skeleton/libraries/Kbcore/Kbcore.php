<?php
defined('BASEPATH') OR die;

/**
 * Kbcore Library
 *
 * This is the Skeleton main library that handles almost everything on the application.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.16
 */
final class Kbcore extends KB_Driver_Library
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	public $ci;

	/**
	 * Whether the site is on production mode.
	 * @var bool
	 */
	public $is_live = false;

	/**
	 * Whether the site in on demo mode.
	 * @var bool
	 */
	public $is_demo = false;

	/**
	 * Flag used for loading dependencies.
	 * @var bool
	 */
	protected $_dependencies_loaded = false;

	/**
	 * Flags to tell whether the user:
	 * 	1. is on a browser.
	 * 	2. is on a mobile device.
	 * 	3. came from another website.
	 * 	4. is a robot.
	 * @var bool
	 */
	public $is_browser  = false;
	public $is_mobile   = false;
	public $is_referral = false;
	public $is_robot    = false;

	/**
	 * Array of valid drivers.
	 * @var array
	 */
	protected $valid_drivers;

	/**
	 * Array of alerts to be used for notifications.
	 * They are different from theme alerts, so if you want
	 * to display a simple alert (i.e: bootstrap alerts), you
	 * would want to use `$this->theme->set_alert(...)`
	 * but for these, you on have `add_alert`, which requires a
	 * text to display as well as a URL to redirect the user to.
	 *
	 * @since 	2.55
	 * @var 	array
	 */
	public $alerts = array();

	/**
	 * Number of queued alerts.
	 * @since 	2.55
	 * @var 	int
	 */
	public $num_alerts = 0;

	/**
	 * Array of objects or groups to be added to the automatically
	 * generated 'sitemap.xml'.
	 * @since 	2.103
	 * @var 	array
	 */
	public $sitemaped = array();

	/**
	 * Class constructor
	 *
	 * @since   1.0
	 * @since   1.33   Updated methods order to avoid loading different language (activities).
	 * @since   2.16   The "_set_language" was moved to "KB_Lang" class.
	 *
	 * @access  public
	 * @return  void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();

		$this->is_live = ('development' !== ENVIRONMENT);
		$this->is_demo = $this->ci->config->item('demo_mode', null, false);

		/**
		 * Fires early, before drivers are loaded.
		 * @since   2.13
		 */
		do_action('init');

		// Fill valid drivers.
		$this->valid_drivers = KPlatform::drivers();

		// Load Skeleton required resources.
		$this->_load_dependencies();

		// Initialize options driver.
		$this->options->initialize();
		$this->ci->options =& $this->options;

		// Initialize authentication library.
		$this->auth->initialize();
		$this->ci->auth =& $this->auth;

		// Initialize lang driver.
		$this->lang->initialize();
		$this->ci->i18n =& $this->lang;

		// Cookies consent section.
		$consent_cookie_name = $this->ci->config->item('cookie_prefix').COOK_CONSENT;
		(empty($accept_cookies = $this->ci->input->cookie($consent_cookie_name))) && $this->_check_cookie_policy();
		$this->ci->config->set_item('accept_cookies', $accept_cookies === 'true');

		// Initialize library drivers.
		foreach ($this->valid_drivers as $driver)
		{
			if ('options' === $driver OR 'lang' === $driver OR 'auth' === $driver)
			{
				continue;
			}

			// Assign driver to CI object.
			$this->ci->$driver =& $this->$driver;

			// Does the driver have "initialize" method?
			method_exists($this->$driver, 'initialize') && $this->$driver->initialize();

			// Should driver load language files?
			property_exists($this->$driver, 'language_files') && $this->ci->lang->load($this->$driver->language_files);
		}

		// Initialize oauth.
		(isset($this->ci->oauth)) OR $this->ci->load->driver('oauth');

		// Things to do on backend.
		if ($this->ci->uri->is_dashboard && $this->auth->is_level(KB_LEVEL_ACP))
		{
			// Call any possible driver's for_dashboard method.
			$this->for_dashboard($this->ci->router->is_section('admin', 'index', false));
		}

		// authenticate user and initialize oauth services.
		$this->auth->authenticate();
		$this->ci->oauth->initialize();

		log_message('info', 'Kbcore Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of objects to add to 'sitemap.xml'.
	 *
	 * @return 	array
	 */
	public function sitemaped()
	{
		if (empty($this->sitemaped))
		{
			return false;
		}

		return $this->ci->db
			->select('id, subtype, username, updated_at')
			->where_in('type', array('group', 'object'))
			->where_in('subtype', array_unique($this->sitemaped))
			->order_by('updated_at', 'DESC')
			->get('entities')
			->result();
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a new alert to display.
	 *
	 * @since 	2.55
	 *
	 * @access public
	 * @param 	array 	$alert 		Alert array.
	 * @param 	int 	$req_level 	User level required to see alerts.
	 * @return 	Kbcore
	 */
	public function add_alert(array $alert, $req_level = KB_LEVEL_ACP)
	{
		if (is_numeric($req_level) && $this->auth->is_level($req_level))
		{
			array_unshift($this->alerts, $alert);
			$this->num_alerts++;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Better way of sending email messages.
	 *
	 * @since   2.0
	 *
	 * @access  public
	 * @param   mixed   $users      A single user ID/object or array.
	 * @param   string  $subect     The email subject.
	 * @param   string  $message    The message to be sent.
	 * @param   array   $data       Array of data to pass to views.
	 * @return  Kbcore::mail_send()
	 */
	public function mail_user($users, $subject, $message, $data = array())
	{
		// make sure a message and user are provided.
		if (empty($message) OR empty($users))
		{
			return false;
		}

		// convert $users to array
		is_array($users) OR $users = array($users);
		if (empty($users))
		{
			return false;
		}

		// site info
		$site_name   = $this->ci->config->item('site_name');
		$site_anchor = anchor('', $site_name, 'target="_blank"');
		$site_url    = $this->ci->config->site_url();

		/**
		 * There are three options to load messages
		 * 1. Just pass the message.
		 * 2. Use "view:xx" to load a specific view file.
		 * 3. Use "lang:xx" to use a language file.
		 */
		if (1 === sscanf($message, 'view:%s', $view))
		{
			$message = $this->ci->load->view($view, array('lang' => $this->ci->lang->idiom), true);
		}
		else
		{
			$message = _translate($message);
		}

		// Prepare default output replacements.
		$search  = array();
		$replace = array();

		// We add IP Address if requested.
		if (isset($data['ip_address']))
		{
			$data['ip_link'] = html_tag('a', array(
				'href'   => 'https://www.iplocation.net/search?ie=UTF-8&q='.$data['ip_address'],
				'target' => '_blank',
				'rel'    => 'nofollow',
			), $data['ip_address']);
		}

		// If we have any other elements, use theme.
		if ( ! empty($data))
		{
			foreach ($data as $key => $val)
			{
				$search[]  = "{{$key}}";
				$replace[] = $val;
			}
		}

		// from?
		$from = array($this->ci->config->item('server_email'), $this->ci->config->item('site_name'));

		// Subject, message and alt_message
		$raw_subject = $this->lang->parse(str_replace($search, $replace, $subject));
		$raw_message = $this->lang->parse(str_replace($search, $replace, $message));

		$message = $this->ci->load->view('emails/_header', null, true);
		$message .= $this->lang->parse($raw_message, 'nl2br');
		$message .= $this->ci->load->view('emails/_footer', null, true);

		$user_search = array('{name}', '{email}', '{subject}');

		foreach ($users as &$user)
		{
			$user = ($user instanceof KB_User) ? $user : $this->users->get($user);
			if ( ! $user)
			{
				continue;
			}

			$user_replace     = array($user->first_name, $user->email, $raw_subject);
			$user_subject     = str_replace($user_search, $user_replace, $raw_subject);
			$user_message     = $this->lang->parse(str_replace($user_search, $user_replace, $message));
			$user_alt_message = strip_all_tags($user_message);

			$user_result = $this->mail_send(
				$from,
				$user->email,
				$user_subject,
				$user_message,
				$user_alt_message,
				null, // reply_to
				null, // reply_name
				null, // cc
				null, // bcc
				'html'
			);

			if (true !== $user_result)
			{
				return $user_result;
			}

			// free memory (doubtful)..
			unset($user_replace, $user_subject, $user_message, $user_alt_message, $user_result);
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Quick method to send emails.
	 * @param   string  $to             the whom send the email.
	 * @param   string  $subject        the email's subject?
	 * @param   string  $message        the email's body.
	 * @param   string  $alt_message    Alternative message;
	 * @param   string  $reply_email
	 * @param   string  $reply_name
	 * @param   string  $cc             carbon copy.
	 * @param   string  $bcc            blind carbon copy.
	 * @author  Kader Bouyakoub
	 * @version 1.0
	 * @return  bool    true if the email is sent.
	 */
	public function mail_send(
		$from,
		$to,
		$subject = '',
		$message = '',
		$alt_message = null,
		$reply_email = null,
		$reply_name = null,
		$cc = null,
		$bcc = null,
		$mailtype = 'text'
	)
	{
		isset($this->ci->email) OR $this->ci->load->library('email');
		$this->ci->email->initialize(array('mailtype' => $mailtype));

		// The from.
		if (is_array($from))
		{
			call_user_func_array(array($this->ci->email, 'from'), $from);
		}
		else
		{
			$this->ci->email->from($from);
		}

		// To whow send this email.
		$this->ci->email->to($to);

		// Prepare the email subject.
		$this->ci->email->subject($subject);

		// Set the email message and alternative message.
		$this->ci->email->message($message);

		// Alternative message?
		empty($alt_message) OR $this->ci->email->set_alt_message(nl2br($alt_message));

		// A reply-to email address.
		empty($reply_email) OR $this->ci->email->reply_to($reply_email, $reply_name);

		// A carbon copy is set?
		empty($cc) OR $this->ci->email->cc($cc);

		// A blind carbon copy is set?
		empty($bcc) OR $this->ci->email->bcc($bcc);

		try {
			$mail_sent = $this->ci->email->send();
		} catch (Exception $e) {
			log_message('critical', 'Emails: '.$e->getMessage());
			$mail_sent = false;
		}

		return $mail_sent;
	}

	// --------------------------------------------------------------------

	/**
	 * Database WHERE clause generator.
	 *
	 * @since   1.30
	 * @since   1.33   Added the possibility to use "or:" for single values.
	 * @since   1.33   Removed lines causing pagination not to work properly.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @return  object  it returns the DB object so that the method can be chainable.
	 */
	public function where($field = null, $match = null, $limit = 0, $offset = 0)
	{
		if ($field !== null)
		{
			// Make sure $field is an array.
			(is_array($field)) OR $field = array($field => $match);

			// Let's generate the WHERE clause.
			foreach ($field as $key => $val)
			{
				// We make sure to ignore empty key.
				if (empty($key) OR is_int($key))
				{
					continue;
				}

				// The default method to call.
				$method = 'where';

				// In case $val is an array.
				if (is_array($val))
				{
					// The default method to call is "where_in".
					$method = 'where_in';

					// Should we use the "or_where_not_in"?
					if (strpos($key, 'or:!') === 0)
					{
						$method = 'or_where_not_in';
						$key    = str_replace('or:!', '', $key);
					}
					// Should we use the "or_where_in"?
					elseif (strpos($key, 'or:') === 0)
					{
						$method = 'or_where_in';
						$key    = str_replace('or:', '', $key);
					}
					// Should we use the "where_not_in"?
					elseif (strpos($key, '!') === 0)
					{
						$method = 'where_not_in';
						$key    = str_replace('!', '', $key);
					}
				}
				elseif (strpos($key, 'or:') === 0)
				{
					$method = 'or_where';
					$key    = str_replace('or:', '', $key);
				}

				$this->ci->db->{$method}($key, $val);
			}
		}

		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		return $this->ci->db;
	}

	// --------------------------------------------------------------------

	/**
	 * Database LIKE clause generator.
	 *
	 * @since   1.30
	 * @since   1.32   The metadata column "key" was renamed back to "name".
	 * @since   1.33   Removed lines causing pagination not to work properly.
	 *
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @param   int     $limit
	 * @param   int     $offset
	 * @param   string  $type   The type of search: users, groups, objects OR null.
	 * @return  object  it returns the DB object so that the method can be chainable.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0, $type = null)
	{
		// We make sure $field is an array.
		(is_array($field)) OR $field = array($field => $match);

		/**
		 * The search is triggered depending of what we are looking for.
		 * This is useful because sometimes we may want to retrieve entities
		 * by their metadata. Otherwise, we generate a default LIKE clause.
		 */
		switch ($type)
		{
			// In case of looking for an entity.
			case 'users':
			case 'groups':
			case 'objects':

				function_exists('singular') OR $this->ci->load->helper('inflector');

				// We make sure to join the required table.
				$this->ci->db
					// We select only main tables fields to avoid joining metadata.
					->select("entities.*, $type.*")
					->distinct()
					->where('entities.type', singular($type))
					->join($type, "$type.guid = entities.id");

				// The following anchoris  used to avoid multiple join.
				$metadata_joint = true;

				// Generate the query.
				$count = 1;
				foreach ($field as $key => $val)
				{
					/**
					 * If we are searching by a field that exists in one of the main
					 * tables: entities, users groups, or objects.
					 */
					if (in_array($key, $this->$type->fields)
						OR in_array($key, $this->entities->fields))
					{
						// Make sure not to search in metadata.
						$metadata_joint = false;

						if ( ! is_array($val))
						{
							$method = ($count == 1) ? 'like' : 'or_like';
							if (strpos($key, '!') === 0)
							{
								$method = ($count == 1) ? 'not_like' : 'or_not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->{$method}($key, $val);
						}
						else
						{
							foreach ($val as $_val)
							{
								$method = 'like';
								if (strpos($key, '!') === 0)
								{
									$method = 'not_like';
									$key = str_replace('!', '', $key);
								}

								$this->ci->db->{$method}($key, $val);
							}
						}

						$count++;
					}
					// Otherwise, we search by metadata.
					else
					{
						// Join metadata table?
						if ($metadata_joint === true)
						{
							$this->ci->db->join('metadata', 'metadata.guid = entities.id');

							// Stop multiple joins.
							$metadata_joint = false;
						}

						if ( ! is_array($val))
						{
							$method = ($count == 1) ? 'like' : 'or_like';
							if (strpos($key, '!') === 0)
							{
								$method = ($count == 1) ? 'not_like' : 'or_not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->where('metadata.name', $key);
							$this->ci->db->{$method}('metadata.value', $val);
						}
						else
						{
							foreach ($val as $_val)
							{
								$method = 'like';
								if (strpos($key, '!') === 0)
								{
									$method = 'not_like';
									$key = str_replace('!', '', $key);
								}

								$this->ci->db->where('metadata.name', $key);
								$this->ci->db->{$method}('metadata.value', $val);
							}
						}

						$count++;
					}
				}

				break;  // End of case 'users', 'groups', 'objects'.

			// Generating default LIKE clause.
			default:

				// Let's now generate the query.
				$count = 1;
				foreach ($field as $key => $val)
				{
					if ( ! is_array($val))
					{
						$method = ($count == 1) ? 'like' : 'or_like';
						if (strpos($key, '!') === 0)
						{
							$method = ($count == 1) ? 'not_like' : 'or_not_like';
							$key = str_replace('!', '', $key);
						}

						$this->ci->db->{$method}($key, $val);
					}
					else
					{
						foreach ($val as $_val)
						{
							$method = 'like';
							if (strpos($key, '!') === 0)
							{
								$method = 'not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->{$method}($key, $val);
						}
					}

					$count++;
				}

				break;  // End of "default".
		}

		// Did we provide a limit?
		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		// Return this so the method can be chainable.
		return $this->ci->db;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates the SELECT portion of the query
	 *
	 * @since   2.16
	 *
	 * @param   string
	 * @param   mixed
	 */
	public function select($select = '*', $escape = null)
	{
		$this->ci->db->select($select, $escape);
		return $this->ci->db;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates the ORDER BY potion of the query
	 *
	 * @since 	2.16
	 *
	 * @param   string  $orderby
	 * @param   string  $direction  ASC, DESC or RANDOM
	 * @param   bool    $escape
	 */
	public function order_by($field, $direction = '', $escape = null)
	{
		$this->ci->db->order_by($field, $direction, $escape);
		return $this->ci->db;
	}

	// --------------------------------------------------------------------

	/**
	 * Used to load required libraries and helpers.
	 * @access  protected
	 * @param   none
	 * @return  void
	 */
	protected function _load_dependencies()
	{
		if (true !== $this->_dependencies_loaded)
		{
			// Load database.
			(isset($this->ci->db)) OR $this->ci->load->database();

			// Load Session library
			(isset($this->ci->session)) OR $this->ci->load->library('session');

			// Load URL helper.
			(function_exists('site_url')) OR $this->ci->load->helper('url');

			// Load HTML helper.
			(function_exists('html_tag')) OR $this->ci->load->helper('html');

			// We detect user's browser details.
			$this->_detect_user_agent();

			$this->_dependencies_loaded = true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set details about the user's browser.
	 * @param   none
	 * @return  array
	 */
	protected function _detect_user_agent()
	{
		// Load User_agent library.
		(isset($this->ci->agent)) OR $this->ci->load->library('user_agent');

		// Set some info we need
		$this->is_browser  = $this->ci->agent->is_browser();
		$this->is_mobile   = $this->ci->agent->is_mobile();
		$this->is_referral = $this->ci->agent->is_referral();
		$this->is_robot    = $this->ci->agent->is_robot();
	}

	// --------------------------------------------------------------------

	/**
	 * Adds cookies policy view to theme.
	 * @access 	protected
	 * @param 	none
	 * @return 	void
	 */
	protected function _check_cookie_policy()
	{
		// Ignore this in dashboard or empty cookies consent message.
		if (($this->ci->config->item('site_offline') && ! $this->auth->online())
			OR $this->ci->uri->is_dashboard
			OR empty($cookies_consent = apply_filters('cookies_consent', $this->ci->lang->line('cookie_consent'))))
		{
			return;
		}
		// Add inline cookies consent script and widget view.
		else
		{
			// cookie consent js code.
			$inline_script = <<<JS
function setCookie(name, value, expires, secure) {
	let t = new Date();
	t.setTime(t.getTime() + 864e5 * expires);

	let s = name + "=" + value + ";expires=" + t.toGMTString() + ";domain=%5\$s;path=%2\$s;SameSite=%3\$s;";
	secure && (s += "Secure;");

	document.cookie = s;
}

document.querySelector(".btn-accept-cookies").addEventListener("click", function(){
	setCookie("%1\$s", "true", 365, %4\$s);
	$(".cookie-consent").hide().remove();
});

document.querySelector(".btn-reject-cookies").addEventListener("click", function(){
	setCookie("%1\$s", "false", 365, %4\$s);
	$(".cookie-consent").hide().remove();
});
JS;

			$this->assets->inline_js(sprintf(
				$inline_script,
				$this->ci->config->item('cookie_prefix').COOK_CONSENT, // 1: Cookie name
				$this->ci->config->item('cookie_path'), // 2: Cookie path
				$this->ci->config->item('cookie_samesite'), // 3: Cookie SameSite
				$this->ci->config->item('cookie_secure') ? 'true' : 'false', // 4: Cookie Secure
				$this->ci->config->item('cookie_domain') // 5: Domain
			));

			$this->theme->add_partial('cookies', array('cookies_consent' => $cookies_consent));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Split entity data to where they should go.
	 *
	 * @param 	array 	$data
	 * @param 	string 	table/driver 	REQUIRED
	 * @return 	mixed
	 */
	public function split_data(array $data, $driver)
	{
		if (empty($data))
		{
			return $data;
		}

		if ('users' !== $driver && 'groups' !== $driver && 'objects' !== $driver)
		{
			return false;
		}

		if ( ! isset($this->$driver, $this->$driver->fields))
		{
			return false;
		}

		$result = array();

		foreach ($data as $key => $val)
		{
			if (in_array($key, $this->entities->fields))
			{
				$result[0][$key] = $val;
			}
			elseif (in_array($key, $this->$driver->fields))
			{
				$result[1][$key] = $val;
			}
			else
			{
				$result[2][$key] = $val;
			}
		}

		if (empty($result))
		{
			return $data;
		}

		isset($result[0]) OR $result[0] = array();
		isset($result[1]) OR $result[1] = array();
		isset($result[2]) OR $result[2] = array();

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given location has a menu assigned.
	 *
	 * @param   string  $slug   the location's slug
	 * @return  bool
	 */
	public function has_menu($slug)
	{
		return isset($this->menus) ? $this->menus->has_menu($slug) : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Handles registering dashboard menus and stats.
	 *
	 * @param 	none
	 * @return 	void
	 */
	private function for_dashboard($is_homepage = false)
	{
		// Things to add for admins.
		if ($this->auth->is_level(KB_LEVEL_ADMIN))
		{
			add_action('settings_menu', array($this, '_menu_settings'), 9999);
			add_action('help_menu', array($this, '_menu_help'), 9999);
		}

		// Allow other drivers to add their stuff.
		foreach ($this->valid_drivers as $driver)
		{
			if (method_exists($this->$driver, 'for_dashboard'))
			{
				$this->$driver->for_dashboard($is_homepage);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Adds menu items to system dropdown on dashboard.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_settings()
	{
		// Add a dropdown divider.
		echo '<div class="dropdown-divider"></div>',

		/**
		 * Database backup.
		 * @since   2.16
		 */
		admin_anchor('backups', $this->ci->lang->line('admin_database_backup'), 'class="dropdown-item"'),

		/**
		 * CodeIgniter Logs.
		 * @since   2.16
		 */
		admin_anchor('logs', $this->ci->lang->line('admin_logs'), 'class="dropdown-item"'),

		// Add a dropdown divider.
		'<div class="dropdown-divider"></div>',

		/**
		 * Glocal settings.
		 * @since   2.0
		 */
		admin_anchor('settings', $this->ci->lang->line('settings_global'), 'class="dropdown-item"'),

		/**
		 * System information.
		 * @since   2.0
		 */
		admin_anchor('settings/sysinfo', $this->ci->lang->line('settings_sysinfo'), 'class="dropdown-item"');
	}

	// --------------------------------------------------------------------

	/**
	 * Displays Wiki URL and Skeleton Shop for admins.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_help()
	{
		if ( ! empty($wiki_url = apply_filters('csk_wiki_url', KPlatform::WIKI_URL)))
		{
			echo anchor($wiki_url, $this->ci->lang->line('documentation'), 'class="dropdown-item" target="_blank"'),
			'<div class="dropdown-divider"></div>';
		}

		echo anchor(KPlatform::SHOP_URL, $this->ci->lang->line('skeleton_shop'), 'class="dropdown-item disabled"');
	}

	// --------------------------------------------------------------------
	// IP Address Blacklist Methods
	// --------------------------------------------------------------------

	/**
	 * Adds the given IP address to the blacklisted ip addresses list.
	 *
	 * @param 	string 	$ip_address 	The ip address to block.
	 * @param 	string 	$reason 		The reason for block (optional)
	 * @return 	bool 	true if added, else false.
	 */
	public function block_ip($ip_address, $reason = null)
	{
		$ip_addresses = $this->ci->config->item('ip_blacklist', null, array());

		if ( ! isset($ip_addresses[$ip_address]))
		{
			$ip_addresses[$ip_address]['created_at'] = TIME;

			if ( ! empty($reason))
			{
				$ip_addresses[$ip_address]['reason'] = $reason;
				log_message('critical', sprintf('IP Blocked: %s | Reason: %s', $ip_address, $reason), false);
			}
			else
			{
				log_message('critical', sprintf('IP Blocked: %s', $ip_address), false);
			}

			return $this->options->set_item('ip_blacklist', $ip_addresses);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * This method is called and executed only on the 404 error page
	 * and helps preventing excessive requests from bots or malicious users.
	 *
	 * The idea behind is simple: if an IP address triggers the 404 error
	 * more than 10 times within a 10 seconds interval, this will block
	 * it immediately.
	 *
	 * @param 	string 	$ip_address
	 * @return 	void
	 */
	public function block_404($ip_address)
	{
		// Only execute the code for 404 status code.
		if ($this->ci->output->status_code !== 404)
		{
			return;
		}
		elseif ( ! isset($this->ci->cache))
		{
			$this->ci->load->driver('cache', array('adapter' => 'file'));
		}

		if ( ! ($e404 = $this->ci->cache->get('error_404')))
		{
			$e404 = array();
		}

		// Initialize the record for the IP if not already set.
		if ( ! isset($e404[$ip_address]))
		{
			$e404[$ip_address] = array('count' => 0, 'last_time' => TIME);
		}

		if (TIME - $e404[$ip_address]['last_time'] > 10)
		{
			// Reset count if last recorded is older than 10 seconds.
			$e404[$ip_address]['count'] = 1;
			$e404[$ip_address]['last_time'] = TIME;
		}
		else
		{
			// Otherwise, we increment the counter since it is within the frame.
			$e404[$ip_address]['count']++;
		}

		if ($e404[$ip_address]['count'] >= 10)
		{
			// If the threshold is reached within the 10 seconds interval
			// we make sure to instantly block the IP address.
			// The admin should take a look at the logs then consider
			// blocking the IP address on the cPanel for better security
			// or unblocking it if this happened by mistake.
			$this->block_ip($ip_address, 'Too many 404 errors.');
			unset($e404[$ip_address]); // Remove after blocking
		}
		else
		{
			// Since the threshold wasn't reached, we just update 'last_ime'.
			$e404[$ip_address]['last_time'] = TIME;
		}

		// Save the updated record.
		$this->ci->cache->save('error_404', $e404, HOUR_IN_SECONDS);
	}

	// --------------------------------------------------------------------

	/**
	 * Removes the given IP address from the blacklisted ip addresses list.
	 *
	 * @param 	string 	$ip_address
	 * @return 	bool 	true if removed, else false.
	 */
	public function unblock_ip($ip_address)
	{
		if (is_array($ip_addresses = $this->ci->config->item('ip_blacklist')) && isset($ip_addresses[$ip_address]))
		{
			unset($ip_addresses[$ip_address]);
			return $this->options->set_item('ip_blacklist', $ip_addresses);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given/current ip address is blocked.
	 *
	 * @param 	string 	$ip_address
	 * @return 	bool 	true if is blocked, else false.
	 */
	public function is_blocked_ip($ip_address = null)
	{
		// Make sure to create the array if it doesn't exist.
		if ( ! is_array($ip_addresses = $this->ci->config->item('ip_blacklist')))
		{
			$this->options->set_item('ip_blacklist', array());
			$ip_addresses = $this->ci->config->item('ip_blacklist');
		}

		empty($ip_address) && $ip_address = ip_address();

		return isset($ip_addresses[$ip_address]);
	}

	// --------------------------------------------------------------------
	// Demo Mode Methods
	// --------------------------------------------------------------------

	/**
	 * redirect_demo
	 *
	 * Can be used to redirect the user to the homepage with an alert about
	 * the feature/page being currently disabled in demo mode.
	 *
	 * @param   string  $uri    URL
	 * @param   string  $method Redirect method 'auto', 'location' or 'refresh'
	 * @param   int $code   HTTP Response status code
	 * @return  void
	 */
	public function redirect_demo($uri = '', $method = 'auto', $code = null)
	{
		// Doesn't have access to demo?
		if ( ! $this->has_demo_access())
		{
			$this->ci->theme->set_alert($this->ci->lang->line('demo_mode_error'), 'warning');
			redirect($uri, $method, $code);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * has_demo_access
	 *
	 * IF demo mode is enabled, most of the actions are disabled and only the
	 * owner of the website can perform them or have access to them.
	 *
	 * @access 	public
	 * @return 	boolean 	true if has access, else false.
	 */
	public function has_demo_access($req_level = -1)
	{
		return $this->auth->is_level($this->is_demo ? KB_LEVEL_OWNER : $req_level);
	}

	// --------------------------------------------------------------------

	/**
	 * Generate a captcha field.
	 *
	 * @access  public
	 * @param   bool    $force  whether to show captcha even if disabled.
	 * @return  array   captcha image URL and form details.
	 */
	public function captcha($force = false)
	{
		// Not using captcha at all?
		if ( ! $this->ci->config->item('use_captcha') && ! $force)
		{
			return null;
		}

		// Using reCAPTCHA?
		elseif ($this->ci->config->item('use_recaptcha'))
		{
			// Add reCAPTCHA script tag.
			$this->assets->js('https://www.google.com/recaptcha/api.js', 'recaptcha', null, true, array('async'));

			// Return captcha field.
			return array('captcha' => sprintf(
				'<div class="%s" data-sitekey="%s"></div>',
				error_class('g-recaptcha-response', 'g-recaptcha'),
				$this->ci->config->item('recaptcha_site_key')
			));
		}
		// Generate the new captcha.
		else
		{
			isset($this->ci->captcha) OR $this->ci->load->library('captcha');

			$captcha = $this->ci->captcha->create();

			return array(
				'captcha_image' => $captcha['image'],
				'captcha' => array(
					'type'        => 'text',
					'name'        => 'captcha',
					'id'          => 'captcha',
					'placeholder' => $this->ci->lang->line('captcha'),
					'maxlength'   => $this->ci->captcha->word_length,
				),
			);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves info about the given IP address.
	 *
	 * @param 	string 	$ip_address 	The IP address.
	 * @return 	mixed 	Array of IP info if found and valid, else false.
	 */
	public function ip_info($ip_address = null)
	{
		static $json, $filepath;

		// Initialize cache file path and data.
		if ( ! isset($json))
		{
			$filepath = KBPATH.'cache/ipinfo.json';

			if ( ! is_file($filepath))
			{
				is_dir(KBPATH.'cache') OR mkdir(KBPATH.'cache', 0755, true);
				file_put_contents($filepath, json_encode(array()));
			}

			// Decode the content of the file.
			$json = json_decode(file_get_contents($filepath), true);
		}

		// Use the current IP if none is provided.
		empty($ip_address) && $ip_address = ip_address();

		// Validate the IP address.
		if ( ! filter_var($ip_address, FILTER_VALIDATE_IP))
		{
			return false;
		}
		// Check if IP data is cached and not expired.
		elseif ( ! isset($json[$ip_address]) OR (TIME - $json[$ip_address]['timestamp'] > DAY_IN_SECONDS))
		{
			isset($this->ci->curl) OR $this->ci->load->library('curl');

			$info = $this->ci->curl->get("http://ip-api.com/json/{$ip_address}");

			if ($info->is_success()
				&& ($data = json_decode($info->response, true))
				&& $data['status'] === 'success')
			{
				$data = array_diff_key($data, array_flip(array(
					'query', 'status', 'org', 'as', 'asname', 'mobile', 'proxy', 'hosting'
				)));

				$data['countryLine'] = 'country_'.strtolower($data['countryCode']);

				// Add a timestamp for expiration checks.
				$data['timestamp'] = TIME;

				$json[$ip_address] = $data;
			}
			else
			{
				$json[$ip_address] = array('error' => 'Failed to retrieve data', 'timestamp' => TIME);
			}

			// Write back to the file with file locking.
			file_put_contents($filepath, json_encode($json), LOCK_EX);
		}

		return isset($json[$ip_address]) ? $json[$ip_address] : false;
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('generate_password'))
{
	/**
	 * Generates a random password drawn from the defined set of characters.
	 * All function arguments are Optional.
	 *
	 * @param   int     $length     the length of password to generate.
	 * @param   bool    special     whether to include standard special characters.
	 * @param   bool    $extra      whether to include other special characters.
	 * @return  string The random password.
	 */
	function generate_password($length = 12, $special = true, $extra = false)
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		(true === $special) && $chars .= '!@#$%^&*()';
		(true === $extra) && $chars .= '-_ []{}<>~`+=,.;:/?|';

		// Generate the password.
		$password = '';
		for ($i = 0; $i < $length; $i++)
		{
			$password .= substr($chars, rand(0, strlen($chars) - 1), 1);
		}

		return $password;
	}
}
