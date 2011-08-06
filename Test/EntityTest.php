<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once '../Database/Connection/Pdo.php';
require_once '../Orm/Entity.php';
require_once 'Fake/Entity.php';

use \Tree\Database\Connection_Pdo;
use \Tree\Orm\Entity;
use \PHPUnit_Framework_TestCase;
use \PHPUnit_Extensions_Database_TestCase;

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
			'dsn' => 'sqlite:memory',
		));
		$this->db->openConnection();

		$pdo = $this->db->getPdo();

		return $this->createDefaultDBConnection($pdo, ':memory:');
	}

	public function getDataSet()
	{
		return $this->createFlatXMLDataSet('../data.xml');		
	}

	public function testSaveEntityInsertsNewRow()
	{

	}

	public function testSaveEntityUpdatesExistingRow()
	{
	}

	public function testDeleteEntityDeletesRow()
	{
	}

	public function testRevertEntityRevertsChanges()
	{
	}

	public function testHydrateEntityPopulatesEntity()
	{
	}

}

