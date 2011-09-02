<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';
require_once 'Fake/EntityParent.php';
require_once 'Fake/EntityChild.php';
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

	public function testGetRelationshipByName()
	{
		$expected = array(
			'name'        => 'attributes',
			'cardinality' => Entity::RELATIONSHIP_ONE_TO_MANY,
			'class'       => '\Tree\Test\Fake_EntityChild',
		);

		$actual = $this->parent->getRelationshipByName('attributes');

		$this->assertEquals($expected, $actual);
	}

}

