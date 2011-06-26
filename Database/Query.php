<?php

namespace Tree\Database;

/**
 * Query 
 *
 * Base class for generating SQL queries 
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @version    0.00
 */
abstract class Query {

	protected $connection;
	
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	public function formatValue($value)
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
			$value = $this->escapeString($value);
			$value = "'$value'";
			return $value;
		}
	}

	abstract public function getSql();

}

