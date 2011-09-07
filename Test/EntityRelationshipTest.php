<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';
require_once 'Fake/EntityParent.php';
require_once 'Fake/EntityChild.php';
require_once 'Spy/Connection.php';
require_once 'PHPUnit/Framework/TestCase.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Orm\Entity;

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

	public function testRelationshipDetection()
	{
		$this->assertTrue($this->parent->isRelatedToEntity($this->child));
		$this->assertTrue($this->child->isRelatedToEntity($this->parent));
		$this->assertFalse($this->parent->isRelatedToEntity($this->unrelated));
		$this->assertFalse($this->child->isRelatedToEntity($this->unrelated));
	}

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
	 */
	public function testAutofetchingRelatedEntity()
	{
		$connection = new Spy_Connection;
		$this->parent->setDatabase($connection);

		$this->parent->article_id = 1;
		$this->parent->attributes;

		$this->assertTrue(isset($connection->queries[0]));
		
		$expected = "SELECT `article_attribute`.`id` AS `attribute:id` "
			. "`article_attribute`.`attribute_name` AS `attribute:attribute_name` "
			. "`article_attribute`.`attribute_value` AS `attribute:attribute_value`\n"
			. "FROM `article_attribute` `attribute`\n"
			. "WHERE `attribute`.`article_id` = '1'\n";

		$actual = $connection->queries[0];

		$this->assertEquals($expected, $actual);
	}

}

