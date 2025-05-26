<?php
defined('BASEPATH') OR die;

/**
 * KB_File Class
 *
 * Core class used to implement the KB_File object.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries\Kbcore
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.48
 */
class KB_File extends KB_Object
{
	/**
	 * Various flags used to determine file type.
	 * @var bool
	 */
	public $is_pdf   = false;
	public $is_image = false;
	public $is_video = false;
	public $is_pptx  = false;
	public $is_txt   = false;

	// --------------------------------------------------------------------

	/**
	 * Sets up object properties.
	 *
	 * @param 	object 	$data
	 */
	public function init($data)
	{
		parent::init($data);

		// Invalid? nothing to do...
		if (0 >= $this->data->id)
		{
			return;
		}

		(is_string($this->data->content)) && $this->data->content = from_bool_or_serialize($this->data->content);

		$this->url = get_instance()->config->uploads_url($this->data->content['file']);

		(isset($this->file_ext)) OR $this->file_ext = pathinfo($this->url, PATHINFO_EXTENSION);

		if ('application/pdf' === $this->content['mime_type'])
		{
			$this->is_pdf = true;
		}
		elseif (is_file_image($this->url))
		{
			$this->is_image = true;

			if (isset($this->data->content['sizes']))
			{
				$CI =& get_instance();
				foreach ($this->data->content['sizes'] as $key => $val)
				{
					$this->$key = $CI->config->uploads_url($val['file']);
				}
			}
		}
		elseif (is_file_video($this->url))
		{
			$this->is_video = true;
		}
		elseif ('pptx' === $this->file_ext)
		{
			$this->is_pptx = true;
		}
		elseif ('txt' === $this->file_ext)
		{
			$this->is_txt = true;
		}
	}

}
