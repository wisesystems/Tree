<?php

namespace Tree\Database;

/**
 * Query_Update  
 * 
 * Generates SQL for UPDATE queries
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Query_Predicate
 * @version    0.00
 */
class Query_Update extends Query {

	private $wherePredicate;

	/**
	 * An associative array of values to be set by the query
	 *
	 * e.g. array(
	 *              'article_id'    => 1234,
	 *              'article_title' => 'Example Article',
	 *      )
	 * 
	 * @access protected
	 * @var    array
	 */
	protected $setValues = array();

	/**
	 * The name of the table to be updated 
	 * 
	 * @access protected
	 * @var    string
	 */
	protected $tableName;

	private $limitStart;

	private $limitEnd;

	public function __construct($connection)
	{
		parent::__construct($connection);

		$this->wherePredicate = new Query_Predicate($connection);
	}

	/**
	 * Generates and returns the UPDATE query SQL 
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		$tableName = $this->getTableName();

		$sql = 'UPDATE '
			. "`$tableName`" . "\n"
			. $this->getSetClause()
			. $this->getWhereExpression()
			. $this->getLimitExpression();

		return $sql;
	}

	/**
	 * Adds a (column, value) pair to the list of values to be modified
	 * by the query
	 *
	 * Can also add several pairs of values, if the first parameter is an
	 * associative array and the second parameter is null.
	 *
	 * The method name has been chosen as syntactic sugar in order to
	 * maintain similarity with SQL
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  string $value 
	 * @return Query_Update
	 */
	public function set($name, $value = null)
	{
		if (is_array($name) && $value === null) {
			$this->setValues($name);
		} elseif($name !== null && $value !== null) {
			$this->setValue($name, $value);
		}
		return $this;
	}

	/**
	 * Sets the name of the table to be modified by the query 
	 * 
	 * The method name has been chosen as syntactic sugar in order to
	 * maintain similarity with SQL
	 * 
	 * @access public
	 * @param  string $tableName 
	 * @return Query_Update
	 */
	public function table($tableName)
	{
		$this->setTableName($tableName);
		return $this;
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
	 * Generates and returns the full 'SET x = y ...' section of the SQL
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getSetClause()
	{
		if (count($this->setValues) == 0) {
			return '';
		}

		$setClause = array();
		foreach ($this->setValues as $name => $value) {
			$value = $this->formatValue($value);
			$setClause[] = "`$name` = $value";
		}
		
		$setClause = implode(",\n", $setClause);
		$setClause = "SET {$setClause}\n";

		return $setClause;
	}

	/**
	 * Returns the name of the database table to be modified 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Sets the database table which is to be modified 
	 * 
	 * @access protected
	 * @param  string $tableName 
	 * @return Query_Update
	 */
	protected function setTableName($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	/**
	 * Stores a (column, value) pair to be included in the query
	 *
	 * @access protected
	 * @param  string $name 
	 * @param  string $value 
	 */
	protected function setValue($name, $value)
	{
		$this->setValues[$name] = $value;
	}

	/**
	 * Stores a set of (column, value) pairs to be included in the query 
	 * 
	 * @access protected
	 * @param  array $values 
	 */
	protected function setValues($values)
	{
		$this->setValues = array_merge($values, $this->setValues);
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

