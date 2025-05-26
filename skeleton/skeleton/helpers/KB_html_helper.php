<?php
defined('BASEPATH') OR die;

/**
 * KB_html_helper
 *
 * Extending and overriding some of CodeIgniter html function.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Helpers
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		1.0.0
 * @version 	1.0.0
 */

if ( ! function_exists('img'))
{
	/**
	 * We simply inverted $attributes and $index_page
	 *
	 * Generates an <img /> element
	 *
	 * @param	mixed
	 * @param	mixed
	 * @param	bool
	 * @return	string
	 */
	function img($src = '', $attributes = '', $index_page = false)
	{
		is_array($src) OR $src = array('src' => $src);

		is_string($attributes) && $attributes = attr_to_array($attributes);

		$src = array_merge($src, $attributes);

		// Chrome built-in lazy loading.
		isset($src['loading']) OR $src['loading'] = 'lazy';

		isset($src['alt']) OR $src['alt'] = '';

		$img = '<img';

		if ( ! empty($src['src']) && ! preg_match('#^(data:[a-z,;])|(([a-z]+:)?(?<!data:)//)#i', $src['src']))
		{
			$src['src'] = (true === $index_page)
				? get_instance()->config->site_url($src['src'])
				: get_instance()->config->content_url($src['src']);
		}

		return $img.array_to_attr($src).' />';
	}
}

// --------------------------------------------------------------------

/**
 * Create a XHTML tag
 *
 * @param	string			The tag name
 * @param	array|string	The tag attributes
 * @param	string|bool		The content to place in the tag, or false for no closing tag
 * @return	string
 */
if ( ! function_exists('html_tag'))
{
	function html_tag($tag, $attr = array(), $content = '')
	{
		if (empty($tag))
		{
			return $content;
		}

		// list of void elements (tags that can not have content)
		static $void_elements = array(
			// html4
			"area","base","br","col","hr","img","input","link","meta","param",
			// html5
			"command","embed","keygen","source","track","wbr",
			// html5.1
			"menuitem",
		);

		/**
		 * Add a custom tag so we can define language direction.
		 * @since	 2.0
		 */
		$CI =& get_instance();

		if ('login' !== $CI->router->class
			&& $CI->i18n->is_rtl()
			&& ('input' === $tag OR ! in_array($tag, $void_elements)))
		{
			if (is_array($attr) && ! isset($attr['dir']))
			{
				$attr['dir'] = 'rtl';
			}
			elseif (is_string($attr) && false === stripos($attr, 'dir="'))
			{
				$attr .= ' dir="rtl"';
			}
		}

		// construct the HTML
		$html = '<'.$tag;
		$html .= ( ! empty($attr)) ? (is_array($attr) ? array_to_attr($attr) : ' '.$attr) : '';

		// a void element?
		if (in_array(strtolower($tag), $void_elements))
		{
			// these can not have content
			$html .= ' />';
		}
		else
		{
			// add the content and close the tag
			$html .= '>'.$content.'</'.$tag.'>';
		}

		return $html;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('build_list'))
{
	function build_list($type = 'ul', array $list = array(), $attr = array(), $indent = '')
	{
		if ( ! is_array($list))
		{
			$result = false;
		}

		$output = '';

		foreach ($list as $key => $value)
		{
			if ( ! is_array($value))
			{
				$output .= $indent."\t".html_tag('li', null, $value).PHP_EOL;
			}
			else
			{
				$output .= $indent."\t".html_tag('li', null, build_list($type, $value, null, $indent."\t\t")).PHP_EOL;
			}
		}

		$result = $indent.html_tag($type, $attr, PHP_EOL.$output.$indent).PHP_EOL;
		return $result;
	}
}
