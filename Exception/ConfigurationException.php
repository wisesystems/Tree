<?php

namespace Tree\Exception;

use \Exception;

/**
 * ConfigurationException 
 *
 * Provides debug information about problems to do with configuration
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2010 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class ConfigurationException extends Exception {

	const FILE_NOT_FOUND     = 0;
	const FILE_NOT_READABLE  = 1;
	const FILE_NOT_PARSEABLE = 2;

}

