<?php
defined('BASEPATH') OR die;

/**
 * KB_Pagination Class
 *
 * We are extending CodeIgniter pagination library so we
 * can use our hooks system and filters some of configuration
 * parameters before passing them back to the parent.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0
 * @version 	2.0
 */
class KB_Pagination extends CI_Pagination
{
	/**
	 * Initialize references.
	 *
	 * @param 	array 	$params 	Initialization parameters.
	 * @return 	CI_Pagination
	 */
	public function initialize(array $params = array())
	{
		/**
		 * We force using bootstrap pagination on dashboard.
		 * @since   1.33
		 */
		if ($this->CI->uri->is_dashboard)
		{
			return parent::initialize(array_merge($params, $this->_admin_params()));
		}

		/**
		 * Otherwise, we use filters if we have any.
		 * @since 	1.33
		 */
		if (has_filter('pagination'))
		{
			// List of parameters that filters can be applied to.
			$filterable_params = $this->_filterable_params();

			// Apply the pagination filter to our parameters.
			$filtered_params = array_intersect_key($params, array_flip($filterable_params));
			$filtered_params = apply_filters('pagination', $filtered_params);

			// For security reasons, we remove unaccepted parameters.
			foreach ($filtered_params as $key => $val)
			{
				if ( ! in_array($key, $filterable_params))
				{
					unset($filtered_params[$key]);
				}
			}

			// We merge back parameters together.
			return parent::initialize(array_merge($params, $filtered_params));
		}

		return parent::initialize($params);
	}

	// --------------------------------------------------------------------

	/**
	 * Pagination dashboard parameters
	 *
	 * Because the dashboard is build using Bootstrap, parameters below
	 * are for Bootstrap pagination.
	 *
	 * @since 	1.33
	 *
	 * @access 	protected
	 * @return 	array
	 */
	protected function _admin_params()
	{
		return array(
			'full_tag_open'        => '<div class="text-center"><ul class="pagination pagination-sm pagination-centered mb-0">',
			'full_tag_close'       => '</ul></div>',
			'num_links'            => 5,
			'num_tag_open'         => '<li class="page-item">',
			'num_tag_close'        => '</li>',
			'prev_tag_open'        => '<li class="page-item">',
			'prev_tag_close'       => '</li>',
			'prev_link'            => '<i class="fa fa-fw fa-backward"></i>',
			'next_tag_open'        => '<li class="page-item">',
			'next_tag_close'       => '</li>',
			'next_link'            => '<i class="fa fa-fw fa-forward"></i>',
			'first_tag_open'       => '<li class="page-item">',
			'first_tag_close'      => '</li>',
			'first_link'           => '<i class="fa fa-fw fa-fast-backward"></i>',
			'last_tag_open'        => '<li class="page-item">',
			'last_tag_close'       => '</li>',
			'last_link'            => '<i class="fa fa-fw fa-fast-forward"></i>',
			'cur_tag_open'         => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close'        => '<span class="sr-only">(current)</span></span></li>',
			'use_page_numbers'     => true,
			'page_query_string'    => true,
			'query_string_segment' => 'page',
			'display_pages'        => true,
			'attributes'           => array('class' => 'page-link'),
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Pagination filterable parameters
	 *
	 * @since 	1.33
	 *
	 * @access 	protected
	 * @return 	array
	 */
	protected function _filterable_params()
	{
		return array(
			'full_tag_open',
			'full_tag_close',
			'num_links',
			'num_tag_open',
			'num_tag_close',
			'prev_tag_open',
			'prev_tag_close',
			'prev_link',
			'next_tag_open',
			'next_tag_close',
			'next_link',
			'first_tag_open',
			'first_tag_close',
			'first_link',
			'last_tag_open',
			'last_tag_close',
			'last_link',
			'cur_tag_open',
			'cur_tag_close',
			'display_pages',
			'attributes',
		);
	}

}
