<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_lang Class
 *
 * Handles all operations done to languages.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.18
 */
final class Kbcore_lang extends KB_Driver
{
	/**
	 * All available language details
	 * @var 	array
	 */
	protected $_details;

	/**
	 * All available site languages.
	 * @var 	array
	 */
	protected $_languages;

	/**
	 * Session key name used to store language.
	 * Default: SESS_LANG
	 * @var 	string
	 */
	protected $_session = SESS_LANG;

	/**
	 * Cookie key name to store language.
	 * defaul: 	ci_lang
	 * @var 	string
	 */
	protected $_cookie = COOK_LANG;

	/**
	 * Default site language.
	 * @var 	array
	 */
	protected $_default;

	/**
	 * Current site language.
	 * @var 	array
	 */
	protected $_current;

	/**
	 * Whether current language is Right-To-Left
	 * @var 	bool
	 */
	protected $_is_rtl;

	/**
	 * Holds the number of available languages,
	 * default excluded.
	 * @since   2.16
	 */
	public $num_languages = 0;

	/**
	 * Flag for whether the site supports multiple languages.
	 * @var 	bool
	 */
	public $polylang = false;

	/**
	 * Class initialization.
	 *
	 * @access 	private
	 * @return 	void
	 */
	public function initialize()
	{
		// Make sure to load session library
		isset($this->ci->session) OR $this->ci->load->library('session');

		// List of all available languages.
		if (empty($this->_languages))
		{
			function_exists('array_subset') OR $this->ci->load->helper('array');

			// Start with an empty array then build it up
			$this->_languages = array_subset($this->all(), $this->ci->config->item('languages'));
			$this->num_languages = count($this->_languages);
			$this->ci->config->set_item('site_languages', $this->_languages);
			$this->polylang = ($this->num_languages > 1);
		}

		/**
		 * Set the default language.
		 *
		 * Even if we set the default language, we do
		 * a small check to make sure it exists in the
		 * available languages list first, otherwise
		 * we fallback to using english... sorry!
		 */
		$this->_default = $this->exists($this->ci->config->item('language'))
			? $this->_languages[$this->ci->config->item('language')]
			: $this->_languages[array_key_first($this->_languages)];

		// Set current language
		$this->_current = $this->set_current_language();
		isset($this->_parent->language) ?: $this->_parent->language = $this->_current;

		// Change current language for user.
		$this->ci->config->set_item('language', $this->_current['folder']);

		// See if we have language files to load
		if ( ! empty($lang_files = apply_filters('language_files', $this->ci->config->item('language_files'))))
		{
			$this->ci->lang->load($lang_files);
		}

		// Add current language to Config and Theme.
		$this->ci->config->set_item('current_language', $this->_current);

		// Load language names language files.
		$this->polylang && $this->ci->lang->load('lang');
	}

	// --------------------------------------------------------------------

	/**
	 * Attempts to find the language by the given field and match.
	 *
	 * @access 	public
	 * @param 	string 	$field
	 * @param 	string 	$match
	 * @return 	mixed 	array of the language if found, else null
	 */
	public function find_by($field = 'folder', $match = 'english')
	{
		foreach ($this->_languages as $lang)
		{
			if (isset($lang[$field]) && $match === $lang[$field])
			{
				return $lang;
			}
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Method used to check the given language is available.
	 *
	 * @access 	public
	 * @param 	string 	$idiom
	 * @return 	bool
	 */
	public function exists($idiom)
	{
		if (empty($idiom) OR ! is_string($idiom) OR ! isset($this->_languages))
		{
			return false;
		}
		else
		{
			$idiom = strtolower(trim($idiom));
		}

		return isset($this->_languages[$idiom]);
	}

	// --------------------------------------------------------------------
	// CLIENT LANGUAGE
	// --------------------------------------------------------------------

	/**
	 * Guesses current client's language.
	 *
	 * @access 	private
	 * @param 	none
	 * @return 	array
	 */
	private function guess_current_language()
	{
		if (empty($folder = $this->ci->input->cookie($this->_cookie, true)))
		{
			$folder = isset($_SESSION[$this->_session]) ? $_SESSION[$this->_session] : null;
		}

		// so far nothing?
		if (empty($folder))
		{
			// search client's preferred language.
			$codes = array_map(array($this, '_return_code'), $this->_details);
			$prefered_code = prefered_language(array_values($codes));

			$folder = (false === ($lang = array_search($prefered_code, $codes)) OR ! isset($this->_details[$lang]))
				? $this->_default['folder']
				: $lang;
		}

		return $folder;
	}

	/**
	 * Used for 'array_map' to return language code.
	 *
	 * @access 	private
	 * @param 	array 	$info
	 * @return 	strign
	 */
	private function _return_code($info)
	{
		return $info['code'];
	}

	// --------------------------------------------------------------------
	// CURRENT & DEFAULT LANGUAGES
	// --------------------------------------------------------------------

	/**
	 * Sets current language.
	 *
	 * @access 	private
	 * @param 	none
	 * @return 	array
	 */
	private function set_current_language()
	{
		// No need to check for cookie or session if the website
		// supports only a single language.
		if ($this->num_languages <= 1)
		{
			return $this->_default;
		}

		$sess_lang = $this->_default['folder'];

		$found_in_session = false;
		$found_in_cookies = false;

		// Check if the session cookie exists.
		if ( ! empty($folder = $this->ci->input->cookie($this->_cookie, true)))
		{
			$sess_lang = $folder;
			$found_in_cookies = true;
		}
		// Not found in cookies? Check session.
		elseif (isset($_SESSION[$this->_session]) && ! empty($folder = $_SESSION[$this->_session]))
		{
			$sess_lang = $folder;
			$found_in_session = true;
		}
		// Otherwise, use clients
		else
		{
			$sess_lang = $this->guess_current_language();
		}

		// User logged in and has different language? use it...
		empty($user_lang = $this->_parent->auth->user('language')) OR $sess_lang = $user_lang;

		// See if the language exists, otherwise use default.
		$current = isset($this->_languages[$sess_lang]) ? $this->_languages[$sess_lang] : $this->_default;

		// Set the session
		if ( ! $found_in_session OR $sess_lang !== $current['folder'])
		{
			$_SESSION[$this->_session] = $current['folder'];
		}

		// Set the session
		if ( ! $found_in_cookies OR $sess_lang !== $current['folder'])
		{
			$this->ci->input->set_cookie($this->_cookie, $current['folder'], 2678400);
		}

		// Unset what we don't need
		unset($sess_lang, $found_in_session, $found_in_cookies);

		return $current;
	}

	/**
	 * Returns current language data.
	 *
	 * @access 	public
	 * @param 	string 	$key
	 * @return 	mixed
	 */
	public function current($key = null)
	{
		if (true === $key)
		{
			return $this->ci->lang->idiom;
		}
		elseif (empty($key))
		{
			return $this->_current;
		}
		else
		{
			return isset($this->_current[$key]) ? $this->_current[$key] : null;
		}
	}

	/**
	 * Returns default language data.
	 *
	 * @access 	public
	 * @param 	string 	$key
	 * @return 	mixed
	 */
	public function default($key = null)
	{
		if (true === $key)
		{
			return $this->_default['folder'];
		}
		elseif (empty($key))
		{
			return $this->_default;
		}
		else
		{
			return isset($this->_default[$key]) ? $this->_default[$key] : null;
		}
	}

	/**
	 * Returns selected or current language data.
	 *
	 * @access 	public
	 * @return 	mixed
	 */
	public function info($key = null, $idiom = null)
	{
		$idiom = $this->exists($idiom) ? $this->_languages[$idiom] : $this->_current;
		return empty($key) ? $idiom : (isset($idiom[$key]) ? $idiom[$key] : null);
	}

	// --------------------------------------------------------------------

	/**
	 * Simply returns true if the selected language is Right-To-Left
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool 	true if it is rtl, else false.
	 */
	public function is_rtl()
	{
		if ( ! isset($this->_is_rtl))
		{
			$this->_is_rtl = ($this->_current['direction'] === 'rtl');
			$this->_is_rtl && $this->_is_rtl = ($this->ci->uri->uri_string() !== Route::named('admin-login'));
		}

		return $this->_is_rtl;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the array of all languages details
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	public function all()
	{
		if ( ! isset($this->_details))
		{
			// User's file has the priority.
			if (is_file(APPPATH.'third_party/languages.php'))
			{
				$details = require_once(APPPATH.'third_party/languages.php');
			}

			// If not found, use our.
			if (empty($details) && is_file(KBPATH.'third_party/bkader/inc/languages.php'))
			{
				$details = require_once(KBPATH.'third_party/bkader/inc/languages.php');
			}

			// Otherwise, use an empty array.
			if (empty($details))
			{
				$details = array();
			}

			// keep only valid and available languages.
			$this->_details = array_filter($details, array($this, 'is_valid'), ARRAY_FILTER_USE_KEY);

			// sort by English name.
			uasort($this->_details, fn($a, $b) => strcmp($a['name_en'], $b['name_en']));
		}

		return $this->_details;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of selected language(s) or all available langauges.
	 *
	 * @access 	public
	 * @param 	mixed 	language folder or array of folders.
	 * @return 	mixed
	 */
	public function languages($folder = null)
	{
		// Nothing provided? Return all languages.
		if (empty($folder))
		{
			return $this->_languages;
		}

		// Multiple languages selected?
		if (is_array($folder))
		{
			$idioms = array();

			foreach ($folder as $f)
			{
				$this->exists($f) && $idioms[$f] = $this->_languages[$f];
			}

			return empty($idioms) ? null : $idioms;
		}

		// A single one?
		return isset($this->_languages[$folder]) ? $this->_languages[$folder] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of available languages other than the excluded one.
	 *
	 * @access 	public
	 * @param 	string 	$exclude 	What to exclude.
	 * @return 	array
	 */
	public function others($exclude = null)
	{
		empty($exclude) && $exclude = 'current';

		if ($idioms = $this->ci->registry->get($exclude, 'i18n_others'))
		{
			return $idioms;
		}

		switch ($exclude)
		{
			case 'default':
				$idioms = array_except($this->_languages, $this->_default['folder']);
				break;

			case 'current':
				$idioms = array_except($this->_languages, $this->_current['folder']);
				break;

			case $this->exists($exclude):
				$idioms = array_except($this->_languages, $exclude);
				break;

			default:
				return false;
		}

		$this->ci->registry->add($exclude, $idioms, 'i18n_others');
		return $idioms;
	}

	// --------------------------------------------------------------------

	/**
	 * Handles changing language.
	 *
	 * @access 	public
	 * @param 	string 	$folder
	 * @return 	bool
	 */

	public function change($folder = 'english', $update_user = true)
	{
		if ( ! $this->exists($folder))
		{
			return false;
		}

		$_SESSION[$this->_session] = $folder;
		$this->ci->input->set_cookie($this->_cookie, $folder);

		// Update user table.
		if ($update_user && $this->_parent->auth->online())
		{
			$this->ci->db
				->set('language', $folder)
				->where('id', $this->_parent->auth->user_id())
				->update('entities');
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Validates language. A valid language is a language that CodeIgniter
	 * Skeleton is translated to.
	 *
	 * @return 	bool
	 */
	protected function is_valid($folder)
	{
		return (is_dir(KBPATH.'language/'.$folder) && is_dir(APPPATH.'language/'.$folder));
	}

	// --------------------------------------------------------------------

	/**
	 * Displays admin dashboard menu.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_languages()
	{
		echo admin_anchor('languages', $this->ci->lang->line('languages'), 'class="dropdown-item"');
	}

	// --------------------------------------------------------------------

	/**
	 * Counts users and displays the info box on the dashboard index.
	 * @since 	2.54
	 *
	 * @return 	void
	 */
	public function _stats_admin()
	{
		if ($this->num_languages > 0)
		{
			echo info_box(
				$this->num_languages, $this->ci->lang->line('languages'),
				'globe', $this->ci->config->admin_url('languages'),
				'teal', 'div', 'class="col"'
			);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Displays menus and other stuff on dashboard.
	 * @since 	2.53
	 *
	 * @param 	bool 	$is_homepage 	Whether we are on dashboard index.
	 * @return 	void
	 */
	public function for_dashboard($is_homepage = false)
	{
		if (true !== $this->_parent->auth->is_level(KB_LEVEL_ADMIN))
		{
			return;
		}

		add_action('extensions_menu', array($this, '_menu_languages'), 99);
		$is_homepage && add_action('admin_index_stats', array($this, '_stats_admin'), 95);
	}

	// --------------------------------------------------------------------

	/**
	 * Builds an array of languages used for select dropdown.
	 *
	 * @param 	bool 	$available 	To list available languages or all.
	 * @return 	array
	 */
	public function list($available = true)
	{
		foreach (($available ? $this->languages() : $this->all()) as $idiom => $info)
		{
			$list[$idiom] = sprintf('%s (%s)', $this->ci->lang->line($info['id']), $info['name_en']);
		}

		return $list;
	}

	// --------------------------------------------------------------------

	/**
	 * Looks into any given string for anything between curly brackets
	 * and replaces it with the corresponding line.
	 *
	 * @uses 	self::_parse_internal
	 *
	 * @param 	string 	$string 	The string to parse.
	 * @param 	mixed 	$callback 	An optional callback to use on final output.
	 * @return 	string
	 */
	public function parse(string $string, $callback = null)
	{
		$string = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', array($this, '_parse_internal'), $string);

		return ($callback && is_callable($callback)) ? call_user_func($callback, $string) : $string;
	}

	// --------------------------------------------------------------------

	/**
	 * Takes an array of matches and return the equivalent language line.
	 *
	 * @param 	array
	 * @return 	string
	 */
	private function _parse_internal($matches)
	{
		switch ($key = $matches[1]) {
			/**
			 * Since we are also parsing sent emails via our application
			 * we make sure to ignore the following lines because they
			 * are reserved for user info.
			 */
			case 'name':
			case 'email':
			case 'first_name':
			case 'last_name':
			case 'firstname':
			case 'lastname':
				return $matches[0];

			/**
			 * The following lines are translated on Kbcore_theme:
			 * 	- site_name
			 * 	- site_description
			 * 	- site_author
			 */
			case 'site_name':
				return $this->_parent->theme->site_name;

			case 'site_description':
				return $this->_parent->theme->site_description;

			case 'site_author':
				return $this->_parent->theme->site_author;

			/**
			 * We can also paste site URL or anchor.
			 * 	- site_url
			 * 	- site_anchor
			 */
			case 'site_url':
			case 'site_link':
				return $this->ci->config->site_url();

			case 'site_anchor':
				return anchor('', $this->_parent->theme->site_name);

			/**
			 * If nothing matches the above conditions, we simply
			 * translate any match we find.
			 */
			default:
				return $this->ci->lang->line($key, null, false);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the current date and time using IntlDateFormatter.
	 *
	 * @param 	int 	$time 		The time used to create the date.
	 * @param 	string 	$pattern 	The patter to use.
	 * @return 	string
	 */
	public function date($time = TIME, $pattern = 'EEEE, dd MMMM yyyy @ H:mm')
	{
		static $formatter;

		if ( ! isset($formatter))
		{
			$formatter = new IntlDateFormatter(
				$this->_current['locale'],
				IntlDateFormatter::FULL,
				IntlDateFormatter::SHORT,
				config_item('time_reference'),
				IntlDateFormatter::GREGORIAN
			);
		}

		$formatter->setPattern(empty($pattern) ? 'EEEE, dd MMMM yyyy @ H:mm' : $pattern);

		return $formatter->format($time);
	}

}
