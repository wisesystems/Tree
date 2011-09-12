<?php

namespace Tree\Test\Orm;

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Extensions/Database/DataSet/QueryDataSet.php';
require_once '../Database/Connection.php';
require_once '../Database/Connection/Pdo.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Insert.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Update.php';
require_once '../Database/Result.php';
require_once '../Database/Result/Pdo.php';
require_once '../Exception/EntityException.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

use \Tree\Database\Connection_Pdo;
use \Tree\Exception\EntityException;
use \Tree\Orm\Entity;
use \Tree\Test\Fake_Entity;
use \PHPUnit_Framework_TestCase;
use \PHPUnit_Extensions_Database_TestCase;
use \PHPUnit_Extensions_Database_DataSet_QueryDataSet;

/**
 * EntityDatabaseTest 
 *
 * Verifies Entity's interactions with the database
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
class EntityDatabaseTest extends PHPUnit_Extensions_Database_TestCase {

	private $db;

	private $initialState;

	public function setUp()
	{
		$this->db = new Connection_Pdo;
		$this->db->setIniValues(array(
			'dsn' => 'sqlite:Databases/article.db',
		));
		$pdo = $this->db->getPdo();
		$this->db->openConnection();
		parent::setUp();
	}

	public function getConnection()
	{
		$pdo = $this->db->getPdo();
		return $this->createDefaultDBConnection($pdo, 'testdb');
	}

	public function getDataSet()
	{
		$initialState = $this->createFlatXMLDataSet('Data/entity-test-initial-state.xml');		
		return $initialState;
	}

	/**
	 * Verifies that commitEntity saves entity data to a new database row if the
	 * entity does not represent a row that already exists in the database
	 *
	 * @covers \Tree\Orm\Entity::commitEntity
	 * @covers \Tree\Orm\Entity::insertEntity
	 */
	public function testCommitEntityInsertsNewRow()
	{
		$entity = new Fake_Entity;
		$entity->setDatabase($this->db);
		$entity->id    = 2;
		$entity->title = 'this is something new';
		$entity->body  = 'a whole new row right here';
		$entity->commitEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-insert.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('article', 'SELECT * FROM article ORDER BY id');

		$this->assertDataSetsEqual($expected, $actual);
	}

	/**
	 * Verifies that commitEntity saves entity data to an existing database row if
	 * the entity corresponds to one
	 *
	 * @covers \Tree\Orm\Entity::commitEntity
	 * @covers \Tree\Orm\Entity::updateEntity
	 */
	public function testCommitEntityUpdatesExistingRow()
	{
		$entity = new Fake_Entity;
		$entity->setDatabase($this->db);
		$entity->addState(Entity::STATE_HYDRATED);
		$entity->id    = 1;
		$entity->title = 'GOTO considered awesome';
		$entity->body  = 'goto rules!';
		$entity->commitEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-update.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('article');

		$this->assertDataSetsEqual($expected, $actual);
	}

	/**
	 * Verifies that deleteEntity successfully removes an entity's corresponding
	 * database row if one exists
	 *
	 * @covers \Tree\Orm\Entity::deleteEntity
	 */
	public function testDeleteEntityDeletesRow()
	{
		$entity = new Fake_Entity($this->db);
		$entity->id = 1;
		$entity->deleteEntity();

		$expected = $this->createFlatXMLDataSet('Data/entity-test-after-delete.xml');

		$actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
		$actual->addTable('article');

		$this->assertDataSetsEqual($expected, $actual);
	}

}


