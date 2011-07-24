<?php

namespace Tree\Response;

/**
 * Response_Xml
 * 
 * Handles sending XML responses
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Response
 * @version    0.00
 * @uses       \Tree\Response\Response
 */
class Response_Xml extends Response {

	public function __construct()
	{
		$this->setHeader('Content-type', 'text/xml');
	}

	/**
	 * Sends an XML response body
	 * 
	 * @access protected
	 */
	protected function sendResponseBody()
	{
		echo $this->responseBody;
	}

}

