<?php
defined('BASEPATH') OR die;

/**
 * Kbcore_files Class
 *
 * Handles all operations with files.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 */
final class Kbcore_files extends KB_Driver
{
	/**
	 * Array of default sizes.
	 * @var array
	 */
	protected $_images_sizes = array(
		'small' => array(
			'width'  => 80,
			'height' => 80,
			'crop'   => true
		),
		'thumbnail' => array(
			'width'  => 150,
			'height' => 150,
			'crop'   => true,
		),
		'medium' => array(
			'width'  => 300,
			'height' => 300,
			'crop'   => true
		),
		'large' => array(
			'width'  => 1024,
			'height' => 1024
		),
		'opengraph' => array(
			'width'  => 1200,
			'height' => 630,
			'crop'   => true
		)
	);

	/**
	 * Config array to pass to upload library.
	 * @since 	2.18
	 * @var 	array
	 */
	protected $_config = array(
		'file_ext_tolower' => true,
		'encrypt_name'     => true,
		'remove_spaces'    => true
	);

	/**
	 * Holds alert messages.
	 * @var string
	 */
	public $message = '';

	/**
	 * Flags used to prevent class from being initialized.
	 * @var bool
	 */
	protected $initialized = false;

	/**
	 * Relation type used to link a file to an entity.
	 * @var string
	 */
	private $relation_key = 'attachment';

	/**
	 * Initialize class.
	 * @return 	void
	 */
	public function initialize()
	{
		// Load file helper.
		(function_exists('is_file_image')) OR $this->ci->load->helper('file');
	}

	// --------------------------------------------------------------------

	/**
	 * Function called to initialize class only once.
	 *
	 * @return 	void
	 */
	protected function init()
	{
		if (true !== $this->initialized)
		{
			// Some stuff from database.
			$this->_config['max_size'] = $this->ci->config->item('max_size', null, 0);

			$this->_config['min_width']  = $this->ci->config->item('min_width', null, 0);
			$this->_config['min_height'] = $this->ci->config->item('min_height', null, 0);

			$this->_config['max_width']  = $this->ci->config->item('max_width', null, 0);
			$this->_config['max_height'] = $this->ci->config->item('max_height', null, 0);

			// Allowed upload types.
			$allowed_types = apply_filters('allowed_types', array('png', 'jpg', 'jpeg'));

			// Make sure to add types from database.
			if (false !== ($types = $this->ci->config->item('allowed_types', null, false)))
			{
				$allowed_types += explode('|', $types);
			}

			// Update config array.
			$this->_config['allowed_types'] = array_unique($allowed_types);
			unset($types, $allowed_types);

			$this->initialized = true;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Builds URL for different files sizes if found.
	 *
	 * @param 	object 	$file
	 * @return 	object 	$file
	 */
	private function build_urls($file)
	{
		// Main file URL.
		($file instanceof KB_File) OR $file = new KB_File($file);
		if ( ! empty($file->content))
		{
			// Different sizes URL.
			if ($file->is_image && isset($file->content['sizes']))
			{
				foreach ($file->content['sizes'] as $key => $val)
				{
					$file->$key = $this->ci->config->uploads_url($val['file']);
				}
			}
		}

		return $file;
	}

	// --------------------------------------------------------------------
	// CRUD METHODS
	// --------------------------------------------------------------------

	/**
	 * Creates a new file item.
	 *
	 * @access 	public
	 * @param 	array 	$data 	Array of data to insert.
	 * @return 	the new file item ID if found, else false.
	 */
	public function create(array $data = array())
	{
		// Make sure all fields are provided.
		if (empty($data))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Make sure the subtype is always file.
		$data['subtype'] = 'file';
		is_array($data['content']) && $data['content'] = to_bool_or_serialize($data['content']);

		// Proceed to creation.
		return $this->_parent->objects->create($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves a single file item by primary key.
	 *
	 * @access 	public
	 * @param 	mixed 	$id
	 * @return 	object if found, else false
	 */
	public function get($id)
	{
		// By ID?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// By name?
		if (is_string($id))
		{
			return $this->get_by('name', $id);
		}

		// Otherwise let the "get_by" method do the rest.
		return $this->get_by($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves a single file item by arbitrary WHERE clause.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 */
	public function get_by($field, $match = null)
	{
		if (empty($field))
		{
			return false;
		}

		(is_array($field)) OR $field = array($field => $match);
		$field['entities.subtype'] = 'file';

		if (false !== ($file = $this->_parent->objects->get_by($field, null, true)))
		{
			return ($file instanceof KB_File) ? $file : new KB_File($file);
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves multiple file items by arbitrary WHERE clause.
	 *
	 * @access  public
	 * @param   mixed   $field  Column name or associative array.
	 * @param   mixed   $match  Comparison value.
	 * @param   int     $limit  Limit to use for getting records.
	 * @param   int     $offset Database offset.
	 * @return  array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Make sure to add the "file subtype".
		$this->ci->db
			->where('entities.subtype', 'file')
			->order_by('entities.id', 'DESC');

		$files = $this->_parent->objects->get_many($field, $match, $limit, $offset);

		if (false !== $files)
		{
			foreach ($files as &$file)
			{
				if ($file instanceof KB_File)
				{
					continue;
				}

				$file = new KB_File($file);
			}

			return $files;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves all file items.
	 *
	 * @access  public
	 * @param   int     $limit  Limit to use for getting records.
	 * @param   int     $offset Database offset.
	 * @return  array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Updates a single file item by its primary key.
	 *
	 * @access  public
	 * @param   mixed   $id     The primary key value.
	 * @param   array   $data   Array of data to update.
	 * @return  boolean
	 */
	public function update($id, array $data = array())
	{
		if (false !== $this->_parent->objects->update($id, $data))
		{
			$this->message = $this->ci->lang->line('admin_media_file_update_success');
			return true;
		}

		$this->message = $this->ci->lang->line('admin_media_file_update_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Update all or multiple file items by arbitrary WHERE clause.
	 *
	 * @access  public
	 * @return  boolean
	 */
	public function update_by()
	{
		// Collect arguments first and make sure there are any.
		if (empty($args = func_get_args()))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Data to update is always the last element.
		$data = array_pop($args);
		if (empty($data))
		{
			$this->message = $this->ci->lang->line('required_fields_error');
			return false;
		}

		// Prepare where clause.
		if ( ! empty($args))
		{
			is_array($args[0]) && $args = $args[0];
			$args['subtype'] = 'file';
		}
		else
		{
			$args['subtype'] = 'file';
		}

		if (false !== $this->_parent->objects->update_by($args, $data))
		{
			$this->message = $this->ci->lang->line('admin_media_file_update_success');
			return true;
		}

		$this->message = $this->ci->lang->line('admin_media_file_update_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes a single file item by its primary key.
	 *
	 * @access  public
	 * @param   mixed   $id     The primary key value.
	 * @return  boolean
	 */
	public function delete($id)
	{
		// To handle calls from delete_by.
		if ($id instanceof KB_Object OR is_object($id))
		{
			$file = $id;
		}
		// We make sure the file exists first.
		elseif ( ! ($file = $this->get($id)))
		{
			$this->message = $this->ci->lang->line('admin_media_file_delete_error');
			return false;
		}

		// From Imgur?
		if (isset($file->content['deletehash']))
		{
			if ($this->_parent->entities->remove($id))
			{
				$this->message = $this->ci->lang->line('admin_media_file_delete_success');
				return true;
			}

			$this->message = $this->ci->lang->line('admin_media_file_delete_error');
			return false;
		}

		// Fallback to old fashion if "file_path" is not set.
		if ( ! isset($file->content['path']) OR empty($filepath = $file->content['path']))
		{
			return $this->_parent->entities->remove($id);
		}

		// Some paths we need.
		$folder = $this->ci->config->uploads_path($filepath);
		$filepath = normalize_path($folder.'/'.$file->username);

		if ($this->_parent->entities->remove($id))
		{
			// delete all files.
			@array_map('unlink', glob($filepath.'*.*'));

			// delete empty folder
			(function_exists('directory_is_empty')) OR $this->ci->load->helper('directory');
			(directory_is_empty($folder, 'index.html')) && directory_delete($folder);

			$this->message = $this->ci->lang->line('admin_media_file_delete_success');
			return true;
		}

		$this->message = $this->ci->lang->line('admin_media_file_delete_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes multiple or all file items by arbitrary WHER clause.
	 *
	 * @access  public
	 * @param   mixed   $field  Column name or associative array.
	 * @param   mixed   $match  Comparison value.
	 * @return  boolean
	 */
	public function delete_by($field = null, $match = null)
	{
		// Found any? Proceed to delete.
		if ($items = $this->get_many($field, $match))
		{
			foreach ($items as $item)
			{
				// Could not be delete? Stop the script.
				if ( ! $this->delete($item->id))
				{
					$this->message = $this->ci->lang->line('admin_media_delete_error');
					return false;
				}
			}

			$this->message = $this->ci->lang->line('admin_media_delete_success');
			return true;
		}

		$this->message = $this->ci->lang->line('admin_media_delete_error');
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Count all objects.
	 *
	 * @access  public
	 * @param   mixed   $field
	 * @param   mixed   $match
	 * @return  int
	 */
	public function count($field = null, $match = null)
	{
		// Prepare where clause.
		if ( ! empty($field))
		{
			(is_array($field)) OR $field = array($field => $match);
			foreach ($field as $key => $val)
			{
				if (is_int($key) && is_array($val))
				{
					$this->ci->db->where($val);
				}
				elseif (is_array($val))
				{
					$this->ci->db->where_in($key, $val);
				}
				else
				{
					$this->ci->db->where($key, $val);
				}
			}
		}

		return $this->ci->db
			->where('entities.type', 'object')
			->where('entities.subtype', 'file')
			->count_all_results('entities');
	}

	// --------------------------------------------------------------------

	/**
	 * Deletes files that are not being used.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	int
	 */
	public function purge()
	{
		// Grab all fiels.
		$files = $this->get_all();

		// Nothing found? Ignore.
		if (false === $files)
		{
			return 0;
		}

		// Initial count.
		$count = 0;

		/**
		 * Now we delete file that:
		 * 1. Have parent_id set to "0".
		 * 2. Parent doesn't exist.
		 */
		foreach ($files as $file)
		{
			if (($file->parent_id === 0 OR ! $this->_parent->entities->get($file->parent_id))
				&& ($file->owner_id === 0 OR ! $this->_parent->entities->get($file->owner_id))
				&& $this->delete($file->id))
			{
				$count++;
			}
		}

		// Return the final count.
		return $count;
	}

	// --------------------------------------------------------------------
	// Themes images sizes methods.
	// --------------------------------------------------------------------

	/**
	 * add_image_size
	 *
	 * Method for adding thumbnails sizes for the currently active theme.
	 *
	 * @access  public
	 * @param   string  $name       The name of the thumbnail.
	 * @param   int     $width      The width of the thumbnail.
	 * @param   int     $height     The height of the thumbnail.
	 * @param   bool    $crop       Whether to crop the image.
	 * @return  void
	 */
	public function add_image_size($name, $width = 0, $height = 0, $crop = false)
	{
		$name = strtolower($name);

		if ( ! isset($this->_images_sizes[$name]))
		{
			$theme = $this->_parent->theme->current();
			$opt = $this->ci->config->item('theme_images_'.$theme);
			is_array($opt) OR $opt = array();

			if ( ! isset($opt[$name]))
			{
				$opt[$name] = array(
					'width'  => (int) $width,
					'height' => (int) $height,
					'cropt'  => (bool) $crop
				);
				$this->_parent->options->set_item('theme_images_'.$theme, $opt);
			}

			$this->_images_sizes[$name] = $opt[$name];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns an array of available image sizes.
	 *
	 * @return 	array
	 */
	public function image_sizes()
	{
		return array_keys($this->_images_sizes);
	}

	// --------------------------------------------------------------------

	/**
	 * _set_images_sizes
	 *
	 * Method for adding thumbnails sizes for the currently active theme.
	 *
	 * @access  public
	 * @param   string 	$theme 	Theme currently in use.
	 * @return  void
	 */
	public function _set_images_sizes($theme = null)
	{
		// No images sizes set? Noting to do.
		if (empty($this->_images_sizes))
		{
			return false;
		}

		empty($theme) && $theme = $this->_parent->theme->current();

		// Values didn't change?
		if ($opt = $this->ci->config->item('theme_images_'.$theme) === $this->_images_sizes)
		{
			return true;
		}

		// Update option.
		return $this->_parent->options->set_item('theme_images_'.$theme, $this->_images_sizes);
	}

	// --------------------------------------------------------------------
	// UPLOAD METHODS
	// --------------------------------------------------------------------

	/**
	 * Handles files upload.
	 *
	 * @param 	string 	$folder 		Folder where to upload files.
	 * @param 	bool 	$add_thumbs 	Whether to generate thumbnails.
	 * @return 	file array if upload, else false.
	 */
	public function upload($folder = '', $add_thumbs = true)
	{
		// Initialize preferences.
		$this->init();

		// Load Upload library if not loaded.
		(isset($this->ci->upload)) OR $this->ci->load->library('upload');

		/**
		 * If the user doesn't provide a folder, we make sure
		 * to use the date/month folder structure.
		 */
		if (empty($folder))
		{
			$add_thumbs = true;
			$folder = $this->ci->config->item('upload_year_month') ? date('Y/m') : '';
		}
		else
		{
			$add_thumbs = false;
			$folder = rtrim($folder, '/');
			$this->ci->upload->ignore_folder($folder);
		}

		// Add upload path to config.
		$this->_config['upload_path']   = $this->ci->config->uploads_path($folder);
		$this->_config['upload_folder'] = $folder;
		$this->ci->upload->initialize($this->_config);

		// There was a problem uploading the file?
		if (true !== $this->ci->upload->do_upload('file'))
		{
			$this->message = $this->ci->upload->display_errors();
			return false;
		}

		// Collect upload data.
		$data = $this->ci->upload->data();

		/**
		 * Here we are preparing the data of the new file details.
		 * Yes, file details are also stored in database.
		 */
		$file = array(
			// Entities table.
			'owner_id'    => $this->_parent->auth->user_id(),
			'username'    => $data['raw_name'],

			// Objects table.
			'name' => $data['raw_name'],

			// Metadata table.
			'content' => array(
				'file'      => $data['folder'].$data['file_name'],
				'path'      => $folder,
				'mime_type' => $data['file_type'],
				'file_ext'  => $data['file_ext'],
				'filesize'  => $data['file_size']
			)
		);

		// Only store these info for images.
		if ($data['is_image'])
		{
			$file['content']['width']  = $data['image_width'];
			$file['content']['height'] = $data['image_height'];
			(true == $add_thumbs) && $file['content']['sizes']  = array();
		}

		return $this->process($folder, $file, $data, $add_thumbs);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for processing uploaded files.
	 *
	 * @access 	private
	 * @param 	string 	$folder 	The folder where file is uploaded.
	 * @param 	object 	$data 		Details about uploaded file.
	 * @param 	bool 	$add_thumbs Whether to generate thumbnails.
	 * @return 	object
	 */
	private function process($folder, $file, $data, $add_thumbs = true)
	{
		// Insert the file to database.
		$guid = $this->create($file);
		if (false === $guid)
		{
			$this->message = $this->ci->lang->line('file_upload_error');
			return false;
		}

		// Grab back the file from database.
		$file['id'] = $guid;
		$db_file = $this->get($guid);

		// Process watermarking and thumbnails for images.
		if ($data['is_image'])
		{
			// Should we watermark it?
			if ($this->ci->config->item('image_watermark'))
			{
				$config['source_image']     = $data['full_path'];
				$config['wm_text']          = $this->ci->config->item('site_name').' '.date('Y');
				$config['wm_type']          = 'text';
				$config['wm_font_path']     = BASEPATH.'fonts/texb.ttf';
				$config['wm_font_size']     = '16';
				$config['wm_font_color']    = 'ffffff';
				$config['wm_vrt_alignment'] = 'bottom';
				$config['wm_hor_alignment'] = 'center';
				$config['wm_shadow_color']  = '444444';

				isset($this->ci->image_lib) OR $this->ci->load->library('image_lib', $config);

				$this->ci->image_lib->watermark();
				$this->ci->image_lib->clear();
			}

			// Should we create thumbs?
			(true === $add_thumbs) && $db_file = $this->create_thumbs($file, $db_file, $data);
		}

		// Something went wrong?
		if (false === $db_file)
		{
			$this->delete($guid);
			return false;
		}

		$this->message = $this->ci->lang->line('file_upload_success');
		return $this->get($guid);
	}

	// --------------------------------------------------------------------

	/**
	 * Handles creating thumbnails for uploaded images.
	 *
	 * @param 	array 	$file
	 * @param 	object 	$db_file
	 * @param 	array 	$data
	 * @param 	string 	$extension
	 * @param 	int 	$width
	 * @param 	int 	$height
	 * @return 	array
	 */
	private function create_thumbs($file, $db_file, $data)
	{
		if (empty($file) OR ! is_object($db_file) OR empty($data))
		{
			$this->message = $this->ci->lang->line('file_upload_error');
			return false;
		}

		// Make sure to load Image_lib
		isset($this->ci->image_lib) OR $this->ci->load->library('image_lib');

		// Prepare sizes to be stored in metadata.
		$db_sizes = $config = array();

		foreach ($this->_images_sizes as $name => $info)
		{
			$_width = $info['width'];
			$_height = $info['height'];

			if ($data['image_width'] < $info['width'])
			{
				// Exception only for Open Graph
				if ($name !== 'opengraph')
				{
					continue;
				}

				// Try half of it...
				if ($data['image_width'] < ($_width = $_width * 0.5))
				{
					continue;
				}

				$_height = $_height * 0.5;
			}

			$this->ci->image_lib->clear();
			unset($config['maintain_ratio']);

			// New image
			$image = sprintf(
				'%s%s-%sx%s%s',
				$data['folder'],
				$data['raw_name'],
				$_width,
				$_height,
				$data['file_ext']
			);

			// Prepare Image_lib config.
			$config['image_library']  = 'gd2';
			$config['source_image']   = $data['full_path'];
			$config['new_image']      = basename($image);
			$config['maintain_ratio'] = true;
			$config['width']          = $_width;
			$config['height']         = $_height;
			$config['quality']        = 80;

			$this->ci->image_lib->initialize($config);

			// Error processing?
			if (false === $this->ci->image_lib->process())
			{
				$this->message = $this->ci->image_lib->display_errors();
				return false;
			}

			// Should we crop?
			if (isset($info['crop']) && true === $info['crop'])
			{
				$config['maintain_ratio'] = false;
				$this->ci->image_lib->initialize($config);
				$this->ci->image_lib->process();
			}

			// Update db_sizes
			$db_sizes[$name] = array(
				'file'   => $image,
				'width'  => $_width,
				'height' => $_height
			);
		}

		// Update metadata.
		if ( ! empty($db_sizes))
		{
			$file['content']['sizes'] = $db_sizes;
			$db_file->update('content', to_bool_or_serialize($file['content']));
		}

		return $db_file;
	}

	// --------------------------------------------------------------------
	// UTILITIES
	// --------------------------------------------------------------------

	/**
	 * has_attachments
	 *
	 * function for checking whether the selected entity has an attached file.
	 *
	 * @param   mixed   $id     The entity's ID.
	 * @return  bool
	 */
	public function has_attachments($id)
	{
		$count = $this->ci->db
			->where('guid_to', $id)
			->where('relation', $this->relation_key)
			->count_all_results('relations');

		return (0 < $count);
	}

	// --------------------------------------------------------------------

	/**
	 * attachments
	 *
	 * Returns an array of all attachments.
	 *
	 * @param 	int 	$id 	The entity id
	 * @return 	array
	 */
	public function attachments($id)
	{
		$rels = $this->ci->db
			->where('guid_to', $id)
			->where('relation', $this->relation_key)
			->get('relations')
			->result();

		if ( ! $rels)
		{
			return false;
		}

		$files = array();

		foreach ($rels as $rel)
		{
			$files[] = $this->get($rel->guid_from);
		}

		return $files;
	}

	/**
	 * attach
	 *
	 * Attaches a file to the given entity.
	 *
	 * @param 	int 	$guid_from 	The file id.
	 * @param 	int 	$guid_to 	The entity id.
	 * @return 	int 	The relation's id.
	 */
	public function attach($guid_from, $guid_to)
	{
		// Multiple attachments?
		if (is_array($guid_from))
		{
			foreach ($guid_from as $id)
			{
				$this->attach($id, $guid_to);
			}

			return true;
		}

		$data = array(
			'guid_from' => $guid_from,
			'guid_to'   => $guid_to,
			'relation'  => $this->relation_key
		);

		$rel = $this->ci->db->get('relations', $data)->row();

		if ($rel)
		{
			return $rel->id;
		}

		$data['created_at'] = $data['updated_at'] = TIME;

		$this->ci->db->insert('relations', $data);

		return $this->ci->db->insert_id();
	}

	// --------------------------------------------------------------------

	/**
	 * Returns the thumbnail for the given file.
	 *
	 * @param 	int 	$file 	The file.
	 * @param 	string 	$size
	 * @return 	string
	 */
	public function thumbnail($file, $size = 'thumbnail')
	{
		// Already a URL?
		if (path_is_url($file))
		{
			return $file;
		}

		// Get the file if we don't provide it.
		is_object($file) OR $file = $this->get($file);

		// Not found?
		if (false === $file)
		{
			return false;
		}

		if ( ! empty($size) && isset($file->$size))
		{
			return $file->$size;
		}

		return $file->url;
	}

	// --------------------------------------------------------------------

	/**
	 * Displays admin dashboard menu.
	 * @since 	2.54
	 * @return 	void
	 */
	public function _menu_files()
	{
		echo admin_anchor('media', $this->ci->lang->line('admin_media'), 'class="dropdown-item"');
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
		if (true !== $this->_parent->auth->is_level(KB_LEVEL_AUTHOR))
		{
			return;
		}

		add_action('content_menu', array($this, '_menu_files'), 99);
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('blank_img_src'))
{
	/**
	 * Returns an empty PNG file source.
	 *
	 * @return 	strince
	 */
	function blank_img_src()
	{
		static $src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';

		return $src;
	}
}
