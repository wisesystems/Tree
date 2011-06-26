<?php

namespace Tree\Database;

use \Countable;
use \SeekableIterator;
use \Tree\Exception\DatabaseException;

/**
 * Result 
 * 
 * @abstract
 * @copyright  2011 Henry Smith
 * @author     Henry Smith <henry@henrysmith.org> 
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \Countable
 * @uses       \SeekableIterator
 * @version    0.00
 */
abstract class Result implements Countable, SeekableIterator {

	/**
	 * Returns the status of the result, i.e. whether the query ran successfully
	 * or not
	 * 
	 * @access public
	 * @return boolean
	 */
	public function getStatus()
	{
		return $this->vendorStatus();
	}

	/**
	 * Countable: Returns the total number of rows in the result set
	 * 
	 * @access public
	 * @return integer
	 */
	public function count()
	{
		$this->requireResultSet();
		return $this->vendorCount();
	}

	/**
	 * Iterator: Returns an associative array of the values in the row at the
	 * current position of the result's internal pointer
	 * 
	 * @access public
	 * @return array
	 */
	public function current()
	{
		$this->requireResultSet();
		return $this->vendorCurrent();
	}

	/**
	 * Iterator: Returns the current value of the result set's internal pointer
	 * 
	 * @access public
	 * @return integer
	 */
	public function key()
	{
		$this->requireResultSet();
		return $this->vendorKey();
	}

	/**
	 * Iterator: Increments the result set's internal pointer so that the next
	 * call to current will return the next row
	 * 
	 * @access public
	 */
	public function next()
	{
		$this->requireResultSet();
		$this->vendorNext();
	}

	/**
	 * Iterator: Resets the result set's internal pointer to zero, pointing it at
	 * the first row
	 * 
	 * @access public
	 */
	public function rewind()
	{
		$this->requireResultSet();
		$this->vendorRewind();
	}

	/**
	 * SeekableIterator: Seeks the result set's internal pointer to the given 
	 * offset
	 * 
	 * @access public
	 * @param  integer $offset 
	 */
	public function seek($offset)
	{
		$this->requireResultSet();
		$this->vendorSeek($offset);
	}

	/**
	 * Iterator: Indicates whether the result set's interal pointer has a valid
	 * value
	 * 
	 * @access public
	 * @return boolean
	 */
	public function valid()
	{
		$this->requireResultSet();
		if ($this->key() >= 0 && $this->key() < $this->count()) {
			return true;
		} else {
			return false;
		}
	}

	abstract protected function vendorCount();

	abstract protected function vendorCurrent();

	abstract protected function vendorKey();

	abstract protected function vendorHasResultSet();

	abstract protected function vendorNext();

	abstract protected function vendorRewind();

	abstract protected function vendorSeek($offset);

	abstract protected function vendorStatus();

	private function requireResultSet()
	{
		if (!$this->vendorHasResultSet()) {
			$message = "Result is not a result set";
			throw new DatabaseException($message);
		}
	}

}

