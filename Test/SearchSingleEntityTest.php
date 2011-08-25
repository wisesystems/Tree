<?php

namespace Tree\Test;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Select.php';
require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once 'Fake/Entity.php';
require_once 'Fake/Search.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query_Select;
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
		$db = new Fake_Connection;
		$this->search = new Fake_Search($db);
	}

	public function testSomething()
	{
		var_dump($this->search->getSql());
		var_dump($this->search->getResult());
	}

}

