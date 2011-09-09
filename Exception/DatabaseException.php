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

	/**
	 * The instance of \Tree\Database\Connection that caused the exception
	 * 
	 * @access private
	 * @var    \Tree\Database\Connection
	 */
	private $connection;

	/**
	 * @access public
	 * @param string                    $message 
	 * @param integer                   $code 
	 * @param \Tree\Database\Connection $connection [optional]
	 */
	public function __construct($message, $code, $connection = null)
	{
		parent::__construct($message, $code);

		$this->connection = $connection;
	}

	/**
	 * Returns the Connection object that caused the exception
	 * 
	 * @access public
	 * @return \Tree\Database\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}

}

