<?php

namespace Tree\Test;

require_once 'Component/TestSuite.php';
require_once 'DatabaseQuery/TestSuite.php';
require_once 'Framework/TestSuite.php';

use \PHPUnit_Framework_TestSuite;
use \Tree\Test\Component\TestSuite     as Component;
use \Tree\Test\DatabaseQuery\TestSuite as DatabaseQuery;
use \Tree\Test\Framework\TestSuite     as Framework;

/**
 * TestSuite 
 *
 * The top-level test suite class
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @package    Tree
 * @subpackage Test
 * @license    GPLv2.0
 * @version    0.00
 */
class TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('PHPUnit');

		$suite->addTest(Component::suite());
		$suite->addTest(DatabaseQuery::suite());
		$suite->addTest(Framework::suite());

		return $suite;
	}

}

