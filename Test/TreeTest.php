<?php

namespace Tree\Test;

require_once 'PHPUnit/Autoload.php';
require_once '../Framework/Tree.php';

use \Tree\Framework\Tree;
use \PHPUnit_Framework_TestCase;
use \ReflectionClass;

/**
 * TreeTest
 *
 * Tests \Tree\Framework\Tree for correctness
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \ReflectionClass
 * @uses       \PHPUnit_Framework_TestCase
 * @uses       \Tree\Framework\Tree
 * @version    0.00
 */
class TreeTest extends PHPUnit_Framework_TestCase {

	/**
	 * Verifies that Tree can tell when the include_path is set up properly 
	 */
	public function testDetectsPresenceInIncludePath()
	{
		$tree       = new Tree;
		$reflection = new ReflectionClass('\Tree\Framework\Tree');

		$method = $reflection->getMethod('includePathContainsTree');
		$method->setAccessible(true);

		$directory = __DIR__;
		$directory = dirname($directory);
		$directory = dirname($directory);
		
		$includePath = get_include_path();

		set_include_path($includePath . PATH_SEPARATOR . $directory);

		$output = $method->invokeArgs($tree, array());

		$this->assertTrue($output);
		set_include_path($includePath);
	}

	/**
	 * Verifies that Tree can tell when it's not in the include_path 
	 */
	public function testDetectsAbsenceFromIncludePath()
	{
		$tree       = new Tree;
		$reflection = new ReflectionClass('\Tree\Framework\Tree');

		$method = $reflection->getMethod('includePathContainsTree');
		$method->setAccessible(true);

		$directory = __DIR__;
		$directory = dirname($directory);
		$directory = dirname($directory);

		$includePath = get_include_path();
		set_include_path($includePath . PATH_SEPARATOR . $directory);
		$paths = explode(PATH_SEPARATOR, $includePath);

		foreach ($paths as $i => $path) {
			if ($path === $directory) {
				unset($paths[$i]);
			}
		}

		set_include_path(implode(PATH_SEPARATOR, $paths));

		$output = $method->invokeArgs($tree, array());

		$this->assertFalse($output);

		set_include_path($includePath);
	}

}

