<?php

namespace Tree\Database;

use \mysqli;
use \Tree\Database\Result_MySql;

/**
 * Connection_MySql 
 *
 * MySQL-specific database connection management
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Connection
 * @uses       \Tree\Database\Result_MySql
 * @version    0.00
 */
class Connection_MySql extends Connection {

	private $username;

	private $password;

	private $hostname;

	private $port;

	private $socket;

	private $database;

	private $mysqli;

	/**
	 * Indicates whether the connection is open and ready to receive queries
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isConnected()
	{
		if (is_null($this->mysqli)) {
			return false;
		}

		if ($this->mysqli->connect_error) {
			return false;
		}

		return true;
	}

	/**
	 * Configures the connection using values from the config file
	 * 
	 * @access public
	 * @param  array $config 
	 */
	public function setIniValues(array $config)
	{
		$this->username = isset($config['username']) ? $config['username'] : null;
		$this->password = isset($config['password']) ? $config['password'] : null;
		$this->hostname = isset($config['hostname']) ? $config['hostname'] : null;
		$this->port     = isset($config['port'])     ? $config['port']     : null;
		$this->socket   = isset($config['socket'])   ? $config['socket']   : null;
		$this->database = isset($config['database']) ? $config['database'] : null;
	}

	/**
	 * Opens the database connection
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorConnect()
	{
		$this->mysqli = @new mysqli(
			$this->hostname, $this->username, $this->password, $this->database,
			$this->port, $this->socket
		);

		if ($this->mysqli->connect_error) {
			return false;
		}

		return true;
	}

	protected function vendorEscape($string)
	{
		return $this->mysqli->real_escape_string($string);
	}

	/**
	 * Sends a query to the database and returns a result
	 * 
	 * @access protected
	 * @param  string $sql 
	 * @return \Tree\Database\Result_MySql
	 */
	protected function vendorQuery($sql)
	{
		$result = $this->mysqli->query($sql);

		$result = new Result_MySql($result);

		return $result;
	}

}

