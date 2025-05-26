<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Third Party
 * @category 	Beautify_Html
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

/**
 * Beautify_Html
 *
 * Beautify_Html is (almost) direct PHP port of beautify-html.js,
 * part of JS Beautifier project. It indents HTML code,
 * making it beautiful.
 *
 * @since 2.16
 *
 * @example
 *     $html = '<ul><li>Html</li><li>to</li><li>indent</li></ul>';
 *
 *     $beautify = new Beautify_Html(array(
 *         'indent_inner_html'     => false,
 *         'indent_char'           => " ",
 *         'indent_size'           => 2,
 *         'wrap_line_length'      => 32786,
 *         'unformatted'           => ['code', 'pre'],
 *         'preserve_newlines'     => false,
 *         'max_preserve_newlines' => 32786,
 *         'indent_scripts'        => 'normal' // keep|separate|normal
 *     ));
 *
 *     echo $beautify->beautify($html);
 */
final class Beautify_Html
{
	/**
	 * Array of options.
	 * @var array
	 */
	private $config;

	/**
	 * Holds the parser position.
	 * @var int
	 */
	private $pos = 0;

	/**
	 * reflects the current Parser mode: TAG/CONTENT.
	 * @var string
	 */
	private $current_mode = 'CONTENT';

	/**
	 * An object to hold tags, their position,
	 * and their parent-tags, initiated with
	 * default values.
	 * @var array
	 */
	private $tags = array(
		'parent'      => 'parent1',
		'parentcount' => 1,
		'parent1'     => ''
	);

	/**
	 * Type of tag.
	 * @var string
	 */
	private $tag_type = '';

	/**
	 * Different tokens.
	 * @var string
	 */
	private $token_text = '';
	private $last_token = '';
	private $last_text = '';
	private $token_type = '';

	/**
	 * Holds count of new lines.
	 * @var int
	 */
	private $newlines = 0;

	/**
	 * Whether to indent content.
	 * @var bool
	 */
	private $indent_content;

	/**
	 * Content indent level.
	 */
	private $indent_level = 0;

	/**
	 * Count to see if wrap_line_length was exceeded.
	 * @var int
	 */
	private $line_char_count = 0;

	/**
	 * Holds the indent string.
	 * @var string
	 */
	private $indent_string;

	private $whitespace = array("\n", "\r", "\t", " ");

	/**
	 * All the single tags for HTML.
	 * @var array
	 */
	private $single_token = array(
		'br', 'input', 'link', 'meta', '!doctype', 'basefont', 'base', 'area',
		'hr', 'wbr', 'param', 'img', 'isindex', '?xml', 'embed', '?php', '?', '?='
	);

	/**
	 * Tags that need a line of whitespace before them.
	 * @var array
	 */
	private $extra_liners = array('head', 'body', '/html');

	/**
	 * Script indent types.
	 * @var array
	 */
	private $script_index_types = array('keep', 'separate', 'normal');

	/**
	 * Instances of CSS and JS Beautifiers.
	 * @var object
	 */
	private $css_beautify;
	private $js_beautify;

	/**
	 * Holds the content to beautify.
	 * @var string
	 */
	private $input;

	/**
	 * The length of the content to beautify.
	 * @var int
	 */
	private $input_length = 0;

	private $output;

	/**
	 * Class contructor
	 *
	 * Instanciates the class.
	 *
	 * @param   array   $params         array of options.
	 * @param   bool    $css_beautify   whether to beautify css.
	 * @param   bool    $js_beautify    whether to beautify js.
	 */
	public function __construct($params = array(), $css_beautify = null, $js_beautify = null)
	{
		// We start by settings config.
		$this->initialize($params);

		$this->css_beautify = ($css_beautify && is_callable($css_beautify)) ? $css_beautify : false;
		$this->js_beautify = ($js_beautify && is_callable($js_beautify)) ? $js_beautify : false;

		$this->indent_content = $this->config['indent_inner_html'];
		$this->indent_string = str_repeat($this->config['indent_char'], $this->config['indent_size']);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @param   array   $params     Initialization parameters
	 * @return  Beautify_Html
	 */
	public function initialize(array $params = array())
	{
		// Whether to indent inner html.
		$this->config['indent_inner_html'] = isset($params['indent_inner_html'])
			? (bool) $params['indent_inner_html']
			: false;

		// The indent size.
		$this->config['indent_size'] = isset($params['indent_size'])
			? (int) $params['indent_size']
			: 4;

		// The indent character.
		$this->config['indent_char'] = isset($params['indent_char'])
			? (string) $params['indent_char']
			: ' ';

		// Whether to indent scripts.
		if (isset($params['indent_scripts'])
			&& in_array($params['indent_scripts'], $this->script_index_types))
		{
			$this->config['indent_scripts'] = $params['indent_scripts'];
		}
		else
		{
			$this->config['indent_scripts'] = 'normal';
		}

		// Length of lines before wrapping.
		$this->config['wrap_line_length'] = isset($params['wrap_line_length'])
			? (int) $params['wrap_line_length']
			: 32786;

		// Array of unformatted tags.
		if (isset($params['unformatted']) && is_array($params['unformatted']))
		{
			$this->config['unformatted'] = $params['unformatted'];
		}
		else
		{
			$this->config['unformatted'] = array(
				'a', 'span', 'bdo', 'em', 'strong', 'dfn', 'code', 'samp', 'kbd', 'var', 'cite', 'abbr',
				'acronym', 'q', 'sub', 'sup', 'tt', 'i', 'b', 'big', 'small', 'u', 's', 'strike',
				'font', 'ins', 'del', 'pre', 'address', 'dt', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'textarea'
			);
		}

		// Whether to preserve new lines.
		$this->config['preserve_newlines'] = isset($params['preserve_newlines'])
			? (bool) $params['preserve_newlines']
			: true;

		// Max lines to preverse.
		if ($this->config['preserve_newlines'] && isset($params['max_preserve_newlines']))
		{
			$this->config['max_preserve_newlines'] = (int) $params['max_preserve_newlines'];
		}
		else
		{
			$this->config['max_preserve_newlines'] = 0;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Traverse and formats whitespaces.
	 *
	 * @param   none
	 * @return  bool    true if traversed, else false
	 */
	private function traverse_whitespace()
	{
		$input_char = isset($this->input[$this->pos]) ? $this->input[$this->pos] : '';
		if ($input_char && in_array($input_char, $this->whitespace))
		{
			$this->newlines = 0;
			while ($input_char && in_array($input_char, $this->whitespace))
			{
				if ($this->config['preserve_newlines']
					&& $input_char === "\n"
					&& $this->newlines <= $this->config['max_preserve_newlines'])
				{
					$this->newlines += 1;
				}

				$this->pos++;
				$input_char = isset($this->input[$this->pos]) ? $this->input[$this->pos] : '';
			}

			return true;
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * function to capture regular content between tags.
	 *
	 * @return  string  the content after being captured.
	 */
	private function get_content()
	{
		$input_char = '';
		$content = array();
		$space = false; //if a space is needed

		while (isset($this->input[$this->pos]) && $this->input[$this->pos] !== '<')
		{
			// Reached the max length?
			if ($this->pos >= $this->input_length)
			{
				return count($content) ? implode('', $content) : array('', 'TK_EOF');
			}

			// Traversed whitespaces?
			if ($this->traverse_whitespace())
			{
				(count($content)) && $space = true;
				continue; // Don't want to insert unnecessary space
			}

			$input_char = $this->input[$this->pos];
			$this->pos++;

			if ($space)
			{
				// Insert a line when the wrap_line_length is reached.
				if ($this->line_char_count >= $this->config['wrap_line_length'])
				{
					$this->print_newline(false, $content);
					$this->print_indentation($content);
				}
				else
				{
					$this->line_char_count++;
					$content[] = ' ';
				}

				$space = false;
			}

			$this->line_char_count++;

			// Letter at-a-time (or string) inserted to an array.
			$content[] = $input_char;
		}

		return count($content) ? implode('', $content) : '';
	}

	// --------------------------------------------------------------------

	/**
	 * Gets the full content of a script or style to pass to js_beautify.
	 *
	 * @param   string  $name
	 * @return  string  $content
	 */
	private function get_contents_to($name)
	{
		if ($this->pos === $this->input_length)
		{
			return array('', 'TK_EOF');
		}

		$input_char = '';
		$content    = '';
		$reg_array  = array();

		preg_match(
			'#</'.preg_quote($name, '#').'\\s*>#im',
			$this->input,
			$reg_array,
			PREG_OFFSET_CAPTURE,
			$this->pos
		);

		// Absolute end of script
		$end_script = $reg_array ? ($reg_array[0][1]) : $this->input_length;

		// Get everything in between the script tags
		if ($this->pos < $end_script)
		{
			$content = substr($this->input, $this->pos, max($end_script-$this->pos, 0));
			$this->pos = $end_script;
		}

		return $content;
	}

	// --------------------------------------------------------------------

	/**
	 * Method to record a tag and its parent in this.tags Object
	 *
	 * @param   string  $tag
	 */
	private function record_tag($tag)
	{
		// Check for the existence of this tag type.
		if (isset($this->tags[$tag.'count']))
		{
			$this->tags[$tag.'count']++;
			// And record the present indent level
			$this->tags[$tag.$this->tags[$tag.'count']] = $this->indent_level;
		}
		// Otherwise initialize this tag type
		else
		{
			$this->tags[$tag.'count'] = 1;
			// And record the present indent level
			$this->tags[$tag.$this->tags[$tag.'count']] = $this->indent_level;
		}

		// Set the parent (i.e. in the case of a div this.tags.div1parent).
		$this->tags[$tag.$this->tags[$tag.'count'].'parent'] = $this->tags['parent'];

		// And make this the current parent (i.e. in the case of a div 'div1').
		$this->tags['parent'] = $tag.$this->tags[$tag.'count'];
	}

	// --------------------------------------------------------------------

	/**
	 * Method to retrieve the opening tag to the corresponding closer.
	 *
	 * @param   string  $tag    the tag to retrieve
	 */
	private function retrieve_tag($tag)
	{
		// If the openener is not in the Object we ignore it.
		if (isset($this->tags[$tag.'count']))
		{
			// Check to see if it's a closable tag.
			$temp_parent = $this->tags['parent'];

			// Till we reach '' (the initial value);
			while ($temp_parent)
			{
				// If this is it use it.
				if ($tag.$this->tags[$tag.'count'] === $temp_parent)
				{
					break;
				}

				// Otherwise keep on climbing up the DOM Tree
				$temp_parent = isset($this->tags[$temp_parent.'parent'])
					? $this->tags[$temp_parent.'parent']
					: '';
			}

			// If we caught something
			if ($temp_parent)
			{
				// Set the indent_level accordingly.
				$this->indent_level = $this->tags[$tag.$this->tags[$tag.'count']];
				// And set the current parent
				$this->tags['parent'] = $this->tags[$temp_parent.'parent'];
			}

			// Delete the closed tags parent reference...
			unset($this->tags[$tag.$this->tags[$tag.'count'].'parent']);

			// ...and the tag itself
			unset($this->tags[$tag.$this->tags[$tag.'count']]);

			if ($this->tags[$tag.'count'] === 1)
			{
				unset($this->tags[$tag.'count']);
			}
			else
			{
				$this->tags[$tag.'count']--;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for indenting until the given tag.
	 *
	 * @param   string  $tag
	 */
	private function indent_to_tag($tag)
	{
		/**
		 * Match the indentation level to the last use
		 * of this tag, but don't remove it.
		 */
		if ( ! $this->tags[$tag.'count'])
		{
			return;
		}

		$temp_parent = $this->tags['parent'];
		while ($temp_parent)
		{
			if ($tag.$this->tags[$tag.'count'] === $temp_parent)
			{
				break;
			}

			$temp_parent = $this->tags[$temp_parent.'parent'];
		}

		if ($temp_parent)
		{
			$this->indent_level = $this->tags[$tag.$this->tags[$tag.'count']];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method to get a full tag and parse its type.
	 *
	 * @param   bool    $peek
	 */
	private function get_tag($peek = false)
	{
		$input_char = '';
		$content = array();
		$comment = '';
		$space = false;
		$tag_start;
		$tag_end;
		$tag_start_char = false;
		$orig_pos = $this->pos;
		$orig_line_char_count = $this->line_char_count;

		do {
			if ($this->pos >= $this->input_length)
			{
				if ($peek)
				{
					$this->pos = $orig_pos;
					$this->line_char_count = $orig_line_char_count;
				}

				return count($content) ? implode('', $content) : array('', 'TK_EOF');
			}

			$input_char = $this->input[$this->pos];
			$this->pos++;

			// Don't want to insert unnecessary space
			if (in_array($input_char, $this->whitespace))
			{
				$space = true;
				continue;
			}

			if ($input_char === "'" OR $input_char === '"')
			{
				$input_char .= $this->get_unformatted($input_char);
				$space = true;
			}

			// No space before =
			($input_char === '=') && $space = false;

			if (count($content)
				&& $content[count($content) - 1] !== '='
				&& $input_char !== '>'
				&& $space)
			{
				// No space after = or before >
				if ($this->line_char_count >= $this->config['wrap_line_length'])
				{
					$this->print_newline(false, $content);
					$this->print_indentation($content);
				}
				else
				{
					$content[] = ' ';
					$this->line_char_count++;
				}

				$space = false;
			}

			if ($input_char === '<' && !$tag_start_char)
			{
				$tag_start = $this->pos - 1;
				$tag_start_char = '<';
			}

			$this->line_char_count++;
			// Inserts character at-a-time (or string)
			$content[] = $input_char;

			// If we're in a comment, do something special.
			if (isset($content[1]) && $content[1] === '!')
			{
				/**
				 * We treat all comments as literals, even more than preformatted tags
				 * we just look for the appropriate close tag
				 */
				$content = array($this->get_comment($tag_start));
				break;
			}

		} while ($input_char !== '>');

		$tag_complete = implode('', $content);

		/**
		 * If there's whitespace, thats where the tag name ends
		 * otherwise go with the tag ending
		 */
		$tag_index = (strpos($tag_complete, ' ') !== false)
			? strpos($tag_complete, ' ')
			: strpos($tag_complete, '>');

		$tag_offset = ($tag_complete[0] === '<') ? 1 : ($tag_complete[2] === '#' ? 3 : 2);

		$tag_check = strtolower(substr($tag_complete, $tag_offset, max($tag_index-$tag_offset, 0)));

		if ($tag_complete[strlen($tag_complete) - 2] === '/'
			/* If this tag name is a single tag type (either in the list or has a closing /) */
			OR in_array($tag_check, $this->single_token))
		{
			($peek) OR $this->tag_type = 'SINGLE';
		}
		elseif ($tag_check === 'script')
		{
			if ( ! $peek)
			{
				$this->record_tag($tag_check);
				$this->tag_type = 'SCRIPT';
			}
		}
		elseif ($tag_check === 'style')
		{
			if ( ! $peek )
			{
				$this->record_tag($tag_check);
				$this->tag_type = 'STYLE';
			}
		}
		// Do not reformat the "unformatted" tags
		elseif ($this->is_unformatted($tag_check))
		{
			// ... delegate to get_unformatted function.
			$comment = $this->get_unformatted('</'.$tag_check.'>', $tag_complete);

			$content[] = $comment;

			// Preserve collapsed whitespace either before or after this tag.
			if ($tag_start > 0 && in_array($this->input[$tag_start - 1], $this->whitespace))
			{
				array_splice($content, 0, 0, $this->input[$tag_start - 1]);
			}

			$tag_end = $this->pos - 1;
			if (isset($this->input[$tag_end + 1]) && in_array($this->input[$tag_end + 1], $this->whitespace))
			{
				$content[] = $this->input[$tag_end + 1];
			}

			$this->tag_type = 'SINGLE';
		}
		// Peek for <! comment
		elseif ($tag_check && $tag_check[0] === '!')
		{
			// for comments content is already correct.
			if ( ! $peek)
			{
				$this->tag_type = 'SINGLE';
				$this->traverse_whitespace();
			}
		}
		elseif (!$peek)
		{
			// This tag is a double tag so check for tag-ending
			if ($tag_check && $tag_check[0] === '/')
			{
				// Remove it and all ancestors
				$this->retrieve_tag(substr($tag_check, 1));
				$this->tag_type = 'END';
				$this->traverse_whitespace();
			}
			// Otherwise it's a start-tag
			else
			{
				// Push it on the tag stack
				$this->record_tag($tag_check);
				(strtolower($tag_check) !== 'html') && $this->indent_content = true;
				$this->tag_type = 'START';

				// Allow preserving of newlines after a start tag.
				$this->traverse_whitespace();
			}
			// Check if this double needs an extra line
			if (in_array($tag_check, $this->extra_liners))
			{
				$this->print_newline(false, $this->output);
				if (count($this->output) && $this->output[count($this->output) - 2] !== "\n")
				{
					$this->print_newline(true, $this->output);
				}
			}
		}

		if ($peek)
		{
			$this->pos = $orig_pos;
			$this->line_char_count = $orig_line_char_count;
		}

		// Returns fully formatted tag.
		return implode('', $content);
	}

	// --------------------------------------------------------------------

	/**
	 * Method to return comment content in its entirety.
	 *
	 * @param   int     $pos    the starting position
	 * @return  string  the comment after being parsed.
	 */
	private function get_comment($pos)
	{
		// This will have very poor perf, but will work for now.
		$comment = '';
		$delimiter = '>';
		$matched = false;

		$this->pos = $pos;
		$input_char = $this->input[$this->pos];
		$this->pos++;

		while ($this->pos <= $this->input_length)
		{
			$comment .= $input_char;

			// Only need to check for the delimiter if the last chars match.
			if ($comment[strlen($comment) - 1] === $delimiter[strlen($delimiter) - 1]
				&& strpos($comment, $delimiter) !== false)
			{
				break;
			}

			// Only need to search for custom delimiter for the first few characters.
			if ( ! $matched && strlen($comment) < 10)
			{
				// Peek for <![if conditional comment.
				if (strpos($comment, '<![if') === 0)
				{
					$delimiter = '<![endif]>';
					$matched = true;
				}
				// If it's a <[cdata[ comment...
				elseif (strpos($comment, '<![cdata[') === 0)
				{
					$delimiter = ']]>';
					$matched = true;
				}
				// Some other ![ comment? ...
				elseif (strpos($comment, '<![') === 0)
				{
					$delimiter = ']>';
					$matched = true;
				}
				// <!-- comment ...
				elseif (strpos($comment, '<!--') === 0)
				{
					$delimiter = '-->';
					$matched = true;
				}
			}

			$input_char = $this->input[$this->pos];
			$this->pos++;
		}

		return $comment;
	}

	// --------------------------------------------------------------------

	/**
	 * Method to return unformatted content in its entirety
	 *
	 * @param   string  $delimiter
	 * @param   bool    $orig_tag
	 * @return  string  the content after being parsed
	 */
	private function get_unformatted($delimiter, $orig_tag = false)
	{
		if ($orig_tag && strpos(strtolower($orig_tag), $delimiter) !== false)
		{
			return '';
		}

		$input_char = '';
		$content = '';
		$min_index = 0;
		$space = true;

		do {
			if ($this->pos >= $this->input_length)
			{
				return $content;
			}

			$input_char = $this->input[$this->pos];
			$this->pos++;

			if (in_array($input_char, $this->whitespace))
			{
				if ( ! $space)
				{
					$this->line_char_count--;
					continue;
				}
				if ($input_char === "\n" OR $input_char === "\r")
				{
					$content .= "\n";

					/**
					 * Don't change tab indention for unformatted blocks.
					 * If using code for html editing, this will greatly affect
					 * <pre> tags if they are specified in the 'unformatted array'
					 *     for ($i = 0; $i < $this->indent_level; i++) {
					 *         $content .= $this->indent_string;
					 *     }
					 *     $space = false; //...and make sure other indentation is erased
					 */

					$this->line_char_count = 0;
					continue;
				}
			}

			$content .= $input_char;
			$this->line_char_count++;
			$space = true;

			/**
			 * Assuming Base64 This method could possibly be applied to All Tags
			 * but Base64 doesn't have " or ' as part of its data
			 * so it is safe to look for the Next delimiter to find the end of the data
			 * instead of reading Each character one at a time.
			 */
			if (preg_match('/^data:image\/(bmp|gif|jpeg|png|svg\+xml|tiff|x-icon);base64$/', $content))
			{
				$content .= substr(
					$this->input,
					$this->pos,
					strpos($this->input, $delimiter, $this->pos) - $this->pos
				);

				$this->line_char_count = strpos($this->input, $delimiter, $this->pos) - $this->pos;

				$this->pos = strpos($this->input, $delimiter, $this->pos);

				continue;
			}


		} while (strpos(strtolower($content), $delimiter, $min_index) === false);

		return $content;
	}

	// --------------------------------------------------------------------

	/**
	 * Initial handler for token-retrieval?
	 *
	 * @return  string  $token
	 */
	private function get_token()
	{
		// Check if we need to format javascript.
		if ($this->last_token === 'TK_TAG_SCRIPT' OR $this->last_token === 'TK_TAG_STYLE')
		{
			$type = substr($this->last_token, 7);
			$token = $this->get_contents_to($type);
			return (is_string($token)) ? array($token, 'TK_'.$type) : $token;
		}

		if ($this->current_mode === 'CONTENT')
		{
			$token = $this->get_content();
			return (is_string($token)) ? array($token, 'TK_CONTENT') : $token;
		}

		if ($this->current_mode === 'TAG')
		{
			$token = $this->get_tag();

			if (is_string($token))
			{
				$tag_name_type = 'TK_TAG_'.$this->tag_type;
				return array($token, $tag_name_type);
			}

			return $token;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Returns full indentation string.
	 *
	 * @param   int     $level
	 */
	private function get_full_indent($level)
	{
		$level = $this->indent_level + $level OR 0;
		return ($level < 1) ? '' : str_repeat($this->indent_string, $level);
	}

	// --------------------------------------------------------------------

	/**
	 * Checks whether the given tag is unformatted.
	 *
	 * @param   string  $tag_check
	 * @return  bool
	 */
	private function is_unformatted($tag_check)
	{
		// Is this an HTML5 block-level link?
		if ( ! in_array($tag_check, $this->config['unformatted']))
		{
			return false;
		}

		if (strtolower($tag_check) !== 'a' OR ! in_array('a', $this->config['unformatted']))
		{
			return true;
		}

		/**
		 * At this point we have an  tag; is its first child something
		 * we want to remain unformatted?
		 */
		$next_tag = $this->get_tag(true); // peek

		// Test next_tag to see if it is just html tag (no external content).
		$matches = array();
		preg_match('/^\s*<\s*\/?([a-z]*)\s*[^>]*>\s*$/', ($next_tag ? $next_tag : ""), $matches);
		$tag = $matches ? $matches : null;

		/**
		 * f next_tag comes back but is not an isolated tag, then
		 * let's treat the 'a' tag as having content
		 * and respect the unformatted option
		 */
		return ( ! $tag OR in_array($tag, $this->config['unformatted'])) ? true : false;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for printing new lines.
	 *
	 * @param   bool    $force  whether to force new lines.
	 * @param   array   $arr
	 */
	private function print_newline($force, &$arr)
	{
		$this->line_char_count = 0;
		if ( ! $arr OR ! count($arr))
		{
			return;
		}

		// We might want the extra line
		if ($force OR ($arr[count($arr) - 1] !== "\n"))
		{
			$arr[] = "\n";
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for printing indentations.
	 *
	 * @param   array   $arr
	 */
	private function print_indentation(&$arr)
	{
		for ($i = 0; $i < $this->indent_level; $i++)
		{
			$arr[] = $this->indent_string;
			$this->line_char_count += strlen($this->indent_string);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Method for printing token.
	 *
	 * @param   string  $text
	 */
	private function print_token($text)
	{
		if ($text OR $text !== '')
		{
			if (count($this->output) && $this->output[count($this->output) - 1] === "\n")
			{
				$this->print_indentation($this->output);
				$text = ltrim($text);
			}
		}

		$this->print_token_raw($text);
	}

	// --------------------------------------------------------------------

	/**
	 * Method for printing raw token
	 *
	 * @param   string  $text
	 */
	private function print_token_raw($text)
	{
		if ($text != null && $text !== '')
		{
			// Unformatted tags can grab newlines as their last character.
			if (strlen($text) > 1 && $text[strlen($text) - 1] === "\n")
			{
				$this->output[] = substr($text, 0, -1);
				$this->print_newline(false, $this->output);
			}
			else
			{
				$this->output[] = $text;
			}
		}

		for ($n = 0; $n < $this->newlines; $n++)
		{
			$this->print_newline($n > 0, $this->output);
		}

		$this->newlines = 0;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for incrementing indent level
	 *
	 * @return  void
	 */
	private function indent()
	{
		$this->indent_level++;
	}

	// --------------------------------------------------------------------

	/**
	 * Method for decrementing indent leve
	 *
	 * @return  void
	 */
	private function unindent()
	{
		($this->indent_level > 0) && $this->indent_level--;
	}

	// --------------------------------------------------------------------

	/**
	 * The core method that performs the magic of beautifying.
	 *
	 * @param   string  $input  the content to beautify
	 * @return  string  the final output after being beautified
	 */
	public function beautify($input)
	{
		// Gets the input for the Parser
		$this->input = $input;
		$this->input_length = strlen($this->input);
		$this->output = array();

		while (true)
		{
			$t = $this->get_token();

			$this->token_text = $t[0];
			$this->token_type = $t[1];

			if ($this->token_type === 'TK_EOF')
			{
				break;
			}

			switch ($this->token_type)
			{
				case 'TK_TAG_START':
					$this->print_newline(false, $this->output);
					$this->print_token($this->token_text);
					if ($this->indent_content)
					{
						$this->indent();
						$this->indent_content = false;
					}
					$this->current_mode = 'CONTENT';
					break;

				case 'TK_TAG_STYLE':
				case 'TK_TAG_SCRIPT':
					$this->print_newline(false, $this->output);
					$this->print_token($this->token_text);
					$this->current_mode = 'CONTENT';
					break;

				case 'TK_TAG_END':
					/**
					 * Print new line only if the tag has no
					 * content and has child.
					 */
					if ($this->last_token === 'TK_CONTENT' && $this->last_text === '')
					{
						$matches = array();
						preg_match('/\w+/', $this->token_text, $matches);
						$tag_name = isset($matches[0]) ? $matches[0] : null;

						$tag_extracted_from_last_output = null;
						if (count($this->output))
						{
							$matches = array();
							preg_match('/(?:<|{{#)\s*(\w+)/', $this->output[count($this->output) - 1], $matches);
							$tag_extracted_from_last_output = isset($matches[0]) ? $matches[0] : null;
						}

						if ($tag_extracted_from_last_output === null
							OR $tag_extracted_from_last_output[1] !== $tag_name)
						{
							$this->print_newline(false, $this->output);
						}
					}

					$this->print_token($this->token_text);
					$this->current_mode = 'CONTENT';
					break;

				case 'TK_TAG_SINGLE':
					/**
					 * Don't add a newline before elements that
					 * should remain unformatted.
					 */
					$matches = array();
					preg_match('/^\s*<([a-z]+)/i', $this->token_text, $matches);
					$tag_check = $matches ? $matches : null;

					if ( ! $tag_check OR ! in_array($tag_check[1], $this->config['unformatted']))
					{
						$this->print_newline(false, $this->output);
					}

					$this->print_token($this->token_text);
					$this->current_mode = 'CONTENT';
					break;

				case 'TK_CONTENT':
					$this->print_token($this->token_text);
					$this->current_mode = 'TAG';
					break;

				case 'TK_STYLE':
				case 'TK_SCRIPT':
					if ($this->token_text !== '')
					{
						$this->print_newline(false, $this->output);
						$text = $this->token_text;
						$_beautifier = false;
						$script_indent_level = 1;

						if ($this->token_type === 'TK_SCRIPT')
						{
							$_beautifier = $this->js_beautify;
						}
						elseif ($this->token_type === 'TK_STYLE')
						{
							$_beautifier = $this->css_beautify;
						}

						if ($this->config['indent_scripts'] === "keep")
						{
							$script_indent_level = 0;
						}
						elseif ($this->config['indent_scripts'] === "separate")
						{
							$script_indent_level = -$this->indent_level;
						}

						$indentation = $this->get_full_indent($script_indent_level);
						if ($_beautifier)
						{
							// call the Beautifier if avaliable.
							$text = $_beautifier(preg_replace('/^\s*/', $indentation, $text), $this->config);
						}
						else
						{
							// simply indent the string otherwise
							$matches = array();
							preg_match('/^\s*/', $text, $matches);
							$white = isset($matches[0]) ? $matches[0] : null;

							$matches = array();
							preg_match('/[^\n\r]*$/', $white, $matches);
							$dummy = isset($matches[0]) ? $matches[0] : null;

							$_level = count(explode($this->indent_string, $dummy)) - 1;
							$reindent = $this->get_full_indent($script_indent_level - $_level);

							$text = preg_replace('/^\s*/', $indentation, $text);
							$text = preg_replace('/\r\n|\r|\n/', "\n".$reindent, $text);
							$text = preg_replace('/\s+$/', '', $text);
						}

						if ($text)
						{
							$this->print_token_raw($indentation.trim($text));
							$this->print_newline(false, $this->output);
						}
					}

					$this->current_mode = 'TAG';
					break;
			}

			$this->last_token = $this->token_type;
			$this->last_text = $this->token_text;
		}

		return implode('', $this->output);
	}

}
