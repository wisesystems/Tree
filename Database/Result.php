<?php

namespace Tree\Database;

use \ArrayAccess;
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
abstract class Result implements ArrayAccess, Countable, SeekableIterator {

	/**
	 * Converts the Result into a HTML table representation for quick and easy
	 * debugging by simply echoing the object
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		$string = '<table border="1">';

		if (count($this) > 0) {

			$string .= '<tr>';
			foreach ($this->offsetGet(0) as $name => $value) {
				$string .= "<th>{$name}</th>";
			}
			$string .= '</tr>';

		}

		foreach ($this as $row) {

			$string .= '<tr>';
			foreach ($row as $name => $value) {
				$string .= "<td>{$value}</td>";
			}
			$string .= '</tr>';

		}

		$string .= '</table>';

		return $string;
	}

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

	/**
	 * ArrayAccess: Indicates whether the result set contains a row at the given
	 * index position
	 * 
	 * @access public
	 * @param  integer $index 
	 * @return boolean
	 */
	public function offsetExists($index)
	{
		if (!$this->vendorHasResultSet()) {
			return false;
		}

		if ($index >= 0 && $index < count($this)) {
			return true;
		}
	}

	/**
	 * ArrayAccess: Returns the result set row at the given index position
	 * 
	 * @access public
	 * @param  integer $index 
	 * @return array
	 */
	public function offsetGet($index)
	{
		$currentKey = $this->key();

		if ($index == $currentKey) {
			$row = $this->current();
		} else {
			$this->seek($index);
			$row = $this->current();
			$this->seek($currentKey);
		}

		return $row;
	}

	/**
	 * ArrayAccess: Would normally set the element at the given index position
	 * to the given value, but doesn't because these are database query results
	 * which are read-only
	 * 
	 * @access public
	 * @param  integer $index 
	 * @param  mixed $value 
	 */
	public function offsetSet($index, $value)
	{
		// do nothing
		// maybe make this throw an exception?
	}

	/**
	 * ArrayAccess: Would normally remove the element at the given index position,
	 * but doesn't because database query results are supposed to be read-only
	 * 
	 * @access public
	 * @param  integer $index 
	 */
	public function offsetUnset($index)
	{
		// do nothing
		// maybe make this throw an exception?
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

