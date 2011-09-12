<?php

namespace Tree\Test\Exceptions;

require_once 'AutoloaderExceptionTest.php';
require_once 'ConfigurationExceptionTest.php';
require_once 'DatabaseExceptionTest.php';
require_once 'EntityExceptionTest.php';
require_once 'SearchExceptionTest.php';
require_once 'TemplateExceptionTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the Exception test suite
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

		$suite->addTestSuite('\Tree\Test\Exceptions\AutoloaderExceptionTest');
		$suite->addTestSuite('\Tree\Test\Exceptions\ConfigurationExceptionTest');
		$suite->addTestSuite('\Tree\Test\Exceptions\DatabaseExceptionTest');
		$suite->addTestSuite('\Tree\Test\Exceptions\EntityExceptionTest');
		$suite->addTestSuite('\Tree\Test\Exceptions\SearchExceptionTest');
		$suite->addTestSuite('\Tree\Test\Exceptions\TemplateExceptionTest');

		return $suite;
	}

}



