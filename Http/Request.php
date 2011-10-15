<?php

namespace Tree\Http;

/**
 * Request 
 * 
 * Models the requests that the application receives
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Http
 * @version    0.00
 */
class Request {

	/**
	 * Returns the given HTTP request header
	 * 
	 * @access public
	 * @param  string $name 
	 * @return string
	 */
	public function getHeader($name)
	{
		$headers = getallheaders();

		if (isset($headers[$name])) {
			return $headers[$name];
		}

		return null;
	}

	/**
	 * Returns the URL of the request 
	 * 
	 * @access public
	 * @return string
	 */
	public function getUrl()
	{
		$scheme   = 'http';
		$hostname = filter_input(INPUT_SERVER, 'HTTP_HOST');
		$port     = filter_input(INPUT_SERVER, 'SERVER_PORT');
		$path     = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$query    = filter_input(INPUT_SERVER, 'QUERY_STRING');

		$path = parse_url($path, PHP_URL_PATH);

		if (filter_input(INPUT_SERVER, 'HTTPS') != null) {
			$scheme = 'https';
		}

		$url = "{$scheme}://{$hostname}";

		if (($scheme == 'http' && $port != 80) || ($scheme == 'https' && $port != 43)) {
			$url .= ":{$port}";
		}

		$url .= $path;

		if ($query != '') {
			$url .= "?{$query}";
		}

		return $url;
	}

}

