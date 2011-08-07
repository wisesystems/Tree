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
 * @uses       \Tree\Database\Query_Predicate
 * @version    0.00
 */

namespace Tree\Database;

class Query_Delete extends Query {

	/**
	 * The name of the table to be deleted from 
	 * 
	 * @access protected
	 * @var    string
	 */
	protected $tableName;

	private $wherePredicate;

	private $limitStart;

	private $limitEnd;

	public function __construct($connection)
	{
		parent::__construct($connection);
		$this->wherePredicate = new Query_Predicate($connection);
	}

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
			. $this->getLimitExpression();

		return $query;
	}

	/**
	 * Adds an AND condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 */
	public function where($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->andPredicate($statement, $parameters);
	}

	/**
	 * Adds an AND condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 */
	public function andWhere($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->andPredicate($statement, $parameters);
	}

	/**
	 * Adds an OR condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 */
	public function orWhere($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->orPredicate($statement, $parameters);
	}

	public function limit($start, $end = null)
	{
		if ($end === null) {
			$this->limitStart = null;
			$this->limitEnd   = $start;
		} else {
			$this->limitStart = $start;
			$this->limitEnd   = $end;
		}
		return $this;
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

		$expression = ' FROM `%s`';
		$expression = sprintf($expression, $tableName);
		$expression .= "\n";

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

	protected function getWhereExpression()
	{
		$whereExpression = $this->wherePredicate->getSql();

		if ($whereExpression == '') {
			return '';
		}

		return "WHERE {$whereExpression}";
	}

	protected function getLimitExpression()
	{
		if ($this->limitStart === null && $this->limitEnd === null) {
			return '';
		}

		$expression = 'LIMIT ';

		if ($this->limitStart !== null) {
			$expression .= $this->limitStart;
			$expression .= ', ';
		}

		if ($this->limitEnd !== null) {
			$expression .= $this->limitEnd;
		}

		$expression .= "\n";

		return $expression;
	}

}

