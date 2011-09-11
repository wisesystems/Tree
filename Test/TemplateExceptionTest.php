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
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::MISSING_REQUIRED_VARIABLES
	 */
	public function testThrowsExceptionIfValuesMissing()
	{
		$this->template->getOutput();
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to set an input value that is not in the list of acceptable
	 * input values
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::INVALID_VALUE_NAME
	 */
	public function testThrowsExceptionIfSettingInvalidValue()
	{
		$this->template->setInputValue('asdfgh', '12345');
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to unset an input value that is not in the list of acceptable
	 * input values
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::INVALID_VALUE_NAME
	 */
	public function testThrowsExceptionIfUnsettingInvalidValue()
	{
		$this->template->unsetInputValue('asdfgh', '12345');
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output when no template filename has yet been
	 * set
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::MISSING_TEMPLATE_FILENAME
	 */
	public function testThrowsExceptionIfFilenameMissing()
	{
		$this->template->setTemplateFilename(null);
		$this->template->getOutput();
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output but the template file cannot be found
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::TEMPLATE_NOT_FOUND
	 */
	public function testThrowsExceptionIfTemplateFileNotFound()
	{
		$this->template->setInputValue('content', 'example content');
		$this->template->setTemplateFilename('incorrect-filename.php');
		$this->template->getOutput();
	}

	/**
	 * Verifies that Template throws the right kind of TemplateException if an
	 * attempt is made to generate output but the template file cannot be read by
	 * the PHP processs
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::TEMPLATE_NOT_READABLE
	 */
	public function testThrowsExceptionIfTemplateFileNotReadable()
	{
		$this->template->setInputValue('content', 'example content');
		chmod('/tmp/template.php', 0220);
		$this->template->getOutput();
	}

	/**
	 * Verifies that Template throws an exception if no static template directory
	 * has been set
	 * 
	 * @expectedException     \Tree\Exception\TemplateException
	 * @expectedExceptionCode \Tree\Exception\TemplateException::TEMPLATE_DIRECTORY_NOT_SET
	 */
	public function testThrowsExceptionIfTemplateDirectoryNotSet()
	{
		Template::setTemplateDirectory(null);
		$this->template->setInputValue('content', 'example content');
		$this->template->getOutput();
	}

}

