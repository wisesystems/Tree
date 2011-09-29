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

	private $absolutePath = '/tmp/test.ini';

	public function setUp()
	{
		file_put_contents($this->absolutePath, 'test = 1');
	}

	public function tearDown()
	{
		unlink($this->absolutePath);
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
		$config = new Configuration($this->absolutePath);

		$this->assertTrue(isset($config['test']));
		$this->assertEquals(1, $config['test']);
	}

}

