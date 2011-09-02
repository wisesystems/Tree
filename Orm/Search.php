<?php

namespace Tree\Orm;

use \Tree\Database\Query_Select;

/**
 * Search 
 *
 * Base class for building searches for entities
 *
 * Heavily influenced by the Jelly ORM library for the Kohana framework
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Orm
 * @uses       \Tree\Database\Query_Select
 * @version    0.00
 */
 class Search extends Query_Select {

	protected $entityClass;

	/**
	 * A blank copy of the base entity being searched for
	 * 
	 * @access private
	 * @var    \Tree\Orm\Entity
	 */
	private $baseEntity;

	/**
	 * @param \Tree\Database\Connection $database 
	 * @param string                    $entityClass [optional]
	 */
	public function __construct($database, $entityClass = null)
	{
		parent::__construct($database);

		if ($entityClass !== null) {
			$this->entityClass = $entityClass;
		}

		$this->baseEntity = new $this->entityClass;

	}

	/**
	 * Generates and returns the SQL representing any search conditions that have
	 * been set
	 * 
	 * @access public
	 * @return string
	 */
	public function getSql()
	{
		$tableName  = $this->baseEntity->getEntityTableName();
		$columnList = $this->baseEntity->getEntityColumnList();

		foreach ($columnList as $column) {
			
			$columnName  = "`{$tableName}`.`{$column}`";
			$columnAlias = "{$tableName}:{$column}";

			$this->addColumn($columnName, $columnAlias);

		}

		$this->from(array($tableName => $tableName));

		return parent::getSql();
	}

	/**
	 * Sends the query to the database, returning the resulting set of entities
	 * 
	 * @access public
	 * @return \Tree\Orm\Result
	 */
	public function getResult()
	{
		$databaseResult = parent::getResult();

		$result = new Result($databaseResult, $this->baseEntity);
		return $result;
	}

}

