<?php

namespace Tree\Database;

/**
 * Query_Where 
 *
 * Base class for types of SQL query that need a WHERE expression
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @version    0.00
 */
abstract class Query_Where extends Query {
	
	/**
	 * An array of WHERE clause statements
	 * e.g. 'AND article_id = 1234'
	 * e.g. 'AND article_id = %d'
	 *
	 * @access protected
	 * @var    array
	 */
	protected $clauseStatements = array();

	/**
	 * An array of WHERE clause parameters corresponding to the statements
	 * in $clauseStatements
	 *
	 * For example, if the statements at index 0 in $clauseStatements is
	 * 'AND article_id = %d', then the corresponding parameters would be
	 * array(1234).
	 * 
	 * @access protected
	 * @var    array
	 */
	protected $clauseParameters = array();

	/**
	 * The second of the two numbers that comprise the query's limit
	 * expression, e.g. the 2 in 'LIMIT 1, 2'
	 *
	 * @access protected
	 * @var    integer
	 */
	protected $limitEnd = null;

	/**
	 * The first of the two numbers that comprise the query's limit
	 * expression, e.g. the 1 in 'LIMIT 1, 2'
	 *
	 * @access protected
	 * @var    integer
	 */
	protected $limitStart = null;

	/**
	 * Adds a WHERE clause with 'AND' prepended 
	 * 
	 * @access public
	 * @param  string $statement   e.g. 'article_id = %d'
	 * @param  array  $parameters  e.g. '1234'
	 * @return Query_Where
	 */
	public function andWhere($statement, $parameters = array())
	{
		$parameters = func_get_args();
		$statement  = array_shift($parameters);
		$statement  = "AND $statement";

		$this->addWhereClause($statement, $parameters);
		return $this;
	}

	/**
	 * Sets the LIMIT boundaries controlling how many of the applicable
	 * rows are actually returned or altered
	 *
	 * If both $i and $j are given, the LIMIT expression generated will be
	 * of the form 'LIMIT $i, $j'.
	 *
	 * If only $i is given, the expression will be of the form 'LIMIT $i'
	 *
	 * @param  integer $i 
	 * @param  integer $j
	 * @return Query_Where
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
	 * Adds a WHERE clause with 'OR' prepended 
	 * 
	 * @access public
	 * @param  string $statement   e.g. 'article_id = %d'
	 * @param  array  $parameters  e.g. array(1234)
	 * @return Query_Where
	 */
	public function orWhere($statement)
	{
		$parameters = func_get_args();
		$statement  = array_shift($parameters);
		$statement  = "OR $statement";

		$this->addWhereClause($statement, $parameters);
		return $this;
	}

	/**
	 * Adds a WHERE clause with 'AND' prepended 
	 * 
	 * @access public
	 * @param  string $statement   e.g. 'article_id = %d'
	 * @param  array  $parameters  e.g. '1234'
	 * @return Query_Where
	 */
	public function where($statement)
	{
		$parameters = func_get_args();

		$statement  = array_shift($parameters);
		$statement  = "AND $statement";

		return $this->addWhereClause($statement, $parameters);
	}

	/**
	 * Used by the front-facing methods to actually store clause data 
	 * 
	 * @access protected
	 * @param  string $statement 
	 * @param  array $parameters 
	 */
	protected function addWhereClause($statement, $parameters)
	{
		if (!is_array($parameters)) {
			$parameters = array($parameters);
		}

		$this->clauseStatements[] = $statement;
		$this->clauseParameters[] = $parameters;
	}

	/**
	 * Generates and returns the full WHERE expression from the clauses
	 * stored
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getWhereExpression()
	{
		if (count($this->clauseStatements) == 0) {
			return '';
		}

		$clauses = array();
		foreach ($this->clauseStatements as $i => $statement) {
			$parameters = $this->clauseParameters[$i];

			if ($i == 0) {

				$spacePosition = strpos($statement, ' ');
				$spacePosition++;

				$statement = substr($statement, $spacePosition);
			}

			foreach ($parameters as $i => $parameter) {
				$parameter = $this->formatValue($parameter);
				$parameters[$i] = $parameter;
			}

			if (count($parameters) > 0) {
				$statement = vsprintf($statement, $parameters);
			}

			$clauses[] = $statement;

		}

		$expression  = "WHERE\n\t";
		$expression .= implode("\n\t", $clauses);
		$expression .= "\n";

		return $expression;
	}

	/**
	 * Generates and returns the LIMIT expression for the query 
	 * 
	 * @access protected
	 * @return string
	 */
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

		return $expression;
	}

}

