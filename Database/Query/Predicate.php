<?php

namespace Tree\Database;

class Query_Predicate {

	/**
	 * The database connection to use for escaping input values
	 * 
	 * @access private
	 * @var    \Tree\Database\Connection
	 */
	private $connection;

	/**
	 * An array of logical operators to be prefixed to each statement, e.g. 'AND'
	 * or 'OR'
	 * 
	 * @access private
	 * @var    array
	 */
	private $operators = array();

	/**
	 * An array of statements comprising the actual logical conditions, e.g.
	 * 'id = 10', 'title = %s' and so on
	 * 
	 * @access private
	 * @var    array
	 */
	private $statements = array();

	/**
	 * An array of parameter values to be escaped and injected into the statements
	 * when generating the SQL
	 * 
	 * @access private
	 * @var    array
	 */
	private $parameters = array();

	/**
	 * @access public
	 * @param  \Tree\Database\Connection $connection 
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Generates and returns the SQL summarising the list of predicates
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		if (count($this->statements) === 0) {
			return '';
		}

		$clauses = array();

		foreach ($this->statements as $i => $statement) {

			$operator   = $this->operators[$i];
			$parameters = $this->parameters[$i];

			$clause = '';

			if ($i > 0) {
				$clause .= $operator;
				$clause .= ' ';
			}

			foreach ($parameters as $i => $parameter) {
				$parameters[$i] = $this->formatValue($parameter);
			}

			if (count($parameters) > 0) {
				$statement = vsprintf($statement, $parameters);
			}

			$clause .= $statement;

			$clauses[] = $clause;

		}

		$expression  = implode("\n", $clauses);
		$expression .= "\n";

		return $expression;
	}

	/**
	 * Adds a predicate prefixed by the AND operator
	 * 
	 * @access public
	 * @param  string $statement 
	 */
	public function andPredicate($statement, array $parameters)
	{
		$this->addStatement('AND', $statement, $parameters);
	}

	/**
	 * Adds a predicate prefixed by the OR operator
	 * 
	 * @access public
	 * @param  string $statement 
	 */
	public function orPredicate($statement, array $parameters)
	{
		$this->addStatement('OR', $statement, $parameters);
	}

	/**
	 * Adds a statement to the list of those to form the expression
	 * 
	 * @access private
	 * @param  string $operator     AND | OR
	 * @param  string $statement    e.g. 'id = %d'
	 * @param  array $parameters    e.g. { 10 }
	 */
	private function addStatement($operator, $statement, array $parameters)
	{
		$this->operators[]  = $operator;
		$this->statements[] = $statement;
		$this->parameters[] = $parameters;
	}

	/**
	 * Formats the given variable as a string to be used in a SQL query
	 * 
	 * @access private
	 * @param  mixed $value 
	 * @return string
	 */
	private function formatValue($value)
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
			$value = $this->connection->escapeString($value);
			$value = "'$value'";
			return $value;
		}
	}

}

