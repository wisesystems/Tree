<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Insert.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Insert;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryInsertTest 
 *
 * Tests generation of SQL INSERT queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Database\Query_Insert
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class QueryInsertTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	public function testBasicInsertQuery()
	{
		$insert = new Query_Insert($this->db);

		$insert->into('sometable');
		$insert->set('id', 1);

		$sql = $insert->getSql();

		$this->assertEquals("INSERT INTO `sometable` (`id`) VALUES (1)", $sql);
	}

}

