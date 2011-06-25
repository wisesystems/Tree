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

	const FILE_NOT_FOUND    = 0;
	const FILE_NOT_READABLE = 1;
	const CLASS_NOT_FOUND   = 2;

}

