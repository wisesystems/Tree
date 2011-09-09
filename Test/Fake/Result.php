<?php

namespace Tree\Test;

use \Tree\Database\Result;

/**
 * Fake_Result 
 *
 * A fake database result to get around the lack of proper connections when
 * testing code issues database queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Result
 * @version    0.00
 */
class Fake_Result extends Result {

	protected function vendorCurrent()
	{
		return array();
	}

	protected function vendorKey()
	{
	}

	protected function vendorNext()
	{
	}

	protected function vendorRewind()
	{
	}

	protected function vendorValid()
	{
	}

	protected function vendorStatus()
	{
	}

}

