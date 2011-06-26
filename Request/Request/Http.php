<?php

namespace Tree\Request;

/**
 * Request_Http 
 *
 * Models aspects of requests that are specific to those that come via HTTP
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Request
 * @uses       \Tree\Request\Request
 * @version    0.00
 */
class Request_Http extends Request {

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

