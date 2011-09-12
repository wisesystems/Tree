<?php

namespace Tree\Test\Orm;

require_once '../Database/Connection.php';
require_once '../Database/Connection/Pdo.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Select.php';
require_once '../Database/Result.php';
require_once '../Database/Result/Pdo.php';
require_once '../Exception/EntityException.php';
require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once '../Orm/Result.php';
require_once 'Fake/Entity.php';
require_once 'Fake/EntityParent.php';
require_once 'Fake/EntityChild.php';
require_once 'Fake/Result.php';
require_once 'Spy/Connection.php';
require_once 'PHPUnit/Framework/TestCase.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Orm\Entity;
use \Tree\Test\Fake_Entity;
use \Tree\Test\Fake_EntityParent;
use \Tree\Test\Fake_EntityChild;
use \Tree\Test\Fake_Result;
use \Tree\Test\Spy_Connection;

/**
 * EntityRelationshipTest
 *
 * Verifies some of the basic non-database functionality of Entity concering
 * relationships between entities
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class EntityRelationshipTest extends PHPUnit_Framework_TestCase {

	private $parent;
	private $child;

	public function setUp()
	{
		$this->parent    = new Fake_EntityParent;
		$this->child     = new Fake_EntityChild;
		$this->unrelated = new Fake_Entity;
	}

	/**
	 * Verifies that isRelatedTo returns the correct answer 
	 * 
	 * @covers \Tree\Orm\Entity::isRelatedToEntity
	 */
	public function testRelationshipDetection()
	{
		$this->assertTrue($this->parent->isRelatedToEntity($this->child));
		$this->assertTrue($this->child->isRelatedToEntity($this->parent));
		$this->assertFalse($this->parent->isRelatedToEntity($this->unrelated));
		$this->assertFalse($this->child->isRelatedToEntity($this->unrelated));
	}

	/**
	 * Verifies that getEntityRelationship returns the expected data
	 * 
	 * @covers \Tree\Orm\Entity::getEntityRelationship
	 */
	public function testGetEntityRelationship()
	{
		$expected = array(
			'name'        => 'attributes',
			'cardinality' => Entity::RELATIONSHIP_ONE_TO_MANY,
			'foreign-key' => 'article_id',
			'class'       => '\Tree\Test\Fake_EntityChild',
		);

		$actual = $this->parent->getEntityRelationship('attributes');

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that hasEntityRelationship returns the right answer 
	 * 
	 * @covers \Tree\Orm\Entity::hasEntityRelationship
	 */
	public function testHasEntityRelationship()
	{
		$this->assertTrue($this->parent->hasEntityRelationship('attributes'));
		$this->assertTrue($this->child->hasEntityRelationship('article'));

		$this->assertFalse($this->parent->hasEntityRelationship('article'));
		$this->assertFalse($this->child->hasEntityRelationship('attributes'));
	}

	/**
	 * Verifies that when attepting to access an entity's related entity that
	 * isn't yet loaded, a well-formed SQL query will be sent to the database to
	 * retrieve it
	 *
	 * @covers \Tree\Orm\Entity::__get
	 * @covers \Tree\Orm\Entity::getRelatedEntity
	 * @covers \Tree\Orm\Entity::fetchRelatedEntity
	 */
	public function testAutofetchingRelatedEntity()
	{
		$connection = new Spy_Connection;
		$this->parent->setDatabase($connection);

		$this->parent->article_id = 1;
		$this->parent->attributes;

		$this->assertTrue(isset($connection->queries[0]));
		
		$expected = "SELECT `article_attribute`.`article_id` AS `article_attribute:article_id`, "
			. "`article_attribute`.`attribute_name` AS `article_attribute:attribute_name`, "
			. "`article_attribute`.`attribute_value` AS `article_attribute:attribute_value`\n"
			. "FROM `article_attribute` `article_attribute`\n"
			. "WHERE `article_id` = '1'\n";

		$actual = $connection->queries[0];

		$this->assertEquals($expected, $actual);
	}

}

