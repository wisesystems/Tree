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

	const STATE_BLANK = 0;
	const STATE_HYDRATED = 1;
	const STATE_DIRTY = 2;

	protected $columnList = array();

	private $currentValues = array();

	private $originalValues = array();

	private $state;

	public function __get($attribute)
	{
		if (!in_array($attribute, $this->columnList)) {
			// throw exception
		}

		if (!isset($this->currentValues[$attribute])) {
			return null;
		}
		
		return $this->currentValues[$attribute];
	}

	public function __set($name, $value)
	{
		if (!in_array($name, $this->columnList)) {
			// throw exception
		}

		if (isset($this->originalValues[$name]) && $this->originalValues[$name] !== $value) {
			$this->state |= self::STATE_DIRTY;
		}

		$this->currentValues[$name] = $value;
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

