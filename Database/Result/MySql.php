<?php

namespace Tree\Database;

use \mysqli_result;
use \Countable;
use \SeekableIterator;
use \Tree\Exception\DatabaseException;

/**
 * Result_MySql 
 *
 * Models results of queries sent using Connection_MySql
 *
 * This class implements Countable and SeekableIterator of its own volition
 * rather than as a requirement mandated by Result. This is because other PHP
 * database extensions, notably PDO, don't provide as much functionality in
 * their representations of result sets. PDOStatement result sets can't
 * reliably be counted or seeked, whereas mysqli ones can, so despite the risk
 * of introducing incompatibilities, the functionality is at least available
 * here.
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \mysqli_result
 * @uses       \Countable
 * @uses       \SeekableIterator
 * @uses       \Tree\Database\Result
 * @uses       \Tree\Exception\DatabaseException
 * @version    0.00
 */
class Result_MySql extends Result implements Countable, SeekableIterator {
	
	/**
	 * Internal pointer indicating the row of the result set that is currently
	 * being pointed to (the one that will be returned by the next call to
	 * current)
	 * 
	 * @access private
	 * @var    integer
	 */
	private $iteratorIndex = 0;

	/**
	 * The mysql_result object representing the query result
	 * 
	 * @access private
	 * @var    \mysqli_result
	 */
	private $result;

	/**
	 * @access public
	 * @param  \mysqli_result $result 
	 */
	public function __construct($result)
	{
		$this->result = $result;
	}

	/**
	 * Countable: Returns the total number of rows in the result set
	 * 
	 * @access public
	 * @return integer
	 */
	public function count()
	{
		return $this->result->num_rows;
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
		$this->result->data_seek($offset);
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
	 * Indicates whether the result set's internal pointer has a valid value, i.e.
	 * one that corresponds to a row in the result set 
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function vendorValid()
	{
		if ($this->iteratorIndex < $this->count()) {
			return true;
		} else {
			return false;
		}
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

}

