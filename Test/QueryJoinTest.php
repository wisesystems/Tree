<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Join.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query;
use \Tree\Database\Query_Join;
use \PHPUnit_Framework_TestCase;
use \Tree\Test\Fake_Connection;

/**
 * QueryJoinTest 
 *
 * Tests generation of JOIN statements for SQL queries
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Database\Query_Join
 * @uses       \Tree\Database\Connection
 * @version    0.00
 */
class QueryJoinTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->db = new Fake_Connection;
	}

	/**
	 * Verifies that Query_Join produces a simple one-line join expression
	 */
	public function testBasicJoinExpression()
	{
		$join = new Query_Join($this->db);
		$join->setTable('article');
		$join->setType('NATURAL');

		$expected = "NATURAL JOIN `article`\n";
		$actual   = $join->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that Query_Join handles table aliasing properly
	 */
	public function testTableAliasing()
	{
		$join = new Query_Join($this->db);
		$join->setTable('article', 'a');
		$join->setType('LEFT INNER');

		$expected = "LEFT INNER JOIN `article` `a`\n";
		$actual   = $join->getSql();

		$this->assertEquals($expected, $actual);
	}

	public function testBasicOnExpression()
	{
		$join = new Query_Join($this->db);
		$join->setTable('article');
		$join->setType('LEFT');
		$join->on('`category`.article_id = `article`.article_id');

		$expected  = "LEFT JOIN `article`\n";
		$expected .= "ON `category`.article_id = `article`.article_id\n";
		
		$actual = $join->getSql();

		$this->assertEquals($expected, $actual);
	}

	public function testLongOnExpression()
	{
		$join = new Query_Join($this->db);
		$join->setTable('article');
		$join->setType('LEFT');
		$join->on('`category`.article_id = `article`.article_id');
		$join->on("`article`.date >= '2010-01-01'");
		$join->orOn("`article`.date <= '2005-01-01'");

		$expected  = "LEFT JOIN `article`\n";
		$expected .= "ON `category`.article_id = `article`.article_id\n";
		$expected .= "AND `article`.date >= '2010-01-01'\n";
		$expected .= "OR `article`.date <= '2005-01-01'\n";
		
		$actual = $join->getSql();

		$this->assertEquals($expected, $actual);
	}

}

