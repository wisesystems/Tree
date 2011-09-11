<?php

namespace Tree\Test;

require '../Response/Response.php';
require '../Response/Response/Html.php';
require '../Component/Page.php';

use \PHPUnit_Framework_Testcase;
use \Tree\Component\Page;

/**
 * PageTest 
 *
 * Tests the Page component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_Testcase
 * @uses       \Tree\Component\Page
 * @version    0.00
 */
class PageTest extends PHPUnit_Framework_Testcase {

	private $page;

	public function setUp()
	{
		$this->page = new Page;
	}

	public function testAddJavascriptDependencyAddsJavascriptDependency()
	{
		$this->page->addJavascriptDependency('example.js');

		$expected = array('example.js');
		$actual   = $this->page->getJavascriptDependencies();

		$this->assertEquals($expected, $actual);
	}

	public function testAddStylesheetDependencyAddsStylesheetDependency()
	{
		$this->page->addStylesheetDependency('example.css');

		$expected = array('example.css');
		$actual   = $this->page->getStylesheetDependencies();

		$this->assertEquals($expected, $actual);
	}

}

