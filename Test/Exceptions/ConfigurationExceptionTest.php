<?php

namespace Tree\Test\Exceptions;

require_once '../Exception/ConfigurationException.php';
require_once '../Framework/Configuration.php';

use \Tree\Exception\ConfigurationException;
use \Tree\Framework\Configuration;
use \PHPUnit_Framework_TestCase;

/**
 * ConfigurationExceptionTest 
 *
 * Verifies that Configuration throws exceptions when it should
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
class ConfigurationExceptionTest extends PHPUnit_Framework_TestCase {

	private $absolutePath = '/private/tmp/test.ini';

	public function setUp()
	{
		file_put_contents($this->absolutePath, 'test = 1');
	}

	public function tearDown()
	{
		unlink($this->absolutePath);
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't find the ini file
	 *
	 * @covers                \Tree\Framework\Configuration::getAbsolutePath
	 * @covers                \Tree\Framework\Configuration::findAbsolutePath
	 * @covers                \Tree\Framework\Configuration::parseIniFile
	 * @expectedException     \Tree\Exception\ConfigurationException
	 * @expectedExceptionCode \Tree\Exception\ConfigurationException::FILE_NOT_FOUND
	 */
	public function testThrowsExceptionIfFileNotFound()
	{
		$config = new Configuration('notfound.ini');
		$config->getIniValues();
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't read the contents of the ini file
	 * 
	 * @covers                \Tree\Framework\Configuration::parseIniFile
	 * @expectedException     \Tree\Exception\ConfigurationException
	 * @expectedExceptionCode \Tree\Exception\ConfigurationException::FILE_NOT_READABLE
	 */
	public function testThrowsExceptionIfFileNotReadable()
	{
		$config = new Configuration('test.ini');

		chmod($this->absolutePath, 0220);

		$config->getIniValues();
	}

	/**
	 * Verifies that Configuration throws the right ConfigurationException if it
	 * can't parse the contents of the ini file
	 * 
	 * @covers                \Tree\Framework\Configuration::parseIniFile
	 * @expectedException     \Tree\Exception\ConfigurationException
	 * @expectedExceptionCode \Tree\Exception\ConfigurationException::FILE_NOT_PARSEABLE
	 */
	public function testThrowsExceptionIfFileNotParseable()
	{
		$config = new Configuration('test.ini');

		file_put_contents($this->absolutePath, ')(*&^%$£');

		$config->getIniValues();
	}

}

