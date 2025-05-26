<?php
defined('BASEPATH') OR die;

/**
 * KB_Form_validation Class
 *
 * Extends CodeIgniter validation class to add/edit some methods.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	2.0
 */
class KB_Form_validation extends CI_Form_validation
{
	/**
	 * Start tag for help wrapping
	 *
	 * @var string
	 */
	protected $_help_prefix = '<div class="form-text text-body-secondary">';

	/**
	 * End tag for help wrapping
	 *
	 * @var string
	 */
	protected $_help_suffix = '</div>';

	/**
	 * Start tag for error wrapping
	 *
	 * @var string
	 */
	protected $_error_prefix = '<div class="invalid-feedback">';

	/**
	 * End tag for error wrapping
	 *
	 * @var string
	 */
	protected $_error_suffix = '</div>';

	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct($rules = array())
	{
		$this->CI =& get_instance();

		/**
		 * Here we merge super-global $_FILES to $_POST to allow for
		 * better file validation or Form_validation library.
		 * @see 	https://goo.gl/NpsmMJ (Bonfire)
		 */
		( ! empty($_FILES) && is_array($_FILES)) && $_POST = array_merge($_POST, $_FILES);

		/**
		 * Use custom delimiters for dashboard.
		 * @since   2.16
		 */
		if ( ! $this->CI->uri->is_dashboard)
		{
			/**
			 * Apply form help prefix filter.
			 * @since   2.16
			 */
			if (isset($rules['help_prefix']))
			{
				$this->_help_prefix = apply_filters('form_help_prefix', $rules['help_prefix']);
				unset($rules['help_prefix']);
			}

			/**
			 * Apply form help suffix filter.
			 * @since   2.16
			 */
			if (isset($rules['help_suffix']))
			{
				$this->_help_suffix = apply_filters('form_help_suffix', $rules['help_suffix']);
				unset($rules['help_suffix']);
			}

			/**
			 * Apply form error prefix filter.
			 * @since   2.16
			 */
			if (isset($rules['error_prefix']))
			{
				$this->_error_prefix = apply_filters('form_error_prefix', $rules['error_prefix']);
				unset($rules['error_prefix']);
			}

			/**
			 * Apply form error suffix filter.
			 * @since   2.16
			 */
			if (isset($rules['error_suffix']))
			{
				$this->_error_suffix = apply_filters('form_error_suffix', $rules['error_suffix']);
				unset($rules['error_suffix']);
			}
		}

		// Call parent's constructor.
		parent::__construct($rules);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for getting field data from the form validation library.
	 *
	 * @param 	string 	$field
	 * @return 	mixed 	field data if found, else null
	 */
	public function field_data($field = null)
	{
		return isset($field, $this->_field_data[$field]) ? $this->_field_data[$field] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Set The Help Delimiter
	 *
	 * Permits a prefix/suffix to be added to each help message with
	 * extra apply_filter.
	 *
	 * @since   2.16
	 *
	 * @param   string
	 * @param   string
	 * @return  CI_Form_validation
	 */
	public function set_help_delimiters($prefix = '<p>', $suffix = '</p>')
	{
		/* Only if not in dashboard. */
		if ( ! $this->CI->uri->is_dashboard)
		{
			$this->_help_prefix = apply_filters('form_help_prefix', $prefix);
			$this->_help_suffix = apply_filters('form_help_suffix', $suffix);
		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set The Error Delimiter
	 *
	 * Permits a prefix/suffix to be added to each error message with
	 * extra apply_filter.
	 *
	 * @since   2.16
	 *
	 * @param   string
	 * @param   string
	 * @return  CI_Form_validation
	 */
	public function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
	{
		/* Only if not in dashboard. */
		if ( ! $this->CI->uri->is_dashboard)
		{
			$this->_error_prefix = apply_filters('form_error_prefix', $prefix);
			$this->_error_suffix = apply_filters('form_error_suffix', $suffix);
		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks if the given field has any error.
	 *
	 * @since   2.16
	 *
	 * @param   string  $field
	 * @return  bool    true if found any error, else false.
	 */
	public function has_error($field)
	{
		if (is_array($field))
		{
			foreach ($field as $_field)
			{
				if ( ! empty($this->_field_data[$_field]['error']))
				{
					return true;
				}
			}

			return false;
		}

		return ( ! empty($this->_field_data[$field]['error']));
	}

	// --------------------------------------------------------------------

	/**
	 * Get Error Message
	 *
	 * Gets the error message associated with a particular field.
	 * Modified to use default value as form help text.
	 *
	 * @since   2.16
	 *
	 * @param   string  $field  Field name
	 * @param   string  $prefix HTML start tag
	 * @param   string  $suffix HTML end tag
	 * @return  string
	 */
	public function error($field, $prefix = '', $suffix = '', $default = '')
	{
		if (empty($this->_field_data[$field]['error']))
		{
			return (empty($default)) ? '' : $this->_help_prefix.$default.$this->_help_suffix;
		}

		/**
		 * Form error prefix & suffix.
		 * @since   2.16
		 */
		empty($prefix) && $prefix = $this->_error_prefix;
		empty($suffix) && $suffix = $this->_error_suffix;

		return $prefix.$this->_field_data[$field]['error'].$suffix;
	}

	// --------------------------------------------------------------------

	/**
	 * Return form validation errors in custom HTML list.
	 * Default: unordered list.
	 * @access 	public
	 * @return 	string 	if found, else empty string.
	 */
	public function validation_errors_list()
	{
		$errors = parent::error_string('<li>', '</li>');
		return (empty($errors)) ? '' : '<ul>'.PHP_EOL.$errors.'</ul>';
	}

	// --------------------------------------------------------------------

	/**
	 * Method for verifying captcha or google recaptcha.
	 * @access 	public
	 * @param 	string 	$str 	The user-entered captcha code.
	 * @return 	bool
	 */
	public function check_captcha($str)
	{
		// Captcha disabled? Why are we even calling this.
		if ( ! $this->CI->config->item('use_captcha'))
		{
			return true;
		}
		// Using Google reCAPTCHA?
		elseif ($this->CI->config->item('use_recaptcha'))
		{
			isset($this->CI->curl) OR $this->CI->load->library('curl');

			$cURL = $this->CI->curl->post('https://www.google.com/recaptcha/api/siteverify', array(
				'secret'   => $this->CI->config->item('recaptcha_private_key'),
				'response' => $str,
				'remoteip' => ip_address()
			));

			if ( ! $cURL->is_success() OR ! ($response = json_decode($cURL->response)))
			{
				$this->set_message('_check_captcha', $this->CI->lang->line('error_unknown'));

				return false;
			}
			elseif ( ! $response->success)
			{
				$this->set_message('_check_captcha', $this->CI->lang->line('form_validation_required'));

				return false;
			}
			else
			{
				return true;
			}
		}

		// No captcha set?
		elseif (empty($str))
		{
			$this->set_message('_check_captcha', $this->CI->lang->line('form_validation_required'));
			return false;
		}

		// First, we delete old captcha
		(isset($this->CI->captcha)) OR $this->CI->load->library('captcha');
		if ($this->CI->captcha->validate($str))
		{
			return true;
		}
		// Not found? Generate the error.
		else
		{
			$this->set_message('_check_captcha', $this->CI->lang->line('error_captcha'));
			return false;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric w/ spaces
	 *
	 * @param   string
	 * @return  bool
	 */
	public function alpha_spaces($str)
	{
		static $regex = '/^[\w\d\s]*$/';
		return (bool) preg_match($regex, $str);
	}

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric with dashes and spaces.
	 *
	 * @param 	string 	$str
	 * @return 	bool
	 */
	public function alpha_dash_space($str)
	{
		static $regex = '/^[a-zA-Z0-9_\-\s]+$/';
		return (bool) preg_match($regex, $str);
	}

	// --------------------------------------------------------------------

	/**
	 * Allow alpha-numeric characters with periods, underscores,
	 * spaces and dashes.
	 * @access 	public
	 * @param 	string 	$str 	The string to check.
	 * @return 	bool
	 */
	public function alpha_extra($str)
	{
		static $regex = "/^([\.\s-a-z0-9_-])+$/i";
		return (bool) preg_match($regex, $str);
	}

	// --------------------------------------------------------------------

	/**
	 * Make sure the entered username is unique.
	 *
	 * @access 	public
	 * @param 	string 	$str 	the usernme to check.
	 * @return 	bool
	 */
	public function unique_username($str, $skip = null)
	{
		/**
		 * Check for reserved modules names,
		 * back-end and front-end context.
		 * @since 2.16
		 */
		if (KPlatform::is_protected_module($str) OR in_array($str, KPlatform::contexts()))
		{
			return false;
		}

		if ( ! str_to_bool($skip) && is_forbidden_username($str))
		{
			return false;
		}

		return parent::is_unique($str, 'entities.username');
	}

	// --------------------------------------------------------------------

	/**
	 * Make sure the selected email address is unique.
	 * @access 	public
	 * @param 	string 	$str 	the email address to check.
	 * @return 	bool
	 */
	public function unique_email($str)
	{
		if ($str !== $this->CI->auth->user('email'))
		{
			return (parent::is_unique($str, 'users.email')
				&& parent::is_unique($str, 'metadata.value')
				&& parent::is_unique($str, 'variables.params'));
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Make sure the user exists using ID, username or email address.
	 *
	 * @since 	1.0
	 * @since 	1.33 	Update the check method.
	 *
	 * @access 	public
	 * @param 	string 	$str
	 * @return 	bool
	 */
	public function user_exists($str)
	{
		return (false !== $this->CI->users->get($str));
	}

	// --------------------------------------------------------------------

	/**
	 * user_admin
	 *
	 * Method for making sure the user trying to login is an admin.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	2.0
	 *
	 * @access 	public
	 * @param 	mixed 	User username or email address.
	 * @return 	bool
	 */
	public function user_admin($str)
	{
		if (false !== ($user = $this->CI->users->get($str))
			&& isset($this->CI->auth->levels[$user->subtype])
			&& $this->CI->auth->levels[$user->subtype] >= KB_LEVEL_ACP)
		{
			return true;
		}

		$this->_is_login() && $this->CI->auth->failed();
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Check user's credentials on login page.
	 * @access 	public
	 * @param 	string 	$password
	 * @param 	string 	$login 	The login field (username or email)
	 * @return 	bool
	 */
	public function check_credentials($password, $login)
	{
		if ( ! ($user = $this->CI->users->get($this->CI->input->post($login, true))))
		{
			$this->CI->auth->failed();
			return false;
		}

		(isset($this->CI->hash)) OR $this->CI->load->library('hash');

		if ( ! $this->CI->hash->check_password($password, $user->password))
		{
			$this->CI->auth->failed($user);
			return false;
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks user's authentication code.
	 * @access 	public
	 * @param 	string 	$str 	User's input authentication code.
	 * @return 	bool
	 */
	public function check_2fa($str)
	{
		$user_id = $this->CI->session->userdata(SESS_USER_2FA);
		if ( ! $this->CI->auth->check_auth_code($user_id, $str))
		{
			// Failed login attempt if needed.
			$this->_is_login() && $this->CI->auth->failed($user_id);
			return false;
		}
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks user's current password.
	 * @access 	public
	 * @param 	string 	$str 	The current password.
	 * @return 	bool
	 */
	public function current_password($str)
	{
		/**
		 * 1. The user is logged in.
		 * 2. The password is correct.
		 */
		if ( ! ($user = $this->CI->auth->user()))
		{
			return false;
		}

		(isset($this->CI->hash)) OR $this->CI->load->library('hash');
		return $this->CI->hash->check_password($str, $user->password);
	}

	// --------------------------------------------------------------------

	/**
	 * Required if another field has a value.
	 * @access 	public
	 * @param 	string 	$str
	 * @param 	string 	$field
	 * @return 	bool
	 */
	public function required($str, $field = null)
	{
		// The other field is empty?
		if (is_string($field) && empty($this->CI->input->post($field)))
		{
			return true;
		}

		// Let the parent do the rest.
		return parent::required($str);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original min_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function min_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);
		return parent::min_length($str, $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original max_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function max_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);
		return parent::max_length($str, $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original exact_length to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function exact_length($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);
		return parent::exact_length($str, $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original greater_than to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function greater_than($str, $min)
	{
		$min = (is_numeric($min)) ? $min : $this->CI->config->item($min);
		return parent::greater_than($str, $min);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original greater_than_equal_to to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function greater_than_equal_to($str, $min)
	{
		$min = (is_numeric($min)) ? $min : $this->CI->config->item($min);
		return parent::greater_than_equal_to($str, $min);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original less_than to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function less_than($str, $max)
	{
		$max = (is_numeric($max)) ? $max : $this->CI->config->item($max);
		return parent::less_than($str, $max);
	}

	// --------------------------------------------------------------------

	/**
	 * Edit original less_than_equal_to to use items from config
	 * @access 	public
	 * @param 	string 	$str 	The string to check
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function less_than_equal_to($str, $max)
	{
		$max = (is_numeric($max)) ? $max : $this->CI->config->item($max);
		return parent::less_than_equal_to($str, $max);
	}

	// --------------------------------------------------------------------

	/**
	 * Makes sure the input is not in the given array.
	 * @since 	2.12
	 * @access 	public
	 * @param 	string 	$value 	The value to check.
	 * @param 	string 	$list 	The list used to check.
	 * @return 	bool 	true if not found in the list, else false.
	 */
	public function not_in_list($value, $list)
	{
		return ( ! in_array($value, explode(',', $list), true));
	}

	// --------------------------------------------------------------------

	/**
	 * Check if the input value exists in the specified database field.
	 * @since 	2.16
	 * @param   string  $str
	 * @param   string  $field
	 * @return  bool
	 */
	public function is_found($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->db)
			? ($this->CI->db->limit(1, 0)->get_where($table, array($field => $str))->num_rows() > 0)
			: true;
	}

	// --------------------------------------------------------------------

	/**
	 * validates phone numbers.
	 *
	 * @since 	2.16
	 *
	 * @param 	string
	 * @return 	bool
	 */
	public function valid_phone($str)
	{
		static $regex = '/^\+?[0-9\s\-\(\)]+$/';
		return (bool) preg_match($regex, $str);
	}

	// --------------------------------------------------------------------

	/**
	 * Validates minimum number of words.
	 *
	 * @param 	string 	$str 	The string to check.
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function min_words($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);

		return (str_word_count(strip_tags($str)) < $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Validates maximum number of words.
	 *
	 * @param 	string 	$str 	The string to check.
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function max_words($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);

		return (str_word_count(strip_tags($str)) > $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Validates exact number of words.
	 *
	 * @param 	string 	$str 	The string to check.
	 * @param 	mixed 	$val 	Integer or string from config.
	 * @return 	bool
	 */
	public function exact_words($str, $val)
	{
		$val = (is_numeric($val)) ? $val : $this->CI->config->item($val);

		return (str_word_count(strip_tags($str)) === (int) $val);
	}

	// --------------------------------------------------------------------

	/**
	 * Build an error message using the field and param with the possibility
	 * to have $param stored in config.
	 * @param	string	The error message line
	 * @param	string	A field's human name
	 * @param	mixed	A rule's optional parameter
	 * @return	string
	 */
	protected function _build_error_msg($line, $field = '', $param = '')
	{
		// Look for $param in config.
		(is_string($param) && $nparam = $this->CI->config->item($param)) && $param = $nparam;

		// Let the parent do the rest.
		return parent::_build_error_msg($line, $field, $param);
	}

	// --------------------------------------------------------------------
	// Private Methods.
	// --------------------------------------------------------------------

	/**
	 * Checks whether the current request is done on a login section.
	 *
	 * @param 	none
	 * @return 	bool
	 */
	private function _is_login()
	{
		$uri_string = $this->CI->uri->uri_string();
		return ($uri_string === Route::named('login') OR $uri_string === Route::named('admin-login'));
	}

}
