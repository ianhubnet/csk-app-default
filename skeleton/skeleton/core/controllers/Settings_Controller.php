<?php
defined('BASEPATH') OR die;

/**
 * Settings_Controller Class
 *
 * Only "Settings.php" controllers should extend this class.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Core Extension
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.0
 * @version     2.0
 */
class Settings_Controller extends Admin_Controller
{
	/**
	 * Array of options tabs and their display order.
	 * @var array
	 */
	protected $_tabs = array();

	/**
	 * __construct
	 *
	 * Load needed resources only.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->page_help  = 'http://bit.ly/CSKContextSettings';
		$this->page_title = $this->lang->line('settings');
		$this->page_icon  = 'sliders';
	}

	// --------------------------------------------------------------------

	/**
	 * _prep_settings
	 *
	 * Method for preparing all settings data and their form validation rules.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.0
	 * @since   1.33   Added the base controller setting handler.
	 * @since   2.0   Moved to Settings_Controller class.
	 *
	 * @access  protected
	 * @param   string
	 * @return  array
	 */
	protected function _prep_settings($tab = 'general')
	{
		if (false === ($settings = $this->options->get_by_tab($tab)))
		{
			return array(false, null);
		}

		if ('general' === $tab)
		{
			$settings[] = (object) array(
				'name'       => 'base_controller',
				'value'      => $this->router->default_controller,
				'tab'       => 'general',
				'field_type' => 'dropdown',
				'options'    => array(),
				'required'   => '1',
			);
		}

		if (isset($this->_tabs[$tab]) && ! empty($this->_tabs[$tab]))
		{
			$keys = $this->_tabs[$tab];
			$_settings = array();
			$order = array_flip($keys);

			foreach ($settings as $index => $setting)
			{
				if (in_array($setting->name, $keys))
				{
					$_settings[$order[$setting->name]] = $setting;

					// Customize 'offline_access_level'
					if ($setting->name === 'offline_access_level')
					{
						$setting->field_type = 'dropdown';
						$setting->options = array_map(function($val) {
							return $this->lang->line('role_'.$val);
						}, array_flip($this->auth->levels));
					}
					elseif ($setting->name === 'time_reference')
					{
						function_exists('timezone_list') OR $this->load->helper('date');
						$setting->options = timezone_list($this->i18n->current('locale'));
					}

					if (false !== ($key = array_search($setting->name, $keys)))
					{
						unset($keys[$key]);
					}
				}
			}

			// Handle remaining keys
			foreach ($keys as $key)
			{
				$_settings[$order[$key]] = (object) array(
					'name'       => $key,
					'value'      => '',
					'tab'        => $tab,
					'field_type' => 'text',
					'options'    => '',
					'required'   => '0',
				);
			}

			if ( ! empty($_settings))
			{
				ksort($_settings);
				$settings = $_settings;
			}

			unset($_settings);
		}

		// Prepare empty form validation rules.
		$rules = array();

		foreach ($settings as $option)
		{
			$data[$option->name] = array(
				'type'  => $option->field_type,
				'name'  => $option->name,
				'id'    => $option->name,
				'value' => $option->value,
			);

			if ($option->required == 1)
			{
				$data[$option->name]['required'] = 'required';
				$rules[$option->name] = array(
					'field' => $option->name,
					'label' => $this->lang->line($option->name),
					'rules' => 'required',
				);
			}

			/**
			 * In case of the base controller settings, we make sure to
			 * grab a list of all available controllers/modules and prepare
			 * the dropdown list.
			 */
			if ('base_controller' === $option->name && empty($option->options))
			{
				// We start with an empty controllers list.
				$controllers = array();

				// We set controllers locations.
				$locations   = array(
					normalize_path(APPPATH.'controllers/') => null,
					normalize_path(KBPATH.'controllers/') => null
				);

				// We add modules locations to controllers locations.
				$modules = $this->router->active_modules(true);
				foreach ($modules as $name => $path)
				{
					if ( ! is_dir($location = $path.'controllers/'))
					{
						continue;
					}

					$locations[normalize_path($location)] = $name;
				}

				// Array of files to be ignored.
				$context_files = array_map('normalize_file', KPlatform::contexts());

				// Fill controllers.
				foreach ($locations as $location => $module)
				{
					// We read the directory.
					if ($handle = opendir($location))
					{
						while (false !== ($file = readdir($handle)))
						{
							// Directory or ignored files?
							if (is_dir($location.$file) OR
								in_array($file, KB_IGNORED_FILES) OR
								in_array($file, $context_files) OR
								in_array($file, KPlatform::$ignored_files))
							{
								continue;
							}

							// We format the file's name.
							$filename = $file = strtolower(str_replace('.php', '', $file));

							/**
							 * If the controller's name is different from module's, we
							 * make sure to add the module to the start.
							 */
							if (null !== $module)
							{
								$filename = $module.' &#187; '.$filename;
								$file = $module.'/'.$file;
							}

							// We fill $controllers array.
							$controllers[$file] = ucwords($filename);
						}
					}
				}

				// We add controllers list.
				$option->options = $controllers;
			}

			if ($option->field_type == 'dropdown' && ! empty($option->options))
			{
				if ('date_format' == $option->name OR 'time_format' == $option->name)
				{
					foreach ($option->options as $key => $val)
					{
						$data[$option->name]['options'][$key] = date($val);
					}
				}
				else
				{
					$data[$option->name]['options'] = array_map(function($val) {
						return is_numeric($val) ? $val : _transfigure($val);
					}, $option->options);
				}

				if (null !== $option->value)
				{
					if (is_bool($option->value))
					{
						$data[$option->name]['selected'] = (true === $option->value) ? 'true' : 'false';
					}
					else
					{
						$data[$option->name]['selected'] = $option->value;
					}

					if (isset($rules[$option->name]))
					{
						$rules[$option->name]['rules'] .= '|in_list['.implode(',', array_keys($option->options)).']';
					}
				}
				else
				{
					$data[$option->name]['selected'] = '';
				}
			}
			else
			{
				$data[$option->name]['placeholder'] = $this->lang->line($option->name);
			}
		}

		return array($data, array_values($rules));
	}

	// --------------------------------------------------------------------

	/**
	 * _save_settings
	 *
	 * Method that handles automatically saving settings.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.0
	 *
	 * @access  protected
	 * @param   array
	 * @param   string
	 * @return  bool
	 */
	protected function _save_settings($inputs, $tab = null)
	{
		// Nothing provided? Nothing to do.
		if (empty($inputs) OR (empty($tab) OR (empty($this->module) && ! isset($this->_tabs[$tab]))))
		{
			$this->theme->set_alert($this->lang->line('error_csrf'), 'error');
			return false;
		}

		// Check nonce.
		elseif ($this->nonce->verify_request('settings-'.$tab) === false)
		{
			$this->theme->set_alert($this->nonce->message, 'error');
			return false;
		}

		/**
		 * We make sure to collect all settings data from the provided
		 * $inputs array (We use their keys).
		 * Then, we loop through all elements and remove those that did
		 * not change to avoid useless update.
		 */
		$settings = $this->input->post(array_keys($inputs), true);
		foreach ($settings as $key => $val)
		{
			if (to_bool_or_serialize($inputs[$key]['value']) === $val)
			{
				unset($settings[$key]);
			}
		}

		/**
		 * If all settings were removed, we will end up with an empty
		 * array, so we simply fake it :) .. We say everything was updated.
		 */
		if (empty($settings))
		{
			$this->theme->set_alert($this->lang->line('settings_update_success'), 'success');
			return true;
		}

		/**
		 * In case we have some left settings, we make sure to updated them
		 * one by one and stop in case one of them could not be updated.
		 */
		foreach ($settings as $key => $val)
		{
			/**
			 * Saving base_controller to a file is a nice approach because
			 * "routes.php" used to use database to get it, this way whenever
			 * we change it, the file is updated so it removes the hassle of
			 * reading from database again.
			 * @since 	2.18
			 */
			if ('base_controller' === $key)
			{
				$this->config->save('base_controller', array('base_controller' => $val));
				continue;
			}
			elseif (false === $this->options->set_item($key, $val, $tab))
			{
				log_message('error', "Unable to update setting {$tab}: {$key}");
				$this->theme->set_alert($this->lang->line('settings_update_error'), 'error');
				return false;
			}


		}

		$this->theme->set_alert($this->lang->line('settings_update_success'), 'success');
		return true;
	}

}
