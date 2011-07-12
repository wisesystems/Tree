<?php

namespace Tree\Component;

/**
 * Action_JsonResponseGenerator
 *
 * To be implemented by Actions that generate JSON responses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
interface Action_JsonResponseGenerator {

	/**
	 * To be overridden with a method that returns a JSON response object
	 * 
	 * @access public
	 * @return \Tree\Response\Response_Json
	 */
	public function getJsonResponse();

}

