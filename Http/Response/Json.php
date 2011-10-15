<?php

namespace Tree\Http;

/**
 * Response_Json 
 *
 * Handles sending JSON-encoded responses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Http
 * @subpackage Response
 * @version    0.00
 */
class Response_Json {

	/**
	 * Sets proper cache control headers to ensure that browsers always get fresh
	 * JSON by default
	 * 
	 * @access public
	 */
	public function __construct()
	{
		$this->setHeader('Content-type', 'application/json');
		$this->setheader('Pragma', 'no-cache');
		$this->setHeader('Cache-Control', 'no-store, no-cache, max-age=0, must-revalidate');
	}

	/**
	 * Sends the JSON response body
	 * 
	 * @access public
	 */
	public function sendResponseBody()
	{
		echo $this->responseBody;
	}

}

