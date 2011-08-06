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

	private $statement;

	private $iteratorIndex = 0;

	private $resultSize = null;

	private $nextRow = null;

	/**
	 * Stores the PDOStatement representing the result
	 * 
	 * @access public
	 * @param  PDOStatement $statement 
	 */
	public function __construct(PDOStatement $statement)
	{
		$this->statement = $statement;

		$this->nextRow = $statement->fetch();
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
		if ($this->statement->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

}

