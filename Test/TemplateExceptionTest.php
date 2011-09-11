<?php

namespace Tree\Test;

require_once '../Component/Template.php';
require_once '../Exception/TemplateException.php';
require_once 'Fake/Template.php';

use \PHPUnit_Framework_TestCase;
use \Tree\Component\Template;
use \Tree\Exception\TemplateException;

/**
 * TemplateExceptionTest
 *
 * Verifies that Template throws exceptions when it should
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \PHPUnit_Framework_TestCase
 * @version    0.00
 */
class TemplateExceptionTest extends PHPUnit_Framework_TestCase {

	private $template;
	private $includePath;

	public function setUp()
	{
		Template::setTemplateDirectory('/tmp');

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
	public function testThrowsExceptionIfSettingInvalidValue()
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
	 * attempt is made to unset an input value that is not in the list of acceptable
	 * input values
	 */
	public function testThrowsExceptionIfUnsettingInvalidValue()
	{
		$code = null;

		try {
			$this->template->unsetInputValue('asdfgh', '12345');
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

		$this->template->setInputValue('content', 'example content');
		$this->template->setTemplateFilename('incorrect-filename.php');

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->template->setTemplateFilename('template.php');

		$this->assertEquals(TemplateException::TEMPLATE_NOT_FOUND, $code);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output but the template file cannot be read by
	 * the PHP processs
	 */
	public function testThrowsExceptionIfTemplateFileNotReadable()
	{
		$code = null;

		$this->template->setInputValue('content', 'example content');
		chmod('/tmp/template.php', 0220);

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(TemplateException::TEMPLATE_NOT_READABLE, $code);
	}

	/**
	 * Verifies that Template throws an exception if no static template directory
	 * has been set
	 */
	public function testThrowsExceptionIfTemplateDirectoryNotSet()
	{
		$code = null;

		Template::setTemplateDirectory(null);
		$this->template->setInputValue('content', 'example content');

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
		}

		$this->assertEquals(TemplateException::TEMPLATE_DIRECTORY_NOT_SET, $code);
	}

}

