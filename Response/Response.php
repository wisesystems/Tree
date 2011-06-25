<?php

namespace Tree\Response;

/**
 * Response 
 *
 * Base class for modelling and sending HTTP responses
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Response
 * @version    0.00
 */
abstract class Response {

	/**
	 * The body of the response, e.g. plain text, HTML, or binary image data
	 * 
	 * @access protected
	 * @var    mixed
	 */
	protected $responseBody;

	/**
	 * An associative array of header names and values 
	 * 
	 * @access private
	 * @var    array
	 */
	private $responseHeaders = array();

	/**
	 * Sends the response
	 * 
	 * @access public
	 */
	public function sendResponse()
	{
		$this->sendResponseHeaders();
		$this->sendResponseBody();
	}

	/**
	 * Stores the given value as the body of the response
	 * 
	 * @access public
	 * @param  mixed $body 
	 */
	public function setBody($body)
	{
		$this->responseBody = $body;
	}

	/**
	 * Sets the given name-value pair as a header to be sent before the response
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  string $value 
	 */
	public function setHeader($name, $value)
	{
		$this->responseHeaders[$name] = $value;
	}

	/**
	 * Sends the HTTP headers of the response to the user
	 * 
	 * @access private
	 */
	private function sendResponseHeaders()
	{
		foreach ($this->responseHeaders as $name => $value) {
			header("{$name}: $value");
		}
	}

	/**
	 * Sends the response body
	 * 
	 * To be implemented by subclasses that actually know how to send their
	 * response data.
	 *
	 * @abstract
	 * @access protected
	 */
	abstract protected function sendResponseBody();

}

