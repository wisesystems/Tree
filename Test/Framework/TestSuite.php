<?php

namespace Tree\Test\Framework;

require_once 'AutoloaderTest.php';
require_once 'ConfigurationTest.php';
require_once 'RouterTest.php';
require_once 'TreeTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the Framework test suite
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @package    Tree
 * @subpackage Test
 * @license    GPLv2.0
 * @version    0.00
 */
class TestSuite {

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

		$suite->addTestSuite('\Tree\Test\Framework\AutoloaderTest');
		$suite->addTestSuite('\Tree\Test\Framework\ConfigurationTest');
		$suite->addTestSuite('\Tree\Test\Framework\RouterTest');
		$suite->addTestSuite('\Tree\Test\Framework\ApplicationTest');

		return $suite;
	}

}



