<?php
defined('BASEPATH') OR die;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

/**
 * Member login (username)
 * @since 2.16
 */
$config['auth.login_username'] = array(
	// username
	array(
		'field' => 'username',
		'label' => 'lang:username',
		'rules' => 'trim|required|alpha_numeric|min_length[username_min]|max_length[username_max]|user_exists'
	),
	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]|check_credentials[username]'
	)
);

/**
 * Member login (email)
 * @since 2.16
 */
$config['auth.login_email'] = array(
	// email address
	array(
		'field' => 'email',
		'label' => 'lang:email_address',
		'rules' => 'trim|required|valid_email|user_exists'
	),
	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]|check_credentials[email]'
	)
);

/**
 * Member login (both)
 * @since 2.16
 */
$config['auth.login_identity'] = array(
	// username/email address
	array(
		'field' => 'identity',
		'label' => 'lang:username_or_email',
		'rules' => 'trim|required|min_length[username_min]|user_exists'
	),
	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]|check_credentials[identity]'
	)
);

/**
 * Two-factor authentication code.
 * @since 2.91
 */
$config['auth.login_2fa'] = array(array(
	'field' => 'tfa',
	'label' => 'lang:auth_code',
	'rules' => 'trim|required|numeric|exact_length[6]|check_2fa'
));

/**
 * User registration.
 * @since 2.16
 */
$config['auth.register'] = array(
	// first name
	array(
		'field' => 'first_name',
		'label' => 'lang:first_name',
		'rules' => 'trim|required|alpha_spaces|min_length[first_name_min]|max_length[first_name_max]'
	),

	// last name
	array(
	 'field' => 'last_name',
	 'label' => 'lang:last_name',
	 'rules' => 'trim|required|alpha_spaces|min_length[last_name_min]|max_length[last_name_max]'
	),

	// email address
	array(
		'field' => 'email',
		'label' => 'lang:email',
		'rules' => 'trim|required|valid_email|unique_email'
	),

	// username
	array(
		'field' => 'username',
		'label' => 'lang:username',
		'rules' => 'trim|required|min_length[username_min]|max_length[username_max]|alpha_numeric|unique_username'
	),

	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]'
	),

	// confirm password
	array(
		'field' => 'cpassword',
		'label' => 'lang:confirm_password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]|matches[password]'
	)
);

/**
 * Resend activation link.
 * @since 2.16
 */
$config['auth.resend'] = array(
	// identity
	array(
		'field' => 'identity',
		'label' => 'lang:username_or_email',
		'rules' => 'trim|required|min_length[username_min]'
	)
);

/**
 * Restore account.
 * @since 2.16
 */
$config['auth.restore'] = array(
	// identity.
	array(
		'field' => 'identity',
		'label' => 'lang:username_or_email',
		'rules' => 'trim|required|min_length[username_min]'
	),

	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]'
	)
);

/**
 * Password recovery.
 * @since 2.16
 */
$config['auth.recover'] = array(
	// Identity
	array(
		'field' => 'identity',
		'label' => 'lang:username_or_email',
		'rules' => 'trim|required|min_length[username_min]|user_exists'
	)
);

/**
 * Password reset.
 * @since 2.16
 */
$config['auth.reset'] = array(
	// new password
	array(
		'field' => 'npassword',
		'label' => 'lang:new_password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]'
	),
	// confirm password
	array(
		'field' => 'cpassword',
		'label' => 'lang:confirm_password',
		'rules' => 'trim|required|min_length[password_min]|max_length[password_max]|matches[npassword]'
	)
);

/**
 * Admin login.
 * @since 2.16
 */
$config['admin.login'] = array(
	// username
	array(
		'field' => 'username',
		'label' => 'lang:username',
		'rules' => 'required|min_length[username_min]|max_length[username_max]|user_exists|user_admin'
	),
	// password
	array(
		'field' => 'password',
		'label' => 'lang:password',
		'rules' => 'required|min_length[password_min]|max_length[password_max]|check_credentials[username]'
	)
);
