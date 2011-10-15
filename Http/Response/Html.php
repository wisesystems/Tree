<?php

namespace Tree\Http;

/**
 * Response_Html 
 * 
 * Handles sending HTML responses
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Http
 * @version    0.00
 * @uses       \Tree\Response\Response
 */
class Response_Html extends Response {

	public function __construct()
	{
		$this->setHeader('Content-type', 'text/html');
	}

	/**
	 * Sends a HTML response body
	 * 
	 * @access protected
	 */
	protected function sendResponseBody()
	{
		echo $this->responseBody;
	}

}

