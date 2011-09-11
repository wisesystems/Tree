<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Select.php';
require_once '../Database/Query/Join.php';
require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once 'Fake/EntityParent.php';
require_once 'Fake/EntityChild.php';
require_once 'Fake/Search.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query_Select;
use \Tree\Orm\Entity;
use \Tree\Orm\Search;
use \PHPUnit_Framework_TestCase;

/**
 * SearchRelatedSingleEntityTest 
 *
 * Verifies basic non-database functionality of a Search for an entity with
 * relationships
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Orm\Search
 * @version    0.00
 */
class SearchRelatedEntityTest extends PHPUnit_Framework_TestCase {

	/**
	 * Verifies that Search generates a basic SELECT query correctly
	 * 
	 * @covers \Tree\Orm\Search::__construct
	 * @covers \Tree\Orm\Search::getSql
	 */
	public function testGeneratesCorrectSqlWithoutRelationship()
	{
		$db = new Fake_Connection;
		$search = new Search($db, '\Tree\Test\Fake_EntityChild');

		$expected  = "SELECT `article_attribute`.`article_id` AS ";
		$expected .= "`article_attribute:article_id`, `article_attribute`.";
		$expected .= "`attribute_name` AS `article_attribute:attribute_name`, ";
		$expected .= "`article_attribute`.`attribute_value` AS ";
		$expected .= "`article_attribute:attribute_value`\n";
		$expected .= "FROM `article_attribute` `article_attribute`\n";

		$actual = $search->getSql();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that Search correctly generates a SELECT query if relationships
	 * are involved
	 * 
	 * @covers \Tree\Orm\Search::getSql
	 * @covers \Tree\Orm\Search::addJoinForRelationship
	 */
	public function testGeneratesCorrectSqlWithRelationship()
	{
		$db = new Fake_Connection;
		$search = new Search($db, '\Tree\Test\Fake_EntityChild');
		$search->withRelationship('article');

		$expected  = "SELECT `article_attribute`.`article_id` AS ";
		$expected .= "`article_attribute:article_id`, `article_attribute`.";
		$expected .= "`attribute_name` AS `article_attribute:attribute_name`, ";
		$expected .= "`article_attribute`.`attribute_value` AS ";
		$expected .= "`article_attribute:attribute_value`, `article`.`article_id` AS ";
		$expected .= "`article:article_id`, `article`.`article_title` AS `article:article_title`, ";
		$expected .= "`article`.`article_body` AS `article:article_body`\n";
		$expected .= "FROM `article_attribute` `article_attribute`\n";
		$expected .= "LEFT JOIN `article` `article`\n";
		$expected .= "ON `article_attribute`.`article_id` = `article`.`article_id`\n";

		$actual = $search->getSql();

		$this->assertEquals($expected, $actual);
	}
}


