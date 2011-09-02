<?php

namespace Tree\Test;

require_once '../Orm/Result.php';
require_once 'Fake/Entity.php';
require_once 'Fake/ResultForResultTest.php';

use \Tree\Orm\Result;
use \PHPUnit_Framework_TestCase;

/**
 * ResultTest 
 *
 * Verifies basic non-database functionality of a Result of a Search for an
 * Entity
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Orm\Result
 * @version    0.00
 */
class ResultTest extends PHPUnit_Framework_TestCase {

	private $result;

	public function setUp()
	{
		$databaseResult = new Fake_ResultForResultTest;
		$baseEntity     = new Fake_Entity;

		$this->result = new Result($databaseResult, $baseEntity);
	}

	/**
	 * Verifies that Result returns instances of the Entity subclass that was
	 * originally searched for
	 */
	public function testCurrentReturnsEntity()
	{
		$entity = $this->result->current();
		$this->assertTrue($entity instanceof Fake_Entity);
	}

	/**
	 * Verifies that Result returns a different entity if told to move its
	 * internal pointer forward
	 */
	public function testNextIncrementsIteratorIndex()
	{
		$entityOne = $this->result->current();
		$this->result->next();
		$entityTwo = $this->result->current();

		$this->assertNotEquals($entityOne, $entityTwo);
	}

	/**
	 * Verifies that the entities returned by Result have been hydrated with the
	 * result data itself
	 */
	public function testReturnsCorrectlyHydratedEntities()
	{
		$entity = $this->result->current();

		$this->assertEquals('1', $entity->id);
		$this->assertEquals('First Article', $entity->title);
		$this->assertEquals('All Work And No Play Makes Jack A Dull Boy', $entity->body);
	}

}


