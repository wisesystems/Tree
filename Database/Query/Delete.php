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

	/**
	 * The object that represents and generates the WHERE expression for the SQL
	 * query
	 * 
	 * @access private
	 * @var    \Tree\Database\Query_Predicate
	 */
	private $wherePredicate;

	/**
	 * The first integer in the LIMIT expression, if any
	 *
	 * e.g. the 'x' in LIMIT x,y
	 * 
	 * @access private
	 * @var    integer
	 */
	private $limitStart;

	/**
	 * The second integer in the LIMIT expression, if any
	 *
	 * e.g. the 'y' in LIMIT x,y
	 * 
	 * @access private
	 * @var    integer
	 */
	private $limitEnd;

	/**
	 * @access public
	 * @param  \Tree\Database\Connection $connection 
	 */
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
	 * @return \Tree\Database\Query_Delete
	 */
	public function where($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->andPredicate($statement, $parameters);

		return $this;
	}

	/**
	 * Adds an AND condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Delete
	 */
	public function andWhere($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->andPredicate($statement, $parameters);

		return $this;
	}

	/**
	 * Adds an OR condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Delete
	 */
	public function orWhere($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->wherePredicate->orPredicate($statement, $parameters);

		return $this;
	}

	/**
	 * Sets the limit offsets to control which rows of the overall result set are
	 * to actually be affected by the query
	 *
	 * In the case of a DELETE, the most common use-case for this method is likely
	 * to be limit(1), i.e. to make sure that only one row is deleted no matter
	 * how many match the WHERE expression.
	 * 
	 * @access public
	 * @param  integer $start 
	 * @param  integer $end 
	 * @return \Tree\Database\Query_Delete
	 */
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
	 * @access private
	 * @return string
	 */
	private function getTableExpression()
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
	 * @access private
	 * @return string
	 */
	private function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Stores the name of the table to be deleted from 
	 * 
	 * @access private
	 * @param  string $tableName 
	 */
	private function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

	/**
	 * Generates and returns the WHERE expression that specifies which rows in the
	 * database table are to be deleted
	 * 
	 * @access private
	 * @return string
	 */
	private function getWhereExpression()
	{
		$whereExpression = $this->wherePredicate->getSql();

		if ($whereExpression == '') {
			return '';
		}

		return "WHERE {$whereExpression}";
	}

	/**
	 * Generates and returns the LIMIT expression specifying which rows in the set
	 * matching the WHERE expression are actually to be deleted
	 * 
	 * @access private
	 * @return string
	 */
	private function getLimitExpression()
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

