<?php
defined('BASEPATH') OR die;

/**
 * KB_Email Class
 *
 * Library used to send emails using PHPMailer.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Libraries
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.88
 * @version 	1.0
 */
class KB_Email extends CI_Email
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $CI;

	/**
	 * Flag to check whether it was initialized.
	 * @var bool
	 */
	protected $initialized = false;

	// --------------------------------------------------------------------

	/**
	 * Constructor - Sets email preferences.
	 *
	 * @param 	array 	$config = array()
	 * @return 	void
	 */
	public function __construct(array $config = array())
	{
		$this->CI =& get_instance();
		parent::__construct($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences.
	 *
	 * @param 	array 	$config 	array
	 * @return 	KB_Email
	 */
	public function initialize(array $config = array())
	{
		parent::initialize($config);

		if ( ! $this->initialized)
		{
			$this->initialized = true;

			// Setup some default stuff.
			$this->useragent = $this->CI->config->item('site_name', null, KB_LABEL);
			$this->protocol = $this->CI->config->item('mail_protocol', null, $this->protocol);
			$this->newline = ($this->mailtype === 'html') ? '<br>' : "\r\n";

			if ('smtp' === $this->protocol)
			{
				$this->smtp_host   = $this->CI->config->item('smtp_host', null, $this->smtp_host);
				$this->smtp_port   = $this->CI->config->item('smtp_port', null, $this->smtp_port);
				$this->smtp_user   = $this->CI->config->item('smtp_user', null, $this->smtp_user);
				$this->smtp_pass   = $this->CI->config->item('smtp_pass', null, $this->smtp_pass);
				$this->smtp_crypto = $this->CI->config->item('smtp_crypto', null, $this->smtp_crypto);
				$this->_smtp_auth  = isset($this->smtp_user[0], $this->smtp_pass[0]);
			}
			elseif ('sendmail' === $this->protocol)
			{
				$this->mailpath = $this->CI->config->item('sendmail_path', null, $this->mailpath);
			}

		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Set FROM
	 *
	 * @param   string  $from
	 * @param   string  $name
	 * @param   string  $return_path = null Return-Path
	 * @return  CI_Email
	 */
	public function from($from, $name = '', $return_path = null)
	{
		// use site name if display name is empty
		empty($name) && $name = $this->CI->config->item('site_name');

		parent::from($from, $name, $return_path); // parent handles this part..

		// using 'noreply' prevents adding 'Reply-To' header.
		$this->_replyto_flag = (empty($from) OR false !== stripos($from, 'noreply') OR false !== stripos($from, 'no-reply'));

		return $this;
	}

}
