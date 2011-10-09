<?php

namespace Tree\Orm;

use \Tree\Behaviour\RelatedEntity;
use \Tree\Database\Query_Select;
use \Tree\Database\Query_Join;
use \Tree\Exception\SearchException;

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
	 * @access protected
	 * @var    \Tree\Orm\Entity
	 */
	protected $baseEntity;

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
		$this->baseEntity->setDatabase($database);

		$tableName  = $this->baseEntity->getEntityTableName();
		$columnList = $this->baseEntity->getEntityColumnList();

		foreach ($columnList as $column) {
			
			$columnName  = "`{$tableName}`.`{$column}`";
			$columnAlias = "{$tableName}:{$column}";

			$this->addColumn($columnName, $columnAlias);

		}

		$this->from(array($tableName => $tableName));

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

	/**
	 * Marks the given relationship to denote that the related entity should be
	 * retrieved from the database 
	 *
	 * Only works with relationships in which there is only one row for the
	 * corresponding related entity
	 * 
	 * @access public
	 * @param  string $relationshipName 
	 */
	public function withRelationship($relationshipName)
	{
		$relationship = $this->baseEntity->getEntityRelationship($relationshipName);

		// this is important for helping people catch little typos in a way that
		// guides them straight to the mistake
		if ($relationship === null) {
			$message = "Cannot include non-existent relationship '{$relationshipName}'";
			$code    = SearchException::NO_SUCH_RELATIONSHIP;
			throw new SearchException($message, $code, $this);
		}

		$joinableCardinalities = array(
			Entity::RELATIONSHIP_ONE_TO_ONE,
			Entity::RELATIONSHIP_MANY_TO_ONE,
		);

		if (!in_array($relationship['cardinality'], $joinableCardinalities)) {
			$message = "Cannot join to multiple rows ({$relationshipName})";
			$code    = SearchException::CANNOT_INCLUDE_RELATIONSHIP;
			throw new SearchException($message, $code, $this);
		}

		$this->addJoinForRelationship($relationshipName);
	}

	/**
	 * Adds a JOIN clause to the query in order for it to pull in data for a
	 * related entity in addition to the table columns of the entity itself
	 * 
	 * @access private
	 * @param  string $relationshipName 
	 */
	private function addJoinForRelationship($relationshipName)
	{
		$tableName  = $this->baseEntity->getEntityTableName();
		$columnList = $this->baseEntity->getEntityColumnList();

		$relationship = $this->baseEntity->getEntityRelationship($relationshipName);

		$className  = $relationship['class'];
		$foreignKey = $relationship['foreign-key'];

		$otherEntity = new $className;
		$otherRelationship = $otherEntity->getEntityRelationship(null, $this->entityClass);
		$otherName  = $otherRelationship['name'];
		$otherTable = $otherEntity->getEntityTableName();
		$otherKey = $otherRelationship['foreign-key'];

		$otherColumns = $otherEntity->getEntityColumnList();

		foreach ($otherColumns as $column) {

			$columnName  = "`{$otherTable}`.`{$column}`";
			$columnAlias = "{$relationshipName}:{$column}";

			$this->addColumn($columnName, $columnAlias);
		}

		$join = new Query_Join($this->connection);
		$join->setTable($otherTable, $relationshipName);
		$join->setType('LEFT');
		$join->on("`$tableName`.`$foreignKey` = `$otherTable`.`$otherKey`");

		$this->addJoin($join);

	}

}

