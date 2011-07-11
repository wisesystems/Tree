<?php

namespace Tree\Exception;

use \Exception;

/**
 * DatabaseException 
 *
 * Provides debug information about problems to do with databases
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class DatabaseException extends Exception {

	/**
	 * Some database action requiring an open connection failed because the
	 * connection could not be opened successfully.
	 */
	const CONNECTION_FAILED = 0;

}

