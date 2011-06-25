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

}

