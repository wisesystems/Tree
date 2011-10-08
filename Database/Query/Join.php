<?php

/**
 * Query_Join
 *
 * Generates SQL for JOIN expressions
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Tree\Database\Query
 * @version    0.00
 */

namespace Tree\Database;

class Query_Join extends Query {

	/**
	 * The name of the table to be joined to
	 * 
	 * @access private
	 * @var    string
	 */
	private $tableName;

	/**
	 * The alias of the table being joined to
	 * 
	 * @access private
	 * @var    string
	 */
	private $tableAlias;

	/**
	 * The type of join to perform
	 * 
	 * @access private
	 * @var    string  e.g. INNER, NATURAL, CROSS
	 */
	private $joinType;

	/**
	 * The Query_Predicate that handles the generation of the ON expression
	 * 
	 * @access private
	 * @var    \Tree\Database\Query_Predicate
	 */
	private $onPredicate;

	/**
	 * @access public
	 * @param  \Tree\Database\Connection $connection 
	 */
	public function __construct($connection)
	{
		parent::__construct($connection);
		$this->onPredicate = new Query_Predicate($connection);
	}

	/**
	 * \Tree\Database\Query: Reverts the query's parameters back to their initial
	 * default state
	 * 
	 * @access public
	 */
	public function clearParameters()
	{
		$this->onPredicate->clearPredicates();

		$this->tableName  = null;
		$this->tableAlias = null;
		$this->joinType   = null;
	}

	/**
	 * Generates and returns the JOIN SQL
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		return $this->getJoinExpression()
			. $this->getOnExpression();
	}

	/**
	 * Stores the name of the table to be joined to, and an alias for that table
	 * if given
	 * 
	 * @access public
	 * @param  string $tableName 
	 * @param  string $tableAlias [optional]
	 * @return \Tree\Database\Query_Join
	 */
	public function setTable($tableName, $tableAlias = null)
	{
		$this->tableName  = $tableName;
		$this->tableAlias = $tableAlias;
		return $this;
	}

	/**
	 * Stores the type of the JOIN to be performed, such as INNER or NATURAL
	 * 
	 * @access public
	 * @param  string $joinType 
	 * @return \Tree\Database\Query_Join
	 */
	public function setType($joinType)
	{
		$this->joinType = $joinType;
		return $this;
	}

	/**
	 * Adds an AND condition to the ON expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Join
	 */
	public function on($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->onPredicate->andPredicate($statement, $parameters);
		return $this;
	}

	/**
	 * Adds an AND condition to the ON expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Join
	 */
	public function andOn($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->onPredicate->andPredicate($statement, $parameters);
		return $this;
	}

	/**
	 * Adds an OR condition to the ON expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Join
	 */
	public function orOn($statement)
	{
		$parameters = func_get_args();
		array_shift($parameters);

		$this->onPredicate->orPredicate($statement, $parameters);
		return $this;
	}

	/**
	 * Generates and returns the JOIN clause of the overall expression
	 * 
	 * @access private
	 * @return string
	 */
	private function getJoinExpression()
	{
		$expression  = strtoupper($this->joinType);
		$expression .= ' JOIN';
		$expression .= " `{$this->tableName}`";

		if ($this->tableAlias !== null) {
			$expression .= " `{$this->tableAlias}`";
		}

		$expression .= "\n";

		return $expression;
	}

	/**
	 * Generates and returns the ON clause of the overall expression
	 * 
	 * @access private
	 * @return string
	 */
	private function getOnExpression()
	{
		$onExpression = $this->onPredicate->getSql();

		if ($onExpression === '') {
			return '';
		}

		return "ON {$onExpression}";
	}

}


