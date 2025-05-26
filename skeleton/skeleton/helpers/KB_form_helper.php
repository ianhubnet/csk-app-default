<?php
defined('BASEPATH') OR die;

/**
 * KB_form_helper
 *
 * Extending and overriding some of CodeIgniter form function.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Helpers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       1.0
 * @version     2.13
 */

if ( ! function_exists('form_nonce'))
{
	/**
	 * form_nonce
	 *
	 * Function for creating hidden nonce fields for form.
	 *
	 * The once field is used to make sure that the contents of the form came
	 * from the location on the current site and not from somewhere else. This
	 * is not an absolute protection option, but bu should protect against most
	 * cases. Make sure to always use it for forms you want to protect.
	 *
	 * Both $action and $name are optional, but it is highly recommended that
	 * you provide them. Anyone who inspects your code (PHP) would simply guess
	 * what should be used to cause damage. So please, provide them.
	 *
	 * Make sure to always check again your fields values after submission before
	 * your process.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @param   string  $action     The action used to generate nonce.
	 * @param   bool    $build      Whether to build form inputs or return the array.
	 * @return  string
	 */
	function form_nonce($action = -1, $build = true)
	{
		$fields[COOK_CSRF] = get_instance()->nonce->create($action);
		return (true === $build) ? form_hidden($fields) : $fields;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('form_textarea'))
{
	/**
	 * Textarea field
	 *
	 * @param   mixed   $data
	 * @param   string  $value
	 * @param   mixed   $extra
	 * @param   bool 	$html_escape
	 * @return  string
	 */
	function form_textarea($data = '', $value = '', $extra = '', $html_escape = true)
	{
		$defaults = array(
			'name' => is_array($data) ? '' : $data,
			'cols' => '40',
			'rows' => '10'
		);

		if ( ! is_array($data) OR ! isset($data['value']))
		{
			$val = $value;
		}
		else
		{
			$val = $data['value'];
			unset($data['value']); // textareas don't use the value attribute
			is_bool($value) && $html_escape = $value;
		}

		return '<textarea '._parse_form_attributes($data, $defaults)._attributes_to_string($extra).'>'
			.($html_escape ? html_escape($val) : $val)
			."</textarea>\n";
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('check_form_nonce'))
{
	/**
	 * check_form_nonce
	 *
	 * Method for checking forms with added security nonce.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   2.16
	 *
	 * @param   string  $action     The action attached (Optional).
	 * @return  bool
	 */
	function check_form_nonce($action = null)
	{
		return get_instance()->nonce->verify_request($action);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('print_input'))
{
	/**
	 * Prints a form input with possibility to add extra
	 * attributes instead of using array_merge on views.
	 * @param   array   $input  form input details.
	 * @param   array   $attrs  additional attributes.
	 * @return  string  the full form input string.
	 */
	function print_input($input = array(), array $attrs = array())
	{
		static $ignored;

		// If $input is empty, nothing to do.
		if ( ! is_array($input) OR empty($input))
		{
			return '';
		}

		// Merge all attributes if there any.
		if ( ! empty($attrs))
		{
			foreach ($attrs as $key => $val)
			{
				if (is_int($key))
				{
					$input[$val] = $val;
					continue;
				}

				/**
				 * We make sure to concatenate CSS classes.
				 * @since   2.13
				 */
				if ('class' === $key && isset($input['class']))
				{
					$input['class'] = trim($input['class'].' '.$val);
					continue;
				}

				$input[$key] = $val;
			}
		}

		// Array of attributes not to transfigure.
		if ( ! isset($ignored))
		{
			$ignored = array(
				'autocomplete',
				'autofocus',
				'class',
				'disabled',
				'form',
				'formaction',
				'formenctype',
				'formmethod',
				'formtarget',
				'id',
				'list',
				'multiple',
				'readonly',
				'rel',
				'required',
				'step',
			);
		}

		array_walk($input, function(&$val, $key) use ($ignored) {
			(in_array($key, $ignored)) OR $val = _transfigure($val);
		});

		/**
		 * Here we loop through all input elements only if it's found,
		 * otherwise, it will simply fall back to "form_input".
		 */
		if (isset($input['type']))
		{
			switch ($input['type'])
			{
				// In case of a textarea.
				case 'textarea':
					unset($input['type']);
					return form_textarea($input, false);
					break;

				// In case of a dropdwn/select.
				case 'select':
				case 'dropdown':
					$name = $input['name'];
					$options = array_map('_translate', $input['options']);
					unset($input['name'], $input['options']);
					if (isset($input['selected']))
					{
						$selected = $input['selected'];
						unset($input['selected']);
					}
					else
					{
						$selected = array();
					}

					// Firefox/Browser 'selected' hack (autocomplete)
					isset($input['autocomplete']) OR $input['autocomplete'] = 'off';

					return form_dropdown($name, $options, $selected, $input);
					break;

				// Default one.
				default:
					return form_input($input);
					break;
			}
		}


		/**
		 * If the user provded a "label" key, we make sure to generate
		 * form label before returning the final output.
		 */
		if (isset($input['label']))
		{
			$label_text = $input['label'];
			unset($input['label']);

			return form_label($label_text, $input['name']).PHP_EOL.form_input($input);
		}

		// Fall-back to form input.
		return form_input($input);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('validation_errors_list'))
{
	/**
	 * Return form validation errors in custom HTML list.
	 * Default: unordered list.
	 * @access  public
	 * @return  string  if found, else empty string.
	 */
	function validation_errors_list()
	{
		return (false === ($OBJ =& _get_validation_object()))
			? ''
			: $OBJ->validation_errors_list();
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('form_error'))
{
	/**
	 * Form Error
	 *
	 * Returns the error for a specific form field. This is a helper for the
	 * form validation class.
	 *
	 * @param   string
	 * @param   string
	 * @param   string
	 * @param   string
	 * @return  string
	 */
	function form_error($field = '', $prefix = '', $suffix = '', $default = '')
	{
		if (false === ($OBJ =& _get_validation_object()))
		{
			return '';
		}

		return $OBJ->error($field, $prefix, $suffix, $default);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('has_error'))
{
	/**
	 * has_error
	 *
	 * Function for checking whether the selected field has any errors.
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @access  public
	 * @param   string  $field  The field's name to check.
	 * @return  bool    true if there are error, else false.
	 */
	function has_error($field)
	{
		$CI =& get_instance();
		(isset($CI->form_validation)) OR $CI->load->library('form_validation');
		return $CI->form_validation->has_error($field);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('error_class'))
{
	/**
	 * error_class
	 *
	 * Builds input class attribute and appends error class to it.
	 *
	 * @param   string  $field  The field's name to check.
	 * @return  bool    true if there are error, else false.
	 */
	function error_class($field, $class = '', $error_class = 'is-invalid')
	{
		$CI =& get_instance();
		(isset($CI->form_validation)) OR $CI->load->library('form_validation');
		($CI->form_validation->has_error($field)) && $class .= ' '.$error_class;
		return $class;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('form_open'))
{
	/**
	 * Form Declaration
	 *
	 * Replaces CodeIgniter default function to force including GET request
	 *
	 * @param   string  the URI segments of the form destination
	 * @param   array   a key/value pair of attributes
	 * @param   array   a key/value pair hidden data
	 * @return  string
	 */
	function form_open($action = '', $attributes = array(), $hidden = array())
	{
		$CI =& get_instance();

		$form = '<form';

		// If no action is provided then set to the current url
		if (false !== $action)
		{
			if (empty($action))
			{
				$action = $CI->config->site_url($CI->uri->uri_string(true));
			}
			// If an action is not a full URL then turn it into one
			elseif (strpos($action, '://') === false)
			{
				$action = $CI->config->site_url($action);
			}

			$form .= ' action="'.$action.'"';
		}

		$attributes = _attributes_to_string($attributes);

		if (stripos($attributes, 'method=') === false)
		{
			$attributes .= ' method="post"';
		}

		if (stripos($attributes, 'accept-charset=') === false)
		{
			$attributes .= ' accept-charset="'.strtolower(config_item('charset')).'"';
		}

		$form .= $attributes.">\n";

		// If nonce is provided, no need to use CSRF protection.
		if (is_array($hidden))
		{
			$form .= form_hidden($hidden);

			if (isset($hidden[COOK_CSRF]))
			{
				return $form;
			}
		}

		// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites
		if ($CI->config->item('csrf_protection') === true && strpos($action, $CI->config->base_url()) !== false && ! stripos($form, 'method="get"'))
		{
			// Prepend/append random-length "white noise" around the CSRF
			// token input, as a form of protection against BREACH attacks
			if (false !== ($noise = $CI->security->get_random_bytes(1)))
			{
				$noise = unpack('c', $noise)[1];
			}
			else
			{
				$noise = mt_rand(-128, 127);
			}

			// Prepend if $noise has a negative value, append if positive, do nothing for zero
			$prepend = $append = '';
			if ($noise < 0)
			{
				$prepend = str_repeat(" ", abs($noise));
			}
			elseif ($noise > 0)
			{
				$append  = str_repeat(" ", $noise);
			}

			$form .= $prepend;
			$form .= form_hidden($CI->security->get_csrf_token_name(), $CI->security->get_csrf_hash());
			$form .= $append."\n";
		}

		return $form;
	}
}
