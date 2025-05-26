<?php
defined('BASEPATH') OR die;

/**
 * KB_Image_lib Class
 *
 * Extends CodeIgniter Image_lib library in order to automatically handle
 * resizing/cropping images.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.5.0
 * @version 	1.5.0
 */
class KB_Image_lib extends CI_Image_lib
{
	/**
	 * Holds a copy of user specified width before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_width  = 0;

	/**
	 * Holds a copy of user specified height before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_height = 0;

	/**
	 * Holds a copy of user specified x_axis before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_x_axis = '';

	/**
	 * Holds a copy of user specified y_axis before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_y_axis = '';

	/**
	 * Thumb marker.
	 * @var string
	 */
	protected $_thumb_marker;

	/**
	 * Whether to create thumbs.
	 * @var bool
	 */
	protected $_create_thumb = false;

	/**
	 * Properties to be saved when using "resize_and_crop" method.
	 * @var array
	 */
	protected $_save_props = array('width', 'height', 'thumb_marker', 'create_thumb');

	/**
	 * initialize
	 *
	 * Simply cache user specified properties then call parent's initialize method.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.5.0
	 *
	 * @access 	public
	 * @param 	array
	 * @return 	bool
	 */
	public function initialize($props = array())
	{
		// Cache user specified properties before they are modified.
		isset($props['width']) && $this->_width = $props['width'];
		isset($props['height']) && $this->_height = $props['height'];
		isset($props['x_axis']) && $this->_x_axis = $props['x_axis'];
		isset($props['y_axis']) && $this->_y_axis = $props['y_axis'];

		// Call parent's "initialize" method.
		return parent::initialize($props);
	}

	// --------------------------------------------------------------------

	/**
	 * clear
	 *
	 * Reset this class properties then let the parent handle the rest.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.5.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function clear()
	{
		// We reset this class properties.
		$this->_width = 0;
		$this->_height = 0;
		$this->_x_axis = '';
		$this->_y_axis = '';

		// Let the parent do the rest.
		return parent::clear();
	}

	// --------------------------------------------------------------------

	/**
	 * process
	 *
	 * This is the method handling the hardest task: automatic resizing/cropping.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	http://bit.ly/KaderGhb
	 * @since 	1.5.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function process()
	{
		// Use user specified dimensions.
		$this->width  = $this->_width;
		$this->height = $this->_height;

		/**
		 * Mode 1:
		 * Auto-scale the image to fit one dimension.
		 */
		if ($this->_width == 0 OR $this->_height == 0)
		{
			if ($this->_width == 0)
			{
				$this->width = ceil($this->_height * $this->orig_width / $this->orig_height);
			}
			else
			{
				$this->height = ceil($this->_width * $this->orig_height / $this->orig_width);
			}

			return $this->resize();
		}

		/**
		 * Mode 2:
		 * Resize and crop the image to fit both dimensions.
		 */
		$this->width  = ceil($this->_height * ($this->orig_width / $this->orig_height));
		$this->height = ceil($this->_width * ($this->orig_height / $this->orig_width));
		
		if ($this->_width != $this->width && $this->_height != $this->height)
		{
			if ($this->master_dim == 'height')
			{
				$this->width = $this->_width;
			}
			else
			{
				$this->height = $this->_height;
			}
		}
		
		// We save the last dynamic output status for later use.
		$dynamic_output       = $this->dynamic_output;
		$this->dynamic_output = false;
		
		// We use a temporary file for dynamic output.
		$tempfile = false;
		
		// Dynamic output set to true?
		if (true === $dynamic_output)
		{
			// We create the file.
			$temp                = tmpfile();
			$tempfile            = array_search('uri', @array_flip(stream_get_meta_data($temp)));
			$this->full_dst_path = $tempfile;
		}
		
		// In case of an issue resizing the image.
		if (false === $this->resize())
		{
			return false;
		}
		
		// Now we calculate cropping axis.
		$this->x_axis = (is_numeric($this->_x_axis))
			? $this->_x_axis
			: floor(($this->width - $this->_width) / 2);
		
		$this->y_axis = (is_numeric($this->_y_axis))
			? $this->_y_axis
			: floor(($this->height - $this->_height) / 2);
		
		// We prepare class cropping options.
		$this->orig_width  = $this->width;
		$this->orig_height = $this->height;
		$this->width       = $this->_width;
		$this->height      = $this->_height;

		// We use the previous generated image for output.
		$this->full_src_path = $this->full_dst_path;
		
		// Put back dynamic output status to where it was.
		$this->dynamic_output = $dynamic_output;
		
		// Issue cropping the file?
		if (false === $this->crop())
		{
			return false;
		}
		
		/**
		 * Because we are nice enough :) ... We make sure to close and
		 * remove the temporary created file.
		 */
		if (false !== $tempfile)
		{
			fclose($temp);
		}
		
		return true;
	}

	// --------------------------------------------------------------------

	/**
	 * resize_and_crop
	 *
	 * Resizes and crops an image.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function resize_and_crop()
	{
		$this->maintain_ratio = true;

		foreach($this->_save_props as $sp)
		{
			$p = '_'.$sp;
			$this->$sp = $this->$p;
		}

		// get the current dimensions and see if it is a portrait or landscape image
		$props = $this->get_image_properties($this->source_folder.$this->source_image, true);
		$orig_width = $props['width'];
		$orig_height = $props['height'];

		empty($this->width) && $this->width = $orig_width;
		empty($this->height) && $this->height = $orig_height;

		if (empty($orig_width) OR empty($orig_height))
		{
			return false;
		}

		$w_ratio = $this->width / $orig_width;
		$h_ratio = $this->height / $orig_height;

		$this->master_dim = ($w_ratio > $h_ratio) ? 'width' : 'height';

		$this->image_reproportion();

		// resize image
		if ( ! $this->resize())
		{
			$this->set_error('imglib_could_not_resize');
		}

		if ( ! empty($this->dest_image))
		{
			// now crop if it is too wide
			$thumb_src = $this->explode_name($this->dest_image);
			$thumb_source = $thumb_src['name'].$this->thumb_marker.$thumb_src['ext'];
			$new_source = $this->dest_folder.$thumb_source;
			$props = $this->get_image_properties($new_source, true);
			$new_width = $props['width'];
			$new_height = $props['height'];

			$config = array();
			$config['width'] = $this->_width;
			$config['height'] = $this->_height;
			$config['source_image'] = $new_source;
			$config['maintain_ratio'] = false;
			$config['create_thumb'] = false;

			// portrait
			if ($new_width < $new_height)
			{
				$this->x_axis = 0;
				$this->y_axis = round(($new_height - $this->_height) / 2);
			}
			// landscape
			else
			{
				$this->x_axis = round(($new_width - $this->_width) / 2);
				$this->y_axis = 0;
			}

			$this->dest_folder = '';
			$this->initialize($config);

			if ( ! $this->crop())
			{
				$this->set_error('imglib_could_not_crop');
			}
		}
		else
		{
			$this->set_error('imglib_could_not_crop');
		}

		return (empty($this->error_msg));

	}

	// --------------------------------------------------------------------

	/**
	 * convert
	 *
	 * Coverts from an image file type to another.
	 *
	 * @access 	public
	 * @param 	mixed 	$ty
	 * @return 	bool
	 */
	public function convert($type = 'jpg', $delete_orig = false)
	{
		$this->full_dst_path = $this->dest_folder.end($this->explode_name($this->dest_image)).'.'.$type;

		if (false === ($src_img = $this->image_create_gd()))
		{
			return false;
		}

		if ('gd2' === $this->image_library && function_exists('imagecreatetruecolor'))
		{
			$create = 'imagecreatetruecolor';
			$copy = 'imagecopyresampled';
		}
		else
		{
			$create = 'imagecreate';
			$copy = 'imagecopyresized';
		}

		$dst_img = $create($this->width, $this->height);
		$copy($dst_img, $src_img, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);

		$types = array('gif' => 1, 'jpg' => 2, 'jpeg' => 2, 'png' => 3);

		$this->image_type = $types[$type];

		($delete_orig) && @unlink($this->full_src_path);

		if ($this->dynamic_output == true)
		{
			$this->image_display_gd($dst_img);
		}
		elseif ( ! $this->image_save_gd($dst_img))
		{
			return false;
		}

		imagedestroy($dst_img);
		imagedestroy($src_img);
		@chmod($this->full_dst_path, DIR_WRITE_MODE);

		return true;
	}

}
