<?php
defined('BASEPATH') OR die;

/**
 * KB_table Class
 *
 * Extends CodeIgniter table library.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.33
 * @version 	2.0
 */
class KB_Table extends CI_Table
{
	/**
	 * Data for table footer.
	 *
	 * @var array
	 */
	public $footer = array();

	/**
	 * We apply the "table_tags" filter to table tags stored in config.
	 * @access 	public
	 * @param 	array 	$config 	The configuration array.
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		/**
		 * Because the dashboard is built using Bootstrap, we simply
		 * ignore any configuration file or template set.
		 *
		 * On other site's sections, we allow modules and themes
		 * use the "table_tags" filter to alter tag.
		 * @see 	application/config/table.php
		 */
		parent::__construct(get_instance()->uri->is_dashboard ? array() : apply_filters('table_tags', $config));
	}

	// --------------------------------------------------------------------

	/**
	 * Set the table footer
	 *
	 * Can be passed as an array or discreet parameters.
	 *
	 * @access 	public
	 * @param 	mixed
	 * @return 	KB_Table
	 */
	public function set_footer($args = array())
	{
		$this->footer = $this->_prep_args(func_get_args());
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * We override parent's "generate" method in order to add table footer.
	 * @access 	public
	 * @param 	mixed 	$table_data
	 * @return 	string
	 */
	public function generate($table_data = null)
	{
		// We let the parent build the first part.
		$out = parent::generate($table_data);

		// This error is return by the parent if no data were found.
		if ('Undefined table data' == $out)
		{
			return $out;
		}

		// Is there a table footer to display?
		if ( ! empty($this->footer))
		{
			// We make sure to remove the table's closing tag.
			$out = str_replace($this->template['table_close'], '', $out);
			
			// We start building our footer with opening tags.
			$out .= $this->template['tfoot_open'].$this->newline.$this->template['footer_row_start'].$this->newline;

			// We loop through data and build the rest of the output.
			foreach ($this->footer as $footer)
			{
				$temp = $this->template['footer_cell_start'];

				foreach ($footer as $key => $val)
				{
					if ($key !== 'data')
					{
						$temp = str_replace('<th', '<th '.$key.'="'.$val.'"', $temp);
					}
				}

				$out .= $temp.(isset($footer['data']) ? $footer['data'] : '').$this->template['footer_cell_end'];
			}

			$out .= $this->template['footer_row_end'].$this->newline.$this->template['tfoot_close'].$this->newline;
			
			// Put back table closing tag.
			$out .= $this->template['table_close'];
		}

		// The parent already cleared its things, we clear ours.
		$this->footer = array();

		// Return the final output.
		return $out;

	}

	// --------------------------------------------------------------------

	/**
	 * Set table data from a database result object
	 * @param	CI_DB_result	$object	Database result object
	 * @return	void
	 */
	protected function _set_from_db_result($object)
	{
		// The parent will handle heading and rows.
		parent::_set_from_db_result($object);

		// And we handle the footer.
		if ( ! empty($this->footer))
		{
			$this->footer = $this->_prep_args($object->list_fields());
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set table data from an array.
	 * @access 	protected
	 * @param 	array 	$data
	 * @return 	void
	 */
	protected function _set_from_array($data)
	{
		// The parent will handle heading and rows.
		parent::_set_from_array($data);

		// And we handle the footer.
		if ( ! empty($this->footer))
		{
			$this->footer = $this->_prep_args(array_shift($data));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Override parent's method to add our "tfoot" tag.
	 * @access 	protected
	 * @return 	void
	 */
	protected function _compile_template()
	{
		// If no template provided, we use the default one.
		if ($this->template === null)
		{
			$this->template = $this->_default_template();
			return;
		}

		// We make sure to add our tag: tfoot_open and tfoot_close.
		$tags = array(
			'tfoot_open',
			'tfoot_close',
			'footer_row_start',
			'footer_row_end',
			'footer_cell_start',
			'footer_cell_end',
		);

		$this->temp = $this->_default_template();
		foreach ($tags as $tag)
		{
			if ( ! isset($this->template[$tag]))
			{
				$this->template[$tag] = $this->temp[$tag];
			}
		}

		// Now we let the parent do the rest.
		parent::_compile_template();
	}

	// --------------------------------------------------------------------

	/**
	 * Override libraries default template so we can use Bootstrap on
	 * the dashboard, and any other template your provide or set by
	 * your site's files (modules, themes ...).
	 * @access 	protected
	 * @return 	array
	 */
	protected function _default_template()
	{
		static $template = array(
			// Table open and close tags.
			'table_open'  => '<table class="table table-sm table-hover table-striped mb-0">',
			'table_close' => '</table>',
			
			// Table header open and close tags.
			'thead_open'         => '<thead>',
			'thead_close'        => '</thead>',
			'heading_row_start'  => '<tr>',
			'heading_row_end'    => '</tr>',
			'heading_cell_start' => '<th>',
			'heading_cell_end'   => '</th>',
			
			// Table body open and close tags.
			'tbody_open'  => '<tbody>',
			'tbody_close' => '</tbody>',
			
			// Table footer open and close tags.
			'tfoot_open'        => '<tfoot>',
			'tfoot_close'       => '</tfoot>',
			'footer_row_start'  => '<tr>',
			'footer_row_end'    => '</tr>',
			'footer_cell_start' => '<td>',
			'footer_cell_end'   => '</td>',
			
			// Rows and alternatives, open and close tags.
			'row_start'     => '<tr>',
			'row_end'       => '</tr>',
			'row_alt_start' => '<tr>',
			'row_alt_end'   => '</tr>',
			
			// Cells and alternatives, open and close tags.
			'cell_start'     => '<td>',
			'cell_end'       => '</td>',
			'cell_alt_start' => '<td>',
			'cell_alt_end'   => '</td>',
		);

		return $template;
	}

}
