<?php

namespace Tree\Test;

require_once '../Component/Template.php';
require_once '../Exception/TemplateException.php';
require_once 'Fake/Template.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Component\Template;
use \Tree\Exception\TemplateException;

/**
 * TemplateTest
 *
 * Tests that the Template class carefully enforces requirements regarding
 * input values
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class TemplateTest extends PHPUnit_Framework_TestCase {

	private $template;
	private $includePath;

	public function setUp()
	{
		$this->template = new Fake_Template;
		$this->template->setTemplateFilename('template.php');
		file_put_contents('/tmp/template.php', 'Content: <?php echo $content; ?>');
		
		$this->includePath = get_include_path();

		set_include_path($this->includePath . PATH_SEPARATOR . '/tmp');

	}

	public function tearDown()
	{
		set_include_path($this->includePath);
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
	 * Tests that setInputValue() runs successfully if given a permissible
	 * input value
	 */
	public function testSetInputValueAcceptsValidValues()
	{
		$this->template->setInputValue('content', 'example content');

		$expected = 'example content';
		$actual   = $this->template->getInputValue('content');

		$this->assertEquals($expected, $actual);
	}

	/**
	 * Verifies that calling unsetInputValue() on a valid value causes that value
	 * to be removed from the template
	 */
	public function testUnsetInputValueRemovesValue()
	{
		$this->template->setInputValue('content', '12345');

		$this->assertEquals('12345', $this->template->getInputValue('content'));

		$this->template->unsetInputValue('content');

		$this->assertEquals(null, $this->template->getInputValue('content'));
	}

	/**
	 * Verifies that passing a name-value pair to Template::setGlobalValue causes
	 * that variable to be available in a Template subclass even if not explicitly
	 * set on that individual object
	 */
	public function testGlobalTemplateVariables()
	{
		$this->template->setInputValue('content', 'example content');
		Template::setGlobalValue('someValue', 123456);

		file_put_contents('/tmp/template.php', 'Content: <?php echo $someValue; ?>');
		$output = $this->template->getOutput();

		$this->assertEquals('Content: 123456', $output);
	}

	/**
	 * Verifies that passing a directory name to Template via
	 * setTemplateDirectory() causes Template to use that directory name to
	 * generate an absolute path to all template files
	 */
	public function testFindsTemplateFileFromAbsolutePath()
	{
		$this->template->setInputValue('content', 'example content');

		// takes /tmp back out of include_path so that getOutput will throw an
		// exception when it can't find the file from its relative path
		set_include_path($this->includePath);

		Template::setTemplateDirectory('/tmp');
		$this->template->setTemplateFilename('template.php');

		$output = $this->template->getOutput();
		$this->assertEquals('Content: example content', $output);
	}

	/**
	 * Verifies that the ArrayAccess implementation sets, unsets and checks values
	 * correctly
	 */
	public function testArrayAccess()
	{
		$this->assertFalse(isset($this->template['content']));
		$this->assertEquals(null, $this->template['content']);

		$this->template['content'] = 'example content 2';

		$this->assertTrue(isset($this->template['content']));
		$this->assertEquals('example content 2', $this->template->getInputValue('content'));
		$this->assertEquals('example content 2', $this->template['content']);

		unset($this->template['content']);

		$this->assertFalse(isset($this->template['content']));
		$this->assertEquals(null, $this->template['content']);

	}

}

