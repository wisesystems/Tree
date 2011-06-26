<?php

namespace Tree\Database;

use \mysqli_result;
use \Tree\Exception\DatabaseException;

/**
 * Result_MySql 
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \mysqli_result
 * @uses       \Tree\Database\Result
 * @uses       \Tree\Exception\DatabaseException
 * @version    0.00
 */
class Result_MySql extends Result {
	
	private $iteratorIndex = 0;
	private $result;

	/**
	 * @access public
	 * @param  mixed $result 
	 */
	public function __construct($result)
	{
		$this->result = $result;
	}

	/**
	 * Returns the row at the position equal to the current value of the result
	 * set's internal pointer
	 * 
	 * @access protected
	 * @return array
	 */
	protected function vendorCurrent()
	{
		$current = $this->result->fetch_assoc();

		$this->seek($this->key());

		return $current;
	}

	/**
	 * Returns the total number of rows in the result set
	 * 
	 * @access protected
	 * @return integer
	 */
	protected function vendorCount()
	{
		return $this->result->num_rows;
	}

	/**
	 * Returns the current value of the result set's internal pointer
	 * 
	 * @access protected
	 * @return void
	 */
	protected function vendorKey()
	{
		return $this->iteratorIndex;
	}

	/**
	 * Increments the internal pointer of the result set
	 * 
	 * @access protected
	 */
	protected function vendorNext()
	{
		$this->iteratorIndex++;
		$this->seek($this->iteratorIndex);
	}

	/**
	 * Resets the internal pointer of the result set to zero
	 * 
	 * @access protected
	 */
	protected function vendorRewind()
	{
		$this->iteratorIndex = 0;
		$this->seek($this->iteratorIndex);
	}

	/**
	 * Seeks the result set's internal pointer to the given offset
	 * 
	 * @access protected
	 * @param  integer $offset 
	 */
	protected function vendorSeek($offset)
	{
		$this->result->data_seek($offset);
	}

	/**
	 * Returns the status of the result, i.e. whether the query executed
	 * successfully or not
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorStatus()
	{
		if ($this->result instanceof mysqli_result) {
			return true;
		} elseif ($this->result === true) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Indicates whether the result can be treated as a set of rows
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorHasResultSet()
	{
		if ($this->result instanceof mysqli_result) {
			return true;
		} else {
			return false;
		}
	}

}

