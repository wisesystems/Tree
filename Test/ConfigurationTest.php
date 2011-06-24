<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Exception/ConfigurationException.php';
require_once '../Framework/Configuration.php';

use \Tree\Exception\ConfigurationException;
use \Tree\Framework\Configuration;
use \PHPUnit_Framework_TestCase;

/**
 * ConfigurationTest 
 *
 * Tests \Tree\Framework\Configuration for correctness
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Framework\Configuration
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase {

	private $relativePath = 'test.ini';
	private $absolutePath = '/private/tmp/test.ini';

	private $includePath;

	public function setUp()
	{
		$this->includePath = get_include_path();

		set_include_path(
			$this->includePath
			. PATH_SEPARATOR
			. dirname($this->absolutePath)
		);

		file_put_contents($this->absolutePath, 'test = 1');
	}

	public function tearDown()
	{
		set_include_path($this->includePath);
		unlink($this->absolutePath);
	}

	/**
	 * Verifies that Configuration finds a file's absolute path given known
	 * conditions
	 */
	public function testFindsAbsolutePath()
	{
		$config = new Configuration($this->relativePath);
		$output = $config->getAbsolutePath();

		$this->assertEquals($this->absolutePath, $output);
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't find the ini file
	 */
	public function testThrowsExceptionIfFileNotFound()
	{
		$config = new Configuration('notfound.ini');
		$code   = null;

		try {
			$config->getIniValues();
		} catch (ConfigurationException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(ConfigurationException::FILE_NOT_FOUND, $code);
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't read the contents of the ini file
	 */
	public function testThrowsExceptionIfFileNotReadable()
	{
		$config = new Configuration('test.ini');
		$code   = null;

		chmod($this->absolutePath, 0220);

		try {
			$config->getIniValues();
		} catch (ConfigurationException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(ConfigurationException::FILE_NOT_READABLE, $code);
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't parse the contents of the ini file
	 */
	public function testThrowsExceptionIfFileNotParseable()
	{
		$config = new Configuration('test.ini');
		$code   = null;

		file_put_contents($this->absolutePath, ')(*&^%$£');

		try {
			$config->getIniValues();
		} catch (ConfigurationException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(ConfigurationException::FILE_NOT_PARSEABLE, $code);
	}

	/**
	 * Verifies that Configuration's ArrayAccess implementation returns the
	 * expected results
	 */
	public function testArrayAccess()
	{
		$config = new Configuration($this->relativePath);

		$this->assertTrue(isset($config['test']));
		$this->assertEquals(1, $config['test']);
	}

}

