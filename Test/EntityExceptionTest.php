<?php

namespace Tree\Test;

require_once 'PHPUnit/Framework/TestCase.php';
require_once '../Exception/EntityException.php';
require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Insert.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';
require_once 'Fake/BrokenNoTableNameEntity.php';
require_once 'Fake/Connection.php';

use \Tree\Exception\EntityException;
use \Tree\Orm\Entity;
use \PHPUnit_Framework_TestCase;

/**
 * EntityExceptionTest 
 *
 * Verifies that Entity throws exceptions when it should and only when it should
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Exception\EntityException
 * @uses       \Tree\Orm\Entity
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class EntityExceptionTest extends PHPUnit_Framework_TestCase {

	private $entity;

	public function setUp()
	{
		$this->entity = new Fake_Entity;
	}

	/**
	 * Verifies that Entity doesn't throw an exception if an attribute is accessed
	 * that is a valid attribute of that entity
	 */
	public function testDoesntThrowExceptionIfValidAttribute()
	{
		$code = null;

		try {
			$this->entity->id = 1;
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertNull($code);

		$code = null;

		try {
			$test = $this->entity->id;
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertNull($code);
	}

	/**
	 * Verifies that Entity throws the right kind of EntityException if an attempt
	 * is made to set an attribute that doesn't actually exist
	 * 
	 * @expectedException     \Tree\Exception\EntityException
	 * @expectedExceptionCode \Tree\Exception\EntityException::NO_SUCH_ATTRIBUTE
	 */
	public function testThrowsNoSuchAttributeExceptionIfSettingBadAttribute()
	{
		$this->entity->asdfgh = 'zxcvbn';
	}

	/**
	 * Verifies that Entity throws the right kind of EntityException if an attempt
	 * is made to get an attribute that doesn't actually exist
	 * 
	 * @expectedException     \Tree\Exception\EntityException
	 * @expectedExceptionCode \Tree\Exception\EntityException::NO_SUCH_ATTRIBUTE
	 */
	public function testThrowsNoSuchAttributeExceptionIfGettingBadAttribute()
	{
		$abc = $this->entity->asdfgh;
	}

	/**
	 * Verifies that Entity throws an exception if an attempt is made to save an
	 * entity that doesn't have a table name set, rather than just sending a junk
	 * query to the database
	 * 
	 * @expectedException     \Tree\Exception\EntityException
	 * @expectedExceptionCode \Tree\Exception\EntityException::NO_TABLE_NAME_SET
	 */
	public function testThrowsExceptionIfTableNameMissing()
	{
		$connection = new Fake_Connection;
		$entity     = new Fake_BrokenNoTableNameEntity;

		$entity->setDatabase($connection);
		
		$entity->commitEntity();
	}
	
	/**
	 * Verifies that Entity throws an exception if an attempt is made to revert
	 * an entity that has no original state to revert to because it has not been
	 * hydrated with database values
	 * 
	 * @expectedException     \Tree\Exception\EntityException
	 * @expectedExceptionCode \Tree\Exception\EntityException::REVERTING_UNHYDRATED_ENTITY
	 */
	public function testThrowsExceptionIfRevertingUnhydratedEntity()
	{
		$this->entity->revertEntity();
	}

	/**
	 * Verifies that Entity throws an exception if an attempt is made to hydrate
	 * an entity with invalid data
	 * 
	 * @expectedException     \Tree\Exception\EntityException
	 * @expectedExceptionCode \Tree\Exception\EntityException::HYDRATED_WITH_INVALID_DATA
	 */
	public function testThrowsExceptionIfHydratedWithInvalidData()
	{
		$invalidData = array(
			'invalid_column' => 12345,
		);

		$this->entity->hydrateEntity($invalidData);
	}

}

