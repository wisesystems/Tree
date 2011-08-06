<?php

namespace Tree\Orm;

/**
 * Entity 
 *
 * Base class for the individual entites that comprise the model of an
 * application
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Orm
 * @version    0.00
 */
abstract class Entity {

	public function __get($attribute)
	{
	}

	public function __set($attribute, $value)
	{
	}

	public function commitEntity()
	{
	}

	public function deleteEntity()
	{
	}

	public function revertEntity()
	{
	}

	public function hydrateEntity($databaseRow)
	{
	}

	public function setDatabaseConnection($connection)
	{
	}

}

