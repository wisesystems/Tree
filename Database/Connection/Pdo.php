<?php

namespace Tree\Database;

use \PDO;
use \PDOException;

/**
 * Connection_Pdo 
 *
 * PDO-specific database connection management
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \PDOStatement
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class Connection_Pdo extends Connection {
	
	/**
	 * The PDO object representing the connection
	 * 
	 * @access private
	 * @var    \PDO
	 */
	private $pdo;

	/**
	 * The Data Source Name for the connection
	 * 
	 * @access private
	 * @var    string
	 */
	private $dsn;

	/**
	 * The name of the database user for the connection
	 * 
	 * @access private
	 * @var    string
	 */
	private $username;

	/**
	 * The password of the user for the connection
	 * 
	 * @access private
	 * @var    string
	 */
	private $password;

	/**
	 * Stores the config values from the INI file required for configuring the
	 * connection
	 * 
	 * @access public
	 * @param  array $config 
	 */
	public function setIniValues(array $config)
	{
		$this->dsn      = isset($config['dsn'])      ? $config['dsn']      : null;
		$this->username = isset($config['username']) ? $config['username'] : null;
		$this->password = isset($config['password']) ? $config['password'] : null;
	}

	/**
	 * Returns the PDO object for when third-party code needs it
	 * 
	 * @access public
	 * @return \PDO
	 */
	public function getPdo()
	{
		return $this->pdo;
	}

	/**
	 * Opens the PDO database connection
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorConnect()
	{
		try {
			$this->pdo = new PDO($this->dsn, $this->username, $this->password);
		} catch (PDOException $e) {
			return false;
		}

		return true;
	}

	/**
	 * Escapes a string for use in a SQL query
	 * 
	 * @access protected
	 * @param  string $string 
	 * @return string
	 */
	protected function vendorEscape($string)
	{
		return $this->pdo->quote($string);
	}

	/**
	 * Indicates whether the connection is open and ready to receive queries
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorIsConnected()
	{
		if ($this->pdo instanceof PDO) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sends a query to the database and returns its result
	 * 
	 * @access protected
	 * @param  string $sql 
	 * @return \Tree\Database\Result_Pdo
	 */
	protected function vendorQuery($sql)
	{
		$statement = $this->pdo->query($sql);
		$result = new Result_Pdo($statement);

		return $result;
	}

}

