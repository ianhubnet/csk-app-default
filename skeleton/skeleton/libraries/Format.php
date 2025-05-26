<?php
defined('BASEPATH') OR die;

/**
 * Format Class
 *
 * This library helps convert between various formats such us XML, JSON, JSON ...
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     1.0
 */
class Format
{
	/**
	 * Instace of CI object.
	 * @var object
	 */
	protected $ci;

	/**
	 * Array output format
	 * @var string
	 */
	private const ARRAY_FORMAT = 'array';

	/**
	 * Comma Separated Value (CSV) output format
	 * @var string
	 */
	private const CSV_FORMAT = 'csv';

	/**
	 * Json output format
	 * @var string
	 */
	private const JSON_FORMAT = 'json';

	/**
	 * HTML output format
	 * @var string
	 */
	private const HTML_FORMAT = 'html';

	/**
	 * PHP output format
	 * @var string
	 */
	private const PHP_FORMAT = 'php';

	/**
	 * Serialized output format
	 * @var string
	 */
	private const SERIALIZED_FORMAT = 'serialized';

	/**
	 * XML output format
	 * @var string
	 */
	private const XML_FORMAT = 'xml';

	/**
	 * Default format of this class
	 */
	private const DEFAULT_FORMAT = self::JSON_FORMAT; // Couldn't be DEFAULT, as this is a keyword

	/**
	 * Data to parse
	 * @var mixed
	 */
	protected $_data = array();

	/**
	 * Type to convert from
	 * @var string
	 */
	protected $_from_type = null;

	/**
	 * Initialize class.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		Autoloader::add_class('Html2Text', KBPATH.'third_party/bkader/class-html2text.php');
	}

	// --------------------------------------------------------------------

	/**
	 * Create an instance of the format class
	 * e.g: echo $this->format->forge(['foo' => 'bar'])->to_csv();
	 *
	 * @param mixed $data Data to convert/parse
	 * @param string $from_type Type to convert from e.g. json, csv, html
	 *
	 * @return object Instance of the format class
	 */
	public function forge($data, $from_type = null)
	{
		return new static($data, $from_type);
	}

	// --------------------------------------------------------------------
	// FORMATTING OUTPUT
	// --------------------------------------------------------------------

	/**
	 * Format data as an array
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @return array Data parsed as an array; otherwise, an empty array
	 */
	public function to_array($data = null)
	{
		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		// Cast as an array if not already
		if (is_array($data) === false)
		{
			$data = (array) $data;
		}

		$array = array();
		foreach ((array) $data as $key => $value)
		{
			if (is_object($value) === true || is_array($value) === true)
			{
				$array[$key] = $this->to_array($value);
			}
			else
			{
				$array[$key] = $value;
			}
		}

		return $array;
	}

	// --------------------------------------------------------------------

	/**
	 * Format data as XML
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @param null $structure
	 * @param string $basenode
	 * @return mixed
	 */
	public function to_xml($data = null, $structure = null, $basenode = 'xml')
	{
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		if ($structure === null)
		{
			$structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
		}

		// Force it to be something useful
		if (is_array($data) === false && is_object($data) === false)
		{
			$data = (array) $data;
		}

		foreach ($data as $key => $value)
		{

			//change false/true to 0/1
			if (is_bool($value))
			{
				$value = (int) $value;
			}

			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				function_exists('singular') OR $this->ci->load->helper('inflector');

				// make string key...
				$key = (singular($basenode) != $basenode) ? singular($basenode) : 'item';
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_\-0-9]/i', '', $key);

			if ($key === '_attributes' && (is_array($value) || is_object($value)))
			{
				$attributes = $value;
				if (is_object($attributes))
				{
					$attributes = get_object_vars($attributes);
				}

				foreach ($attributes as $attribute_name => $attribute_value)
				{
					$structure->addAttribute($attribute_name, $attribute_value);
				}
			}
			// if there is another array found recursively call this function
			elseif (is_array($value) || is_object($value))
			{
				$node = $structure->addChild($key);

				// recursive call.
				$this->to_xml($value, $node, $key);
			}
			else
			{
				// add single node.
				$value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');

				$structure->addChild($key, $value);
			}
		}

		return $structure->asXML();
	}

	// --------------------------------------------------------------------

	/**
	 * Format data as HTML
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @return mixed
	 */
	public function to_html($data = null)
	{
		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		// Cast as an array if not already
		if (is_array($data) === false)
		{
			$data = (array) $data;
		}

		// Check if it's a multi-dimensional array
		if (isset($data[0]) && count($data) !== count($data, COUNT_RECURSIVE))
		{
			// Multi-dimensional array
			$headings = array_keys($data[0]);
		}
		else
		{
			// Single array
			$headings = array_keys($data);
			$data = [$data];
		}

		// Load the table library
		$this->ci->load->library('table');

		$this->ci->table->set_heading($headings);

		foreach ($data as $row)
		{
			// Suppressing the "array to string conversion" notice
			// Keep the "evil" @ here
			$row = @array_map('strval', $row);

			$this->ci->table->add_row($row);
		}

		return $this->ci->table->generate();
	}

	// --------------------------------------------------------------------

	/**
	 * Formats data as plain text using Html2Text class.
	 *
	 * @param   string  $data   the string to textify
	 * @return  string  the string after all html stuff stripped.
	 */
	public function to_text($data = '', $options = array())
	{
		$html = new Html2Text($data, $options);
		return $html->getText();
	}

	// --------------------------------------------------------------------

	/**
	 * @link http://www.metashock.de/2014/02/create-csv-file-in-memory-php/
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @param string $delimiter The optional delimiter parameter sets the field
	 * delimiter (one character only). null will use the default value (,)
	 * @param string $enclosure The optional enclosure parameter sets the field
	 * enclosure (one character only). null will use the default value (")
	 * @return string A csv string
	 */
	public function to_csv($data = null, $delimiter = ',', $enclosure = '"')
	{
		// Use a threshold of 1 MB (1024 * 1024)
		$handle = fopen('php://temp/maxmemory:1048576', 'w');
		if ($handle === false)
		{
			return null;
		}

		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		// If null, then set as the default delimiter
		if ($delimiter === null)
		{
			$delimiter = ',';
		}

		// If null, then set as the default enclosure
		if ($enclosure === null)
		{
			$enclosure = '"';
		}

		// Cast as an array if not already
		if (is_array($data) === false)
		{
			$data = (array) $data;
		}

		// Check if it's a multi-dimensional array
		if (isset($data[0]) && count($data) !== count($data, COUNT_RECURSIVE))
		{
			// Multi-dimensional array
			$headings = array_keys($data[0]);
		}
		else
		{
			// Single array
			$headings = array_keys($data);
			$data = [$data];
		}

		// Apply the headings
		fputcsv($handle, $headings, $delimiter, $enclosure);

		foreach ($data as $record)
		{
			// If the record is not an array, then break. This is because the 2nd param of
			// fputcsv() should be an array
			if (is_array($record) === false)
			{
				break;
			}

			// Suppressing the "array to string conversion" notice.
			// Keep the "evil" @ here.
			$record = @ array_map('strval', $record);

			// Returns the length of the string written or false
			fputcsv($handle, $record, $delimiter, $enclosure);
		}

		// Reset the file pointer
		rewind($handle);

		// Retrieve the csv contents
		$csv = stream_get_contents($handle);

		// Close the handle
		fclose($handle);

		// Convert UTF-8 encoding to UTF-16LE which is supported by MS Excel
		$csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');

		return $csv;
	}

	// --------------------------------------------------------------------

	/**
	 * Encode data as json
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @return string Json representation of a value
	 */
	public function to_json($data = null)
	{
		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		// Get the callback parameter (if set)
		$callback = $this->ci->input->get('callback');

		if (empty($callback) === true)
		{
			return json_encode($data, JSON_UNESCAPED_UNICODE);
		}

		// We only honour a jsonp callback which are valid javascript identifiers
		elseif (preg_match('/^[a-z_\$][a-z0-9\$_]*(\.[a-z_\$][a-z0-9\$_]*)*$/i', $callback))
		{
			// Return the data as encoded json with a callback
			return $callback.'('.json_encode($data, JSON_UNESCAPED_UNICODE).');';
		}

		// An invalid jsonp callback function provided.
		// Though I don't believe this should be hardcoded here
		$data['warning'] = 'INVALID JSONP CALLBACK: '.$callback;

		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	// --------------------------------------------------------------------

	/**
	 * Encode data as a serialized array
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @return string Serialized data
	 */
	public function to_serialized($data = null)
	{
		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		return serialize($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Format data using a PHP structure
	 *
	 * @param mixed|null $data Optional data to pass, so as to override the data passed
	 * to the constructor
	 * @return mixed String representation of a variable
	 */
	public function to_php($data = null)
	{
		// If no data is passed as a parameter, then use the data passed
		// via the constructor
		if ($data === null && func_num_args() === 0)
		{
			$data = $this->_data;
		}

		return var_export($data, true);
	}

	// --------------------------------------------------------------------
	// INTERNAL FUNCTIONS
	// --------------------------------------------------------------------


	/**
	 * @param string $data XML string
	 * @return array XML element object; otherwise, empty array
	 */
	protected function _from_xml($data)
	{
		return $data ? (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}

	// --------------------------------------------------------------------

	/**
	 * @param string $data CSV string
	 * @param string $delimiter The optional delimiter parameter sets the field
	 * delimiter (one character only). null will use the default value (,)
	 * @param string $enclosure The optional enclosure parameter sets the field
	 * enclosure (one character only). null will use the default value (")
	 * @return array A multi-dimensional array with the outer array being the number of rows
	 * and the inner arrays the individual fields
	 */
	protected function _from_csv($data, $delimiter = ',', $enclosure = '"')
	{
		// If null, then set as the default delimiter
		if ($delimiter === null)
		{
			$delimiter = ',';
		}

		// If null, then set as the default enclosure
		if ($enclosure === null)
		{
			$enclosure = '"';
		}

		return str_getcsv($data, $delimiter, $enclosure);
	}

	// --------------------------------------------------------------------

	/**
	 * @param string $data Encoded json string
	 * @return mixed Decoded json string with leading and trailing whitespace removed
	 */
	protected function _from_json($data)
	{
		return json_decode(trim($data));
	}

	// --------------------------------------------------------------------

	/**
	 * @param string $data Data to unserialize
	 * @return mixed Unserialized data
	 */
	protected function _from_serialize($data)
	{
		return unserialize(trim($data));
	}

	// --------------------------------------------------------------------

	/**
	 * @param string $data Data to trim leading and trailing whitespace
	 * @return string Data with leading and trailing whitespace removed
	 */
	protected function _from_php($data)
	{
		return trim($data);
	}

}
