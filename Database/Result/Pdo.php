<?php

namespace Tree\Database;

use \PDO;
use \PDOStatement;

/**
 * Result_Pdo 
 *
 * Models results of PDO database queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Database
 * @uses       \PDO
 * @uses       \PDOStatement
 * @uses       \Tree\Database\Result
 * @version    0.00
 */
class Result_Pdo extends Result {

	/**
	 * The PDOStatement object representing the result of the query
	 * 
	 * @access private
	 * @var    \PDOStatement
	 */
	private $statement;

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
	 * The size of the result set
	 *
	 * This value is only set once it is known. It is only known once we have
	 * iterated through the entire result set and reached the end. At that point
	 * we store this value so as to have an accurate measure of the size of the
	 * result set.
	 * 
	 * @access private
	 * @var    integer
	 */
	private $resultSize = null;

	/**
	 * The next row of the result set to be returned by current
	 *
	 * Unfortunately, PDOStatement result sets can't be counted reliably. As a
	 * result, it's impossible to know when calling fetch() whether or not it'll
	 * return a row. In order to be able to return a meaningful boolean value for
	 * valid() in this class, we have to constantly pre-fetch the next row of the
	 * result set and store it here.
	 * 
	 * @access private
	 * @var    array
	 */
	private $nextRow = null;

	/**
	 * Stores the PDOStatement representing the result
	 * 
	 * @access public
	 * @param  PDOStatement $statement 
	 */
	public function __construct($statement)
	{
		$this->statement = $statement;

		if ($statement instanceof PDOStatement) {
			$this->nextRow = $statement->fetch();
		} else {
			$this->resultSize = 0;
		}
	}

	/**
	 * Returns the current row of the result set
	 *
	 * @access protected
	 * @return array
	 */
	protected function vendorCurrent()
	{
		$currentRow = $this->nextRow;

		$this->nextRow = $this->statement->fetch();

		if ($this->nextRow === false) {
			// because PDOStatement results can't reliably be counted, the only way to
			// be able to return something sensible in valid() is by fetching the next
			// row in the result set to see if it's there
			$this->resultSize = $this->iteratorIndex;
		}

		return $currentRow;
	}

	/**
	 * Returns the position of the current row in the result set
	 * 
	 * @access protected
	 * @return integer
	 */
	protected function vendorKey()
	{
		return $this->iteratorIndex;
	}

	/**
	 * Moves the pointer forward by one to point at the next row of the result set
	 * 
	 * @access protected
	 */
	protected function vendorNext()
	{
		$this->iteratorIndex++;
	}

	/**
	 * Resets the pointer to point at the first row of the result set
	 * 
	 * @access protected
	 */
	protected function vendorRewind()
	{
		$this->iteratorIndex = 0;
	}

	/**
	 * Indicates whether the current value of the row pointer is valid
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorValid()
	{
		if ($this->resultSize !== null && $this->iteratorIndex >= $this->resultSize) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Indicates whether the query affected any rows
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function vendorStatus()
	{
		if ($this->statement instanceof PDOStatement && $this->statement->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

}

