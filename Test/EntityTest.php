<?php

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Orm/Entity.php';

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
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Orm\Entity
 * @version    0.00
 */
class EntityTest extends PHPUnit_Framework_TestCase {

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

