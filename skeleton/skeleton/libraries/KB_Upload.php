<?php
defined('BASEPATH') OR die;

/**
 * KB_Upload Class
 *
 * This class was added since version 1.40 in order to use our awesome hooks
 * system do allow the user alter settings.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.40
 * @version 	1.40
 */
class KB_Upload extends CI_Upload
{
	/**
	 * Holds a cached version of default config to avoid repeating.
	 * @var array
	 */
	protected $_config;

	/**
	 * Array of folders to which datetime isn't appended.
	 * @var array
	 */
	protected $ignored_folders = array();

	/**
	 * Raw upload path.
	 * @var string
	 */
	public $upload_folder = '';

	/**
	 * Whether to skip appending current date.
	 * @var bool
	 */
	public $skip_date = false;

	/**
	 * __construct
	 *
	 * Simply loads the default configuration if not set then pass it to parent.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.40
	 *
	 * @access 	public
	 * @param 	array
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		parent::__construct();

		$config = array_replace_recursive($this->_default_config(), $config);

		empty($config) OR $this->initialize($config, false);

		$this->skip_date = (true !== $this->_CI->config->item('upload_year_month'));
	}

	/**
	 * Adds a folder to the ignore paths list so whenever the user
	 * uploads images, datetiame won't be appended.
	 *
	 * @access 	public
	 * @param 	string 	$folder
	 * @return 	bool 	true if added or existing, else false.
	 */
	public function ignore_folder($folder = null)
	{
		if ( ! empty($folder) && ! in_array($folder, $this->ignored_folders))
		{
			$this->ignored_folders[] = $folder;
		}

		return $this;
	}

	/**
	 * validate_upload_path
	 *
	 * Verifies that the upload is valid and has proper permissions.
	 * If the folder does not exist, it will create it if possible.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.40
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function validate_upload_path()
	{
		$path = $this->_config['upload_path'];
		empty($this->upload_path) && $this->upload_path = $path;
		$this->upload_path = rtrim($this->upload_path, '/').'/';

		// Set upload folder.
		if (empty($this->upload_folder) && true !== $this->skip_date)
		{
			$this->upload_folder = date('Y/m/');
			$this->upload_path .= $this->upload_folder;
		}
		// Ignored folder?
		elseif (in_array($this->upload_folder, $this->ignored_folders))
		{
			$this->upload_folder .= '/';
			$this->upload_path .= $this->upload_folder;
		}

		// We make sure to create the folder if it does not exist.
		if ( ! create_static_index($this->upload_path))
		{
			$this->set_error('upload_no_filepath', 'error');
			return false;
		}

		// Another layer of formatting.
		$this->upload_path = str_replace('\\', '/', realpath($this->upload_path));

		if ( ! is_really_writable($this->upload_path))
		{
			$this->set_error('upload_not_writable', 'error');
			return false;
		}

		$this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * Enable multiple files upload.
	 *
	 * @since 	2.16
	 *
	 * @access 	public
	 * @param 	string 	$field
	 * @param 	string 	$return_info
	 * @param 	string 	$filenames
	 */
	public function do_multi_upload($field = 'userfile', $return_info = true, $filenames = null)
	{
		// Is $_FILES[$field] set? If not, no reason to continue.
		if ( ! isset($_FILES[$field]))
		{
			$this->set_error('upload_no_file_selected', 'debug');
			return false;
		}

		// If not every file filled was used, clear the empties.
		foreach ($_FILES[$field]['name'] as $k => $n)
		{
			if (empty($n))
			{
				foreach($_FILES[$field] as $kk => $f)
				{
					unset($_FILES[$field][$kk][$k]);
				}
			}
		}

		// Is the upload path valid?
		if ( ! $this->validate_upload_path($field) )
		{
			return false;
		}

		// Multiple file upload?
		if (is_array( $_FILES[$field]))
		{
			foreach ($_FILES[$field]['name'] as $k => $file)
			{
				// Was the file able to be uploaded? If not, determine the reason why.
				if ( ! is_uploaded_file($_FILES[$field]['tmp_name'][$k]))
				{
					$error = isset($_FILES[$field]['error'][$k]) ? $_FILES[$field]['error'][$k] : 4;

					switch ($error)
					{
						case 1:
						case UPLOAD_ERR_INI_SIZE:
							$this->set_error('upload_file_exceeds_limit', 'info');
							break;

						case 2:
						case UPLOAD_ERR_FORM_SIZE:
							$this->set_error('upload_file_exceeds_form_limit', 'info');
							break;

						case 3:
						case UPLOAD_ERR_PARTIAL:
							$this->set_error('upload_file_partial', 'debug');
							break;

						case 4:
						case UPLOAD_ERR_NO_FILE:
							$this->set_error('upload_no_file_selected', 'debug');
							break;

						case 6:
						case UPLOAD_ERR_NO_TMP_DIR:
							$this->set_error('upload_no_temp_directory', 'error');
							break;

						case 7:
						case UPLOAD_ERR_CANT_WRITE:
							$this->set_error('upload_unable_to_write_file', 'error');
							break;

						case 8:
						case UPLOAD_ERR_EXTENSION:
							$this->set_error('upload_stopped_by_extension', 'debug');
							break;

						default:
							$this->set_error('upload_no_file_selected', 'debug');
							break;
					}

					return false;
				}

				// Set the uploaded data as class variables
				$this->file_temp = $_FILES[$field]['tmp_name'][$k];
				$this->file_size = $_FILES[$field]['size'][$k];
				$this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type'][$k]);
				$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));

				if (empty($filenames))
				{
					$this->file_name = $this->_prep_filename($_FILES[$field]['name'][$k]);
				}
				else
				{
					$this->file_name = $this->_prep_filename($filenames[$k]);
				}

				$this->file_ext	 = $this->get_extension($this->file_name);
				$this->client_name = $this->file_name;

				// Is the file type allowed to be uploaded?
				if ( ! $this->is_allowed_filetype())
				{
					$this->set_error('upload_invalid_filetype');
					return false;
				}

				// if we're overriding, let's now make sure the new name and type is allowed
				if ($this->_file_name_override != '')
				{
					$this->file_name = $this->_prep_filename($this->_file_name_override);

					// If no extension was provided in the file_name config item, use the uploaded one
					if (strpos($this->_file_name_override, '.') === false)
					{
						$this->file_name .= $this->file_ext;
					}

					// An extension was provided, lets have it!
					else
					{
						$this->file_ext	 = $this->get_extension($this->_file_name_override);
					}

					if ( ! $this->is_allowed_filetype(true))
					{
						$this->set_error('upload_invalid_filetype');
						return false;
					}
				}

				// Convert the file size to kilobytes
				if ($this->file_size > 0)
				{
					$this->file_size = round($this->file_size/1024, 2);
				}

				// Is the file size within the allowed maximum?
				if ( ! $this->is_allowed_filesize())
				{
					$this->set_error('upload_invalid_filesize');
					return false;
				}

				// Are the image dimensions within the allowed size?
				// Note: This can fail if the server has an open_basdir restriction.
				if ( ! $this->is_allowed_dimensions())
				{
					$this->set_error('upload_invalid_dimensions');
					return false;
				}

				// Sanitize the file name for security
				$this->file_name = $this->clean_file_name($this->file_name);

				// Truncate the file name if it's too long
				if ($this->max_filename > 0)
				{
					$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
				}

				// Remove white spaces in the name
				if ($this->remove_spaces == true)
				{
					$this->file_name = preg_replace("/\s+/", "_", $this->file_name);
				}

				/*
				 * Validate the file name
				 * This function appends an number onto the end of
				 * the file if one with the same name already exists.
				 * If it returns false there was a problem.
				 */
				$this->orig_name = $this->file_name;

				if ($this->overwrite == false)
				{
					$this->file_name = $this->set_filename($this->upload_path, $this->file_name);

					if ($this->file_name === false)
					{
						return false;
					}
				}

				/*
				 * Run the file through the XSS hacking filter
				 * This helps prevent malicious code from being
				 * embedded within a file.  Scripts can easily
				 * be disguised as images or other file types.
				 */
				if ($this->xss_clean)
				{
					if ($this->do_xss_clean() === false)
					{
						$this->set_error('upload_unable_to_write_file');
						return false;
					}
				}

				/*
				 * Move the file to the final destination
				 * To deal with different server configurations
				 * we'll attempt to use copy() first.  If that fails
				 * we'll use move_uploaded_file().  One of the two should
				 * reliably work in most environments
				 */
				if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
				{
					if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
					{
						$this->set_error('upload_destination_error');
						return false;
					}
				}

				/*
				 * Set the finalized image dimensions
				 * This sets the image width/height (assuming the
				 * file was an image).  We use this information
				 * in the "data" function.
				 */
				$this->set_image_properties($this->upload_path.$this->file_name);

				if (true === $return_info)
				{
					$return_value[$k] = $this->data();
				}
				else
				{
					$return_value = true;
				}
			}

			return $return_value;
		}

		// Single file upload, rely on parent's method
		return parent::do_upload($field);
	}

	// --------------------------------------------------------------------

	/**
	 * Finalized Data Array
	 *
	 * Returns an associative array containing all of the information
	 * related to the upload, allowing the developer easy access in one array.
	 *
	 * @param   string  $index
	 * @return  mixed
	 */
	public function data($index = null)
	{
		$data = parent::data($index);

		is_array($data) && $data['folder'] = $this->upload_folder;

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Set Upload folder
	 *
	 * @param   string  $path
	 * @return  CI_Upload
	 */
	public function set_upload_folder($folder)
	{
		// Make sure it has a trailing slash
		$this->upload_folder = rtrim($folder, '/').'/';
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * _default_config
	 *
	 * Returns an array of default configuration in case no config is passed,
	 * with extra filters applied to them.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.40
	 *
	 * @access 	protected
	 * @param 	none
	 * @return 	array
	 */
	protected function _default_config()
	{
		// If not cached, cache it first.
		if ( ! isset($this->_config))
		{
			$this->_config = array(
				// Options stored in database.
				'upload_path'      => $this->_CI->config->item('upload_path', null, 'content/uploads'),
				'allowed_types'    => $this->_CI->config->item('allowed_types', null, 'gif|png|jpeg'),
				'max_size'         => $this->_CI->config->item('max_size', null, 2048),
				'max_width'        => $this->_CI->config->item('max_width', null, 1024),
				'max_height'       => $this->_CI->config->item('max_height', null, 1024),
				'min_width'        => $this->_CI->config->item('min_width', null, 0),
				'min_height'       => $this->_CI->config->item('min_height', null, 0),

				// Other options.
				'file_ext_tolower' => false,
				'encrypt_name'     => false,
				'remove_spaces'    => false,
			);

			// Apply filters on settings.
			foreach ($this->_config as $key => &$val)
			{
				$val = ('upload_path' === $key)
					? apply_filters('upload_dir', $val)
					: apply_filters('upload_'.$key, $val);
			}
		}

		return $this->_config;
	}

}
