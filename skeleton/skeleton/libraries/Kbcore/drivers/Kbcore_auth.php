<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_auth Class
 *
 * Handles all user authentication systems on the site.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 */
final class Kbcore_auth extends KB_Driver
{
	/**
	 * Holds the currently logged in user's ID.
	 * @since   2.0
	 * @var     integer
	 */
	private $id = 0;

	/**
	 * Cache user's online status to reduce checks.
	 * @since 	2.124
	 * @var 	bool
	 */
	private $online;

	/**
	 * Holds the currently logged in user's object.
	 * @var object
	 */
	private $user;

	/**
	 * Holds the current player's role.
	 * @since   2.16
	 * @var     string
	 */
	private $role;

	/**
	 * Holds the current player's level.
	 * @since   2.16
	 * @var     integer
	 */
	private $level = 0;

	/**
	 * Holds whether the current user is an admin or not.
	 * @since   2.0
	 * @var     boolean
	 */
	private $admin;

	/**
	 * Olds whether the current user can access dashboard.
	 * @since 	2.18
	 * @var 	boolean
	 */
	private $dashboard;

	/**
	 * Current user's timezone.
	 * @since 	2.118
	 * @var 	string
	 */
	private $timezone;

	/**
	 * Holds users levels.
	 * @since   2.16
	 * @var     array
	 */
	public $levels = array(
		'regular' => KB_LEVEL_REGULAR,
		'author'  => KB_LEVEL_AUTHOR,
		'editor'  => KB_LEVEL_EDITOR,
		'manager' => KB_LEVEL_MANAGER,
		'admin'   => KB_LEVEL_ADMIN,
		'owner'   => KB_LEVEL_OWNER
	);

	/**
	 * Holds message that can be used for alerts.
	 * @since 	2.16
	 * @var 	string
	 */
	public $message = '';

	/**
	 * Lock out after this many tries.
	 * @var 	int
	 */
	protected $allowed_attempts = 4;

	/**
	 * Lock out for this many minutes.
	 * @var 	int
	 */
	protected $short_lockout = 20;

	/**
	 * Long lockout after this many fails.
	 * @var 	int
	 */
	protected $allowed_lockouts = 4;

	/**
	 * Long lock out for this many hours.
	 * @var 	int
	 */
	protected $long_lockout = 6;

	/**
	 * Default cookie name.
	 * @var 	string
	 */
	protected $cookie_name = COOK_USER_AUTH;

	/**
	 * Default cookie life.
	 * @var 	int
	 */
	protected $cookie_life = MONTH_IN_SECONDS * 2;

	/**
	 * Online token variable name.
	 * @var 	string
	 */
	public $online_token_var_name = 'online_token';

	/**
	 * Online token life span.
	 * @var 	int
	 */
	protected $online_token_life = DAY_IN_SECONDS * 2;

	/**
	 * Activation code variable name.
	 * @var 	string
	 */
	public $activation_code_var_name = 'activation_code';

	/**
	 * Activation code life span.
	 * @var 	int
	 */
	protected $activation_code_life = DAY_IN_SECONDS * 2;

	/**
	 * Password reset code variable name.
	 * @var 	string
	 */
	public $password_code_var_name = 'password_code';

	/**
	 * Password code life span.
	 * @var 	int
	 */
	protected $password_code_life = DAY_IN_SECONDS * 2;

	/**
	 * Authentication code variable name.
	 * @var 	string
	 */
	public $tfa_code_var_name = '2fa_code';

	/**
	 * Quick login code variable name.
	 * @var 	string
	 */
	public $quick_login_var_name = 'quick_login';

	/**
	 * Quick login life span.
	 * @var 	int
	 */
	protected $quick_login_code_life = MINUTE_IN_SECONDS * 15;

	// --------------------------------------------------------------------

	/**
	 * initialize
	 *
	 * Initialize this class.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function initialize()
	{
		// Make sure to load session library.
		(isset($this->ci->session)) OR $this->ci->load->library('session');

		/**
		 * Prepare users levels array.
		 * @since 	2.0 	Added array.
		 * @since 	2.18 	Added filter.
		 */
		has_filter('user_levels') && $this->levels = apply_filters('user_levels', $this->levels);

		/**
		 * We make sure to order by level.
		 * @since 	2.18
		 */
		asort($this->levels);

		/**
		 * Allow external change of user cookie life time.
		 * @since 	2.0
		 */
		if (has_filter('user_cookie_life'))
		{
			$this->cookie_life = apply_filters('user_cookie_life', $this->cookie_life);

			if ( ! is_int($this->cookie_life) OR 0 >= $this->cookie_life)
			{
				$this->cookie_life = MONTH_IN_SECONDS * 2;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Attempt to authenticate the current user.
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function authenticate()
	{
		// No cookie?
		if (empty($cookie = $this->_get_cookie()))
		{
			return;
		}

		// Let's make sure the cookie is set first.
		[$user_id, $token, $random, $expiration] = $cookie;

		/**
		 * We make sure to unset to cookie if:
		 * 	1. Empty token provided.
		 * 	2. No expiration provided or expired already.
		 * 	3. Provided '$radom' is invalid.
		 */
		if (empty($token)
			OR empty($expiration)
			OR $expiration <= TIME
			OR ! hash_equals($random, $this->hash_hmac($user_id.'|'.$expiration.'|'.$token)))
		{
			$this->ci->input->set_cookie($this->cookie_name, '', -86400);
			return;
		}

		// Let's get the variable from database.
		// Only if we do not allow multiple session.
		if ( ! $this->ci->config->item('allow_multi_session'))
		{
			$query = $this->ci->db
				->where('guid', $user_id)
				->where('name', $this->online_token_var_name)
				->where('BINARY(value)', $token)
				->where('updated_at >' , TIME)
				->get('variables');

			// We didn't find any?
			if ($query->num_rows() <= 0)
			{
				$query->free_result();

				// Destroy both cookie and session.
				$this->ci->input->set_cookie($this->cookie_name, '', -86400);
				$this->ci->session->unset_userdata(SESS_USER_ID);
				$this->ci->session->unset_userdata(SESS_USER_TOKEN);
				$this->ci->session->unset_userdata(SESS_PREV_USER_ID);

				return;
			}
		}

		// Let's get the user from database.
		$query = $this->ci->db
			->where('id', $user_id)
			->where('type', 'user')
			->join('users', 'entities.id = users.guid')
			->limit(1, 0)
			->get('entities');

		if ($query->num_rows() <= 0)
		{
			$query->free_result();
			return;
		}

		$user = $query->row();
		$query->free_result();

		/**
		 * This is useful if the user is disabled, deleted or
		 * banned while he/she is logged in, we log him/her out.
		 */
		if ($user->enabled != 1 OR $user->deleted > 0)
		{
			$this->logout($user_id);
			return;
		}

		// Cache the user to reduce DB access.
		$this->ci->registry->add($user->id, $user, 'users');
		$this->ci->registry->add($user->username, $user->id, 'usernames');
		empty($user->email) OR $this->ci->registry->add($user->email, $user->id, 'emails');

		// Set user object.
		$this->user = ($user instanceof KB_User) ? $user : new KB_User($user);

		// If the session is already set, nothing to do.
		if ($this->ci->session->userdata(SESS_USER_ID) === $this->id)
		{
			return;
		}

		// If the session is not set, we set it.
		$this->_set_session($user->id, $token, $user->language);
	}

	// --------------------------------------------------------------------

	/**
	 * A method user if the user can authenticate.
	 * @access 	private
	 * @param 	objects 	$user
	 * @return 	bool 		true if the user can authenticate, else false.
	 */
	private function can_authenticate($user)
	{
		// Invalid or missing user?
		if ( ! $user instanceof KB_User)
		{
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}
		// Account temporarily locked?
		elseif (-2 == $user->enabled)
		{
			$this->message = $this->ci->lang->line(array('account_locked_error', 'try_again_later'));
			return false;
		}
		// Make sure the account is enabled.
		elseif (0 == $user->enabled)
		{
			$this->message = $this->ci->lang->sline(
				'account_disabled_error',
				anchor('resend-link', $this->ci->lang->line('click_here'))
			);
			return false;
		}
		// Make sure the account is not banned.
		elseif (-1 == $user->enabled)
		{
			$this->message = $this->ci->lang->line('account_banned_error');
			return false;
		}
		// Account deleted by an admin?
		elseif (0 > $user->deleted)
		{
			$this->message = $this->ci->lang->line('account_deleted_error_admin');
			return false;
		}
		// Make sure the account is not deleted.
		elseif (0 < $user->deleted)
		{
			$this->message = $this->ci->lang->sline(
				'account_deleted_error',
				anchor('restore-account', $this->ci->lang->line('click_here'))
			);
			return false;
		}
		// Passed all!
		else
		{
			return true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Return the currently logged in user's object.
	 *
	 * @since   1.0
	 * @since   1.33   Switched to config instead of options.
	 *
	 * @access  public
	 * @param   string 	$key
	 * @return  object if found, else false.
	 */
	public function user($key = null)
	{
		// Not already cached? Get from database and cache the object.
		if ( ! isset($this->user))
		{
			$this->user = false;

			do {
				// Nothing stored in session? Nothing to do.
				if ( ! $this->ci->session->userdata(SESS_USER_ID) OR
					! $this->ci->session->userdata(SESS_USER_TOKEN))
				{
					break;
				}

				/**
				 * If multiple sessions are not allowed, we compare
				 * stored tokens and make sure only a single user
				 * per session is allowed.
				 */
				elseif ( ! $this->ci->config->item('allow_multi_session'))
				{
					// Get the variable from database.
					$query = $this->ci->db
						->where('guid', $this->ci->session->userdata(SESS_USER_ID))
						->where('name', $this->online_token_var_name)
						->where('BINARY(value)', $this->ci->session->userdata(SESS_USER_TOKEN))
						->where('updated_at >' , TIME)
						->get('variables');

					if (1 !== $query->num_rows())
					{
						$query->free_result();
						break;
					}

					// Free result anyways
					$query->free_result();
				}

				// Get the user from database.
				$user = $this->_parent->users->get($this->ci->session->userdata(SESS_USER_ID));
				if ( ! $user)
				{
					break;
				}

				/**
				 * This is useful if the user is disabled, deleted or
				 * banned while he/she is logged in, we log him/her out.
				 */
				elseif ((1 != $user->enabled OR 0 < $user->deleted) && ! $this->ci->session->userdata(SESS_PREV_USER_ID))
				{
					$this->logout($user->id);
					break;
				}

				/**
				 * Now that everything went well, we make sure to cache
				 * the current user as well as the ID.
				 */
				$this->user  = $user;
				$this->id    = $user->id;
				$this->role  = $user->role;
				$this->level = $user->level;

				// admins are of level KB_LEVEL_ADMIN and above (admin & owner).
				$this->admin = ($user->admin OR KB_LEVEL_ADMIN <= $user->level);

				// user has access to dashboard
				$this->dashboard = (KB_LEVEL_ACP <= $this->level);
				break;

			// We make sure required data are stored in session.
			} while ( ! $this->user);
		}

		// update next online check
		if ($this->user && ! isset($this->online))
		{
			// We can set the flag here to speed up things right?
			$this->online = true;

			// Make sure online status is set to 1
			if ('1' !== $this->user->online)
			{
				$this->ci->db
					->set('online', $this->user->online = '1')
					->where('guid', $this->id)
					->update('users');
			}

			// Update next online check.
			if (TIME >= (int) $this->user->check_online_at)
			{
				$this->ci->db
					->set('check_online_at', $this->user->check_online_at = TIME + (MINUTE_IN_SECONDS * 15))
					->where('guid', $this->id)
					->update('users');
			}

			// Set user's timzeone.
			if ( ! isset($this->timezone))
			{
				$this->timezone = $this->user->timezone ?: $this->ci->config->item('time_reference');
				date_default_timezone_set($this->timezone);
			}
		}

		// Now we can return what was request (if available.)
		return ($this->user && ! empty($key))
			? (isset($this->user->$key) ? $this->user->$key : null)
			: $this->user;
	}

	// --------------------------------------------------------------------

	/**
	 * Whether the current user is logged in.
	 * @access  public
	 * @param   none
	 * @return  bool
	 */
	public function online()
	{
		isset($this->online) OR $this->online = ($this->user() !== false);
		return $this->online;
	}

	// --------------------------------------------------------------------

	/**
	 * is_role
	 *
	 * Method for checking whether the current user is of the specified role.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  public
	 * @param   string  $role   the user's role to check.
	 * @return  bool    true if the player is of the specified role.
	 */
	public function is_role($role = 'regular')
	{
		if ( ! isset($this->role) && $this->online() && isset($this->user->role))
		{
			$this->role = $this->user->role;
			return ($this->role === $role);
		}

		return (isset($this->role) && $this->role === $role);
	}

	// --------------------------------------------------------------------

	/**
	 * level
	 *
	 * Method for caching and returning the current users level.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  public
	 * @return  integer
	 */
	public function level()
	{
		if ($this->level <= 0 && $this->online() && isset($this->user->level))
		{
			$this->level = $this->user->level;
			return $this->level;
		}

		return $this->level;
	}

	// --------------------------------------------------------------------

	/**
	 * is_level
	 *
	 * Method for comparing the current user's level (greater than [or equal to]).
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @access  public
	 * @param   integer 	$level 	the level used for comparison
	 * @param   bool 		$equal 	whether to accept equal
	 * @return  bool 		true if the user is an admin, else false.
	 */
	public function is_level($level = 0, $equal = true)
	{
		return ($equal === true) ? ($this->level() >= $level) : ($this->level() > $level);
	}

	// --------------------------------------------------------------------

	/**
	 * is_admin
	 *
	 * Method for checking whether the current user is an administrator.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 *
	 * @access  public
	 * @param   none
	 * @return  bool    true if the user is an admin, else false.
	 */
	public function is_admin()
	{
		if ( ! isset($this->admin) && $this->online() && isset($this->user->admin))
		{
			$this->admin = $this->user->admin;
			return $this->admin;
		}
		return ($this->admin === true);
	}

	// --------------------------------------------------------------------

	/**
	 * has_dashboard
	 *
	 * Checks whether the user hash dasboard access.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.18
	 *
	 * @access  public
	 * @param   none
	 * @return  bool    true if the user is an admin, else false.
	 */
	public function has_dashboard()
	{
		if ( ! isset($this->dashboard) && $this->online() && isset($this->user->level))
		{
			$this->dashboard = ($this->user->level >= KB_LEVEL_ACP);
			return $this->dashboard;
		}
		return ($this->dashboard == true);
	}

	// --------------------------------------------------------------------

	/**
	 * user_id
	 *
	 * Method for returning the currently logged in user's ID
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  int     Returns the current user's ID.
	 */
	public function user_id()
	{
		if ($this->id <= 0 && $this->online())
		{
			$this->id = $this->user->id;
		}
		return $this->id;
	}

	// --------------------------------------------------------------------
	// Authentication methods.
	// --------------------------------------------------------------------

	/**
	 * Login method.
	 *
	 * @since   1.0
	 * @since   1.33   Log activity was moved from "_set_session" to "login", and
	 *                  added a check to see who deleted the user.
	 *
	 * @access  public
	 * @param   string  $identity   username or emaila address.
	 * @param   string  $password   the password.
	 * @param   string  $area       area from which the user logged in.
	 * @param   string  $language 	what language to use
	 * @return  bool
	 */
	public function login($identity, $password, $area = 'site', $language = null)
	{
		if (empty($identity) OR empty($password))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Fires before processing.
		do_action_ref_array('before_user_login', array(&$identity, &$password));

		// What type of login to use?
		$login_type = $this->ci->config->item('login_type');
		switch ($login_type)
		{
			// Get the user by username.
			case 'username':
				$user = $this->_parent->users->get_by('entities.username', sanitize_username($identity, true));
				break;

			// Get user by email address.
			case 'email':
				$user = $this->_parent->users->get_by('users.email', sanitize_email($identity));
				break;

			// Get user by username or email address.
			case 'both':
			default:
				$user = $this->_parent->users->get($identity);
				break;
		}

		// Missing user?
		if ( ! $user)
		{
			$this->failed();
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}

		// Check the password.
		(isset($this->ci->hash)) OR $this->ci->load->library('hash');
		if (true !== $this->ci->hash->check_password($password, $user->password))
		{
			$this->failed($user);
			$this->message = $this->ci->lang->line('account_credentials_error');
			return false;
		}

		$admin_area = (KB_ADMIN === strtolower($area));

		// Site offline and level is not enough to access?
		if ($this->ci->config->item('site_offline')
			&& $user->level < $this->ci->config->item('offline_access_level', null, KB_LEVEL_MANAGER))
		{
			$this->failed();
			$this->message = $this->ci->lang->line(array('login_error_offline', 'try_again_later'));
			return $admin_area ? 0 : false;
		}

		// User cannot authenticate?
		elseif ( ! $this->can_authenticate($user))
		{
			$this->failed();
			return $admin_area ? 0 : false;
		}

		// Not admin trying to log to admin panel?
		elseif ($admin_area && true !== $user->admin)
		{
			$this->failed();
			$this->message = $this->ci->lang->line('permission_error_access');
			return 0;
		}

		// User has two-factor authentication enabled?
		elseif ($user->two_factor_auth)
		{
			$this->ci->session->set_userdata(SESS_USER_2FA, $user->id);
			return $this->send_auth_code($user->id);
		}

		// Unsuccessful login or something went wrong?
		elseif (true !== $this->quick_login($user, $language, $area))
		{
			$this->failed();
			return $admin_area ? 0 : false;
		}

		$this->success($user->id);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Quick login method without passing by all filters found in login().
	 *
	 * @since   1.0
	 * @since   1.33   Added a little check.
	 * @since   2.0   Added language selection.
	 *
	 * @access  public
	 * @param   object  $user       the user's object to login.
	 * @param   string  $language   user's selected language
	 * @param   string  $arena      arena from which the player logged in.
	 * @param   bool 	$log_action whether to log login action to activities.
	 * @return  bool
	 */
	public function quick_login($user, $language = null, $area = 'site', $log_action = true)
	{
		// ID, username or email provided?
		if ( ! $user instanceof KB_User OR ! is_object($user))
		{
			$user = $this->_parent->users->get($user);
		}

		// Make sure the user can authenticate.
		if ( ! $this->can_authenticate($user))
		{
			return false;
		}

		// Using user's default language?
		elseif (empty($language) OR $language === 'default')
		{
			$language = $user->language;
		}

		// Proceed
		$this->user = $user;

		if ($this->_set_session($user->id, null, $language))
		{
			$this->_parent->purge->password_codes($user->id);
			$this->_parent->purge->auth_codes($user->id);
			$this->_parent->purge->quick_login($user->id);
			$this->_parent->purge->email_codes($user->id);

			// The only way this is false is when an admin switches account.
			if ($log_action)
			{
				$this->_parent->activities->log($user->id, "report_users_login_{$area}");

				// Change users language if needed.
				if ($language !== $user->language && $this->_parent->lang->exists($language))
				{
					$this->ci->db
						->where('id', $user->id)
						->set('language', $language)
						->update('entities');
				}
			}

			$this->message = '';
			return true;
		}

		$this->message = $this->ci->lang->line(array('error_unknown', 'try_again_later'));
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * prep_quick_login
	 *
	 * Method for preparing quick login.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.134
	 *
	 * @param   string
	 * @return  bool
	 */
	public function prep_quick_login($identity)
	{
		// $identity is empty?
		if (empty($identity))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}
		// Make sure the user can authenticate.
		elseif ( ! $this->can_authenticate($user = $this->_parent->users->get($identity)))
		{
			return false;
		}

		// Prepare process status.
		$status = false;

		// Check if there is an existing password code.
		$query = $this->ci->db
			->where('guid', $user->id)
			->where('name', $this->quick_login_var_name)
			->get('variables');

		if (1 === $query->num_rows())
		{
			$var = $query->row();
			$query->free_result();

			if (TIME < $var->updated_at)
			{
				$code   = $var->value;
				$status = true;
			}
			else
			{
				function_exists('random_string') OR $this->ci->load->helper('string');
				$code = random_string('alnum', 40);

				$this->ci->db
					->where('id', $var->id)
					->set('value', $code)
					->set('params', ip_address())
					->set('updated_at', TIME + $this->quick_login_code_life)
					->update('variables');

				$status = (0 < $this->ci->db->affected_rows());
			}
		}
		else
		{
			function_exists('random_string') OR $this->ci->load->helper('string');
			$code = random_string('alnum', 40);

			$this->ci->db->insert('variables', array(
				'guid'       => $user->id,
				'name'       => $this->quick_login_var_name,
				'value'      => $code,
				'params'     => ip_address(),
				'created_at' => TIME,
				'updated_at' => TIME + $this->quick_login_code_life,
			));

			$status = (0 < $this->ci->db->affected_rows());
		}

		// Successful process?
		if ($status)
		{
			// TODO: Log the activity.
			$this->_parent->activities->log($user->id, 'report_users_link');

			$this->_parent->mail_user(
				$user,
				$this->ci->lang->line('mail_login_link'),
				'view:emails/users/link',
				array(
					'link' => anchor('quick-login/'.$code),
					'ip_address' => ip_address()
				)
			);

			// Set alert and log the activity.
			$this->message = $this->ci->lang->line('auth_link_success');
			return true;
		}

		$this->message = $this->ci->lang->line('auth_link_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * check_quick_login
	 *
	 * Method for checking the provided one-click login code.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.134
	 *
	 * @param   string  $code   The one-click login code.
	 * @return  mixed   Return the user's object if found, else false.
	 */
	public function check_quick_login($code = null)
	{
		// First we check if set and check the length.
		if (empty($code) OR strlen($code) !== 40)
		{
			$this->message = $this->ci->lang->line('auth_link_error_link');
			return false;
		}

		// Attempt to get the variable from database.
		$query = $this->ci->db
			->where('name', $this->quick_login_var_name)
			->where('BINARY(value)', $code)
			->where('updated_at >', TIME)
			->get('variables');

		if (1 !== $query->num_rows())
		{
			$this->message = $this->ci->lang->line('auth_link_error_link');
			return false;
		}

		$var = $query->row();
		$query->free_result();

		// Delete the variable on usage.
		$this->ci->db->delete('variables', array('id' => $var->id));

		return $this->quick_login($this->_parent->users->get($var->guid), null, 'site', true);
	}

	// --------------------------------------------------------------------

	/**
	 * Logout method.
	 * @access  public
	 * @param   int 	$user_id
	 * @param   bool 	$unset_cookie
	 * @return  void
	 */
	public function logout($user_id = null, $unset_cookie = false)
	{
		// Catch the user's ID for later use.
		empty($user_id) && $user_id = $this->user_id();

		if ($user_id)
		{
			// Fires before logging out the user.
			do_action('before_user_logout', $user_id);

			// Remove logged-in thing.
			if ($prev_user_id = $this->ci->session->userdata(SESS_PREV_USER_ID))
			{
				$this->_parent->users->update($user_id, array('enabled' => 1));
			}

			// Put the user offline.
			$this->ci->db->update('users', array('online' => 0), array('guid' => $user_id));
			$prev_user_id && $this->ci->db->update('users', array('online' => 0), array('guid' => $prev_user_id));
		}

		// Delete the cookie.
		if ($this->ci->config->item('allow_remember') OR $unset_cookie)
		{
			$this->ci->input->set_cookie($this->cookie_name, '', -86400);
		}

		// Delete online tokens & captcha.
		$this->_parent->purge->online_tokens($user_id);

		$this->user = false;

		// Destroy the session.
		if (PHP_SESSION_NONE !== session_status())
		{
			$this->ci->session->sess_destroy();
		}

		// Fires After user is logged out, cookie deleted and session destroyed.
		do_action('after_user_logout', $user_id);
	}

	// --------------------------------------------------------------------

	/**
	 * register
	 *
	 * Method used for users registration to the front-end.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   array   $data   Array of user's data.
	 * @return  mixed   The user's ID if created, else false.
	 */
	public function register($data = array())
	{
		// Attempt to create user.
		if ( ! ($guid = $this->_parent->users->create($data)))
		{
			$this->message = $this->_parent->users->message;
			return false;
		}
		// Requires a manual activation?
		elseif ( ! isset($data['enabled']) && $this->ci->config->item('manual_activation'))
		{
			$this->_parent->mail_user(
				$guid,
				$this->ci->lang->line('manual_activation'),
				'view:emails/users/manual_activation'
			);

			$this->message = $this->ci->lang->line('auth_register_success_manual');
			return $guid;
		}
		// No activation required?
		elseif ( ! $this->ci->config->item('email_activation') OR (isset($data['enabled']) && $data['enabled'] == 1))
		{
			if ( ! isset($data['enabled']) OR $data['enabled'] != 1)
			{
				$this->_parent->users->update($guid, array('enabled' => 1));
			}

			$this->_parent->mail_user(
				$guid,
				$this->ci->lang->line('mail_register_welcome'),
				'view:emails/users/welcome'
			);

			$this->message = $this->ci->lang->line(array('account_create_success', 'you_may_login'));
			return $guid;
		}

		// We create the activation code then send it to user.
		function_exists('random_string') OR $this->ci->load->helper('string');
		$code = random_string('alnum', 40);
		$this->ci->db->insert('variables', array(
			'guid'       => $guid,
			'name'       => $this->activation_code_var_name,
			'value'      => $code,
			'params'     => ip_address(),
			'created_at' => TIME,
			'updated_at' => TIME + $this->activation_code_life
		));

		// TODO: Log the activity.
		$this->_parent->activities->log($guid, 'report_users_register');

		$this->_parent->mail_user(
			$guid,
			$this->ci->lang->line('mail_register_activate'),
			'view:emails/users/register',
			array('link' => anchor('activate-account/'.$code))
		);

		$this->message = $this->ci->lang->line(array('account_create_success', 'activation_link_sent'));
		return $guid;
	}

	// --------------------------------------------------------------------

	/**
	 * resend_link
	 *
	 * Method for resending account activation link to user;
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   mixed   $identity   User's ID, username or email address.
	 * @return  bool    true if successful, else false.
	 */
	public function resend_link($identity)
	{
		// Nothing passed? Nothing to do...
		if (empty($identity))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Get the user from database and make sure he/she exists.
		if ( ! ($user = $this->_parent->users->get($identity)))
		{
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}

		// User already enabled?
		if ($user->enabled == 1)
		{
			$this->message = $this->ci->lang->line('auth_resend_error_enabled');
			return false;
		}

		// Process status.
		$status = false;

		// Check if a variable already exists.
		$query = $this->ci->db
			->where('guid', $user->id)
			->where('name', $this->activation_code_var_name)
			->get('variables');

		if (1 === $query->num_rows())
		{
			$var = $query->row();
			$query->free_result();

			if (TIME < $var->updated_at)
			{
				$code   = $var->value;
				$status = true;
			}
			else
			{
				function_exists('random_string') OR $this->ci->load->helper('string');
				$code = random_string('alnum', 40);

				$this->ci->db
					->where('id', $var->id)
					->set('value', $code)
					->set('params', ip_address())
					->set('updated_at', TIME + $this->activation_code_life)
					->update('variables');

				$status = (0 < $this->ci->db->affected_rows());
			}
		}
		else
		{
			function_exists('random_string') OR $this->ci->load->helper('string');
			$code = random_string('alnum', 40);

			$this->ci->db->insert('variables', array(
				'guid'       => $user->id,
				'name'       => $this->activation_code_var_name,
				'value'      => $code,
				'params'     => ip_address(),
				'created_at' => TIME,
				'updated_at' => TIME + $this->activation_code_life,
			));

			$status = (0 < $this->ci->db->affected_rows());
		}

		// The process was successful?
		if ($status)
		{
			$this->_parent->activities->log($user->id, 'report_users_activate_link');

			$this->_parent->mail_user(
				$user,
				$this->ci->lang->line('mail_register_resend'),
				'view:emails/users/resend',
				array(
					'link' => anchor('activate-account/'.$code),
					'ip_address' => ip_address()
				)
			);

			$this->message = $this->ci->lang->line(array('activation_link_sent', 'check_inbox_or_spam'));
			return true;
		}

		$this->message = $this->ci->lang->line('auth_resend_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * restore_account
	 *
	 * Method for allowing users to restore their deleted accounts.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   string  $identity   User's username or email address.
	 * @param   string  $password   Account's password.
	 * @return  bool
	 */
	public function restore_account($identity, $password)
	{
		if (empty($identity) OR empty($password))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Get user from database and make sure he/she exists.
		if ( ! ($user = $this->_parent->users->get($identity)))
		{
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}

		// Check the password.
		(isset($this->ci->hash)) OR $this->ci->load->library('hash');
		if (true !== $this->ci->hash->check_password($password, $user->password))
		{
			$this->message = $this->ci->lang->line('account_credentials_error');
			return false;
		}

		// The user is not really deleted?
		if ($user->deleted == 0)
		{
			$this->message = $this->ci->lang->line('auth_restore_error_deleted');
			return false;
		}

		// Successfully restored and logged in?
		if (false !== $this->_parent->entities->restore_by('id', $user->id) && $this->quick_login($user))
		{
			// TODO: Log the activity.
			$this->_parent->activities->log($user->id, 'report_users_restore');

			$this->_parent->mail_user(
				$user,
				$this->ci->lang->line('mail_login_restore'),
				'view:emails/users/restore'
			);

			$this->message = $this->ci->lang->line('auth_restore_success');

			return true;
		}

		$this->message = $this->ci->lang->line('auth_restore_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * prep_password_reset
	 *
	 * Method for preparing account for password reset.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   string
	 * @return  bool
	 */
	public function prep_password_reset($identity)
	{
		// $identity is empty?
		if (empty($identity))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Get user from database and make sure (s)he exists.
		if ( ! ($user = $this->_parent->users->get($identity)))
		{
			$this->message = $this->ci->lang->line('account_missing_error');
			return false;
		}

		// Make sure the account is not banned.
		if ($user->enabled < 0)
		{
			$this->message = $this->ci->lang->line('account_banned_error');
			return false;
		}

		// Make sure the account is not deleted.
		if ($user->deleted > 0)
		{
			$this->message = $this->ci->lang->sline(
				'account_deleted_error',
				anchor('login/restore', $this->ci->lang->line('click_here'))
			);
			return false;
		}

		// Prepare process status.
		$status = false;

		// Check if there is an existing password code.
		$query = $this->ci->db
			->where('guid', $user->id)
			->where('name', $this->password_code_var_name)
			->get('variables');

		if (1 === $query->num_rows())
		{
			$var = $query->row();
			$query->free_result();

			if (TIME < $var->updated_at)
			{
				$code   = $var->value;
				$status = true;
			}
			else
			{
				function_exists('random_string') OR $this->ci->load->helper('string');
				$code = random_string('alnum', 40);

				$this->ci->db
					->where('id', $var->id)
					->set('value', $code)
					->set('params', ip_address())
					->set('updated_at', TIME + $this->password_code_life)
					->update('variables');

				$status = (0 < $this->ci->db->affected_rows());
			}
		}
		else
		{
			function_exists('random_string') OR $this->ci->load->helper('string');
			$code = random_string('alnum', 40);

			$this->ci->db->insert('variables', array(
				'guid'       => $user->id,
				'name'       => $this->password_code_var_name,
				'value'      => $code,
				'params'     => ip_address(),
				'created_at' => TIME,
				'updated_at' => TIME + $this->password_code_life,
			));

			$status = (0 < $this->ci->db->affected_rows());
		}

		// Successful process?
		if ($status)
		{
			// TODO: Log the activity.
			$this->_parent->activities->log($user->id, 'report_users_recover');

			$this->_parent->mail_user(
				$user,
				$this->ci->lang->line('mail_login_recover'),
				'view:emails/users/recover',
				array(
					'link' => anchor('reset-password/'.$code),
					'ip_address' => ip_address()
				)
			);

			// Set alert and log the activity.
			$this->message = $this->ci->lang->line('auth_recover_success');
			return true;
		}

		$this->message = $this->ci->lang->line('auth_recover_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * check_password_code
	 *
	 * Method for checking the provided password reset code.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   string  $code   The password reset code.
	 * @return  mixed   Return the user's ID if found, else false.
	 */
	public function check_password_code($code = null)
	{
		// First we check if set and check the length.
		if (empty($code) OR strlen($code) !== 40)
		{
			$this->message = $this->ci->lang->line('auth_reset_error_link');
			return false;
		}

		// Attempt to get the variable from database.
		$query = $this->ci->db
			->where('name', $this->password_code_var_name)
			->where('BINARY(value)', $code)
			->where('updated_at >', TIME)
			->get('variables');

		if (1 !== $query->num_rows())
		{
			$this->message = $this->ci->lang->line('auth_reset_error_link');
			return false;
		}

		$var = $query->row();
		$query->free_result();

		return $var->guid;
	}

	// --------------------------------------------------------------------

	/**
	 * reset_password
	 *
	 * Method for reseting user's password.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   int     $guid       The user's ID;
	 * @param   string  $password   The new password.
	 * @return  bool
	 */
	public function reset_password($guid, $password)
	{
		// Nothing provided? Nothing to do...
		if (empty($guid) OR empty($password))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Make sure the user exists.
		if ( ! ($user = $this->_parent->users->get($guid)))
		{
			$this->message = $this->ci->lang->line('password_change_error');
			return false;
		}

		/**
		 * The process status depending on two things:
		 * 1. Whether the user is using the same old password.
		 * 2. Whether a different password is provided and user updated.
		 */

		// Same password? status is set to true.
		(isset($this->ci->hash)) OR $this->ci->load->library('hash');
		$status = $this->ci->hash->check_password($password, $user->password);

		// Different password? The status depends on the update.
		$status OR $status = $user->update('password', $password);

		// Successful?
		if ($status)
		{
			// TODO: Log the activity.
			$this->_parent->activities->log($user->id, 'report_users_reset');

			$this->_parent->mail_user(
				$guid,
				$this->ci->lang->line('mail_password_changed'),
				'view:emails/users/password',
				array('ip_address' => ip_address())
			);

			// Purge password codes.
			$this->_parent->purge->password_codes($guid);

			$this->message = $this->ci->lang->line('password_change_success');
			return true;
		}

		$this->message = $this->ci->lang->line('password_change_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * activate_by_code
	 *
	 * Method for activating a user by the given activation code.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 * @since   2.76 	Moved to Kbcore_auth
	 *
	 * @param   string  $code   The account activation code.
	 * @return  bool    true if activated, else false.
	 */
	public function activate_by_code($code = null)
	{
		// Check whether it's set and check its length.
		if (empty($code) OR strlen($code) !== 40)
		{
			$this->message = $this->ci->lang->line('account_enable_error_link');
			return false;
		}

		// Get variable from database and make sure it exists.
		$query = $this->ci->db
			->select('guid')
			->where('name', $this->activation_code_var_name)
			->where('BINARY(value)', $code)
			->where('updated_at >', TIME)
			->get('variables');

		if (1 !== $query->num_rows())
		{
			$this->message = $this->ci->lang->line('account_enable_error_link');
			return false;
		}

		$var = $query->row();
		$query->free_result();

		/**
		 * If the user does not exists, we fake the message telling that
		 * the key is invalid instead of saying the user does not exist.
		 */
		$query = $this->ci->db
			->select('id')
			->where('id', $var->guid)
			->where('type', 'user')
			->where('enabled !=', 1)
			->get('entities');

		if (1 !== $query->num_rows())
		{
			$query->free_result();

			// First purge activation codes.
			$this->_parent->auth->activation_codes($var->guid);

			$this->message = $this->ci->lang->line('account_enable_error_link');
			return false;
		}

		$user_id = $query->row()->id;
		$query->free_result();

		// Attempt to activate user.
		$status = $this->ci->db
			->where('id', $user_id)
			->set('enabled', 1)
			->update('entities');

		// Successfully activated?
		if ($status)
		{
			// TODO: Log the activity.
			$this->_parent->activities->log($user_id, 'report_users_activate');

			$this->_parent->mail_user(
				$user_id,
				$this->ci->lang->line('mail_account_activated'),
				'view:emails/users/activated'
			);

			// Purge activation codes.
			$this->_parent->auth->activation_codes($var->guid);

			$this->message = $this->ci->lang->line(array('account_enable_success', 'you_may_login'));
			return true;
		}

		// Otherwise, an error occurred somewhere.
		$this->message = $this->ci->lang->line('account_enable_error');
		return false;
	}

	// --------------------------------------------------------------------
	// Authentication Code Methods.
	// --------------------------------------------------------------------

	/**
	 * Login user using two-factor authentication code.
	 *
	 * @param 	int 	$user_id 	The user's id.
	 * @param 	string 	$auth_code 	The user's authentication code.
	 * @param 	string 	$area 		The login area.
	 * @return 	bool
	 */
	public function login_2fa($user_id = 0, $auth_code = '', $area = 'site')
	{
		if (true !== $this->check_auth_code($user_id, $auth_code))
		{
			$this->failed();
			$this->message = $this->ci->lang->line('two_factor_code_error');
			return false;
		}

		elseif (true !== $this->quick_login($user_id, null, $area))
		{
			$this->failed();
			$this->message = $this->ci->lang->line('error_unknown');
			return false;
		}

		$this->ci->session->unset_userdata(SESS_USER_2FA);
		$this->success($user_id);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Retireves user's authentication code from database.
	 *
	 * This method returns the authentication code if found and still didn't
	 * expire yet. If it expired already, it makes sure to update the code,
	 * and the expiration date. Otherwise, it simple generates a new code and
	 * stores it in the 'variables' table.
	 *
	 * @param 	int 	$user_id 	The user's ID or object.
	 * @return 	string 	The authentication code.1
	 */
	public function get_auth_code($user_id = 0)
	{
		$user_id = ($user_id instanceof KB_User) ? $user_id->id : $user_id;

		// Attempt to get the code from 'variables' table.
		$query = $this->ci->db
			->where('guid', $user_id)
			->where('name', $this->tfa_code_var_name)
			->get('variables');

		// Found a record? Just use it.
		if (1 === $query->num_rows())
		{
			$var = $query->row();
			$query->free_result();

			// The code already expired?
			if (TIME >= $var->updated_at)
			{
				// Generate a new one.
				function_exists('random_string') OR $this->ci->load->helper('string');
				$code = random_string('numeric', 6);

				// Update the table.
				$this->ci->db
					->where('id', $var->id)
					->set('value', $code)
					->set('params', ip_address())
					->set('updated_at', TIME + (MINUTE_IN_SECONDS * 5))
					->update('variables');

				// Resent the code...
				$this->send_auth_code($user_id, $code);
				return $code; // return it.
			}

			// Return the code as it didn't expire yet.
			return $var->value;
		}

		// Generate a new code and store it into 'variable' table.
		function_exists('random_string') OR $this->ci->load->helper('string');
		$code = random_string('numeric', 6);

		$this->ci->db->insert('variables', array(
			'guid'       => $user_id,
			'name'       => $this->tfa_code_var_name,
			'value'      => $code,
			'params'     => ip_address(),
			'created_at' => TIME,
			'updated_at' => TIME + (MINUTE_IN_SECONDS * 5),
		));

		return $code;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks user's authentication code.
	 *
	 * @param 	mixed 	$user_id 	The user's ID or object
	 * @param 	string 	$auth_code 	The user's type (unsafe) auth code.
	 * @return 	bool
	 */
	public function check_auth_code($user_id = 0, $auth_code = '')
	{
		if (empty($user_id) OR 0 >= $user_id = (int) $user_id OR empty($auth_code))
		{
			return false;
		}

		return hash_equals($this->get_auth_code($user_id), $auth_code);
	}

	// --------------------------------------------------------------------

	/**
	 * Sends authentication code to the user.
	 *
	 * @param 	int 	$user_id 	The user's ID or object.
	 * @param 	string 	$code 		Optional code.
	 * @return 	Kbcore::mail_user
	 */
	private function send_auth_code($user_id = 0, $code = null)
	{
		$user_id = ($user_id instanceof KB_User) ? $user_id->id : $user_id;

		empty($code) && $code = $this->get_auth_code($user_id);

		$this->_parent->mail_user(
			$user_id,
			$this->ci->lang->line('two_factor_auth'),
			'view:emails/users/two_factor',
			array('code' => $code, 'ip_address' => ip_address()),
		);

		$this->message = $this->ci->lang->line('two_factor_send_success');
		return 1;
	}

	// --------------------------------------------------------------------
	// Session and Cookie Management.
	// --------------------------------------------------------------------

	/**
	 * Setup session data at login and autologin.
	 *
	 * @since   1.0
	 * @since   1.33   Log activity was moved from "_set_session" to "login".
	 *
	 * @access  private
	 * @param   int     $user_id    the user's ID.
	 * @param   string  $token      the user's online token.
	 * @param   string  $language   the user's language.
	 * @return  bool
	 */
	private function _set_session($user_id, $token = null, $language = null)
	{
		// Make sure all neded data are present.
		if (empty($user_id))
		{
			return false;
		}

		// If no $token is provided, we generate a new one.
		elseif (empty($token))
		{
			(isset($this->ci->hash)) OR $this->ci->load->library('hash');
			$token = $this->ci->hash->hash($user_id.session_id().rand());
		}

		// Fires before logging in the user.
		do_action('after_user_login', $user_id);

		// Prepare session data.
		$sess_data = array(
			SESS_USER_ID    => $user_id,
			SESS_USER_TOKEN => $token,
			SESS_USER_AGENT => $this->ci->agent->agent_string(),
			SESS_IP_ADDRESS => ip_address()
		);

		// Add user language only if available.
		empty($language) OR $this->_parent->lang->change($language);

		// Now we set session data.
		$this->ci->session->set_userdata($sess_data);

		// Now we create/update the variable.
		if ( ! $this->ci->config->item('allow_multi_session'))
		{
			$this->ci->db->save(
				'variables',
				array( // INSERT
					'guid'       => $user_id,
					'name'       => $this->online_token_var_name,
					'value'      => $token,
					'params'     => ip_address(),
					'created_at' => TIME,
					'updated_at' => TIME + $this->online_token_life
				),
				array( // UPDATE
					'value'      => $token,
					'params'     => ip_address(),
					'updated_at' => TIME + $this->online_token_life
				)
			);
		}

		// Put the user online && update last online status.
		$this->ci->db
			->set('online', 1)
			->set('online_at', TIME)
			->set('check_online_at', TIME + (MINUTE_IN_SECONDS * 15))
			->set('ip_address', ip_address())
			->where('guid', $user_id)
			->update('users');

		// Should we remember the user?
		if ($this->ci->config->item('allow_remember') && $this->ci->input->post('remember') === '1')
		{
			return $this->_set_cookie($user_id, $token);
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the cookie for the user after a login.
	 * @access  private
	 * @param   int     $user_id    the user's ID.
	 * @param   string  $token      the user's online token.
	 * @return  bool
	 */
	private function _set_cookie($user_id, $token)
	{
		// If no data provided, nothing to do.
		if (empty($user_id) OR empty($token))
		{
			return false;
		}

		/**
		 * The idea behind this is to generate a new random string
		 * and append it to the user's ID and token then encode
		 * everything. IT will be harder to crack the cookie and when
		 * we try to get the cookie back, we only need the two first
		 * elements of the exploded cookie.
		 */
		$expiration = TIME + $this->cookie_life;
		$random = $this->hash_hmac($user_id.'|'.$expiration.'|'.$token);

		// $cookie_value = $this->ci->hash->encode($user_id, $hmac, $token, TIME + $this->cookie_life);
		$cookie_value = $this->ci->hash->encode($user_id, $token, $random, $expiration);

		// Now we set the cookie.
		$this->ci->input->set_cookie($this->cookie_name, $cookie_value, $this->cookie_life);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Uses hash_hmac to hash given string.
	 * @access 	private
	 * @param 	string 	$str
	 * @return 	string
	 */
	private function hash_hmac($str)
	{
		return hash_hmac(KB_HMAC_ALGO, $str, $this->ci->config->item('encryption_key'));
	}

	// --------------------------------------------------------------------

	/**
	 * Attempt to retrieve and decode the current user's cookie.
	 * @access  private
	 * @param   none
	 * @return  array if found, else false.
	 */
	private function _get_cookie()
	{
		if ( ! $this->ci->config->item('allow_remember'))
		{
			return false;
		}

		// Check whether the cookie exists.
		$cookie = $this->ci->input->cookie($this->cookie_name, true);
		if ( ! $cookie)
		{
			return false;
		}

		// We load the hash library and decode the cookie.
		(isset($this->ci->hash)) OR $this->ci->load->library('hash');
		$cookie = $this->ci->hash->decode($cookie);

		/**
		 * For the cookie to be valid, it has to not to be
		 * empty and MUST contain three (3) elements:
		 * 1. The user's ID.
		 * 2. The online token.
		 * 3. The random string generated when encoding the cookie.
		 */
		return (empty($cookie) OR count($cookie) !== 4) ? false : $cookie;
	}

	// --------------------------------------------------------------------
	// LOGIN LOCK/LIMIT
	// --------------------------------------------------------------------

	/**
	 * Sets the login lockout message depending on the duration.
	 *
	 * @access 	private
	 * @param 	int 	$duration
	 * @return 	void
	 */
	private function _set_lockout_message($duration)
	{
		$this->message = ($duration > 3600)
			? $this->ci->lang->sline('login_attempts_error_long', ceil($duration / 3600))
			: $this->ci->lang->sline('login_attempts_error_short', ceil($duration / 60));

		// Some $_POST still hanging? Force refresh.
		if ( ! empty($_POST))
		{
			redirect($this->ci->uri->uri_string());
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Cleans up expired lockouts, login attempts, and associated timestamps.
	 *
	 * This method ensures the login protection data doesn't grow indefinitely
	 * by removing any expired entries from the `limit_login_lockouts`,
	 * `limit_login_attempts`, and `limit_login_datetime`.
	 *
	 * @access private
	 * @param 	array|null 	$lockouts	Optional array of lockout timestamps by IP.
	 * @param 	array|null 	$attempts	Optional array of attempt counts by IP.
	 * @param 	array|null 	$datetime	Optional array of expiration timestamps by IP.
	 * @param 	int|null 	$now		Optional current timestamp (used for testing).
	 * @return 	void
	 */
	private function _clean_login_attempts($lockouts = null, $attempts = null, $datetime = null, $now = null)
	{
		is_null($now) && $now = TIME;

		// Clean expired lockouts.
		is_null($lockouts) && $lockouts = $this->ci->config->item('limit_login_lockouts', null, false);
		if (is_array($lockouts))
		{
			foreach ($lockouts as $ip => $lockout)
			{
				if ($lockout < $now)
				{
					unset($lockouts[$ip]);
				}
			}
			$this->_parent->options->set_item('limit_login_lockouts', $lockouts);
		}

		// Clean expired or invalid attempts and timestamps.
		is_null($attempts) && $attempts = $this->ci->config->item('limit_login_attempts', null, false);
		is_null($datetime) && $datetime = $this->ci->config->item('limit_login_datetime', null, false);

		if ( ! is_array($attempts) || ! is_array($datetime))
		{
			return;
		}

		// Remove expired timestamped entries and sync both arrays.
		foreach ($datetime as $ip => $expiry)
		{
			if ($expiry < $now)
			{
				unset($datetime[$ip], $attempts[$ip]);
			}
		}

		// Remove any unsynced attempts.
		foreach ($attempts as $ip => $count)
		{
			if ( ! isset($datetime[$ip]))
			{
				unset($attempts[$ip]);
			}
		}

		// Save updated attempts and datetime arrays.
		$this->_parent->options->set_item('limit_login_attempts', $attempts);
		$this->_parent->options->set_item('limit_login_datetime', $datetime);
	}

	// --------------------------------------------------------------------

	/**
	 * Checks if the current IP address is locked out due to too many
	 * failed login attempts.
	 *
	 * @return 	bool 	True if locked, false otherwise.
	 */
	public function is_locked(): bool
	{
		// Login protection disabled?
		if ( ! $this->ci->config->item('login_fail_enabled', null, true))
		{
			return false;
		}

		// Retrieve the lockouts array.
		$lockouts = $this->ci->config->item('limit_login_lockouts', null, []);
		if ( ! is_array($lockouts))
		{
			return false;
		}

		// Current time and IP address.
		$now = TIME;
		$ip  = ip_address();

		// Is the IP currently locked?
		if (isset($lockouts[$ip]) && $now < $lockouts[$ip])
		{
			$this->_set_lockout_message($lockouts[$ip] - $now);
			return true;
		}

		// Clean expired entries if not locked.
		$this->_clean_login_attempts($lockouts, null, null, $now);

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Handles a failed login attempt: increments counters, applies lockouts,
	 * and sends alerts.
	 *
	 * This method keeps track of failed login attempts per IP.
	 * If repeated attempts are made, short lockouts are applied. After a
	 * configured number of short lockouts, a long lockout is applied.
	 *
	 * @access 	public
	 * @param 	mixed 	$user User object or null
	 * @return	void
	 */
	public function failed($user = null)
	{
		// Bail early if login protection is disabled.
		if ( ! $this->ci->config->item('login_fail_enabled', null, true))
		{
			return;
		}

		$now = TIME;
		$ip  = ip_address();

		// Load or initialize config items.
		$lockouts      = is_array($this->ci->config->item('limit_login_lockouts', null, false)) ? $this->ci->config->item('limit_login_lockouts', null, false) : array();
		$attempts      = is_array($this->ci->config->item('limit_login_attempts', null, false)) ? $this->ci->config->item('limit_login_attempts', null, false) : array();
		$datetime      = is_array($this->ci->config->item('limit_login_datetime', null, false)) ? $this->ci->config->item('limit_login_datetime', null, false) : array();

		// Make sure short lockout counter exists
		$lockout_count = is_array($this->ci->config->item('limit_login_count', null, false))
			? $this->ci->config->item('limit_login_count', null, false)
			: array();

		// If IP is currently locked out, prevent login.
		if (isset($lockouts[$ip]) && $now < $lockouts[$ip])
		{
			$this->_set_lockout_message($lockouts[$ip] - $now);
			return;
		}

		// Increment login attempts or initialize.
		if (isset($attempts[$ip]) && isset($datetime[$ip]) && $now < $datetime[$ip])
		{
			$attempts[$ip]++;
		}
		else
		{
			$attempts[$ip] = 1;
		}

		// Set or refresh the attempt expiration timestamp.
		$datetime[$ip] = $now + $this->long_lockout;

		// Fetch allowed max attempts and lockouts.
		$allowed_attempts = $this->ci->config->item('login_fail_allowed_attempts', null, $this->allowed_attempts);
		$allowed_lockouts = $this->ci->config->item('login_fail_allowed_lockouts', null, $this->allowed_lockouts);

		// If allowed attempts not reached yet, just clean old records and return.
		if ($attempts[$ip] % $allowed_attempts !== 0)
		{
			$this->_clean_login_attempts($lockouts, $attempts, $datetime, $now);
			return;
		}

		// Count short lockouts for IP.
		$lockout_count[$ip] = isset($lockout_count[$ip]) ? $lockout_count[$ip] + 1 : 1;

		// If max lockouts reached, apply long lockout.
		if ($lockout_count[$ip] >= $allowed_lockouts)
		{
			$user && $this->login_alert($user, false);
			$long_lockout       = $this->ci->config->item('login_fail_long_lockout', null, $this->long_lockout);
			$lockouts[$ip]      = $now + $long_lockout * HOUR_IN_SECONDS;

			// Reset all counters for this IP.
			unset($attempts[$ip], $datetime[$ip], $lockout_count[$ip]);
		}
		else
		{
			// Otherwise, apply short lockout.
			$user && $this->login_alert($user, false);
			$short_lockout      = $this->ci->config->item('login_fail_short_lockout', null, $this->short_lockout);
			$lockouts[$ip]      = $now + $short_lockout * MINUTE_IN_SECONDS;
		}

		// Clean up stale records.
		$this->_clean_login_attempts($lockouts, $attempts, $datetime, $now);

		// Save updated lockout counter.
		$this->_parent->options->set_item('limit_login_count', $lockout_count);
	}

	// --------------------------------------------------------------------

	/**
	 * Clears login lockouts, attempts and times on success login.
	 *
	 * @access 	private
	 * @param 	none
	 * @return 	void
	 */
	private function success($user_id = 0)
	{
		// Save user's login device.
		($user_id && $user_id > 0) && $this->save_device($user_id);

		// Purge online users and delete expired sessions.
		// These methods have run intervals, so it is safe to call them
		// here since they won't be executed if the it's not yet time.
		$this->_parent->purge->mark_offline();
		$this->_parent->purge->sessions();

		// Clean login lockouts.
		if (is_array($lockouts = $this->ci->config->item('limit_login_lockouts', null, false))
			&& isset($lockouts[ip_address()]))
		{
			unset($lockouts[ip_address()]);
			$this->_parent->options->set_item('limit_login_lockouts', $lockouts);
		}

		// Clean login attempts.
		if (is_array($attempts = $this->ci->config->item('limit_login_attempts', null, false))
			&& isset($attempts[ip_address()]))
		{
			unset($attempts[ip_address()]);
			$this->_parent->options->set_item('limit_login_attempts', $attempts);
		}

		// Clean login timestamps.
		if (is_array($datetime = $this->ci->config->item('limit_login_datetime', null, false))
			&& isset($datetime[ip_address()]))
		{
			unset($datetime[ip_address()]);
			$this->_parent->options->set_item('limit_login_datetime', $datetime);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Saves user's login device.
	 *
	 * @param 	int 	$user_id
	 * @return 	void
	 */
	private function save_device(int $user_id)
	{
		$this->login_alert($user_id, true);

		$meta = $this->ci->db
			->where('guid', $user_id)
			->where('name', 'login_devices')
			->get('metadata')
			->row();

		$data = $meta ? unserialize($meta->value) : array();

		isset($this->ci->agent) OR $this->ci->load->library('user_agent');

		array_unshift($data, array(
			'browser' => $this->ci->agent->is_mobile() ? $this->ci->agent->mobile() : $this->ci->agent->browser(),
			'platform' => $this->ci->agent->platform(),
			'ip_address' => ip_address(),
			'created_at' => TIME,
		));

		// Only keep last 20
		$data = array_truncate($data, 20);

		// Insert or Update
		$this->ci->db->save(
			'metadata',
			array('guid' => $user_id, 'name' => 'login_devices', 'value' => serialize($data)),
			array('value' => serialize($data))
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Sends successful/failed login attempt alerts to user.
	 *
	 * @param 	object 	$user
	 * @param 	bool 	$success
	 * @return 	void
	 */
	private function login_alert($user, $success = true)
	{
		/**
		 * We simply do not alert the user if:
		 * 1. Missing user ID or object.
		 * 2. A successful login but the alert is disabled (success).
		 * 3. A failed login but the alert is disabled (failed).
		 */
		if ( ! $user
			OR ($success && ! $this->ci->config->item('alert_login_success'))
			OR ( ! $success && ! $this->ci->config->item('alert_login_failed')))
		{
			return;
		}

		isset($this->ci->agent) OR $this->ci->load->library('user_agent');

		$data = array(
			'date' => $this->_parent->lang->date(),
			'date' => date('l, d F Y @ H:i', TIME),
			'browser' => $this->ci->agent->is_mobile() ? $this->ci->agent->mobile() : $this->ci->agent->browser(),
			'platform' => $this->ci->agent->platform(),
			'ip_address' => ip_address(),
		);

		if ($success)
		{
			$subject = $this->ci->lang->line('alert_login_success');
			$message = 'view:emails/users/login_success';
		}
		else
		{
			$subject = $this->ci->lang->line('alert_login_failed');
			$message = 'view:emails/users/login_failed';

			// Password reset link.
			$data['reset_link'] = anchor($this->ci->config->site_url('lost-password'));
		}

		$this->_parent->mail_user($user, $subject, $message, $data);
	}

}

// --------------------------------------------------------------------
// Helpers
// --------------------------------------------------------------------

if ( ! function_exists('current_user'))
{
	/**
	 * current_user
	 *
	 * Return the currently logged in user's object.
	 *
	 * @param   string 	$key
	 * @return  object if found, else false.
	 */
	function current_user($key = null)
	{
		return get_instance()->auth->user($key);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('user_is_level'))
{
	/**
	 * user_is_level
	 *
	 * Function for comparing the current user's level (greater than [or equal to]).
	 *
	 * @param   integer 	$level 	the level used for comparison
	 * @param   bool 		$equal 	whether to accept equal
	 * @return  bool 		true if the user is an admin, else false.
	 */
	function user_is_level($level = 0, $equal = true)
	{
		return get_instance()->auth->is_level($level, $equal);
	}
}
