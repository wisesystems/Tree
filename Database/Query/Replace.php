<?php

namespace Tree\Database;

/**
 * Query_Select 
 *
 * Generates REPLACE SQL queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Connection
 * @uses       \Tree\Database\Query
 * @version    0.00
 */
class Query_Replace extends Query {

	/**
	 * The name of the table into which the data is to go
	 * 
	 * @access private
	 * @var    string
	 */
	private $tableName;

	/**
	 * An associative array of column names and values
	 * 
	 * @access private
	 * @var    array
	 */
	private $setValues = array();

	/**
	 * \Tree\Database\Query: Reverts the query's parameters back to their initial
	 * default state
	 * 
	 * @access public
	 */
	public function clearParameters()
	{
		$this->tableName = null;
		$this->setValues = array();
	}

	/**
	 * Sets the name of the table into which the data is to be put
	 * 
	 * @access public
	 * @param  string $tableName 
	 */
	public function into($tableName)
	{
		$this->tableName = $tableName;
	}

	/**
	 * Stores a name-value pair of data corresponding to a column name and the
	 * value to be put into that column
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  mixed $value 
	 */
	public function set($name, $value)
	{
		$this->setValues[$name] = $value;
	}

	/**
	 * Generates and returns the REPLACE SQL
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		return "REPLACE INTO `{$this->tableName}`\n"
			. $this->getSetExpression();
	}

	/**
	 * Generates and returns the part of the SQL query that lists the values to
	 * be put into each column of the table
	 * 
	 * @access private
	 * @return string
	 */
	private function getSetExpression()
	{
		$clauses = array();

		foreach ($this->setValues as $name => $value) {

			$value  = $this->formatValue($value);
			$clause = "`{$name}` = $value";

			$clauses[] = $clause;
		}

		$expression  = 'SET ';
		$expression .= implode(",\n", $clauses);
		$expression .= "\n";

		return $expression;
	}

}

