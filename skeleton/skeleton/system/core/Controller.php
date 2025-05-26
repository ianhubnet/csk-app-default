<?php
defined('BASEPATH') OR die;

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Libraries
 * @author      EllisLab Dev Team
 * @link        https://codeigniter.com/userguide3/general/controllers.html
 */
#[AllowDynamicProperties]
class CI_Controller
{
	/**
	 * Reference to the CI singleton
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * CI_Loader
	 *
	 * @var CI_Loader
	 */
	public $load;

	/**
	 * Class constructor
	 *
	 * @return  void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core', $this);
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return  object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

}
