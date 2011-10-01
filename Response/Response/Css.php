<?php

namespace Tree\Response;

/**
 * Response_Css 
 * 
 * Handles sending CSS responses
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Response
 * @version    0.00
 * @uses       \Tree\Response\Response
 */
class Response_Css extends Response {

	public function __construct()
	{
		// http://www.ietf.org/rfc/rfc2318.txt
		$this->setHeader('Content-type', 'text/css');
	}

	/**
	 * Sends a CSS response body
	 * 
	 * @access protected
	 */
	protected function sendResponseBody()
	{
		echo $this->responseBody;
	}

}


