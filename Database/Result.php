<?php

namespace Tree\Database;

use \ArrayAccess;
use \Iterator;
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
 * @uses       \Iterator
 * @version    0.00
 */
abstract class Result implements Iterator {

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
	 * Iterator: Returns an associative array of the values in the row at the
	 * current position of the result's internal pointer
	 * 
	 * @access public
	 * @return array
	 */
	public function current()
	{
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
		$this->vendorRewind();
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
		return $this->vendorValid();
		if ($this->key() >= 0 && $this->key() < $this->count()) {
			return true;
		} else {
			return false;
		}
	}

	abstract protected function vendorCurrent();

	abstract protected function vendorKey();

	abstract protected function vendorNext();

	abstract protected function vendorRewind();

	abstract protected function vendorValid();

	abstract protected function vendorStatus();

}

