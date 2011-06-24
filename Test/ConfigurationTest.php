<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Framework/Configuration.php';

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

	/**
	 * Verifies that Configuration finds a file's absolute path given known
	 * conditions
	 */
	public function testFindsAbsolutePath()
	{
		$relativePath = 'test.ini';
		$absolutePath = '/private/tmp/test.ini';

		$config = new Configuration($relativePath);

		$originalPath = get_include_path();
		set_include_path('/private/tmp');

		file_put_contents($absolutePath, 'test = 1');

		$output = $config->getAbsolutePath();

		set_include_path($originalPath);
		unlink($absolutePath);

		$this->assertEquals($absolutePath, $output);
	}

}

