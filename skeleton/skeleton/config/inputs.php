<?php
defined('BASEPATH') OR die;

/**
 * This configuration file holds all possible form inputs and is 
 * loaded every time you use the "prep_form" method in your 
 * controllers. This way, no need to declare array of form inputs, 
 * you simple need to use like so:
 *
 * @example
 *
 * $data['username'] = $this->config->item('username', 'inputs')
 *
 * Then pass the data to view load (or theme render method) and you 
 * can later our provided function "print_input" that accepts extra 
 * parameter, array of attributes. For instance, in your view:
 *
 * echo print_input($username, array('class' => 'form-control'))
 *
 * @since 1.0
 */

// Username field.
$config['username'] = array(
	'name'        => 'username',
	'id'          => 'username',
	'placeholder' => 'lang:username'
);

// Identity field.
$config['identity'] = array(
	'name'        => 'identity',
	'id'          => 'identity',
	'placeholder' => 'lang:username_or_email'
);

// Remember me field.
$config['remember'] = array(
	'type'  => 'checkbox',
	'name'  => 'remember',
	'id'    => 'remember',
	'value' => '1'
);

// Two-factor authentication code.
$config['tfa'] = array(
	'name'        => 'tfa',
	'id'          => 'tfa',
	'placeholder' => 'lang:auth_code',
);

// --------------------------------------------------------------------
// Passwords fields.
// --------------------------------------------------------------------

// Password field.
$config['password'] = array(
	'type'         => 'password',
	'name'         => 'password',
	'id'           => 'password',
	'autocomplete' => 'off',
	'placeholder'  => 'lang:password'
);

// Confirm field.
$config['cpassword'] = array(
	'type'         => 'password',
	'name'         => 'cpassword',
	'id'           => 'cpassword',
	'autocomplete' => 'off',
	'placeholder'  => 'lang:confirm_password'
);

// New password field.
$config['npassword'] = array(
	'type'         => 'password',
	'name'         => 'npassword',
	'id'           => 'npassword',
	'autocomplete' => 'off',
	'placeholder'  => 'lang:new_password'
);

// Current password field.
$config['opassword'] = array(
	'type'         => 'password',
	'name'         => 'opassword',
	'id'           => 'opassword',
	'autocomplete' => 'off',
	'placeholder'  => 'lang:current_password'
);

// --------------------------------------------------------------------
// Email addresses fields.
// --------------------------------------------------------------------

// Email field.
$config['email'] = array(
	'type'        => 'email',
	'name'        => 'email',
	'id'          => 'email',
	'placeholder' => 'lang:email_address'
);

// New email field.
$config['nemail'] = array(
	'type'        => 'email',
	'name'        => 'nemail',
	'id'          => 'nemail',
	'placeholder' => 'lang:new_email_address'
);

// --------------------------------------------------------------------
// User profile fields.
// --------------------------------------------------------------------

// First name field.
$config['first_name'] = array(
	'name'        => 'first_name',
	'id'          => 'first_name',
	'placeholder' => 'lang:first_name'
);

// Last name field.
$config['last_name'] = array(
	'name'        => 'last_name',
	'id'          => 'last_name',
	'placeholder' => 'lang:last_name'
);

// Gravatar checkbox.
$config['gravatar'] = array(
	'type'  => 'checkbox',
	'name'  => 'gravatar',
	'id'    => 'gravatar',
	'value' => '1'
);

// Role field
$config['role'] = array(
	'type' => 'dropdown',
	'name' => 'subtype',
	'id' => 'subtype',
	'options' => array(
		'regular' => 'lang:role_regular',
		'author'  => 'lang:role_author',
		'editor'  => 'lang:role_editor',
		'manager' => 'lang:role_manager',
		'admin'   => 'lang:role_admin',
		'owner'   => 'lang:role_owner',
	)
);

// Gender field.
$config['gender'] = array(
	'type' => 'dropdown',
	'name' => 'gender',
	'id' => 'gender',
	'options' => array(
		'unspecified' => 'lang:unspecified',
		'male'        => 'lang:male',
		'female'      => 'lang:female',
	)
);

// Address field.
$config['address'] = array(
	'type'        => 'text',
	'name'        => 'address',
	'id'          => 'address',
	'placeholder' => 'lang:address'
);

// City field.
$config['city'] = array(
	'type'        => 'text',
	'name'        => 'city',
	'id'          => 'city',
	'placeholder' => 'lang:city'
);

// Zipcode field.
$config['zipcode'] = array(
	'type'        => 'text',
	'name'        => 'zipcode',
	'id'          => 'zipcode',
	'placeholder' => 'lang:zip_code'
);

// State field.
$config['state'] = array(
	'type'        => 'text',
	'name'        => 'state',
	'id'          => 'state',
	'placeholder' => 'lang:state'
);

// Company field.
$config['company'] = array(
	'type'        => 'text',
	'name'        => 'company',
	'id'          => 'company',
	'placeholder' => 'lang:company'
);

// Phone field.
$config['phone'] = array(
	'type'        => 'tel',
	'name'        => 'phone',
	'id'          => 'phone',
	'placeholder' => 'lang:phone_num'
);

// Location field.
$config['location'] = array(
	'type'        => 'text',
	'name'        => 'location',
	'id'          => 'location',
	'placeholder' => 'lang:location'
);

// Website field.
$config['website'] = array(
	'type'        => 'text',
	'name'        => 'website',
	'id'          => 'website',
	'placeholder' => 'lang:website'
);

// --------------------------------------------------------------------
// Fields used by groups and objects.
// --------------------------------------------------------------------

// Name fields (form groups and objects).
$config['name'] = array(
	'name'        => 'name',
	'id'          => 'name',
	'placeholder' => 'lang:name'
);

// Title (same as name field).
$config['title'] = array(
	'name'        => 'name',
	'id'          => 'name',
	'placeholder' => 'lang:title'
);

// Elements slug.
$config['slug'] = array(
	'name'         => 'username',
	'id'           => 'username',
	'autocomplete' => 'off',
	'placeholder'  => 'lang:slug'
);

// Elements content.
$config['content'] = array(
	'type'        => 'textarea',
	'name'        => 'content',
	'id'          => 'content',
	'placeholder' => 'lang:content'
);

// Description field.
$config['description'] = array(
	'type'        => 'textarea',
	'name'        => 'description',
	'id'          => 'description',
	'placeholder' => 'lang:description'
);

// Except field.
$config['except'] = array(
	'type'        => 'textarea',
	'name'        => 'description',
	'id'          => 'description',
	'placeholder' => 'lang:excerpt'
);

// Used by menu items.
$config['href'] = array(
	'name'        => 'href',
	'id'          => 'href',
	'placeholder' => 'lang:url'
);

// Menu order.
$config['order'] = array(
	'type'        => 'number',
	'name'        => 'order',
	'id'          => 'order',
	'placeholder' => 'lang:order'
);

// Privacy.
$config['privacy'] = array(
	'type'    => 'dropdown',
	'name'    => 'privacy',
	'id'      => 'privacy',
	'options' => array(
		'2' => 'lang:public',
		'1' => 'lang:private',
		'0' => 'lang:hidden',
	)
);

// Message.
$config['message'] = array(
	'type'        => 'textarea',
	'name'        => 'message',
	'id'          => 'message',
	'placeholder' => 'lang:message'
);

// File.
$config['file'] = array(
	'type'        => 'file',
	'name'        => 'file',
	'id'          => 'file',
	'placeholder' => 'lang:file'
);

// Language
$config['language'] = array(
	'type' => 'dropdown',
	'name' => 'language',
	'id'   => 'language'
);

// Timezone
$config['timezone'] = array(
	'type' => 'dropdown',
	'name' => 'timezone',
	'id'   => 'timezone'
);

// --------------------------------------------------------------------
// SEO Fields.
// --------------------------------------------------------------------

// Meta title.
$config['meta_title'] = array(
	'name'        => 'meta_title',
	'id'          => 'meta_title',
	'placeholder' => 'lang:meta_title',
	'maxlength'   => '70'
);

// Meta description
$config['meta_description'] = array(
	'type'        => 'textarea',
	'name'        => 'meta_description',
	'id'          => 'meta_description',
	'placeholder' => 'lang:meta_description',
	'maxlength'   => '160'
);

// Meta keywords.
$config['meta_keywords'] = array(
	'name'        => 'meta_keywords',
	'id'          => 'meta_keywords',
	'placeholder' => 'lang:meta_keywords',
	'maxlength'   => '255'
);
