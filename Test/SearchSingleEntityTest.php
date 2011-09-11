<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Select.php';
require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once '../Orm/Result.php';
require_once 'Fake/Entity.php';
require_once 'Fake/Search.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query_Select;
use \Tree\Orm\Entity;
use \Tree\Orm\Search;
use \Tree\Orm\Result;
use \PHPUnit_Framework_TestCase;

/**
 * SearchSingleEntityTest 
 *
 * Verifies basic non-database functionality of a Search for a single Entity
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Database\Connection_Pdo
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Orm\Entity
 * @version    0.00
 */
class SearchSingleEntityTest extends PHPUnit_Framework_TestCase {

	private $search;

	public function setUp()
	{
		$db = new Fake_Connection;
		$this->search = new Fake_Search($db);
	}

	/**
	 * Verifies that the SQL generated for the query that is to return entity data
	 * is generated as expected
	 * 
	 * @covers \Tree\Orm\Search::getSql
	 */
	public function testGeneratesCorrectSql()
	{
		$expected  = 'SELECT `article`.`id` AS `article:id`, `article`.`title` AS ';
		$expected .= '`article:title`, `article`.`body` AS `article:body`' . "\n";
		$expected .= "FROM `article` `article`\n";

		$actual = $this->search->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that Search returns a Result object
	 *
	 * @covers \Tree\Orm\Search::getResult
	 */
	public function testReturnsResult()
	{
		$result = $this->search->getResult();

		$this->assertTrue($result instanceof Result);
	}

}

