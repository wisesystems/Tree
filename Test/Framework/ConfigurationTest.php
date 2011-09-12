<?php

namespace Tree\Test\Framework;

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
	 * 
	 * @covers \Tree\Framework\Configuration::findAbsolutePath
	 * @covers \Tree\Framework\Configuration::getAbsolutePath
	 */
	public function testFindsAbsolutePath()
	{
		$config = new Configuration($this->relativePath);
		$output = $config->getAbsolutePath();

		$this->assertEquals($this->absolutePath, $output);
	}

	/**
	 * Verifies that Configuration's ArrayAccess implementation returns the
	 * expected results
	 *
	 * @covers \Tree\Framework\Configuration::offsetExists
	 * @covers \Tree\Framework\Configuration::offsetGet
	 * @covers \Tree\Framework\Configuration::offsetSet
	 * @covers \Tree\Framework\Configuration::offsetUnset
	 */
	public function testArrayAccess()
	{
		$config = new Configuration($this->relativePath);

		$this->assertTrue(isset($config['test']));
		$this->assertEquals(1, $config['test']);
	}

}

