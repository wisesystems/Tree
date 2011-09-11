<?php

namespace Tree\Test;

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
	 */
	public function testThrowsExceptionIfFileNotReadable()
	{
		$code = null;

		chmod($this->absolutePath, 0220);

		try {
			$this->autoloader->autoloadClass($this->className);
		} catch (AutoloaderException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(AutoloaderException::FILE_NOT_READABLE, $code);
	}

	/**
	 * Verifies that Autoloader throws the right AutoloaderException if it can't
	 * find the file in the include_path
	 */
	public function testThrowsExceptionIfFileNotFound()
	{
		$code = null;

		try {
			$this->autoloader->autoloadClass('\WrongClass');
		} catch (AutoloaderException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(AutoloaderException::FILE_NOT_FOUND, $code);
	}

	/**
	 * Verifies that Autoloader throws the right AutoloaderException if the file
	 * it finds doesn't actually contain the class that it should
	 */
	public function testThrowsExceptionIfClassNotFound()
	{
		$code = null;

		file_put_contents('/private/tmp/TestClassX.php', '<?php class TestClassY {}');

		try {
			$this->autoloader->autoloadClass('\TestClassX');
		} catch (AutoloaderException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(AutoloaderException::CLASS_NOT_FOUND, $code);
	}

}

