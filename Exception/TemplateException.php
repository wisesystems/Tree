<?php

namespace Tree\Exception;

use \Exception;

/**
 * TemplateException 
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class TemplateException extends Exception {

	/**
	 * An attempt to generate output was made when one or more required input
	 * variables were not yet set
	 */
	const MISSING_REQUIRED_VARIABLE = 1;

	/**
	 * An attempt was made to set an input variable whose name did not appear in
	 * either the list of required or optional input variables
	 */
	const INVALID_VALUE_NAME = 2;

	/**
	 * An attempt was made to generate template output but the template filename
	 * was not set
	 */
	const MISSING_TEMPLATE_FILENAME = 3;

	/**
	 * The template file could not be found
	 */
	const TEMPLATE_NOT_FOUND = 4;

	/**
	 * The template file could not be read by the PHP process
	 */
	const TEMPLATE_NOT_READABLE = 5;

	/**
	 * No template directory was set, so the template couldn't be loaded
	 */
	const TEMPLATE_DIRECTORY_NOT_SET = 6;

	/**
	 * The instance of \Tree\Component\Template that caused the exception
	 * 
	 * @access private
	 * @var    \Tree\Component\Template
	 */
	private $template;

	/**
	 * @access public
	 * @param  string                   $message 
	 * @param  integer                  $code
	 * @param  \Tree\Component\Template $template [optional]
	 */
	public function __construct($message, $code, $template = null)
	{
		parent::__construct($message, $code);

		$this->template = $template;
	}

	/**
	 * Returns the Template object that threw the exception
	 * 
	 * @access public
	 * @return \Tree\Component\Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}

}

