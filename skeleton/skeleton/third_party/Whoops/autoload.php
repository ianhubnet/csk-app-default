<?php
defined('BASEPATH') OR die;

/**
 * Register Whoops classes to Autoloader.
 * @since 2.91
 */
Autoloader::add_classes(array(
	// Run files.
	'Whoops\RunInterface'                => KBPATH.'third_party/Whoops/RunInterface.php',
	'Whoops\Run'                         => KBPATH.'third_party/Whoops/Run.php',

	// Utilities.
	'Whoops\Util\HtmlDumperOutput'       => KBPATH.'third_party/Whoops/Util/HtmlDumperOutput.php',
	'Whoops\Util\Misc'                   => KBPATH.'third_party/Whoops/Util/Misc.php',
	'Whoops\Util\SystemFacade'           => KBPATH.'third_party/Whoops/Util/SystemFacade.php',
	'Whoops\Util\TemplateHelper'         => KBPATH.'third_party/Whoops/Util/TemplateHelper.php',

	// Handlers.
	'Whoops\Handler\CallbackHandler'     => KBPATH.'third_party/Whoops/Handler/CallbackHandler.php',
	'Whoops\Handler\Handler'             => KBPATH.'third_party/Whoops/Handler/Handler.php',
	'Whoops\Handler\HandlerInterface'    => KBPATH.'third_party/Whoops/Handler/HandlerInterface.php',
	'Whoops\Handler\JsonResponseHandler' => KBPATH.'third_party/Whoops/Handler/JsonResponseHandler.php',
	'Whoops\Handler\PlainTextHandler'    => KBPATH.'third_party/Whoops/Handler/PlainTextHandler.php',
	'Whoops\Handler\PrettyPageHandler'   => KBPATH.'third_party/Whoops/Handler/PrettyPageHandler.php',
	'Whoops\Handler\XmlResponseHandler'  => KBPATH.'third_party/Whoops/Handler/XmlResponseHandler.php',

	// Exceptions.
	'Whoops\Exception\ErrorException'    => KBPATH.'third_party/Whoops/Exception/ErrorException.php',
	'Whoops\Exception\Formatter'         => KBPATH.'third_party/Whoops/Exception/Formatter.php',
	'Whoops\Exception\Frame'             => KBPATH.'third_party/Whoops/Exception/Frame.php',
	'Whoops\Exception\FrameCollection'   => KBPATH.'third_party/Whoops/Exception/FrameCollection.php',
	'Whoops\Exception\Inspector'         => KBPATH.'third_party/Whoops/Exception/Inspector.php',
));
