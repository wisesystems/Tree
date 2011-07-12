<?php

namespace Tree\Component;

/**
 * Action_TextResponseGenerator
 *
 * To be implemented by Actions that generate plain text responses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
interface Action_TextResponseGenerator {

	/**
	 * To be overridden with a method that returns a text response object
	 * 
	 * @access public
	 * @return \Tree\Response\Response_Text
	 */
	public function getTextResponse();

}

