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
abstract class Search extends Query_Select {

	abstract protected function getEntityClass();

	public function __construct($database)
	{
		parent::__construct($database);


		$entityClass = $this->getEntityClass();
		$entity      = new $entityClass;
		$tableName   = $entity->getEntityTableName();
		$columnList  = $entity->getEntityColumnList();


		foreach ($columnList as $column) {
			
			$columnName  = "`{$tableName}`.`{$column}`";
			$columnAlias = "{$tableName}:{$column}";

			$this->addColumn($columnName, $columnAlias);

		}

		$this->from(array($tableName => $tableName));

	}

}

