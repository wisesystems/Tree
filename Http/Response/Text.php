<?php

namespace Tree\Http;

/**
 * Response_Text 
 * 
 * Handles plain text HTTP responses
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Http
 * @uses       \Tree\Response\Response
 * @version    0.00
 */
class Response_Text extends Response {

	public function __construct()
	{
		$this->setHeader('Content-type', 'text/plain');
	}

	/**
	 * Sends a plain text response body
	 * 
	 * @access protected
	 */
	protected function sendResponseBody()
	{
		echo $this->responseBody;
	}

}

