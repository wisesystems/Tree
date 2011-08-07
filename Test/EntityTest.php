<?php

namespace Tree\Test;

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../Exception/EntityException.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

use \Tree\Exception\EntityException;
use \Tree\Orm\Entity;
use \PHPUnit_Framework_TestCase;

/**
 * EntityTest 
 *
 * Verifies that Entity subclasses get populated and handled correctly by
 * Entity itself
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
class EntityTest extends PHPUnit_Framework_TestCase {

	/**
	 * Verifies that the __get and __set methods actually save entity attributes
	 */
	public function testMagicGetAndMagicSet()
	{
		$entityId    = 1;
		$entityTitle = 'GOTO considered harmful';
		$entityBody  = 'goto sucks';

		$entity        = new Fake_Entity;
		$entity->id    = $entityId;
		$entity->title = $entityTitle;
		$entity->body  = $entityBody;

		$this->assertEquals($entityId, $entity->id);
		$this->assertEquals($entityTitle, $entity->title);
		$this->assertEquals($entityBody, $entity->body);
	}

	/**
	 * Verifies that Entity throws the right kind of EntityException if an attempt
	 * is made to get or set an attribute that doesn't actually exist
	 */
	public function testNoSuchAttributeException()
	{
		$entity = new Fake_Entity;

		$code = null;
		try {
			$entity->asdfgh = 'zxcvbn';
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::NO_SUCH_ATTRIBUTE, $code);

		$code = null;
		try {
			$abc = $entity->asdfgh;
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::NO_SUCH_ATTRIBUTE, $code);

	}

	/**
	 * Verifies that hydrateEntity correctly populates an entity with data from 
	 * a database row
	 */
	public function testHydrateEntityPopulatesEntity()
	{
		$entityId    = 1;
		$entityTitle = 'GOTO considered harmful';
		$entityBody  = 'goto sucks';

		$databaseRow = array(
			'id'    => $entityId,
			'title' => $entityTitle,
			'body'  => $entityBody,
		);

		$entity = new Fake_Entity;
		$entity->hydrateEntity($databaseRow);

		$this->assertEquals($entityId, $entity->id);
		$this->assertEquals($entityTitle, $entity->title);
		$this->assertEquals($entityBody, $entity->body);
	}

	/**
	 * Verifies that revertEntity undoes any changes to an entity's data that were
	 * made after the entity was hydrated with data from the database
	 */
	public function testRevertEntityRevertsChanges()
	{
		$entityId    = 1;
		$entityTitle = 'GOTO considered harmful';
		$entityBody  = 'goto sucks';

		$databaseRow = array(
			'id'    => $entityId,
			'title' => $entityTitle,
			'body'  => $entityBody,
		);

		$entity = new Fake_Entity;
		$entity->hydrateEntity($databaseRow);

		$entity->id    = 2;
		$entity->title = 'asdfghjkl';
		$entity->body  = 'qwertyuiop';

		$entity->revertEntity();

		$this->assertEquals($entityId, $entity->id);
		$this->assertEquals($entityTitle, $entity->title);
		$this->assertEquals($entityBody, $entity->body);
	}

	/**
	 * Verifies that calling addState sets Entity to have the given state
	 */
	public function testAddStateAddsState()
	{
		$entity = new Fake_Entity;
		$entity->addState(Entity::STATE_HYDRATED);

		$this->assertTrue($entity->hasState(Entity::STATE_HYDRATED));
		$this->assertFalse($entity->hasState(Entity::STATE_NONE));
		$this->assertFalse($entity->hasState(Entity::STATE_DIRTY));
	}

	/**
	 * Verifies that calling removeState sets Entity to not have the given state
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

}

