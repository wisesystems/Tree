<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Delete.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Delete;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryDeleteTest 
 *
 * Tests generation of SQL DELETE queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Database\Query_Delete
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class QueryDeleteTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	/**
	 * Verifies that a basic delete query is generated correctly 
	 * 
	 * @covers \Tree\Database\Query_Delete::__construct
	 * @covers \Tree\Database\Query_Delete::from
	 * @covers \Tree\Database\Query_Delete::getSql
	 */
	public function testBasicDeleteQuery()
	{
		$delete = new Query_Delete($this->db);
		$delete->from('sometable');

		$expected = "DELETE FROM `sometable`\n";
		$actual   = $delete->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that a delete query with a WHERE clause is generated correctly
	 * 
	 * @covers \Tree\Database\Query_Delete::__construct
	 * @covers \Tree\Database\Query_Delete::from
	 * @covers \Tree\Database\Query_Delete::where
	 * @covers \Tree\Database\Query_Delete::getSql
	 * @covers \Tree\Database\Query_Delete::getWhereExpression
	 */
	public function testDeleteWhere()
	{
		$delete = new Query_Delete($this->db);
		$delete->from('sometable');
		$delete->where('id = %d', 1);

		$expected  = "DELETE FROM `sometable`\n";
		$expected .= "WHERE id = 1\n";

		$actual = $delete->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that a delete query with a WHERE clause is generated correctly
	 * 
	 * @covers \Tree\Database\Query_Delete::__construct
	 * @covers \Tree\Database\Query_Delete::from
	 * @covers \Tree\Database\Query_Delete::where
	 * @covers \Tree\Database\Query_Delete::limit
	 * @covers \Tree\Database\Query_Delete::getSql
	 * @covers \Tree\Database\Query_Delete::getWhereExpression
	 * @covers \Tree\Database\Query_Delete::getLimitExpression
	 */
	public function testDeleteWhereLimit()
	{
		$delete = new Query_Delete($this->db);
		$delete->from('sometable');
		$delete->where('id = %d', 1);
		$delete->limit(1);

		$expected  = "DELETE FROM `sometable`\n";
		$expected .= "WHERE id = 1\n";
		$expected .= "LIMIT 1\n";

		$actual = $delete->getSql();

		$this->assertEquals($expected, $actual);
	}

}

