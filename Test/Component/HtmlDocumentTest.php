<?php

namespace Tree\Test\Component;

require '../Response/Response.php';
require '../Response/Response/Html.php';
require '../Component/HtmlDocument.php';
require_once 'Fake/HtmlDocument.php';

use \PHPUnit_Framework_Testcase;
use \Tree\Component\HtmlDocument;
use \Tree\Test\Fake_HtmlDocument as FakeDocument;

/**
 * HtmlDocumentTest 
 *
 * Tests the HtmlDocument component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_Testcase
 * @uses       \Tree\Component\HtmlDocument
 * @version    0.00
 */
class HtmlDocumentTest extends PHPUnit_Framework_Testcase {

	private $document;

	public function setUp()
	{
		$this->document = new FakeDocument;
	}

	/**
	 * Verifies that calling addJavascriptDependency adds the file to the list
	 * 
	 * @covers \Tree\Component\HtmlDocument::addJavascriptDependency
	 * @covers \Tree\Component\HtmlDocument::getJavascriptDependencies
	 */
	public function testAddJavascriptDependencyAddsJavascriptDependency()
	{
		$this->document->addJavascriptDependency('example.js');

		$expected = array('example.js');
		$actual   = $this->document->getJavascriptDependencies();

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that calling addStylesheetDependency adds the file to the list
	 * 
	 * @covers \Tree\Component\HtmlDocument::addStylesheetDependency
	 * @covers \Tree\Component\HtmlDocument::getStylesheetDependencies
	 */
	public function testAddStylesheetDependencyAddsStylesheetDependency()
	{
		$this->document->addStylesheetDependency('example.css');

		$expected = array('example.css');
		$actual   = $this->document->getStylesheetDependencies();

		$this->assertEquals($expected, $actual);
	}

}

