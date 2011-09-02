<?php

namespace Tree\Test;

require_once '../Database/Result.php';

use \Tree\Database\Result;

/**
 * Fake_ResultForResultTest 
 *
 * A fake database result for the purpose of returning a fixed set of data
 * mimicking a real database result set containing two known rows
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Result
 * @version    0.00
 */
class Fake_ResultForResultTest extends Result {

	private $rows = array(
		array(
			'article:id'    => '1',
			'article:title' => 'First Article',
			'article:body'  => 'All Work And No Play Makes Jack A Dull Boy',
		),
		array(
			'article:id'    => '2',
			'article:title' => 'Second Article',
			'article:body'  => 'No TV And No Beer Make Homer Go Crazy',
		),
	);

	private $iteratorIndex = 0;

	protected function vendorCurrent()
	{
		return $this->rows[$this->iteratorIndex];
	}

	protected function vendorKey()
	{
		return $this->iteratorIndex;
	}

	protected function vendorNext()
	{
		$this->iteratorIndex++;
	}

	protected function vendorRewind()
	{
		$this->iteratorIndex = 0;
	}

	protected function vendorStatus()
	{
		return true;
	}
	
	protected function vendorValid()
	{
		if ($this->iteratorIndex >= 0 && $this->iteratorIndex < count($this->rows)) {
			return true;
		} else {
			return false;
		}
	}

}

