<?php
defined('BASEPATH') OR die;

if ( ! function_exists('bs_badge'))
{
	/**
	 * bs_badge
	 *
	 * Function for generating Bootstrap badges
	 *
	 * @author  Kader Bouyakoub
	 * @link    http://bit.ly/KaderGhb
	 * @since   1.4
	 *
	 * @param   string 	$value
	 * @param   string 	$class
	 * @param   string 	$before
	 * @param   string 	$after
	 * @return  void
	 */
	function bs_badge($value = '', $class = 'default', $before = '', $after = '')
	{
		static $template; // remember it

		isset($template) OR $template = '<span class="badge bg-%1$s">%2$s</span>';

		$badge = sprintf($template, $class, $value);

		empty($before) OR $badge = $before.$badge;

		empty($after) OR $badge .= $after;

		return $badge;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('info_box'))
{
	/**
	 * Generates an info box
	 *
	 * @since   2.0.1
	 *
	 * @param   string  $head
	 * @param   string  $text
	 * @param   string  $icon
	 * @param   string  $url
	 * @param   string  $color
	 * @return  string
	 */
	function info_box($head = null, $text = null, $icon = null, $url = null, $color = 'primary', $container = '', $container_attr = null)
	{
		$color && $color = ' bg-'.$color;

		$output = '';

		if ( ! empty($container))
		{
			$output .= "<{$container}".array_to_attr($container_attr).'>';
		}

		// Opening tag.
		$output .= "<div class=\"info-box{$color}\">";

		// Info box content.
		if (null !== $head OR null !== $text)
		{
			$output .= '<div class="inner">';
			(null !== $head) && $output .= '<h3>'.$head.'</h3>';
			(null !== $text) && $output .= '<p>'.$text.'</p>';
			$output .= '</div>';
		}

		// Add the icon.
		$icon && $output .= '<div class="icon end-0"><i class="fa fa-fw fa-'.$icon.'"></i></div>';

		if ($url)
		{
			$output .= html_tag('a', array(
				'href'  => $url,
				'class' => 'info-box-footer',
			), fa_icon('arrow-circle-right ms-2', null, line('manage')));
		}

		// Closing tag.
		$output .= '</div>';

		if ( ! empty($container))
		{
			$output .= "</{$container}>";
		}

		return $output;
	}
}
