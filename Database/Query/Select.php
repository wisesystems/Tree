<?php

namespace Tree\Database;

/**
 * Query_Select 
 *
 * Generates SELECT SQL queries
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
class Query_Select extends Query {

	/**
	 * The object that represents the predicates comprising the query's WHERE
	 * expression
	 * 
	 * @access private
	 * @var    \Tree\Database\Query_Predicate
	 */
	private $wherePredicate;

	/**
	 * A list of the columns selected by the query
	 *
	 * @access private
	 * @var    array $columnNames
	 */
	private $columnNames = array();

	/**
	 * A list of aliases for the columns in $columnNames,
	 *   e.g SELECT id AS $columnAlias
	 *
	 * @access private
	 * @var    array $columnAliases
	 */
	private $columnAliases = array();

	/**
	 * A list of the tables selected from by the query
	 *
	 * @access private
	 * @var    array $tableNames
	 */
	private $tableNames = array();

	/**
	 * A list of aliases for the tables in $tableNames
	 *    e.g. SELECT FROM articles a, users u ...
	 *
	 * @access private
	 * @var    array $tableAliases
	 */
	private $tableAliases = array();

	/**
	 * An array of Query_Join objecs representing joins to other database tables
	 * 
	 * @access private
	 * @var    array
	 */
	private $joins = array();

	/**
	 * A list of column names by which the results should be grouped, which are
	 * compiled into SQL GROUP BY statements.
	 *
	 * @access private
	 * @var    array $groupColumns
	 */
	private $groupColumns = array();
	
	/**
	 * A list of column names by which the results should be ordered, which are
	 * compiled into SQL ORDER BY statements.
	 *
	 * @access private
	 * @var    array $orderColumns
	 */
	private $orderColumns = array();

	/**
	 * A list of directions in which the orderings from $orderColumns should
	 * run, i.e. either "ASC" or "DESC"
	 *
	 * @access private
	 * @var    array $orderDirections
	 */
	private $orderDirections = array();

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
	 * Sets the tables which should be queried
	 *
	 * Two types of parameter are supported:
	 *   @param array  Arrays are treated as a list of key => value pairs
	 *                 in which the keys are table names and the values
	 *                 are aliases
	 *   @param string Strings are treated as simple table names without
	 *                 aliases 
	 *
	 * @access public
	 * @return \Tree\Database\Query_Select
	 */
	public function from()
	{
		foreach (func_get_args() as $argument) {
			if (is_array($argument)) {
				foreach ($argument as $tableName => $tableAlias) {
					$this->addTable($tableName, $tableAlias);
				}
			} else {
				$this->addTable($argument);
			}
		}
		return $this;		
	}

	/**
	 * Generates and returns the SQL statement
	 *
	 * Based on the following syntax specification:
	 *   SELECT [ DISTINCT | ALL ]
	 *   column_expression1, column_expression2, ....
	 *   [ FROM from_clause ]
	 *   [ WHERE where_expression ]
	 *   [ GROUP BY expression1, expression2, .... ]
	 *   [ HAVING having_expression ]
	 *   [ ORDER BY order_column_expr1, order_column_expr2, .... ]
	 *
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		return
			'SELECT '
			. $this->getColumnExpressions()
			. $this->getFromClause()
			. $this->getJoinExpressions()
			. $this->getWhereExpression()
			. $this->getGroupByClause()
			. $this->getHavingExpression()
			. $this->getOrderExpressions()
			. $this->getLimitExpression()
		;
	}

	/**
	 * Adds a column name by which to group the results
	 *
	 * @access public
	 * @param  string $groupBy
	 * @return \Tree\Database\Query_Select
	 */
	public function groupBy($columnIdentifier)
	{
		$this->groupColumns[] = $columnIdentifier;
		return $this;
	}

	/**
	 * Adds a column name by which to order the results
	 *
	 * @access public
	 * @param  string $orderBy
	 * @param  string $direction  ( ASC | DESC )
	 * @return \Tree\Database\Query_Select
	 */
	public function orderBy($columnIdentifier, $direction = '')
	{
		$this->addOrderBy($columnIdentifier, $direction);
		return $this;
	}

	/**
	 * Sets the columns which should be returned
	 *
	 * @access public
	 * @param  mixed   If an array is given, the keys are treated as column names and the
	 *                 values as aliases. Otherwise the arguments are added as column names
	 *                 without aliases
	 * @return \Tree\Database\Query_Select
	 */
	public function select()
	{
		$arguments = func_get_args();
		//$arguments = Utility::flattenArray($arguments);

		foreach ($arguments as $columnName => $columnAlias) {
			if (is_int($columnName)) {
				$columnName  = $columnAlias;
				$columnAlias = null;
			}
			$this->addColumn($columnName, $columnAlias);
		}

		return $this;
	}

	/**
	 * Adds an AND condition to the WHERE expression
	 * 
	 * @access public
	 * @param  string $statement 
	 * @return \Tree\Database\Query_Select
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
	 * @return \Tree\Database\Query_Select
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
	 * @return \Tree\Database\Query_Select
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
	 * to actually be returned by the query
	 *
	 * @access public
	 * @param  integer $start 
	 * @param  integer $end 
	 * @return \Tree\Database\Query_Select
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
	 * Adds a Query_Join object representing another JOIN expression to be part of
	 * the SELECT query
	 * 
	 * @access public
	 * @param  \Tree\Database\Query_Join $join 
	 * @return \Tree\Database\Query_Select
	 */
	public function addJoin($join)
	{
		$this->joins[] = $join;
		return $this;
	}

	/**
	 * Adds a column to the list of columns whose values are to be selected
	 * 
	 * @access protected
	 * @param  string $columnName   The name of the column in the table
	 * @param  string $columnAlias  The alias of the column
	 */
	protected function addColumn($columnName, $columnAlias = '')
	{
		$this->columnNames[]   = $columnName;
		$this->columnAliases[] = $columnAlias;
	}

	/**
	 * Adds an ORDER BY clause to the list
	 *
	 * @access private
	 * @param  string $columnIdentifier   The name of the column by which to order to result
	 * @param  string $direction          ( ASC | DESC )
	 */
	private function addOrderBy($columnIdentifier, $direction = '')
	{
		$this->orderColumns[]    = $columnIdentifier;
		$this->orderDirections[] = strtoupper($direction);
	}

	/**
	 * Adds a table to the list of tables to select values from
	 *
	 * @access private
	 * @param  string $tableName   The name of the table
	 * @param  string $tableAlias  The alias of the table
	 */
	private function addTable($tableName, $tableAlias = '')
	{
		$this->tableNames[]   = $tableName;
		$this->tableAliases[] = $tableAlias;
	}

	/**
	 * Generates and returns the column expressions that specify which columns
	 * to return
	 *
	 * Based on this syntax:
	 *   column_expression ::= expression [ AS ] [ column_alias ]
	 *
	 * @access private 
	 * @return string
	 */
	private function getColumnExpressions()
	{
		$columnExpressions = array();

		foreach ($this->columnNames as $i => $columnName) {

			$columnExpression = $columnName;
			if ($this->columnAliases[$i] != '') {
				$columnExpression .= ' AS ' . $this->columnAliases[$i];
			}
			$columnExpressions[] = $columnExpression;

		}

		$columnExpressions = implode(', ', $columnExpressions) . "\n";

		return $columnExpressions;
	}

	/**
	 * Generates and returns the FROM clause specifying which tables to
	 * use for the query
	 *
	 * So far the following syntaxes are supported:
	 *
	 *   from_clause  ::= select_table1, select_table2, ...
	 *   select_table ::= table_name [ AS ] [ table_alias ]
	 *
	 * The following are currently unsupported but may be added later:
	 *
	 *   from_clause  ::= select_table1 LEFT [OUTER] JOIN select_table2 ON expr  ...
	 *   from_clause  ::= select_table1 RIGHT [OUTER] JOIN select_table2 ON expr  ...
	 *   from_clause  ::= select_table1 [INNER] JOIN select_table2  ...
	 *   select_table ::= ( sub_select_statement ) [ AS ] [ table_alias ]
	 *
	 * @access private
	 * @return string
	 */
	private function getFromClause()
	{
		$fromClauses = array();

		foreach ($this->tableNames as $i => $tableName) {

			$fromClause = "`{$tableName}`";
			if ($this->tableAliases[$i] != '') {
				$fromClause .= " `{$this->tableAliases[$i]}`";
			}
			$fromClauses[] = $fromClause;
		}

		$fromClause  = 'FROM ';
		$fromClause .= implode(', ', $fromClauses);
		$fromClause .= "\n";

		return $fromClause;
	}

	/**
	 * Generates and returns the JOIN expressions specifying which tables the main
	 * table should be joined to and how
	 * 
	 * @access private
	 * @return string
	 */
	private function getJoinExpressions()
	{
		if (count($this->joins) === 0) {
			return '';
		}

		$joinExpressions = '';

		foreach ($this->joins as $join) {
			$joinExpressions .= $join->getSql();
		}

		return $joinExpressions;
	}

	/**
	 * Generates and returns the GROUP BY clause grouping the results of
	 * the query by column name
	 * 
	 * @access private
	 * @return string
	 */
	private function getGroupByClause()
	{
		if (count($this->groupColumns) == 0) {
			return '';
		}
		$groupByClause  = "GROUP BY ";
		$groupByClause .= implode(", ", $this->groupColumns);
		$groupByClause .= "\n";
		return $groupByClause;
	}

	/**
	 * Generates and returns the HAVING clause
	 *
	 * TODO: Implement HAVING expressions in SELECT queries
	 * 
	 * @access private
	 * @return string
	 */
	private function getHavingExpression()
	{
		return '';
	}

	/**
	 * Generates and returns the ORDER BY expressions that order the results
	 * of the query by column names
	 *
	 * Based on this syntax:
	 *   order_column_expr ::= expression [ ASC | DESC ]
	 *
	 * @access private
	 * @return string
	 */
	private function getOrderExpressions()
	{
		if (count($this->orderColumns) == 0) {
			return '';
		}

		$orderExpressions = array();

		foreach ($this->orderColumns as $i => $columnIdentifier) {
			$orderExpression = "`{$columnIdentifier}`";
			if ($this->orderDirections[$i] != '') {
				$orderExpression .= ' ' . $this->orderDirections[$i];
			}
			$orderExpressions[] = $orderExpression;
		}

		$orderExpressions = 'ORDER BY '
			. implode(', ', $orderExpressions)
			. "\n";

		return $orderExpressions;
	}

	/**
	 * Generates and returns the WHERE expression that restricts what rows are
	 * returned according to the values of those rows
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
	 * matching the WHERE expression are actually to be returned
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

