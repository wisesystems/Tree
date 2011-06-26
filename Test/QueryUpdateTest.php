<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Update.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
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

	public function testBasicUpdateQuery()
	{
		$update = new Query_Update($this->db);

		$update->table('sometable');
		$update->set('id', 1);

		$sql = $update->getSql();

		$this->assertEquals("UPDATE\n\t`sometable`\nSET\n\t`id` = 1\n", $sql);
	}

	public function testWhereUpdateQuery()
	{
		$update = new Query_Update($this->db);

		$update->table('sometable');
		$update->set('id', 1);
		$update->where('id = %d', 2);

		$sql = $update->getSql();

		$this->assertEquals("UPDATE\n\t`sometable`\nSET\n\t`id` = 1\nWHERE\n\tid = 2\n", $sql);
	}

}

