<?php
defined('BASEPATH') OR die;

/**
 * KB_User Class
 *
 * Core class used to implement the KB_User object.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries\Kbcore
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.30
 */
class KB_User extends KB_Entity
{
	/**
	 * Driver to use.
	 * @var string
	 */
	protected $driver = 'users';

	/**
	 * User's full name.
	 * @var string
	 */
	public $full_name;

	/**
	 * User's avatar, md5 of email.
	 * @var string
	 */
	public $avatar;

	/**
	 * User's avatar URL.
	 * @var string
	 */
	public $avatar_url;

	/**
	 * User's gravatar URL or false.
	 * @var mixed
	 */
	public $gravatar = false;

	/**
	 * User's role key.
	 * @var string
	 */
	public $role;

	/**
	 * User's role name language line.
	 * @var string
	 */
	public $role_name;

	/**
	 * User's access level.
	 * @var int
	 */
	public $level = 0;

	/**
	 * Whether the user is admin.
	 * @var bool
	 */
	public $admin = false;

	/**
	 * User's IP address anchor.
	 * @var string
	 */
	public $ip_anchor;

	/**
	 * A flag used to check if the user is banned.
	 * @var bool
	 */
	public $banned = false;

	/**
	 * A flag used to check if the user is locked.
	 * @var bool
	 */
	public $locked = false;

	/**
	 * Constructor.
	 *
	 * Retrieves the user data and passes it to KB_User::init().
	 *
	 * @param   mixed    $id    User's ID, username or email.
	 * @return  void
	 */
	public function __construct($id = 0)
	{
		if ($id instanceof KB_User)
		{
			$this->init($id->data);
			return;
		}
		elseif (is_object($id))
		{
			$this->init($id);
			return;
		}

		if (is_numeric($id) && 0 < $id = (int) $id)
		{
			$data = get_instance()->users->get_by('id', $id);
		}
		elseif (is_string($id))
		{
			$data = get_instance()->users->get_by('username', $id);
			empty($data) && $data = get_instance()->users->get_by('email', $id);
		}

		if (isset($data))
		{
			$this->init($data);
		}
		else
		{
			$this->data = (object) array();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Sets up object properties.
	 *
	 * @param   object
	 */
	public function init($data)
	{
		$this->id = (int) $data->id;

		// Invalid? nothing to do...
		if (0 >= $this->id)
		{
			return;
		}
		else
		{
			$this->data = $data;
		}

		// Things that should be integers.
		$this->data->id         = $this->id;
		$this->data->guid       = $this->id;
		$this->data->privacy    = (int) $this->data->privacy;
		$this->data->enabled    = (int) $this->data->enabled;
		$this->data->deleted    = (int) $this->data->deleted;
		$this->data->created_at = (int) $this->data->created_at;
		$this->data->updated_at = (int) $this->data->updated_at;
		$this->data->deleted_at = (int) $this->data->deleted_at;

		// Set "locked" flag.
		if ($this->data->enabled === -2)
		{
			$this->locked = true;
		}
		// Set "banned" flag.
		elseif ($this->data->enabled === -1)
		{
			$this->banned = true;
		}

		// Things that should be sanitized before output.
		$this->data->username   = html_escape($this->data->username, false);
		$this->data->email      = html_escape($this->data->email, false);
		$this->data->first_name = html_escape($this->data->first_name, false);
		$this->data->last_name  = html_escape($this->data->last_name, false);

		// User's full name.
		isset($this->full_name) OR $this->_set_full_name();

		// Initiliaz user's avatar.
		isset($this->avatar) OR $this->avatar = md5(empty($this->data->email) ? 0 : $this->data->email);

		// Add user's role to the object.
		if ( ! isset($this->role))
		{
			$this->role      = $this->data->subtype;
			$this->role_name = 'role_'.$this->data->subtype;
		}

		// Add user's level.
		if ( ! empty($level = get_instance()->auth->levels[$this->role]))
		{
			$this->level = $level;
			unset($level);
		}

		// Whether the user is an admin or not.
		$this->admin = ('admin' === $this->role OR KB_LEVEL_ADMIN <= $this->level);

		// IP anchor.
		if ( ! isset($this->ip_anchor))
		{
			$this->ip_anchor = empty($this->data->ip_address)
				? $this->data->ip_address
				: ip_anchor($this->data->ip_address, null, 'target="_blank"');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns user's avatar.
	 * @since 	2.20
	 *
	 * @param 	int 	$size
	 * @param 	mixed 	$attrs
	 */
	public function avatar($size = 100, $attrs = array())
	{
		$CI =& get_instance();

		// In case of using a URL.
		if ( ! isset($this->avatar_url) && ! empty($avatar_url = $this->get_meta('avatar_url')))
		{
			$this->avatar_url = $avatar_url;
		}

		// Has uploaded image?
		elseif ( ! isset($this->avatar_url) && is_file($CI->config->uploads_path('avatars/'.$this->avatar.'.jpg')))
		{
			$this->avatar_url = $CI->config->uploads_url('avatars/'.$this->avatar.'.jpg');
			$this->gravatar   = false;
		}

		// No avatar URL? Fallback to gravatar.
		elseif ( ! isset($this->avatar_url))
		{
			$this->avatar_url = $this->gravatar = '//www.gravatar.com/avatar/'.$this->avatar.'?r=g&amp;d=mm';
		}

		if (is_array($attrs))
		{
			$attrs['style'] = "width:{$size}px;height:{$size}px;";
			$attrs['width'] = $attrs['height'] = $size;
			isset($attrs['alt']) OR $attrs['alt'] = $this->full_name;
		}
		else
		{
			$attrs .= " style=\"width:{$size}px;height:{$size}px;\" width=\"100%\" height=\"100%\"";
			str_contains($attrs, 'alt') OR $attrs .= ' alt="'.$this->full_name.'"';
		}

		return isset($this->avatar_url) ? img($this->avatar_url, $attrs) : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Compares the user's level.
	 *
	 * @param 	int 	$level 	The level used for comparison.
	 * @param 	bool 	$equal 	Whether to acceot equal.
	 * @return 	bool 	True if the user's level is greather than (or equal).
	 */
	public function is_level($level = 0, $equal = true)
	{
		return is_numeric($level) ? ((true === $equal) ? ($this->level >= $level) : ($this->level > $level)) : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves the translation.
	 *
	 * @param 	string 	$idiom 	The language to check.
	 * @param 	bool 	$return Whether to return the ID.
	 * @return 	void
	 */
	public function after_translate($idiom = null)
	{
		$this->_set_full_name();
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method that returns localized full name.
	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->full_name;
	}

	// --------------------------------------------------------------------

	/**
	 * Simply combines first and last names to make a full name.
	 *
	 * @return 	void
	 */
	private function _set_full_name()
	{
		// Default to username.
		$this->full_name = $this->data->username;

		if ( ! empty($this->data->first_name))
		{
			$this->full_name = $this->data->first_name;
			empty($this->data->last_name) OR $this->full_name .= ' '.$this->data->last_name;
		}

	}

}
