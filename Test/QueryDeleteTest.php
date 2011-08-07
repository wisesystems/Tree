<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
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

	public function testBasicDeleteQuery()
	{
		$delete = new Query_Delete($this->db);
		$delete->from('sometable');

		$expected = "DELETE FROM `sometable`\n";
		$actual   = $delete->getSql();

		$this->assertEquals($expected, $actual);
	}

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

