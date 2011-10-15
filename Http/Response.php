<?php

namespace Tree\Http;

use \stdClass;
use \Exception;

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
 * @subpackage Http
 * @version    0.00
 */
abstract class Response {

	/**
	 * An associative array of HTTP status codes and their associated messages
	 *
	 * @access private
	 * @var    array
	 * @static
	 */
	private static $statusCodes = array(
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => "I'm a teapot",
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);

	/**
	 * The HTTP status code of the response, e.g. 404, 500 etc
	 * 
	 * @access protected
	 * @var    integer
	 */
	protected $responseStatus = 200;

	/**
	 * The body of the response, e.g. plain text, HTML, or binary image data
	 * 
	 * @access protected
	 * @var    mixed
	 */
	protected $responseBody;

	private $responseCookies = array();

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
		$this->sendStatusHeader();
		$this->sendResponseHeaders();
		$this->sendResponseCookies();
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

	public function setCookie($name, $value, $expire = 0, $path = '/', $domain = '', $secure = true, $httpOnly = true)
	{
		$this->responseCookies[$name]           = new stdClass;
		$this->responseCookies[$name]->name     = $name;
		$this->responseCookies[$name]->value    = $value;
		$this->responseCookies[$name]->expire   = $expire;
		$this->responseCookies[$name]->path     = $path;
		$this->responseCookies[$name]->domain   = $domain;
		$this->responseCookies[$name]->secure   = $secure;
		$this->responseCookies[$name]->httpOnly = $httpOnly;
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
	 * Sets the HTTP status code of the response
	 * 
	 * @access public
	 * @param  integer $status 
	 * @return string
	 */
	public function setStatus($status)
	{
		if (!isset(self::$statusCodes[$status])) {
			throw new Exception('Unsupported HTTP status code');
		}

		$this->responseStatus = $status;
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

	private function sendResponseCookies()
	{
		foreach ($this->responseCookies as $name => $cookie) {
			setcookie($cookie->name, $cookie->value, $cookie->expire, $cookie->path,
				$cookie->domain, $cookie->secure, $cookie->httpOnly);
		}
	}

	/**
	 * Sends the HTTP status header of the response to the user
	 * 
	 * @access private
	 */
	private function sendStatusHeader()
	{
		$statusCode = $this->responseStatus;
		$statusText = self::$statusCodes[$statusCode];

		$statusHeader = "HTTP/1.1 {$statusCode} {$statusText}";
		header($statusHeader);
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

