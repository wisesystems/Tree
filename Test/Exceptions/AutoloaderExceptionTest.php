<?php

namespace Tree\Test\Exceptions;

require_once '../Framework/Autoloader.php';
require_once '../Exception/AutoloaderException.php';

use \Tree\Exception\AutoloaderException;
use \Tree\Framework\Autoloader;
use \PHPUnit_Framework_TestCase;

/**
 * AutoloaderExceptionTest 
 *
 * Verifies that Autoloader throws exceptions when it should, and only when it
 * should
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Framework\Autoloader
 * @uses       \Tree\Exception\AutoloaderException
 * @version    0.00
 */
class AutoloaderExceptionTest extends PHPUnit_Framework_TestCase {

	private $absolutePath   = '/private/tmp/TestClass.php';
	private $classDirectory = '/private/tmp';
	private $classFile      = 'TestClass.php';
	private $className      = '\TestClass';
	private $includePath;
	private $autoloader;

	public function setUp()
	{
		$this->includePath = get_include_path();

		set_include_path(
			$this->includePath
			. PATH_SEPARATOR
			. dirname($this->absolutePath)
		);

		file_put_contents($this->absolutePath, '<?php class TestClass {}');

		// registering the autoloader just before running each tests ensures it'll be
		// the last one on the autoloader list and therefore will throw exceptions
		// when things aren't loading
		$this->autoloader = new Autoloader;
		$this->autoloader->registerAutoloader();
	}

	public function tearDown()
	{
		set_include_path($this->includePath);
		unlink($this->absolutePath);
	}

	/**
	 * Verifies that Autoloader throws the right AutoloaderException if it can't
	 * read the file once it's found it
	 * 
	 * @covers                \Tree\Framework\Autoloader::autoloadClass
	 * @covers                \Tree\Framework\Autoloader::loadClassFromFile
	 * @expectedException     \Tree\Exception\AutoloaderException
	 * @expectedExceptionCode \Tree\Exception\AutoloaderException::FILE_NOT_READABLE
	 */
	public function testThrowsExceptionIfFileNotReadable()
	{
		chmod($this->absolutePath, 0220);

		$this->autoloader->autoloadClass($this->className);
	}

	/**
	 * Verifies that Autoloader throws the right AutoloaderException if it can't
	 * find the file in the include_path
	 * 
	 * @covers                \Tree\Framework\Autoloader::autoloadClass
	 * @covers                \Tree\Framework\Autoloader::loadClassFromFile
	 * @expectedException     \Tree\Exception\AutoloaderException
	 * @expectedExceptionCode \Tree\Exception\AutoloaderException::FILE_NOT_FOUND
	 */
	public function testThrowsExceptionIfFileNotFound()
	{
		$this->autoloader->autoloadClass('\WrongClass');
	}

	/**
	 * Verifies that Autoloader throws the right AutoloaderException if the file
	 * it finds doesn't actually contain the class that it should
	 * 
	 * @covers                \Tree\Framework\Autoloader::autoloadClass
	 * @covers                \Tree\Framework\Autoloader::loadClassFromFile
	 * @expectedException     \Tree\Exception\AutoloaderException
	 * @expectedExceptionCode \Tree\Exception\AutoloaderException::CLASS_NOT_FOUND
	 */
	public function testThrowsExceptionIfClassNotFound()
	{
		file_put_contents('/private/tmp/TestClassX.php', '<?php class TestClassY {}');

		$this->autoloader->autoloadClass('\TestClassX');
	}

}

