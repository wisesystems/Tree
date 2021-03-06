<?php

namespace Tree\Database;

use \Tree\Exception\DatabaseException;

/**
 * Connection 
 *
 * Abstract base class for defining global behaviour of database connections
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @version    0.00
 */
abstract class Connection {

	/**
	 * Subclasses must implement this with a method that accepts an array of config
	 * values from the database and uses them to configure the connection
	 * 
	 * @abstract
	 * @access public
	 * @param  array $config 
	 */
	abstract public function setIniValues(array $config);

	/**
	 * Subclasses must implement this with a method that opens their database
	 * connection
	 * 
	 * @abstract
	 * @access protected
	 * @return boolean
	 */
	abstract protected function vendorConnect();

	/**
	 * Subclasses must implement this with a method that returns strings escaped
	 * so as to be safe for inclusion in SQL queries
	 * 
	 * @abstract
	 * @access protected
	 * @param  string $string 
	 * @return string
	 */
	abstract protected function vendorEscape($string);

	/**
	 * Subclasses must implement this with a method that indicates whether their
	 * connection is open and ready to receive queries
	 * 
	 * @abstract
	 * @access public
	 * @return boolean
	 */
	abstract protected function vendorIsConnected();

	/**
	 * Subclasses must implement this with a method that accepts SQL and returns a
	 * subclass of Result
	 * 
	 * @abstract
	 * @access protected
	 * @param  string $sql 
	 * @return \Tree\Database\Result
	 */
	abstract protected function vendorQuery($sql);

	/**
	 * Escapes and returns the given string
	 * 
	 * @access public
	 * @param  string $string 
	 * @return string
	 */
	public function escapeString($string)
	{
		$this->requireConnection();
		return $this->vendorEscape($string);
	}

	/**
	 * Indicates whether the connection is open and ready to receive queries 
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isConnected()
	{
		return $this->vendorIsConnected();
	}

	/**
	 * Opens the database connection
	 * 
	 * @access public
	 * @return boolean
	 */
	public function openConnection()
	{
		if ($this->isConnected() || $this->vendorConnect()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sends the given SQL to the database and returns the result
	 * 
	 * @access public
	 * @param  string $sql 
	 * @return \Tree\Database\Result
	 */
	public function query($sql)
	{
		$this->requireConnection();

		return $this->vendorQuery($sql);
	}

	/**
	 * For methods that cannot run without a database connection, this is a lazy-
	 * loading method that will attempt to connect if necessary and throw an
	 * exception if it fails
	 * 
	 * @access protected
	 */
	protected function requireConnection()
	{
		if ($this->isConnected() || $this->openConnection()) {
			return true;
		} else {

			$message = 'Could not connect to database';
			$code    = DatabaseException::CONNECTION_FAILED;
			
			throw new DatabaseException($message, $code, $this);
		}
	}

}

