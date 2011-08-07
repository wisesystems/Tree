<?php

namespace Tree\Orm;

use \Tree\Exception\EntityException;

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

	const STATE_BLANK = 0;
	const STATE_HYDRATED = 1;
	const STATE_DIRTY = 2;

	protected $columnList = array();

	protected $primaryKey = array();

	protected $tableName;

	private $currentValues = array();

	private $originalValues = array();

	private $state;

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
		// todo: throw exception if not hydrated

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
	 * @param  \Tree\Database\Connection $connection 
	 */
	public function setDatabaseConnection($connection)
	{
	}

}

