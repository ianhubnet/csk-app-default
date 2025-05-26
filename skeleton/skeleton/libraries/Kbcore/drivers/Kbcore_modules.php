<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_modules Class
 *
 * Core module management class responsible for handling loading,
 * activation, deactivation, deletion and info retrieval for
 * application modules.
 *
 * This class works closely with the router to resolve module paths,
 * maintains an internal state of active and available modules,
 * and triggers relevant hooks/actions during module lifecycle events.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_modules extends KB_Driver
{
	/**
	 * List of all available modules indexed by module folder name.
	 *
	 * @var array<string, string> 	Array of module folder => path.
	 */
	protected $_modules;

	/**
	 * Detailed info about each module, keyed by module name.
	 * Cached for performance to avoid repeated file reads.
	 *
	 * @var array<string, array<string, mixed>>
	 */
	protected $_module_details = array();

	/**
	 * List of currently active (enabled) modules by folder name.
	 *
	 * @var string[]
	 */
	protected $_active_modules;

	/**
	 * Massage holder to pass success or error notifications after operations.
	 * Useful for UI feedback or logging.
	 *
	 * @var string
	 */
	public $message = '';

	/**
	 * Defaults headers for module info.
	 * This defines the expected keys in module info arrays.
	 *
	 * @var array<string, mixed>
	 */
	private $_default_headers = array(
		'access_level'       => null,
		'access_level_admin' => null,
		'name'               => null,
		'module_uri'         => null,
		'description'        => null,
		'version'            => null,
		'license'            => null,
		'license_uri'        => null,
		'author'             => null,
		'author_uri'         => null,
		'author_email'       => null,
		'tags'               => null,
		'language_folder'    => null,
		'language_index'     => null,
		'admin_menu'         => null,
		'admin_uri'          => null,
		'admin_context'      => null,
		'content_menu'       => null,
		'content_uri'        => null,
		'content_context'    => null,
		'help_menu'          => null,
		'help_uri'           => null,
		'help_context'       => null,
		'reports_menu'       => null,
		'reports_uri'        => null,
		'reports_context'    => null,
		'settings_menu'      => null,
		'settings_uri'       => null,
		'settings_context'   => null,
		'protected'          => false
	);

	/**
	 * Headers collected during module info parsing.
	 * This is dynamically merged from module info files.
	 *
	 * @var array<string, mixed>
	 */
	private $_headers;

	/**
	 * Headers that be localized/translatable in module info.
	 * Keys expected to have translation strings.
	 *
	 * @var string[]
	 */
	private $_localized_headers = array(
		'name',
		'description',
		'author',
		'tags',
		'license',
		'admin_menu'
	);

	/**
	 * Path where marker files for enabled modules are stored.
	 * Marker files indicate a module's enabled state persistently.
	 *
	 * @var string
	 */
	private $markers_path = APPPATH.'cache/modules/';

	// --------------------------------------------------------------------

	/**
	 * load_active_modules
	 *
	 * Loads and initializes the active modules.
	 * Creates marker files and fires activation hooks when necessary.
	 *
	 * @param 	array 	$modules 	Array of module folder => path.
	 * @return 	void
	 */
	private function load_active_modules($modules)
	{
		// No active modules to load?
		if (empty($modules))
		{
			return;
		}
		// Ensure markers directory exists for module enabled state files.
		elseif ( ! is_dir($this->markers_path)
			&& ! mkdir($this->markers_path, 0755, true)
			&& ! is_dir($this->markers_path))
		{
			log_message('critical', 'Unable to create modules markers directory.');
			return; // Can't track enabled modules without marker folder.
		}

		foreach ($modules as $folder => $path)
		{
			// Add module to active list.
			$this->_active_modules[] = $folder;

			// Cache module details to avoid repetitive loading.
			$this->_module_details[$folder] = $this->details($folder, $path);

			// If there's a hook for module activation and no marker file exists,
			// touch the file and trigger hook.
			if (has_action('module_activate_'.$folder) && ! is_file($this->markers_path.$folder))
			{
				@touch($this->markers_path.$folder);
				do_action('module_activate_'.$folder);
			}

			// Always trigger 'module_loaded' action regardless of previous activation state.
			do_action('module_loaded_'.$folder);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * activate
	 *
	 * Activate a module by name.
	 * Adds it to the active modules list and updates persistent options.
	 *
	 * @param 	string 	$name 	Module folder name.
	 * @return 	bool 	True on successful activation, false if already active or failure.
	 */
	public function activate($name)
	{
		// Get module path, verify it exists and not already active.
		if (($path = $this->ci->router->module_path($name)) &&
			(! isset($this->_active_modules) OR ! in_array($name, $this->_active_modules)))
		{
			// Clean up any leftover marker file before activating.
			is_file($this->markers_path.$name) && @unlink($this->markers_path.$name);

			// Add module to active list and sort for consistency.
			$this->_active_modules[] = $name;
			asort($this->_active_modules);

			// Persist active modules list.
			$this->_parent->options->set_item('active_modules', $this->_active_modules);

			$this->message = $this->ci->lang->line('admin_modules_enable_success');
			return true;
		}

		// Already active or path invalid.
		$this->message = $this->ci->lang->line('admin_modules_enable_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * deactivate
	 *
	 * Deactivate a module by name.
	 * Removes it from active modules list, deletes marker file,
	 * and reverts default controller if necessary.
	 *
	 * @param 	string 	$name 	Module folder name.
	 * @return 	bool 	True on success, false on failure.
	 */
	public function deactivate($name)
	{
		if (($path = $this->ci->router->module_path($name))
			&& false !== ($index = array_search($name, $this->_active_modules)))
		{
			// Remove marker file to signal deactivation.
			if (is_file($this->markers_path.$name) && ! @unlink($this->markers_path.$name))
			{
				$this->message = $this->ci->lang->line('admin_modules_disable_error');
				return false;
			}

			// Remove module from active list and reorder.
			unset($this->_active_modules[$index]);
			asort($this->_active_modules);

			// Persist changes.
			$this->_parent->options->set_item('active_modules', $this->_active_modules);

			// Check if default controller points to this module, revert if yes.
			if (str_starts_with($this->ci->router->default_controller, $name))
			{
				$this->ci->config->save('base_controller', array('base_controller' => KB_BASE));
			}

			$this->message = $this->ci->lang->line('admin_modules_disable_success');
			return true;
		}

		$this->message = $this->ci->lang->line('admin_modules_disable_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * delete
	 *
	 * Delete a module completely from disk.
	 * Only non-active, non-core modules can be deleted.
	 *
	 * @param 	string 	$name 	Module folder name.
	 * @return 	bool 	True if deletion successful, false otherwise.
	 */
	public function delete($name)
	{
		// Ensure module exists, is inactive, has valid path, and is not core.
		if (
			$name
			&& ! in_array($name, $this->_active_modules)
			&& is_dir($path = $this->path($name))
			&& false === strpos($path, KBPATH))
		{
			// Load helper for recursive directory deletion if not already loaded.
			function_exists('directory_delete') OR $this->ci->load->helper('directory');

			// Attempt recursive directory delete.
			if (false !== directory_delete($path))
			{
				$this->message = $this->ci->lang->line('admin_modules_delete_success');
				return true;
			}
		}

		$this->message = $this->ci->lang->line('admin_modules_delete_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * path
	 *
	 * Alias for KB_Router::module_path
	 *
	 * Get the filesystem path for a module.
	 * Alias for KB_Router::module_path.
	 *
	 * @param 	string 			$name	Module folder name.
	 * @param 	string 			$uri	Optional URI to append.
	 * @return 	string|false 	Module path string or false if not found.
	 */
	public function path($name, string $uri = '')
	{
		return $this->ci->router->module_path($name, $uri);
	}

	// --------------------------------------------------------------------

	/**
	 * details
	 *
	 * Retrieve detailed information about a module.
	 * Reads from the module's info file and caches the result.
	 *
	 * @param 	string			$name 	Module folder name.
	 * @param 	string|null 	$path 	Optional module path to avoid multiple lookups.
	 * @return 	array<string, mixed> 	Module info array with keys as defined in $_default_headers.
	 */
	public function details($name, $path = null)
	{
		empty($name) && $name = $this->ci->router->module;
		if (empty($name))
		{
			return false;
		}

		if (empty($path) && false === ($path = $this->ci->router->module_path($name)))
		{
			return false;
		}

		// Return cached info if available.
		if (isset($this->_module_details[$name]))
		{
			return $this->_module_details[$name];
		}

		elseif ( ! is_file($info_file = $path.'info.php') OR empty($info = include_once($info_file)) OR ! is_array($info))
		{
			return false;
		}

		// Grab module's headers.
		$headers = array_merge_exists($this->_headers, $info);

		// Remove not listed headers.
		foreach ($headers as $key => $val)
		{
			if ( ! array_key_exists($key, $this->_default_headers))
			{
				unset($headers[$key]);
			}
		}

		// Translated stuff?
		if (isset($info['translations'], $info['translations'][$this->ci->lang->idiom]))
		{
			foreach ($this->_localized_headers as $key)
			{
				if (isset($info['translations'][$this->ci->lang->idiom][$key]))
				{
					$headers[$key] = $info['translations'][$this->ci->lang->idiom][$key];
				}
			}
		}

		$headers['folder'] = $name;
		$headers['full_path'] = normalize_path($path);

		if ($headers['license'] == 'MIT' && empty($headers['license_uri']))
		{
			$headers['license_uri'] = 'https://opensource.org/licenses/MIT';
		}

		// Whether it is protected.
		$headers['protected'] = (false !== strpos($headers['full_path'], KBPATH));

		// Remove all empty stuff.
		$headers = array_clean_keys($headers);

		// See if module is enabled.
		$headers['enabled'] = $this->ci->router->module_enabled($name);

		// Module contexts.
		$headers['contexts'] = $this->ci->router->module_contexts($name, $path);

		return $this->_module_details[$name] = $headers;
	}

	// --------------------------------------------------------------------

	/**
	 * list
	 *
	 * List all available modules.
	 *
	 * @access 	public
	 * @param 	bool 	$details 	Whether to include details.
	 * @return 	array
	 */
	public function list($details = false)
	{
		isset($this->_modules) OR $this->_modules = $this->ci->router->list_modules();

		if ( ! $details OR empty($this->_modules))
		{
			return $this->_modules;
		}

		$details = array();

		foreach ($this->_modules as $name => $path)
		{
			$details[$name] = $this->details($name, $path);
		}

		return $details;
	}

	// --------------------------------------------------------------------

	/**
	 * url
	 *
	 * Returns the URL to the public modules folder.
	 *
	 * @param 	string 	$uri 	The URI string.
	 * @param 	string 	$name 	The module name
	 * @return 	string
	 */
	public function url($uri = '', $name = null)
	{
		empty($name) && $name = $this->ci->router->module;
		empty($name) OR $uri = $name.'/'.$uri;
		return $this->ci->config->static_url($uri, 'modules');
	}

	// --------------------------------------------------------------------

	/**
	 * active
	 *
	 * Method for returning the list of active modules.
	 *
	 * @param 	bool 	$details 	Whether to include details.
	 * @return 	array
	 */
	public function active($details = false)
	{
		if (false === $details OR empty($this->_active_modules))
		{
			return $this->_active_modules;
		}

		$details = array();
		$modules = array_intersect_key($this->_module_details, array_flip($this->_active_modules));
		foreach ($modules as $name => $path)
		{
			$details[$name] = $this->details($name, $path);
		}

		unset($modules);
		return $details;
	}

	// --------------------------------------------------------------------

	/**
	 * Displays admin dashboard menu.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_modules()
	{
		echo admin_anchor('modules', $this->ci->lang->line('admin_modules'), 'class="dropdown-item"');
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
		if (($count = count($this->list())) > 0)
		{
			echo info_box(
				$count, $this->ci->lang->line('admin_modules'),
				'cubes', $this->ci->config->admin_url('modules'),
				'blue', 'div', 'class="col"'
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
		if ( ! $this->_parent->auth->is_level(KB_LEVEL_ADMIN))
		{
			return;
		}

		add_action('extensions_menu', array($this, '_menu_modules'), 97);
		$is_homepage && add_action('admin_index_stats', array($this, '_stats_admin'), 85);

		// Add dashboard contexts.
		foreach (KPlatform::admin_contexts() as $context)
		{
			$context_menu = $context.'_menu';
			$this->_localized_headers[] = $context_menu;
			$this->_default_headers[$context_menu] = null;
			$this->_default_headers['access_level_'.$context] = null;
		}

		$this->_headers = apply_filters('modules_headers', $this->_default_headers);
		empty($this->_headers) && $this->_headers = $this->_default_headers;

		// Only load active modules.
		$this->load_active_modules($this->ci->router->active_modules(true));
	}

}
