<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once 'Fake/Entity.php';
require_once 'Fake/Search.php';

use \Tree\Orm\Entity;
use \Tree\Orm\Search;
use \PHPUnit_Framework_TestCase;

/**
 * SearchSingleEntityTest 
 *
 * Verifies basic non-database functionality of a Search for a single Entity
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
class SearchSingleEntityTest extends PHPUnit_Framework_TestCase {

	private $search;

	public function setUp()
	{
		$this->search = new Fake_Search;
	}

	public function testSomething()
	{
		print_r($this->search);
	}

}

