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

	private $wherePredicate;

	/**
	 * A list of the columns selected by the query
	 *
	 * @access protected
	 * @var    array $columnNames
	 */
	protected $columnNames = array();

	/**
	 * A list of aliases for the columns in $columnNames,
	 *   e.g SELECT id AS $columnAlias
	 *
	 * @access protected
	 * @var    array $columnAliases
	 */
	protected $columnAliases = array();

	/**
	 * A list of the tables selected from by the query
	 *
	 * @access protected
	 * @var    array $tableNames
	 */
	protected $tableNames = array();

	/**
	 * A list of aliases for the tables in $tableNames
	 *    e.g. SELECT FROM articles a, users u ...
	 *
	 * @access protected
	 * @var    array $tableAliases
	 */
	protected $tableAliases = array();

	/**
	 * A list of column names by which the results should be grouped, which are
	 * compiled into SQL GROUP BY statements.
	 *
	 * @access protected
	 * @var    array $groupColumns
	 */
	protected $groupColumns = array();
	
	/**
	 * A list of column names by which the results should be ordered, which are
	 * compiled into SQL ORDER BY statements.
	 *
	 * @access protected
	 * @var    array $orderColumns
	 */
	protected $orderColumns = array();

	/**
	 * A list of directions in which the orderings from $orderColumns should
	 * run, i.e. either "ASC" or "DESC"
	 *
	 * @access protected
	 * @var    array $orderDirections
	 */
	protected $orderDirections = array();

	protected $whereExpression;

	private $limitStart;

	private $limitEnd;

	public function __construct($connection)
	{
		parent::__construct($connection);

		$this->wherePredicate = new Query_Predicate($connection);

		$this->whereExpression = new Query_Where($connection);
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
	 * @return Query_Select
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
	 * @return Query_Select
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
	 * @return Query_Select
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
	 * @return Query_Select
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
		return $this;}

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
	 * @access protected
	 * @param  string $columnIdentifier   The name of the column by which to order to result
	 * @param  string $direction          ( ASC | DESC )
	 */
	protected function addOrderBy($columnIdentifier, $direction = '')
	{
		$this->orderColumns[]    = $columnIdentifier;
		$this->orderDirections[] = strtoupper($direction);
	}

	/**
	 * Adds a table to the list of tables to select values from
	 *
	 * @access protected
	 * @param  string $tableName   The name of the table
	 * @param  string $tableAlias  The alias of the table
	 */
	protected function addTable($tableName, $tableAlias = '')
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
	 * @access protected 
	 * @return string
	 */
	protected function getColumnExpressions()
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
	 * @access protected
	 * @return string
	 */
	protected function getFromClause()
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
	 * Generates and returns the GROUP BY clause grouping the results of
	 * the query by column name
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getGroupByClause()
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
	 * @access protected
	 * @return string
	 */
	protected function getHavingExpression()
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
	 * @access protected
	 * @return string
	 */
	protected function getOrderExpressions()
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
	 * @access protected
	 * @return string
	 */
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

