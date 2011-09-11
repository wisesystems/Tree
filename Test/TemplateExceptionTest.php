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

	public function setUp()
	{
		Template::setTemplateDirectory('/tmp');

		$this->template = new Fake_Template;
		$this->template->setTemplateFilename('template.php');
		file_put_contents('/tmp/template.php', 'Content: <?php echo $content; ?>');
	}

	public function tearDown()
	{
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
		$tpl  = null;

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::MISSING_REQUIRED_VARIABLE, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to set an input value that is not in the list of acceptable
	 * input values
	 */
	public function testThrowsExceptionIfSettingInvalidValue()
	{
		$code = null;
		$tpl  = null;

		try {
			$this->template->setInputValue('asdfgh', '12345');
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::INVALID_VALUE_NAME, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to unset an input value that is not in the list of acceptable
	 * input values
	 */
	public function testThrowsExceptionIfUnsettingInvalidValue()
	{
		$code = null;
		$tpl  = null;

		try {
			$this->template->unsetInputValue('asdfgh', '12345');
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::INVALID_VALUE_NAME, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output when no template filename has yet been
	 * set
	 */
	public function testThrowsExceptionIfFilenameMissing()
	{
		$code = null;
		$tpl  = null;

		try {
			$this->template->setTemplateFilename(null);
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::MISSING_TEMPLATE_FILENAME, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output but the template file cannot be found
	 */
	public function testThrowsExceptionIfTemplateFileNotFound()
	{
		$code = null;
		$tpl  = null;

		$this->template->setInputValue('content', 'example content');
		$this->template->setTemplateFilename('incorrect-filename.php');

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->template->setTemplateFilename('template.php');
		$this->assertTrue($tpl instanceof Fake_Template);

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
		$tpl  = null;

		$this->template->setInputValue('content', 'example content');
		chmod('/tmp/template.php', 0220);

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::TEMPLATE_NOT_READABLE, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

	/**
	 * Verifies that Template throws an exception if no static template directory
	 * has been set
	 */
	public function testThrowsExceptionIfTemplateDirectoryNotSet()
	{
		$code = null;
		$tpl  = null;

		Template::setTemplateDirectory(null);
		$this->template->setInputValue('content', 'example content');

		try {
			$this->template->getOutput();
		} catch (TemplateException $e) {
			$code = $e->getCode();
			$tpl  = $e->getTemplate();
		}

		$this->assertEquals(TemplateException::TEMPLATE_DIRECTORY_NOT_SET, $code);
		$this->assertTrue($tpl instanceof Fake_Template);
	}

}

