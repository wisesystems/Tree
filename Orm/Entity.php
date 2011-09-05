<?php

namespace Tree\Orm;

use \Tree\Behaviour\RelatedEntity;
use \Tree\Exception\EntityException;
use \Tree\Database\Query_Update;
use \Tree\Database\Query_Insert;

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

	/**
	 * Indicates that the entity has been hydrated with values from a database
	 * table row
	 */
	const STATE_HYDRATED = 1;

	/**
	 * Indicates that the entity's values have been altered and now differ from
	 * those in its corresponding database table row
	 */
	const STATE_DIRTY = 2;

	/**
	 * The bitmask of the Entity::$state bitfield
	 */
	const STATE_BITMASK = 4;

	const RELATIONSHIP_ONE_TO_ONE = 1;
	const RELATIONSHIP_ONE_TO_MANY = 2;
	const RELATIONSHIP_MANY_TO_ONE = 3;
	const RELATIONSHIP_MANY_TO_MANY = 4;

	/**
	 * To be overridden with a method that returns an array listing the column
	 * names that comprise the entity's primary key
	 * 
	 * @abstract
	 * @access public
	 * @return array
	 */
	abstract public function getEntityPrimaryKey();

	/**
	 * To be overridden with a method that returns an array listing the column
	 * names of the entity's database table
	 * 
	 * @abstract
	 * @access public
	 * @return array
	 */
	abstract public function getEntityColumnList();

	/**
	 * To be overridden with a method that returns a string denoting the name of
	 * the database table corresponding to the entity
	 * 
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function getEntityTableName();

	/**
	 * Associative array of the current values of the entity's fields
	 * 
	 * @access private
	 * @var    array
	 */
	private $currentValues = array();

	/**
	 * Associative array of the name-value pairs of data with which the entity was
	 * originally hydrated
	 * 
	 * @access private
	 * @var    array
	 */
	private $originalValues = array();

	/**
	 * A bitfield representing the state of the entity
	 *
	 * See the Entity::STATE_* constants for more information.
	 * 
	 * @access private
	 * @var    integer
	 */
	private $state;

	/**
	 * The database connection that the entity uses to store itself
	 * 
	 * @access private
	 * @var    \Tree\Database\Connection
	 */
	private $database;

	/**
	 * Returns the value of the attribute of the given name
	 * 
	 * @access public
	 * @param  string $attribute 
	 * @return mixed
	 * @throws \Tree\Exception\EntityException
	 */
	public function __get($attribute)
	{
		$columnList = $this->getEntityColumnList();

		if (in_array($attribute, $columnList)) {

			if (isset($this->currentValues[$attribute])) {
				return $this->currentValues[$attribute];
			} else {
				// attribute is valid but entity probably just hasn't been hydrated, so
				// return null without throwing an exception
				return null;
			}

		}

		/*
		TODO:
		Check for a relationship whose name matches $attribute.
		If the related entity/entities are not loaded, fetch them from database.
		*/

		$message = "No such attribute: {$attribute}";
		$code    = EntityException::NO_SUCH_ATTRIBUTE;
		throw new EntityException($message, $code);
	}

	/**
	 * Sets the given attribute's value 
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  string $value 
	 * @throws \Tree\Exception\EntityException
	 */
	public function __set($name, $value)
	{
		$columnList = $this->getEntityColumnList();
		if (!in_array($name, $columnList)) {
			$message = "No such attribute: {$name}";
			$code    = EntityException::NO_SUCH_ATTRIBUTE;
			throw new EntityException($message, $code);
		}

		if (isset($this->originalValues[$name]) && $this->originalValues[$name] !== $value) {
			$this->addState(self::STATE_DIRTY);
		}

		$this->currentValues[$name] = $value;
	}

	/**
	 * Stores the entity's values to the database
	 *
	 * @access public
	 * @return boolean
	 */
	public function commitEntity()
	{
		if ($this->getEntityTableName() === null) {
			$message = 'No database table name set';
			$code    = EntityException::NO_TABLE_NAME_SET;
			throw new EntityException($message, $code);
		}

		if ($this->hasState(Entity::STATE_HYDRATED)) {
			return $this->updateEntity();
		} else {
			return $this->insertEntity();
		}

		return false;
	}

	/**
	 * Removes the entity's corresponding database row, if one exists
	 * 
	 * @access public
	 * @return boolean
	 */
	public function deleteEntity()
	{
		return false;
	}

	/**
	 * Resets the entity to the state it was in when it was hydrated, undoing any
	 * changes to the values of its attributes
	 * 
	 * @access public
	 */
	public function revertEntity()
	{
		if (!$this->hasState(self::STATE_HYDRATED)) {
			$message = 'Cannot revert an entity that has not been hydrated';
			$code    = EntityException::REVERTING_UNHYDRATED_ENTITY;
			throw new EntityException($message, $code);
		}

		$this->currentValues = $this->originalValues;

		$this->removeState(self::STATE_DIRTY);
	}

	/**
	 * Populates the entity's attributes with data from its corresponding database
	 * row
	 * 
	 * @access public
	 * @param  array $databaseRow 
	 */
	public function hydrateEntity(array $databaseRow)
	{
		$columnList = $this->getEntityColumnList();

		foreach ($databaseRow as $name => $value) {
		
			if (!in_array($name, $columnList)) {
				$message = "Hydration aborted because of invalid data: {$name}, {$value}";
				$code    = EntityException::HYDRATED_WITH_INVALID_DATA;
				throw new EntityException($message, $code);
			}

			$this->originalValues[$name] = $value;
			$this->currentValues[$name]  = $value;

		}

		$this->addState(self::STATE_HYDRATED);
	}

	/**
	 * Sets the database connection with which the entity should is to insert,
	 * update or delete itself
	 * 
	 * @access public
	 * @param  \Tree\Database\Connection $database 
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}

	/**
	 * Sets the entity to have the given state
	 * 
	 * @access public
	 * @param  integer $entityState  e.g. Entity::STATE_HYDRATED
	 */
	public function addState($entityState)
	{
		$this->state |= 1 << $entityState;
	}

	/**
	 * Removes the given state from the entity
	 * 
	 * @access public
	 * @param  integer $entityState  e.g. Entity::STATE_HYDRATED
	 */
	public function removeState($entityState)
	{
		$this->state &= ~ (1 << $entityState);
	}

	/**
	 * Indicates whether the entity has the given state
	 * 
	 * @access public
	 * @param  integer $entityState  e.g. Entity::STATE_HYDRATED
	 * @return boolean
	 */
	public function hasState($entityState)
	{
		$mask = 1 << $entityState;

		if (($mask & $this->state) === $mask) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Indicates whether the given entity is related to this one
	 * 
	 * @access public
	 * @param  mixed $entity  Either an entity object or class name
	 * @return boolean
	 */
	public function isRelatedToEntity($entity)
	{
		if (!($this instanceof RelatedEntity)) {
			return false;
		}

		$myClass     = get_class($this);
		$theirClass  = is_object($entity) ? get_class($entity) : $entity;
		$theirEntity = is_object($entity) ? $entity : new $theirEntity;

		if (is_object($theirEntity) && !($theirEntity instanceof RelatedEntity)) {
			return false;
		}


		$myRelated    = false;
		$theirRelated = false;

	
		$myRelationships = $this->getEntityRelationships();
		foreach ($myRelationships as $relationship) {
		
			if (is_a($theirEntity, $relationship['class'])) {
				$myRelated = true;
				break;
			}
		}

		
		$theirRelationships = $theirEntity->getEntityRelationships();
		foreach ($theirRelationships as $relationship) {
		
			if (is_a($this, $relationship['class'])) {
				$theirRelated = true;
				break;
			}
		}


		if ($myRelated && $theirRelated) {
			return true;
		} elseif (!$myRelated && !$theirRelated) {
			return false;
		} else {
			// TODO: throw relationship mismatch exception
		}

	}

	/**
	 * Finds and returns the relationship of the given name
	 * 
	 * @access public
	 * @param  string $name 
	 * @return array
	 */
	public function getRelationshipByName($name)
	{
		if (!($this instanceof RelatedEntity)) {
			return null;
		}

		$relationships = $this->getEntityRelationships();

		foreach ($relationships as $relationship) {
			if ($relationship['name'] === $name) {
				return $relationship;
			}
		}
	}

	/**
	 * Inserts the entity's data into its corresponding database table as a new row
	 * 
	 * @access private
	 * @return boolean
	 */
	private function insertEntity()
	{
		$query = new Query_Insert($this->database);
		$query->into($this->getEntityTableName());

		$columnList = $this->getEntityColumnList();

		foreach ($columnList as $column) {
			$query->set($column, $this->$column);
		}
		
		$result = $query->getResult();

		return $result->getStatus();
	}

	/**
	 * Updates the entity's corresponding database row with its current data
	 * 
	 * @access private
	 * @return boolean
	 */
	private function updateEntity()
	{
		$columnList = $this->getEntityColumnList();
		$primaryKey = $this->getEntityPrimaryKey();

		$query = new Query_Update($this->database);
		$query->table($this->getEntityTableName());

		foreach ($primaryKey as $column) {
			$query->where("`$column` = %s", $this->$column);
		}

		foreach ($columnList as $column) {

			if (in_array($column, $primaryKey)) {
				continue;
			}

			$query->set($column, $this->$column);

		}

		$result = $query->getResult();
		
		return $result->getStatus();
	}

}

