<?php
defined('BASEPATH') OR die;

/**
 * KB_Object Class
 *
 * Core class used to implement the KB_Object object.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries\Kbcore
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.30
 */
class KB_Object extends KB_Entity
{
	/**
	 * Driver to use.
	 * @var string
	 */
	protected $driver = 'objects';

	/**
	 * Constructor.
	 *
	 * Retrieves the object data and passes it to KB_Object::init().
	 *
	 * @param   mixed    $id    Object's ID, username, object or WHERE clause.
	 * @return  void
	 */
	public function __construct($id = 0)
	{
		if ($id instanceof KB_Object)
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
			$data = get_instance()->objects->get_by('id', $id);
		}
		elseif (is_string($id))
		{
			$data = get_instance()->objects->get_by('username', $id);
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
	 * @param 	object 	$data
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
		$this->data->parent_id  = (int) $this->data->parent_id;
		$this->data->owner_id   = (int) $this->data->owner_id;
		$this->data->privacy    = (int) $this->data->privacy;
		$this->data->enabled    = (int) $this->data->enabled;
		$this->data->deleted    = (int) $this->data->deleted;
		$this->data->created_at = (int) $this->data->created_at;
		$this->data->updated_at = (int) $this->data->updated_at;
		$this->data->deleted_at = (int) $this->data->deleted_at;

		/**
		 * Things that should be strings. We make sure to us
		 * 'html_escape' except for the content.
		 */
		$this->data->name        = html_escape($this->data->name, false);
		$this->data->description = html_escape($this->data->description, false);
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves object meta from metadata table and cache them.
	 *
	 * @return 	void
	 */
	public function set_meta()
	{
		$CI =& get_instance();

		$meta_keys = array_merge($CI->objects->metas, $CI->files->image_sizes());

		$query = $CI->db
			->where('guid', $this->id)
			->where_in('name', $meta_keys)
			->get('metadata');

		if (0 >= $query->num_rows())
		{
			foreach ($meta_keys as $key)
			{
				$this->data->$key = null;
			}

			return;
		}

		foreach ($query->result() as $row)
		{
			$this->data->{$row->name} = from_bool_or_serialize($row->value);
		}

		$query->free_result();

		foreach ($meta_keys as $key)
		{
			isset($this->data->$key) OR $this->data->$key = null;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Parses object name, description and content for any language lines.
	 *
	 * @param 	CI_Controller 	$CI
	 * @return 	void
	 */
	public function parse(CI_Controller $CI)
	{
		$this->data->name = $CI->i18n->parse($this->data->name);
		$this->data->description = $CI->i18n->parse($this->data->description);
		$this->data->content = $CI->i18n->parse($this->data->content);
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method that returns localized name.
	 *
	 * @return 	string
	 */
	public function __toString()
	{
		return $this->name;
	}

}
