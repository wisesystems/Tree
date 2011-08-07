<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Replace.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Replace;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryReplaceTest 
 *
 * Tests generation of SQL REPLACE queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Database\Query_Replace
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class QueryReplaceTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	public function testBasicReplaceQuery()
	{
		$replace = new Query_Replace($this->db);
		$replace->into('sometable');
		$replace->set('id', 1);
		$replace->set('title', 'example');
		$replace->set('body', 'dfdfdf');

		$expected  = "REPLACE INTO `sometable`\n";
		$expected .= "SET `id` = 1,\n";
		$expected .= "`title` = 'example',\n";
		$expected .= "`body` = 'dfdfdf'\n";

		$actual = $replace->getSql();

		$this->assertEquals($expected, $actual);

	}

}

