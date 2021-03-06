<?php

namespace Tree\Test\Orm;

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../Exception/EntityException.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Orm\Entity;
use \Tree\Test\Fake_Entity;

/**
 * EntityStateTest 
 *
 * Verifies that Entity's state transitions work correctly
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
class EntityStateTest extends PHPUnit_Framework_TestCase {

	/**
	 * Verifies that calling addState sets Entity to have the given state
	 */
	public function testAddStateAddsState()
	{
		$entity = new Fake_Entity;
		$entity->addState(Entity::STATE_HYDRATED);

		$this->assertTrue($entity->hasState(Entity::STATE_HYDRATED));
		$this->assertFalse($entity->hasState(Entity::STATE_DIRTY));
	}

	/**
	 * Verifies that calling removeState sets Entity to not have the given state
	 *
	 * @covers \Tree\Orm\Entity::addState
	 * @covers \Tree\Orm\Entity::hasState
	 * @covers \Tree\Orm\Entity::removeState
	 */
	public function testRemoveStateRemovesState()
	{
		$entity = new Fake_Entity;
		$entity->addState(Entity::STATE_HYDRATED);
		$entity->addState(Entity::STATE_DIRTY);

		$this->assertTrue($entity->hasState(Entity::STATE_HYDRATED));
		$this->assertTrue($entity->hasState(Entity::STATE_DIRTY));

		$entity->removeState(Entity::STATE_HYDRATED);
		$this->assertFalse($entity->hasState(Entity::STATE_HYDRATED));
		$this->assertTrue($entity->hasState(Entity::STATE_DIRTY));

		$entity->removeState(Entity::STATE_DIRTY);
		$this->assertFalse($entity->hasState(Entity::STATE_HYDRATED));
		$this->assertFalse($entity->hasState(Entity::STATE_DIRTY));
	}

	/**
	 * Verifies that Entity adds and removes STATE_HYDRATED correctly
	 *
	 * @covers \Tree\Orm\Entity::hydrateEntity
	 */
	public function testHydratedState()
	{
		$entity = new Fake_Entity;

		$this->assertFalse($entity->hasState(Entity::STATE_HYDRATED));

		$entity->hydrateEntity(array(
			'id'    => 1,
			'title' => 'test',
			'body'  => 'The Quick Brown Fox',
		));

		$this->assertTrue($entity->hasState(Entity::STATE_HYDRATED));
	}

	/**
	 * Verifies that Entity adds and removes STATE_DIRTY correctly
	 *
	 * @covers \Tree\Orm\Entity::__set
	 */
	public function testDirtyState()
	{
		$entity = new Fake_Entity;
		$this->assertFalse($entity->hasState(Entity::STATE_DIRTY));

		$entity->hydrateEntity(array(
			'id'    => 1,
			'title' => 'test',
			'body'  => 'The Quick Brown Fox',
		));

		$this->assertFalse($entity->hasState(Entity::STATE_DIRTY));

		$entity->title = 'different title';
		$this->assertTrue($entity->hasState(Entity::STATE_DIRTY));
	}

}


