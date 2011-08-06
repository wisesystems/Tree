<?php

namespace Tree\Database;

use \Tree\Database\Result;
use \Tree\Database\Result_MySql;

/**
 * Query 
 *
 * Base class for generating SQL queries 
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
abstract class Query {

	/**
	 * The database connection to be used in escaping parameters and obtaining a
	 * result
	 * 
	 * @access protected
	 * @var    \Tree\Database\Connection
	 */
	protected $connection;
	
	/**
	 * Just stores the database connection 
	 * 
	 * @access public
	 * @param  \Tree\Database\Connection $connection 
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Returns the SQL generated for the query
	 *
	 * Intended for ease of debugging in that a simple 'echo $query;'
	 * instantly shows the SQL.
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		return $this->getSql();
	}

	/**
	 * Formats the given variable as a string to be used in a SQL query
	 * 
	 * @access public
	 * @param  mixed $value 
	 * @return string
	 */
	public function formatValue($value)
	{
		if (is_int($value) || ctype_digit($value)) {
			return $value;
		} elseif (is_array($value)) {

			foreach ($value as $i => $element) {
				$value[$i] = $this->formatValue($element);
			}
			$value = implode(', ', $value);
			$value = "($value)";
			return $value;

		} elseif ($value === null) {
			return 'NULL';
		} elseif ($value === false) {
			return '0';
		} elseif ($value === true) {
			return '1';
		} else {
			$value = $this->escapeString($value);
			$value = "'$value'";
			return $value;
		}
	}

	/**
	 * Returns the result of the query
	 * 
	 * @access public
	 * @return \Tree\Database\Result
	 */
	public function getResult()
	{
		$sql    = $this->getSql();
		$result = $this->connection->query($sql);

		return $result;
	}

	/**
	 * Subclasses should implement this as a method that compiles and returns a
	 * full SQL query ready to be sent to the database
	 * 
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function getSql();

}

