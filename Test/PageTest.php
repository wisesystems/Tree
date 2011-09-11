<?php

namespace Tree\Test;

require '../Response/Response.php';
require '../Response/Response/Html.php';
require '../Component/Page.php';
require_once 'Fake/Page.php';

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
		$this->page = new Fake_Page;
	}

	/**
	 * Verifies that calling addJavascriptDependency adds the file to the list
	 * 
	 * @covers \Tree\Component\Page::addJavascriptDependency
	 * @covers \Tree\Component\Page::getJavascriptDependencies
	 */
	public function testAddJavascriptDependencyAddsJavascriptDependency()
	{
		$this->page->addJavascriptDependency('example.js');

		$expected = array('example.js');
		$actual   = $this->page->getJavascriptDependencies();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that calling addStylesheetDependency adds the file to the list
	 * 
	 * @covers \Tree\Component\Page::addStylsheetDependency
	 * @covers \Tree\Component\Page::getStylesheetDependencies
	 */
	public function testAddStylesheetDependencyAddsStylesheetDependency()
	{
		$this->page->addStylesheetDependency('example.css');

		$expected = array('example.css');
		$actual   = $this->page->getStylesheetDependencies();

		$this->assertEquals($expected, $actual);
	}

}

