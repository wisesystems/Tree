<?php

namespace Tree\Test\Orm;

require_once 'EntityDatabaseTest.php';
require_once 'EntityRelationshipTest.php';
require_once 'EntityStateTest.php';
require_once 'EntityTest.php';
require_once 'ResultTest.php';
require_once 'SearchRelatedEntityTest.php';
require_once 'SearchSingleEntityTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the Orm test suite
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
		$suite = new PHPUnit_Framework_TestSuite('Tree');

		$suite->addTestSuite('\Tree\Test\Orm\EntityDatabaseTest');

		return $suite;
	}

}


