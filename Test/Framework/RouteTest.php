<?php

namespace Tree\Test\Framework;

require_once '../Framework/Route.php';
require_once 'Fake/Action.php';

use \Tree\Framework\Route;
use \Tree\Test\Fake_Action;
use \PHPUnit_Framework_TestCase;

/**
 * RouteTest 
 *
 * Tests the mapping of requests to actions by Routes
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \ReflectionClass
 * @uses       \Tree\Framework\Router
 * @version    0.00
 */
class RouteTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers \Tree\Framework\Route::matchesPath 
	 * @test
	 */
	public function acceptsMatchingPath()
	{
		$route = new Route('/example/{id}', 'Action');

		$this->assertTrue($route->matchesPath('/example/1234'));
	}

	/**
	 * @covers \Tree\Framework\Route::matchesPath 
	 * @test
	 */
	public function rejectsNonMatchingPath()
	{
		$route = new Route('/example/{id}', 'Action');

		$this->assertFalse($route->matchesPath('/test/1234'));
	}

	/**
	 * @covers \Tree\Framework\Route::matchesPath 
	 * @test
	 */
	public function acceptsMatchingPathWithPattern()
	{
		$route = new Route('/example/{id}', 'Action');
		$route->setParameterPattern('id', '\d{1,4}');

		$this->assertTrue($route->matchesPath('/example/1234'));
	}

	/**
	 * @covers \Tree\Framework\Route::matchesPath 
	 * @test
	 */
	public function rejectsNonMatchingPathWithPattern()
	{
		$route = new Route('/example/{id}', 'Action');
		$route->setParameterPattern('id', '\d{1,4}');

		$this->assertFalse($route->matchesPath('/example/12345'));
	}

	/**
	 * @covers \Tree\Framework\Route::getAction
	 * @test
	 */
	public function returnsConfiguredActionIfPathMatches()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');

		$action = $route->getAction('/example/12345');

		$expected = 12345;
		$actual   = $action->getParameter('id');

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @covers \Tree\Framework\Route::getAction
	 * @test
	 */
	public function returnsNullActionIfPathDoesntMatch()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');

		$action = $route->getAction('/test/something/12345');

		$this->assertNull($action);
	}

	/**
	 * @covers \Tree\Framework\Route::getPath
	 * @test
	 */
	public function returnsPathIfActionMatches()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');

		$expected = '/example/1234';
		$actual   = $route->getPath('\Tree\Test\Fake_Action', array(
			'id' => 1234,
		));

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @covers \Tree\Framework\Route::getPath
	 * @test
	 */
	public function rejectsPathIfActionDoesntMatch()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');

		$path = $route->getPath('\Some_Action', array(
			'user' => 'root',
		));

		$this->assertNull($path);

	}

	/**
	 * @covers \Tree\Framework\Route::getPath
	 * @test
	 */
	public function returnsPathIfActionMatchesWithPattern()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');
		$route->setParameterPattern('id', '\d+');

		$expected = '/example/1234';
		$actual   = $route->getPath('\Tree\Test\Fake_Action', array(
			'id' => 1234,
		));

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @covers \Tree\Framework\Route::getPath
	 * @test
	 */
	public function rejectsPathIfActionDoesntMatchWithPattern()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');
		$route->setParameterPattern('id', '\d+');

		$path = $route->getPath('\Tree\Test\Fake_Action', array(
			'id' => 'notnumbers',
		));

		$this->assertNull($path);

	}


}


