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
	 * is made to get or set an attribute that doesn't actually exist
	 */
	public function testThrowsNoSuchAttributeExceptionIfBadAttribute()
	{
		$code = null;
		try {
			$this->entity->asdfgh = 'zxcvbn';
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::NO_SUCH_ATTRIBUTE, $code);

		$code = null;
		try {
			$abc = $this->entity->asdfgh;
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::NO_SUCH_ATTRIBUTE, $code);
	}

	/**
	 * Verifies that Entity throws an exception if an attempt is made to save an
	 * entity that doesn't have a table name set, rather than just sending a junk
	 * query to the database
	 */
	public function testThrowsExceptionIfTableNameMissing()
	{
		$connection = new Fake_Connection;
		$entity     = new Fake_BrokenNoTableNameEntity;

		$entity->setDatabase($connection);
		
		$code = null;

		try {
			$entity->commitEntity();
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::NO_TABLE_NAME_SET, $code);
	}
	
	public function testThrowsExceptionIfRevertingUnhydratedEntity()
	{
		$code = null;

		try {
			$this->entity->revertEntity();
		} catch (EntityException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(EntityException::REVERTING_UNHYDRATED_ENTITY, $code);
	}

}

