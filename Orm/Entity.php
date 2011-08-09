<?php

namespace Tree\Orm;

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

	const STATE_NONE = 0;
	const STATE_HYDRATED = 1;
	const STATE_DIRTY = 2;
	const STATE_BITMASK = 3;

	protected $columnList = array();

	protected $primaryKey = array();

	protected $tableName;

	private $currentValues = array();

	private $originalValues = array();

	private $state;

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
		if (!in_array($attribute, $this->columnList)) {
			$message = "No such attribute: {$attribute}";
			$code    = EntityException::NO_SUCH_ATTRIBUTE;
			throw new EntityException($message, $code);
		}

		if (!isset($this->currentValues[$attribute])) {
			return null;
		}
		
		return $this->currentValues[$attribute];
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
		if (!in_array($name, $this->columnList)) {
			$message = "No such attribute: {$name}";
			$code    = EntityException::NO_SUCH_ATTRIBUTE;
			throw new EntityException($message, $code);
		}

		if (isset($this->originalValues[$name]) && $this->originalValues[$name] !== $value) {
			$this->state |= self::STATE_DIRTY;
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
		if ($this->tableName === null) {
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
		foreach ($databaseRow as $name => $value) {
		
			if (!in_array($name, $this->columnList)) {
				// todo: throw exception
				continue;
			}

			$this->originalValues[$name] = $value;
			$this->currentValues[$name]  = $value;

		}
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
	 * Inserts the entity's data into its corresponding database table as a new row
	 * 
	 * @access private
	 * @return boolean
	 */
	private function insertEntity()
	{
		$query = new Query_Insert($this->database);
		$query->into($this->tableName);

		foreach ($this->columnList as $column) {
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
		$query = new Query_Update($this->database);
		$query->table($this->tableName);

		foreach ($this->primaryKey as $column) {
			$query->where("`$column` = %s", $this->$column);
		}

		foreach ($this->columnList as $column) {

			if (in_array($column, $this->primaryKey)) {
				continue;
			}

			$query->set($column, $this->$column);

		}

		$result = $query->getResult();
		
		return $result->getStatus();
	}

}

