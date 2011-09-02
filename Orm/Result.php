<?php

namespace Tree\Orm;

use \Iterator;
use \Tree\Database\Result as DatabaseResult;

/**
 * Result 
 *
 * Generates and manages sets of Entity objects based on data from database
 * query result sets
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Orm
 * @uses       \Iterator
 * @uses       \Tree\Database\Query_Result
 * @version    0.00
 */
class Result implements Iterator {

	/**
	 * The database result set containing the data to be turned into entities
	 * 
	 * @access private
	 * @var    \Tree\Database\Result
	 */
	private $databaseResult;
	
	/**
	 * A blank copy of the base entity that was originally searched for
	 * 
	 * @access private
	 * @var    \Tree\Orm\Entity
	 */
	private $baseEntity;

	/**
	 * @access public
	 * @param  \Tree\Database\Result $databaseResult 
	 * @param  \Tree\Orm\Entity      $baseEntity 
	 */
	public function __construct($databaseResult, $baseEntity)
	{
		$this->databaseResult = $databaseResult;
		$this->baseEntity     = $baseEntity;
	}

	/**
	 * Iterator: Generates and returns an Entity subclass object representing the
	 * data at the database result set's current internal pointer
	 * 
	 * @access public
	 * @return \Tree\Orm\Entity
	 */
	public function current()
	{
		$row    = $this->databaseResult->current();
		$entity = clone $this->baseEntity;

		$tableName  = $entity->getEntityTableName();

		$entityData = $this->getValuesByKeyPrefix($row, "{$tableName}:");

		$entity->hydrateEntity($entityData);

		return $entity;
	}

	/**
	 * Iterator: Increments the internal pointer of the result set
	 * 
	 * @access public
	 */
	public function next()
	{
		$this->databaseResult->next();
	}

	/**
	 * Iterator: Returns the current value of the result set's internal pointer
	 * 
	 * @access public
	 * @return integer
	 */
	public function key()
	{
		$this->databaseResult->key();
	}
	
	/**
	 * Iterator: Resets the value of the result set's internal pointer to zero
	 * 
	 * @access public
	 */
	public function rewind()
	{
		$this->databaseResult->rewind();
	}

	/**
	 * Iterator: Indicates whether the current value of the result set's internal
	 * pointer corresponds to the number of a row in the result set
	 * 
	 * @access public
	 * @return boolean
	 */
	public function valid()
	{
		return $this->databaseResult->valid();
	}

	/**
	 * Finds and returns all key-value pairs in the given array whose key name
	 * matches the given prefix string
	 * 
	 * For example, given the following input array
	 *
	 * {
	 *     'article:id'    : '1',
	 *     'article:title' : 'Example Article',
	 *     'author:name'   : 'Rudyard Kipling'
	 * }
	 *
	 * And given a key prefix of 'article:', the output would be the array:
	 *
	 * {
	 *     'id'    : '1',
	 *     'title' : 'Example Article
	 * }
	 * 
	 * @access private
	 * @param  array $input 
	 * @param  string $keyPrefix 
	 * @return array
	 */
	private function getValuesByKeyPrefix($input, $keyPrefix)
	{
		$output = array();

		foreach ($input as $key => $value) {

			if (strpos($key, $keyPrefix) !== 0) {
				continue;
			}

			$prefixLength = strlen($keyPrefix);
			$keySuffix = substr($key, $prefixLength);

			$output[$keySuffix] = $value;

		}

		return $output;
	}

}

