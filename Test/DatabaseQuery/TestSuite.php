<?php

namespace Tree\Test\DatabaseQuery;

require_once 'QueryDeleteTest.php';
require_once 'QueryInsertTest.php';
require_once 'QueryJoinTest.php';
require_once 'QueryPredicateTest.php';
require_once 'QueryReplaceTest.php';
require_once 'QuerySelectTest.php';
require_once 'QueryUpdateTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the DatabaseQuery test suite
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

		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryDeleteTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryInsertTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryJoinTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryPredicateTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryReplaceTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QuerySelectTest');
		$suite->addTestSuite('\Tree\Test\DatabaseQuery\QueryUpdateTest');

		return $suite;
	}

}


