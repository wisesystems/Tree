<?php

namespace Tree\Test\Traits;

require_once 'BitFieldTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the Traits test suite
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @package    Tree
 * @subpackage Test
 * @license    GPLv2.0
 * @version    0.00
 */
class TestSuite {

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

		$suite->addTestSuite('\Tree\Test\Traits\BitFieldTest');

		return $suite;
	}

}




