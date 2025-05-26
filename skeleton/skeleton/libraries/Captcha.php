<?php
defined('BASEPATH') OR die;

/**
 * Captcha Class
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Libraries
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.18
 * @version     1.0
 */
class Captcha
{
	/**
	 * Holds an instance of CI.
	 * @var object
	 */
	protected $ci;

	/**
	 * Class defaults.
	 * Theese are the same as CI3 captcha helper config.
	 * @var array
	 */
	protected $config;

	/**
	 * Holds images extension.
	 * @var array
	 */
	protected $extens = array('.jpg', '.png');

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Initializes the class if using captcha is renabled.
	 *
	 * @access  public
	 * @param   none
	 * @return  void
	 */
	public function __construct($config = array())
	{
		$this->ci =& get_instance();
		if ($this->ci->config->item('use_captcha'))
		{
			function_exists('html_tag') or $this->ci->load->helper('html');
		}

		$this->initialize($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Magic method for getting a property key from defaults.
	 * @access  public
	 * @param   string  $key    the property key to retrieve.
	 * @return  mixed
	 */
	public function __get($key)
	{
		return isset($key, $this->config[$key]) ? $this->config[$key] : null;
	}

	// --------------------------------------------------------------------

	/**
	 * Initializes the class.
	 * @access  public
	 * @param   array $config
	 * @return  void
	 */
	public function initialize($config = array())
	{
		// set class defaults.
		if ( ! isset($this->config))
		{
			$this->config = array(
				'word'        => '',
				'img_path'    => '',
				'img_url'     => '',
				'img_width'   => 150,
				'img_height'  => 30,
				'img_alt'     =>  'captcha',
				'font_path'   => '',
				'font_size'   => 16,
				'word_length' => 8,
				'img_id'      => '',
				'img_class'   => '',
				'expiration'  => MINUTE_IN_SECONDS * 5,
				'pool'        => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
				'colors'      => array(
					'background' => array(255, 255, 255),
					'border'     => array(153, 102, 102),
					'text'       => array(204, 153, 153),
					'grid'       => array(255, 182, 182)
				)
			);
		}

		// Merge defaults with provided params.
		empty($config) OR $this->config = array_merge($this->config, $config);
	}

	// --------------------------------------------------------------------

	/**
	 * Creates a new CAPTCHA image/array.
	 *
	 * @access  public
	 * @param   string  $img_path   the path to create the image in.
	 * @param   string  $img_url    the URL of the CAPTCHA image folder.
	 * @param   string  $font_path  the path to the font used for captcha.
	 * @return  array   the array holding captcha details.
	 */
	public function create($img_path = '', $img_url = '', $font_path = '')
	{
		$img_path  = empty($img_path) ? $this->img_path : $img_path;
		$img_url   = empty($img_url) ? $this->img_url : $img_url;
		$font_path = empty($font_path) ? $this->font_path : $font_path;

		if ( ! create_static_index($img_path)
			OR $img_url === '' // no image url?
			OR !extension_loaded('gd')) // missing gd extension?
		{
			return false;
		}

		// We start by removing old images.
		$now = microtime(true);
		$current_dir = @opendir($img_path);
		while ($filename = @readdir($current_dir))
		{
			if (in_array(substr($filename, -4), $this->extens)
				&& (str_replace($this->extens, '', $filename) + $this->expiration) < $now)
			{
				@unlink($img_path . $filename);
			}
		}

		@closedir($current_dir);

		// Do we have a "word" yet?
		$word        = $this->word;
		$word_length = $this->word_length;
		$pool        = $this->pool;

		// No word? Generate one.
		if (empty($word))
		{
			$word        = '';
			$pool_length = strlen($pool);
			$rand_max    = $pool_length - 1;

			// PHP7 or a suitable polyfill
			if (function_exists('random_int'))
			{
				try {
					for ($i = 0; $i < $word_length; $i++) {
						$word .= $this->pool[random_int(0, $rand_max)];
					}
				}
				catch (Exception $e) {
					// This means fallback to the next possible
					// alternative to random_int()
					$word = '';
				}
			}
		}

		// Still no word yet?
		if (empty($word)) {
			/**
			 * Nobody should have a character pool larger than 256
			 * characters, but let's handle it just in case ...
			 */
			if ($pool_length > 256)
			{
				return false;
			}

			if (($bytes = $this->random_bytes($pool_length)) !== false)
			{
				$byte_index = $word_index = 0;
				while ($word_index < $word_length)
				{
					/**
					 * Do we have more random data to use? It could be exhausted
					 * by previous iterations ignoring bytes higher than $rand_max.
					 */
					if ($byte_index === $pool_length) {
						/**
						 * No failures should be possible if the first random_bytes()
						 * call didn't return false, but still ...
						 */
						for ($i = 0; $i < 5; $i++)
						{
							if (($bytes = $this->random_bytes($pool_length)) === false)
							{
								continue;
							}

							$byte_index = 0;
							break;
						}

						if ($bytes === false)
						{
							// Unfortunately, we'll fallback to mt_rand()
							$word = '';
							break;
						}
					}

					$rand_index = unpack('C', $bytes[$byte_index++])[1];
					if ($rand_index > $rand_max)
					{
						continue;
					}

					$word .= $pool[$rand_index];
					$word_index++;
				}
			}
		}

		// No word yet?
		if (empty($word))
		{
			for ($i = 0; $i < $word_length; $i++)
			{
				$word .= $pool[mt_rand(0, $rand_max)];
			}
		}
		elseif ( ! is_string($word))
		{
			$word = (string) $word;
		}


		// Determine angle and position
		$img_height = (int) $this->img_height;
		$img_width  = (int) $this->img_width;
		$colors     = $this->colors;
		$font_size  = $this->font_size;
		$img_id     = $this->img_id;
		$img_class  = $this->img_class;

		$length = strlen($word);
		$angle  = ($length >= 6) ? mt_rand(-($length - 6), ($length - 6)) : 0;
		$x_axis = mt_rand(6, (int) (360 / $length) - 16);
		$y_axis = ($angle >= 0) ? mt_rand($img_height, $img_width) : mt_rand(6, $img_height);

		/**
		 * Create image
		 * PHP.net recommends imagecreatetruecolor(), but it isn't always available
		 */
		$im = function_exists('imagecreatetruecolor') ? imagecreatetruecolor($img_width, $img_height) : imagecreate($img_width, $img_height);

		// Assign colors
		is_array($colors) OR $colors = $this->colors;

		foreach (array_keys($colors) as $key)
		{
			// Check for a possible missing value
			is_array($colors[$key]) OR $colors[$key];
			$colors[$key] = imagecolorallocate($im, $colors[$key][0], $colors[$key][1], $colors[$key][2]);
		}

		// Create the rectangle
		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $colors['background']);

		// Create the spiral pattern
		$theta   = 1;
		$thetac  = 7;
		$radius  = 16;
		$circles = 20;
		$points  = 32;

		for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++)
		{
			$theta += $thetac;
			$rad = $radius * ($i / $points);
			$x   = round(($rad * cos($theta)) + $x_axis);
			$y   = round(($rad * sin($theta)) + $y_axis);
			$theta += $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1   = round(($rad1 * cos($theta)) + $x_axis);
			$y1   = round(($rad1 * sin($theta)) + $y_axis);
			imageline($im, $x, $y, $x1, $y1, $colors['grid']);
			$theta -= $thetac;
		}

		// Write the text
		$use_font = ($font_path !== '' && is_file($font_path) && function_exists('imagettftext'));
		if ($use_font === false)
		{
			($font_size > 5) && $font_size = 5;
			$x = mt_rand(0, round($img_width / ($length / 3)));
			$y = 0;
		}
		else
		{
			$x = mt_rand(0, round($img_width / $length / 2));
			$y = $font_size + 2;
		}

		for ($i = 0; $i < $length; $i++)
		{
			if ($use_font === false)
			{
				$y = mt_rand(0, $img_height / 2);
				imagestring($im, $font_size, $x, $y, $word[$i], $colors['text']);
				$x += ($font_size * 2);
			}
			else
			{
				$y = mt_rand(round($img_height / 2), $img_height - 3);
				imagettftext($im, $font_size, $angle, $x, $y, $colors['text'], $font_path, $word[$i]);
				$x += $font_size;
			}
		}

		// Create the border
		imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $colors['border']);

		// Generate the image
		if (function_exists('imagejpeg'))
		{
			$img_filename = $now . '.jpg';
			imagejpeg($im, $img_path . $img_filename);
		}
		elseif (function_exists('imagepng'))
		{
			$img_filename = $now . '.png';
			imagepng($im, $img_path . $img_filename);
		}
		else
		{
			$img_filename = null;
		}

		if (isset($img_filename))
		{
			$img_src = rtrim($img_url, '/').'/'.$img_filename;
		}
		else
		{
			// I don't see an easier way to get the image contents without writing to file
			$buffer = fopen('php://memory', 'wb+');
			imagepng($im, $buffer);
			rewind($buffer);
			$img_src = '';

			// fread() will return an empty string (not false) after the entire contents are read
			while (strlen($read = fread($buffer, 4096)))
			{
				$img_src .= $read;
			}

			fclose($buffer);
			$img_src = 'data:image/png;base64,'.base64_encode($img_src);
		}

		$attrs = array(
			'src'       => $img_src,
			'style'     => sprintf('width:%spx;height:%spx;border:0;', $img_width, $img_height),
			'alt'       => $this->img_alt,
			'draggable' => 'false'
		);

		($img_id !== '') && $attrs['id'] = $img_id;
		($img_class !== '') && $attrs['class'] = $img_class;

		$img = html_tag('img', $attrs);

		ImageDestroy($im);

		$captcha = array(
			'word'       => $word,
			'time'       => $now,
			'image'      => $img,
			'url'        => $img_src,
			'ip_address' => ip_address()
		);
		isset($img_filename) && $captcha['filename'] = $img_filename;

		// Store it in session.
		$this->ci->session->set_userdata(array(
			SESS_CAPTCHA_WORD => $captcha['word'],
			SESS_CAPTCHA_TIME => TIME + $this->expiration
		));

		return $captcha;
	}

	// --------------------------------------------------------------------

	/**
	 * Checks captcha validation.
	 *
	 * This function also cleans expired captcha stores in session.
	 *
	 * @access  public
	 * @param   string  $word       the CAPTCHA word to check.
	 * @param   string  $ip_address the user's ip address.
	 * @param   int     $expires the CAPTCHA's expiration time.
	 * @return  bool    true if the CAPTCHA is valid, otherwise false.
	 */
	public function validate($word)
	{
		// Collect data because we are removing them after.
		$sess_word   = $this->ci->session->userdata(SESS_CAPTCHA_WORD);
		$sess_time   = $this->ci->session->userdata(SESS_CAPTCHA_TIME);

		// Remove them to make them usable only once.
		$this->ci->session->unset_userdata(array(SESS_CAPTCHA_WORD, SESS_CAPTCHA_TIME));

		// All conditions must be met.
		return ($sess_word && $sess_time && $sess_word === $word && $sess_time > TIME);
	}

	// --------------------------------------------------------------------

	/**
	 * Returns random bytes.
	 *
	 * @access  private
	 * @param   int     $length     output length.
	 * @return  string
	 */
	private function random_bytes($length)
	{
		if (empty($length) OR !ctype_digit((string) $length))
		{
			return false;
		}

		// Sadly, none of the following PRNGs is guaranteed to exist.
		if (defined('MCRYPT_DEV_URANDOM')
			&& ($output = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)) !== false)
		{
			return $output;
		}

		if (is_readable('/dev/urandom') && ($fp = fopen('/dev/urandom', 'rb')) !== false)
		{
			// Try not to waste entropy.
			is_php('5.4') && stream_set_chunk_size($fp, $length);
			$output = fread($fp, $length);
			fclose($fp);
			if ($output !== false)
			{
				return $output;
			}
		}

		if (function_exists('openssl_random_pseudo_bytes'))
		{
			return openssl_random_pseudo_bytes($length);
		}

		return false;
	}

}
