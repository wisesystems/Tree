<?php

namespace Tree\Test\Framework;

require_once '../Framework/Router.php';
require_once '../Framework/Route.php';
require_once 'Fake/Action.php';

use \Tree\Framework\Router;
use \Tree\Framework\Route;
use \Tree\Test\Fake_Action;
use \PHPUnit_Framework_TestCase;
use \ReflectionClass;

/**
 * RouterTest 
 *
 * Tests the mapping of requests to actions by Router
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
class RouterTest extends PHPUnit_Framework_TestCase {

	protected $router;

	public function setUp()
	{
		$this->router = new Router;
		$this->router->setUrlPrefix('http://localhost');
	}

	/**
	 * @covers \Tree\Framework\Router::getAction
	 * @test
	 */
	public function rejectsUrlIfDoesntMatchPrefix()
	{
		$this->assertNull($this->router->getAction('http://example.com/123'));
	}

	/**
	 * @covers \Tree\Framework\Router::getAction
	 * @test
	 */
	public function returnsActionIfUrlMatches()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');
		$this->router->addRoute($route);

		$action = $this->router->getAction('http://localhost/example/1234');

		$this->assertTrue($action instanceof \Tree\Test\Fake_Action);
	}

	/**
	 * @covers \Tree\Framework\Router::getAction
	 * @test
	 */
	public function returnsUrlIfActionMatches()
	{
		$route = new Route('/example/{id}', '\Tree\Test\Fake_Action');
		$this->router->addRoute($route);

		$expected = 'http://localhost/example/1234';
		$actual   = $this->router->getUrl('\Tree\Test\Fake_Action', array('id' => 1234));

		$this->assertEquals($expected, $actual);

	}

}

