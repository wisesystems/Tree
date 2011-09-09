<?php

namespace Tree\Exception;

use \Exception;

/**
 * AutoloaderException 
 *
 * Provides debug information about problems to do with autoloading classes
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class AutoloaderException extends Exception {

	/**
	 * Autoloader was unable to locate the class's PSR-0 filename anywhere in the
	 * include_path. The most likely cause of this error is a mis-configured
	 * include_path.
	 */
	const FILE_NOT_FOUND = 0;

	/**
	 * Autoloader found the file, but couldn't open it for reading. This is a
	 * permissions problem.
	 */
	const FILE_NOT_READABLE = 1;

	/**
	 * The file was found and opened, but it did not contain the class it was
	 * supposed to. This is most likely a result of a typo in the name of the
	 * class being instantiated or in the declaration itself.
	 */
	const CLASS_NOT_FOUND = 2;

	/**
	 * The name of the class that could not be loaded
	 * 
	 * @access private
	 * @var    string
	 */
	private $className;

	/**
	 * The path to the file which was determined to contain the class
	 * 
	 * @access private
	 * @var    string
	 */
	private $absolutePath;

	/**
	 * @access public
	 * @param string  $message
	 * @param integer $code 
	 * @param string  $class    [optional] The class that couldn't be loaded
	 * @param string  $path     [optional] The filename that was tried
	 */
	public function __construct($message, $code, $class = null, $path = null)
	{
		parent::__construct($message, $code);

		$this->className    = $class;
		$this->absolutePath = $path;
	}

	/**
	 * Returns the name of the class that failed to load
	 * 
	 * @access public
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * Returns the name of the file that was thought to contain the class
	 * 
	 * @access public
	 * @return string
	 */
	public function getAbsolutePath()
	{
		return $this->absolutePath;
	}

}

