<?php

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

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Component/Template.php';
require '../Exception/TemplateException.php';
require 'Mock/Template.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Component\Template;
use \Tree\Exception\TemplateException;

class TemplateTest extends PHPUnit_Framework_TestCase {

	private $template;
	private $includePath;

	public function setUp()
	{
		$this->template = new Mock_Template;
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
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output when all required inputs are not yet
	 * available
	 */
	public function testThrowsExceptionIfValuesMissing()
	{
		$code = null;

		$includePath = get_include_path();

		set_include_path('/tmp');
		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		set_include_path($includePath);

		$this->assertEquals(TemplateException::MISSING_REQUIRED_VARIABLE, $code);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to set an input value that is not in the list of acceptable
	 * input values
	 */
	public function testThrowsExceptionIfGivenInvalidValue()
	{
		$code = null;

		try {
			$this->template->setInputValue('asdfgh', '12345');
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(TemplateException::INVALID_VALUE_NAME, $code);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output when no template filename has yet been
	 * set
	 */
	public function testThrowsExceptionIfFilenameMissing()
	{
		$code = null;

		try {
			$this->template->setTemplateFilename(null);
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(TemplateException::MISSING_TEMPLATE_FILENAME, $code);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output but the template file cannot be found
	 * in the include_path
	 */
	public function testThrowsExceptionIfTemplateFileNotFound()
	{
		$code = null;

		$this->template->setTemplateFilename('incorrect-filename.php');

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(TemplateException::TEMPLATE_NOT_FOUND, $code);
	}

}

