<?php

namespace Tree\Component;

/**
 * Action_HtmlResponseGenerator
 *
 * To be implemented by Actions that generate HTML responses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
interface Action_HtmlResponseGenerator {

	/**
	 * To be overridden with a method that returns a HTML response object
	 * 
	 * @access public
	 * @return \Tree\Response\Response_Html
	 */
	public function getHtmlResponse();

}

