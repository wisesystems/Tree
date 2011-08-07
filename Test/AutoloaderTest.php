<?php

namespace Tree\Test;

require_once '../Framework/Autoloader.php';
require_once '../Exception/AutoloaderException.php';

use \Tree\Exception\AutoloaderException;
use \Tree\Framework\Autoloader;
use \PHPUnit_Framework_TestCase;
use \ReflectionClass;

/**
 * AutoloaderTest 
 *
 * Tests \Tree\Framework\Autoloader for correctness
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \ReflectionClass
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Framework\Autoloader
 * @version    0.00
 */
class AutoloaderTest extends PHPUnit_Framework_TestCase {

	private $absolutePath   = '/private/tmp/TestClass.php';
	private $classDirectory = '/private/tmp';
	private $classFile      = 'TestClass.php';
	private $className      = '\TestClass';
	private $includePath;

	public function setUp()
	{
		$this->includePath = get_include_path();

		set_include_path(
			$this->includePath
			. PATH_SEPARATOR
			. dirname($this->absolutePath)
		);

		file_put_contents($this->absolutePath, '<?php class TestClass {}');
	}

	public function tearDown()
	{
		set_include_path($this->includePath);
		unlink($this->absolutePath);
	}

	/**
	 * Tests Autoloader::translateClassName for PSR-0 compliance. 
	 */
	public function testTranslatesClassNames()
	{
		$autoloader = new Autoloader;
		$reflection = new ReflectionClass('\Tree\Framework\Autoloader');

		$method = $reflection->getMethod('translateClassName');
		$method->setAccessible(true);

		$testCases = array(
			'SomeClass'                       => 'SomeClass.php',
			'\Vendor\SomeClass'               => 'Vendor/SomeClass.php',
			'\Vendor\Package\SomeClass'       => 'Vendor/Package/SomeClass.php',
			'\Vendor\Package_A\SomeClass'     => 'Vendor/Package_A/SomeClass.php',
			'\Vendor_A\Package_A\SomeClass_A' => 'Vendor_A/Package_A/SomeClass/A.php',
		);

		foreach ($testCases as $class => $filename) {

			$arguments = array($class);
			$output    = $method->invokeArgs($autoloader, $arguments);

			$this->assertEquals($filename, $output);
			
		}
		
	}

	/**
	 * Verifies that Autoloader can tell when it's the last autoloader in the
	 * autoload stack
	 */
	public function testKnowsIfLastAutoloader()
	{
		$autoloader = new Autoloader;
		$autoloader->registerAutoloader();

		$this->assertTrue($autoloader->isLastAutoloader());
	}

	/**
	 * Verifies that Autoloader can tell when it's not the last autoloader in the
	 * autoload stack
	 */
	public function testKnowsIfNotLastAutoloader()
	{
		$autoloader = new Autoloader;
		$autoloader->registerAutoloader();

		$function = function() {};
		spl_autoload_register($function);

		$this->assertFalse($autoloader->isLastAutoloader());
		spl_autoload_unregister($function);
	}

	/**
	 * Verifies that Autoloader actually manages to load a class given known valid
	 * circumstances
	 */
	public function testLoadsValidClass()
	{
		$autoloader = new Autoloader;
		$output     = $autoloader->autoloadClass($this->className);

		$this->assertTrue($output);
	}

}

