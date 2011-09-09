<?php

namespace Tree\Test;

require '../Component/Page.php';

use \PHPUnit_Framework_Testcase;
use \Tree\Component\Page;

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

