<?php

namespace Tree\Test\Component;

require_once 'ActionTest.php';
require_once 'PageTest.php';
require_once 'TemplateTest.php';

use \PHPUnit_Framework_TestSuite;

/**
 * TestSuite 
 *
 * Defines the Component test suite
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

		$suite->addTestSuite('\Tree\Test\Component\ActionTest');
		$suite->addTestSuite('\Tree\Test\Component\PageTest');
		$suite->addTestSuite('\Tree\Test\Component\TemplateTest');

		return $suite;
	}

}



