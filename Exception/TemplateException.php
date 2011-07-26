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
	 * The template file could not be found in the include_path
	 */
	const TEMPLATE_NOT_FOUND = 4;

}

