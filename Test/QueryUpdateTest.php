<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Update.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Predicate;
use \Tree\Database\Query_Update;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryUpdateTest 
 *
 * Tests generation of SQL UPDATE queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Database\Query_Update
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class QueryUpdateTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	/**
	 * Verifis that a basic UPDATE query is generated correctly
	 * 
	 * @covers \Tree\Database\Query_Update::__construct
	 * @covers \Tree\Database\Query_Update::table
	 * @covers \Tree\Database\Query_Update::set
	 * @covers \Tree\Database\Query_Update::getSql
	 */
	public function testBasicUpdateQuery()
	{
		$update = new Query_Update($this->db);
		$update->table('sometable');
		$update->set('id', 1);

		$expected  = "UPDATE `sometable`\n";
		$expected .= "SET `id` = 1\n";

		$actual = $update->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that a query with a WHERE expression is generated correctly
	 * 
	 * @covers \Tree\Database\Query_Update::__construct
	 * @covers \Tree\Database\Query_Update::table
	 * @covers \Tree\Database\Query_Update::set
	 * @covers \Tree\Database\Query_Update::where
	 * @covers \Tree\Database\Query_Update::getSql
	 * @covers \Tree\Database\Query_Update::getWhereExpression
	 */
	public function testWhereUpdateQuery()
	{
		$update = new Query_Update($this->db);
		$update->table('sometable');
		$update->set('id', 1);
		$update->where('`id` = %d', 2);

		$expected  = "UPDATE `sometable`\n";
		$expected .= "SET `id` = 1\n";
		$expected .= "WHERE `id` = 2\n";

		$actual = $update->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that a query with a WHERE expression is generated correctly
	 * 
	 * @covers \Tree\Database\Query_Update::__construct
	 * @covers \Tree\Database\Query_Update::table
	 * @covers \Tree\Database\Query_Update::set
	 * @covers \Tree\Database\Query_Update::limit
	 * @covers \Tree\Database\Query_Update::getSql
	 * @covers \Tree\Database\Query_Update::getLimitExpression
	 */
	public function testLimitExpression()
	{
		$update = new Query_Update($this->db);
		$update->table('sometable');
		$update->set('id', 1);
		$update->limit(1);

		$expected  = "UPDATE `sometable`\n";
		$expected .= "SET `id` = 1\n";
		$expected .= "LIMIT 1\n";

		$actual = $update->getSql();

		$this->assertEquals($expected, $actual);
	}


}

