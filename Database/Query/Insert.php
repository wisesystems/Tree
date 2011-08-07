<?php

/**
 * Query_Insert
 *
 * Generates SQL for INSERT queries
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

class Query_Insert extends Query {

	/**
	 * The name of the table into which the data is to be inserted 
	 * 
	 * @access protected
	 * @var    string
	 */
	protected $tableName;

	/**
	 * An associative array in which the keys are names of columns of the
	 * target database table, and the values are the values to be inserted
	 * into those columns in the new row
	 * 
	 * @access protected
	 * @var    array
	 */
	protected $columnValuePairs = array();

	/**
	 * Generates and returns the INSERT SQL 
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		return 'INSERT INTO '
			. "`{$this->getTableName()}`"
			. $this->getColumnNames()
			. ' VALUES '
			. $this->getColumnValues();
	}

	/**
	 * Sets the name of the table into which the data will be inserted 
	 *
	 * The name of this method is intended as syntactic sugar, mimicking
	 * normal SQL syntax: INSERT INTO $tableName ....
	 * 
	 * @access public
	 * @param  string $tableName 
	 * @return Query_Insert
	 */
	public function into($tableName)
	{
		$this->setTableName($tableName);
		return $this;
	}

	/**
	 * Sets one column-value pair of data, e.g. if a table has a column
	 * 'name', then a typical set of arguments might be ('name', 'Richard')
	 * 
	 * The name of this method isn't quite exactly syntactic sugar, as
	 * INSERT queries don't use the SET keyword. There's no real clear
	 * choice of name, so using 'set' will at least keep things consistent
	 * with REPLACE INTO queries.
	 *
	 * @access public
	 * @param  string $column 
	 * @param  string $value 
	 * @return Query_Insert
	 */
	public function set($column, $value)
	{
		$this->setColumnValuePair($column, $value);
		return $this;
	}

	/**
	 * Generates and returns the column name list part of the query
	 *
	 * e.g. '(`user_id`, `user_name`, `user_password`)'
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getColumnNames()
	{
		$names = array_keys($this->columnValuePairs);

		foreach ($names as $i => $name) {
			$names[$i] = "`$name`";
		}

		$names = implode(', ', $names);
		$names = " ($names)";

		return $names;
	}

	/**
	 * Generates and returns the column value list part of the query
	 *
	 * e.g. "(1234, 'admin', 'swordfish')"
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getColumnValues()
	{
		$values = array_values($this->columnValuePairs);

		foreach ($values as $i => $value) {
			$values[$i] = $this->formatValue($value);
		}

		$values = implode(', ', $values);
		$values = "($values)";

		return $values;
	}

	/**
	 * Returns the name of the table into which the data is to be inserted 
	 * 
	 * @access protected
	 * @return string
	 */
	protected function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Sets the name of the table into which the data is to be inserted 
	 * 
	 * @access protected
	 * @param  string $name 
	 */
	protected function setTableName($name)
	{
		$this->tableName = $name;
	}

	/**
	 * Stores one column-value pair of data to be inserted 
	 *
	 * The purpose of using this method rather than writing directly to
	 * the array in every public-facing method that needs to store this
	 * data is to keep the data access all in one place.
	 * 
	 * @access protected
	 * @param  string $column 
	 * @param  string $value 
	 */
	protected function setColumnValuePair($column, $value)
	{
		$this->columnValuePairs[$column] = $value;
	}

}

