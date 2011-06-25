<?php

/**
 * TemplateTest
 *
 * Tests that the Template class carefully enforces requirements regarding
 * input values
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2010 - 2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       PHPUnit_Framework_TestCase
 * @version    0.00
 */

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Component/Template.php';

use \Tree\Component\Template;
use \PHPUnit_Framework_TestCase;

class TemplateTest extends PHPUnit_Framework_TestCase {

	private $template;

	public function setUp()
	{
		$this->template = new TemplateMock;
		$this->template->setTemplateFilename('/tmp/template.php');
		file_put_contents('/tmp/template.php', 'Content: <?php echo $content; ?>');
	}

	public function tearDown()
	{
		unlink('/tmp/template.php');
	}

	/**
	 * Tests that getOutput() runs successfully if the required input
	 * values have been set, and that it passes those values through
	 * to generateOutput()
	 */
	public function testGetOutputReturnsOutputIfValuesPresent()
	{
		$this->template->setInputValue('content', 'example content');

		$output = $this->template->getOutput();
		
		$this->assertEquals('Content: example content', $output);
	}

	/**
	 * Tests that getOutput() throws an Exception if it is called before
	 * the Template object has been provided with all the input values that
	 * it needs to execute
	 */
	public function testGetOutputThrowsExceptionIfValuesMissing()
	{
		$this->setExpectedException('\Exception');
		$this->template->getOutput();
	}

	/**
	 * Tests that setInputValue() runs successfully if given a permissible
	 * input value
	 */
	public function testSetInputValueAcceptsValidValues()
	{
		$this->template->setInputValue('content', 'example content');
	}

	/**
	 * Tests that setInputValue() throws an Exception if it is given an
	 * input value that is not in the list of specifically permitted values
	 */
	public function testSetInputValueRejectsInvalidValues()
	{
		$this->setExpectedException('\Exception');
		$this->template->setInputValue('foo', 'bar');
	}

}

class TemplateMock extends Template {

	protected $optionalInputValues = array(
		'footnote' => 'example footnote',
	);

	protected $requiredInputValues = array(
		'content',
	);

}


