<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Predicate;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryPredicateTest 
 *
 * Verifies that Query_Predicate correctly generates logical statements for
 * use in SQL queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class QueryPredicateTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	/**
	 * Verifies that a simple list of parameter-less conditions is generated
	 * correctly
	 */
	public function testBasicPredicateList()
	{
		$predicate = new Query_Predicate($this->db);

		$predicate->andPredicate('a > 10', array());
		$predicate->orPredicate('a < 4', array());

		$expression = $predicate->getSql();

		$this->assertEquals("a > 10\nOR a < 4\n", $expression);
	}

	/**
	 * Verifies that parameters are handled and escaped properly
	 */
	public function testStatementParameters()
	{
		$predicate = new Query_Predicate($this->db);

		$predicate->andPredicate('a > %d', array(10));
		$predicate->orPredicate('name = %s', array("fred's name"));

		$expression = $predicate->getSql();

		$this->assertEquals("a > 10\nOR name = 'fred\'s name'\n", $expression);
	}

}

