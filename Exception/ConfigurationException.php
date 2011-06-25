<?php

namespace Tree\Exception;

use \Exception;

/**
 * ConfigurationException 
 *
 * Provides debug information about problems to do with configuration
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class ConfigurationException extends Exception {

	/**
	 * The config file could not be found anywhere in the include_path. The most
	 * likely cause of this problem is a mis-configured include_path.
	 */
	const FILE_NOT_FOUND = 0;

	/**
	 * The config file was found, but could not be opened for reading. This is a
	 * permissions problem.
	 */
	const FILE_NOT_READABLE = 1;

	/**
	 * The config file was found and read, but its contents could not be parsed
	 * as an ini file. The file needs to be checked manually for errors.
	 */
	const FILE_NOT_PARSEABLE = 2;

}

