<?php

namespace Tree\Test;

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../Exception/EntityException.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

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
	 *
	 * @covers \Tree\Orm\Entity::__get
	 * @covers \Tree\Orm\Entity::__set
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
	 * Verifies that hydrateEntity correctly populates an entity with data from 
	 * a database row
	 *
	 * @covers \Tree\Orm\Entity::hydrateEntity
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
	 *
	 * @covers \Tree\Orm\Entity::revertEntity
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

}

