<?php

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Framework/Router.php';

use \Tree\Framework\Router;
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
	protected $reflection;

	public function setUp()
	{
		$this->router     = new Router;
		$this->reflection = new ReflectionClass('\Tree\Framework\Router');
	}

	/**
	 * Tests that RequestRouter::generateRegEx creates well-formed regular
	 * expressions to perform named-capture of the parameters in the
	 * request path
	 */
	public function testGenerateRegEx()
	{
		$method = $this->reflection->getMethod('generateRegEx');
		$method->setAccessible(true);

		$examples = array(
			array(
				'input'  => '/test/',
				'output' => '|^/test$|',
			),
			array(
				'input'  => '/test/{id}',
				'output' => '|^/test/(?P<id>[^/]+)$|',
			),
			array(
				'input'  => '/test/{id/\d/}',
				'output' => '|^/test/(?P<id>\d)$|',
			),
			array(
				'input'  => '/test/{id/\d{4}/}',
				'output' => '|^/test/(?P<id>\d{4})$|',
			),
		);

		foreach ($examples as $example) {

			$input    = $example['input'];
			$expected = $example['output'];
			
			$arguments = array($input);
			$actual    = $method->invokeArgs($this->router, $arguments);

			$this->assertEquals($expected, $actual);

		}
	}

	/**
	 * Tests that RequestRouter::parsePattern correctly extracts lists of
	 * parameter names from request patterns
	 */
	public function testParsePattern()
	{
		$method = $this->reflection->getMethod('parsePattern');
		$method->setAccessible(true);

		$examples = array(
			array(
				'input'  => '/test/',
				'output' => array(),
			),
			array(
				'input'  => '/test/{id}',
				'output' => array(
					'id' => null,
				),
			),
		);

		foreach ($examples as $example) {

			$input    = $example['input'];
			$expected = $example['output'];
			
			$arguments = array($input);
			$actual    = $method->invokeArgs($this->router, $arguments);

			$this->assertEquals($actual, $expected);

		}

	}

	/**
	 * Tests that RequestRouter::injectParameters correctly injects
	 * parameters into request patterns to generate request paths
	 */
	public function testInjectParameters()
	{
		$method = $this->reflection->getMethod('injectParameters');
		$method->setAccessible(true);

		$arguments = array(
			'/example/path/{parameter}',
			array(
				'parameter' => 12345,
			),
		);

		$path = $method->invokeArgs($this->router, $arguments);
		
		$this->assertEquals($path, '/example/path/12345');

	}


	/**
	 * Tests that RequestRouter applies the right rules to requests and
	 * extracts the parameters from those requests correctly
	 */
	public function testRoutesValidRequestsCorrectly()
	{
		$this->router->addRoute('/article/{id}', 'ArticleView', 'text/html');
		$this->router->addRoute('/article/{id}/extra', 'ArticleView', 'text/html', array(
			'extra' => true,
		));

		$route = $this->router->getAction('/article/12345');
		$this->assertEquals($route[0], 'ArticleView');
		$this->assertEquals($route[1], array(
			'id' => '12345'
		));

		$route = $this->router->getAction('/article/12345/extra');
		$this->assertEquals($route[0], 'ArticleView');
		$this->assertEquals($route[1], array(
			'id'    => '12345',
			'extra' => true,
		));
	}

	/**
	 * Tests that RequestRouter doesn't inadvertantly route requests that
	 * don't actually match any of the rules it has been given
	 */
	public function testRejectsInvalidRequests()
	{
		$this->router->addRoute('/test/{hi}', 'SomeAction', 'text/html');

		$route = $this->router->getAction('/foo/bar/wut');

		$this->assertEquals($route[0], null);
		$this->assertEquals($route[1], null);
	}

	/**
	 * Tests that RequestRouter::getPath returns paths that are correctly-
	 * formed according to the specified request routes
	 */
	public function testGetPath()
	{
		$this->router->addRoute('/test/{hi}', 'SomeAction', 'text/html');
		$this->router->addRoute('/test/{hi}/{foo}', 'OtherAction', 'text/html');

		$path = $this->router->getPath('SomeAction', array('hi' => 'test'));

		$this->assertEquals('/test/test', $path);
	}

	/**
	 * Tests that RequestRouter::getPath and RequestRouter::getAction fit
	 * together to create a two-way mapping between actions and patterns
	 */
	public function testTwoWayMapping()
	{
		$this->router->addRoute('/article/{id}', 'ArticleView', 'text/html');
		$this->router->addRoute('/article/{id}/extra', 'ArticleView', 'text/html', array(
			'extra' => true,
		));
		$this->router->addRoute('/test/{hi}', 'SomeAction', 'text/html');
		$this->router->addRoute('/test/{hi}/{foo}', 'OtherAction', 'text/html');

		$paths = array(
			'/article/12345',
			'/article/12345/extra',
			'/test/whatever',
			'/test/whatever/no',
		);

		foreach ($paths as $path) {
			
			$action0 = $this->router->getAction($path);
			$path0   = $this->router->getPath($action0[0], $action0[1]);
			$action1 = $this->router->getAction($path);
			$path1   = $this->router->getPath($action1[0], $action1[1]);

			$this->assertEquals($path, $path0);
			$this->assertEquals($action0, $action1);
			$this->assertEquals($path0, $path1);
		}
		
	}

	/**
	 * Tests that setting a URL prefix allows us to give RequestRouter a
	 * full request URL and expect it to parse out the part that actually
	 * matters
	 */
	public function testUrlPrefix()
	{
		$this->router->setUrlPrefix('http://example.com');
		$this->router->addRoute('/article/{id}', 'ArticleView', 'text/html');

		$parameters = array(
			'id' => 12345,
		);
		$url = $this->router->getUrl('ArticleView', $parameters);

		$this->assertEquals('http://example.com/article/12345', $url);
	}

}

