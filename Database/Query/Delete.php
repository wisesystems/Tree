<?php

namespace Tree\Database;

/**
 * Query_Delete  
 *
 * Generates SQL DELETE queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Connection
 * @uses       \Tree\Database\Query_Where
 * @version    0.00
 */

namespace Tree\Database;

class Query_Delete extends Query_Where {

	/**
	 * The name of the table to be deleted from 
	 * 
	 * @access protected
	 * @var    string
	 */
	protected $tableName;

	/**
	 * Sets the name of the table to be deleted from 
	 *
	 * The name is intended as syntactic sugar, mimicking the familiar SQL
	 * syntax "DELETE FROM `$tableName`".
	 * 
	 * @access public
	 * @param  string $tableName 
	 * @return Query_Delete
	 */
	public function from($tableName)
	{
		$this->setTableName($tableName);
		return $this;
	}

	/**
	 * Generates and returns the DELETE SQL query 
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		$query = 'DELETE'
			. $this->getTableExpression()
			. $this->getWhereExpression()
			. $this->getLimitExpression()
			. "\n";

		return $query;
	}

	/**
	 * Generates and returns the table expression part of the query
	 *
	 * e.g. "FROM `article`"
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getTableExpression()
	{
		$tableName = $this->getTableName();

		$expression = ' FROM `%s` ';
		$expression = sprintf($expression, $tableName);

		return $expression;
	}

	/**
	 * Returns the name of the table to be deleted from 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Stores the name of the table to be deleted from 
	 * 
	 * @access protected
	 * @param  string $tableName 
	 */
	protected function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

}

