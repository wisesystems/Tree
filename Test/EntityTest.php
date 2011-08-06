<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Extensions/Database/DataSet/QueryDataSet.php';
require_once '../Database/Connection.php';
require_once '../Database/Connection/Pdo.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

use \Tree\Database\Connection_Pdo;
use \Tree\Orm\Entity;
use \PHPUnit_Framework_TestCase;
use \PHPUnit_Extensions_Database_TestCase;
use \PHPUnit_Extensions_Database_DataSet_QueryDataSet;

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
class EntityTest extends PHPUnit_Extensions_Database_TestCase {

	private $db;

	public function setUp()
	{
	}

	public function getConnection()
	{
		$this->db = new Connection_Pdo;
		$this->db->setIniValues(array(
			'dsn' => 'sqlite:/tmp/memory',
		));
		$this->db->openConnection();

		$pdo = $this->db->getPdo();

		return $this->createDefaultDBConnection($pdo, ':memory:');
	}

	public function getDataSet()
	{
		return $this->createFlatXMLDataSet('Data/entity-test-initial-state.xml');		
	}

	public function testCommitEntityInsertsNewRow()
	{
		$entity = new Fake_Entity($this->db);
		$entity->title = 'this is something new';
		$entity->body  = 'a whole new row right here';
		$entity->commitEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-insert.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('user');

		$this->assertEquals($expected, $actual);
	}

	public function testCommitEntityUpdatesExistingRow()
	{
		$entity = new Fake_Entity($this->db);
		$entity->id    = 1;
		$entity->title = 'GOTO considered awesome';
		$entity->body  = 'goto rules!';
		$entity->commitEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-update.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('user');

		$this->assertEquals($expected, $actual);
	}

	public function testDeleteEntityDeletesRow()
	{
		$entity = new Fake_Entity($this->db);
		$entity->id = 1;
		$entity->deleteEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-delete.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('user');

		$this->assertEquals($expected, $actual);
	}

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

		$this->assertEquals($entityId, $entity->id);
		$this->assertEquals($entityTitle, $entity->title);
		$this->assertEquals($entityBody, $entity->body);
	}

}

