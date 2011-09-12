<?php

namespace Tree\Test\Exceptions;

require_once '../Database/Connection.php';
require_once '../Database/Query.php';
require_once '../Database/Query/Predicate.php';
require_once '../Database/Query/Select.php';
require_once '../Database/Query/Join.php';
require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';
require_once '../Exception/SearchException.php';
require_once 'Fake/EntityParent.php';
require_once 'Fake/EntityChild.php';
require_once 'Fake/SearchChild.php';
require_once 'Fake/SearchParent.php';
require_once 'Fake/Connection.php';

use \Tree\Database\Query_Select;
use \Tree\Orm\Entity;
use \Tree\Orm\Search;
use \Tree\Exception\SearchException;
use \Tree\Test\Fake_EntityParent;
use \Tree\Test\Fake_EntityChild;
use \Tree\Test\Fake_SearchParent;
use \Tree\Test\Fake_SearchChild;
use \Tree\Test\Fake_Connection;
use \PHPUnit_Framework_TestCase;

/**
 * SearchExceptionTest
 *
 * Verifies that exceptions are thrown as expected by Search
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Orm\Search
 * @version    0.00
 */
class SearchExceptionTest extends PHPUnit_Framework_TestCase {

	private $parentSearch;
	private $childSearch;

	public function setUp()
	{
		$connection = new Fake_Connection;

		$this->parentSearch = new Fake_SearchParent($connection);
		$this->childSearch  = new Fake_SearchChild($connection);
	}

	/**
	 * Verifies that an exception is thrown if a call to withRelationship()
	 * tries to include a non-existent relationship
	 * 
	 * @covers                \Tree\Orm\Search::withRelationship
	 * @expectedException     \Tree\Exception\SearchException
	 * @expectedExceptionCode \Tree\Exception\SearchException::NO_SUCH_RELATIONSHIP
	 */
	public function testThrowsExceptionIfIncludingNonexistentRelationship()
	{
		$this->parentSearch->withRelationship('whatever');
	}

	/**
	 * Make sure that an exception is thrown if a call to withRelationship()
	 * tries to include a relationship that would join to multiple rows and
	 * break the query
	 * 
	 * @covers                \Tree\Orm\Search::withRelationship
	 * @expectedException     \Tree\Exception\SearchException
	 * @expectedExceptionCode \Tree\Exception\SearchException::CANNOT_INCLUDE_RELATIONSHIP
	 */
	public function testThrowsExceptionIfNotJoinableRelationship()
	{
		$this->parentSearch->withRelationship('attributes');
	}

}

