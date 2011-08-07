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

	/**
	 * The username to connect with
	 * 
	 * @access private
	 * @var    string
	 */
	private $username;

	/**
	 * The password to connect with
	 * 
	 * @access private
	 * @var    string
	 */
	private $password;

	/**
	 * The hostname to connect to
	 * 
	 * @access private
	 * @var    string
	 */
	private $hostname;

	/**
	 * The port to use if connecting over TCP/IP
	 * 
	 * @access private
	 * @var    integer
	 */
	private $port;

	/**
	 * The path to the UNIX socket to be used for the connection if not connecting
	 * over TCP/IP
	 * 
	 * @var mixed
	 * @access private
	 */
	private $socket;

	/**
	 * The name of the database to use on the connection
	 * 
	 * @access private
	 * @var    string
	 */
	private $database;

	/**
	 * The mysqli object representing the connection
	 * 
	 * @access private
	 * @var    \mysqli
	 */
	private $mysqli;

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

	/**
	 * Indicates whether the connection is open and ready to receive queries
	 * 
	 * @access public
	 * @return boolean
	 */
	protected function vendorIsConnected()
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
	 * Escapes a string for use in a SQL query
	 *
	 * Note that this method wraps the string in single quotes. This is because
	 * some database extensions, notably PDO, do this internally when escaping
	 * values. It's therefore easier to have everything behave the same way, so 
	 * this class copies that behaviour.
	 * 
	 * @access protected
	 * @param  string $string 
	 * @return string
	 */
	protected function vendorEscape($string)
	{
		$string = $this->mysqli->real_escape_string($string);
		$string = "'{$string}'";

		return $string;
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

