<?php

namespace Tree\Framework;

/**
 * RequestRouter 
 *
 * Creates a two-way mapping between requests and Action subclasses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Router {

	/**
	 * An array of route specifications of the following form:
	 *      array(
	 *              'request-pattern'    => '/article/{id}',
	 *              'regular-expression' => '|^/article/(?P<id>[^/]+)$|',
	 *              'action-id'          => 'ArticleView',
	 *              'action-parameters'  => array(),
	 *      )
	 * 
	 * @access private
	 * @var    array
	 */
	private $routes = array();

	/**
	 * A string to prefix to URLs when generating request URLs
	 * 
	 * @access private
	 * @var    string
	 */
	private $urlPrefix;

	/**
	 * Adds a route to the list of those to be considered when routing 
	 * or generating requests
	 * 
	 * @access public
	 * @param  string $requestPattern 
	 * @param  string $actionId 
	 * @param  string $response         The MIME type of the response
	 * @param  array  $actionParameters [optional]
	 */
	public function addRoute($requestPattern, $actionId, $response, array $actionParameters = array())
	{
		$regularExpression = $this->generateRegEx($requestPattern);
		$patternParameters = $this->parsePattern($requestPattern);

		$actionParameters = array_merge($patternParameters, $actionParameters);

		$this->routes[] = array(
			'request-pattern'    => $requestPattern,
			'regular-expression' => $regularExpression,
			'action-id'          => $actionId,
			'action-parameters'  => $actionParameters,
			'response-mimetype'  => $response,
		);

	}

	/**
	 * Maps a request path to an action 
	 *
	 * @access public
	 * @param  string $requestPath
	 * @return array
	 */
	public function getAction($requestPath)
	{
		$requestPath = trim($requestPath, '/');
		$requestPath = '/' . $requestPath;

		foreach ($this->routes as $route) {

			$requestPattern    = $route['request-pattern'];
			$regularExpression = $route['regular-expression'];
			$actionId          = $route['action-id'];
			$actionParameters  = $route['action-parameters'];
			$responseMimeType  = $route['response-mimetype'];

			if (preg_match($regularExpression, $requestPath, $matches)) {

				foreach ($matches as $name => $value) {
					if (!is_int($name)) {
						$actionParameters[$name] = $value;
					}
				}

				return array($actionId, $actionParameters, $responseMimeType);

			}

		}

		return null;
	}

	/**
	 * Maps an action to a request path 
	 * 
	 * @access public
	 * @param  string $actionId 
	 * @param  array  $actionParameters [optional]
	 * @return string
	 */
	public function getPath($actionId, array $actionParameters = array())
	{
		$parameterList = array_keys($actionParameters);

		foreach ($this->routes as $route) {

			$routePattern    = $route['request-pattern'];
			$routeActionId   = $route['action-id'];
			$routeParameters = $route['action-parameters'];

			if ($parameterList == array_keys($routeParameters)) {

				$path = $routePattern;
				$path = $this->injectParameters($path, $actionParameters);

				return $path;
			}

		}

		return null;
	}

	/**
	 * Maps an action to a request URL
	 *
	 * Requires a URL prefix to have been set using setUrlPrefix(), and
	 * will throw an exception if this has not been done.
	 * 
	 * @access public
	 * @param  string $actionId 
	 * @param  array  $actionParameters [optional]
	 * @return string
	 */
	public function getUrl($actionId, array $actionParameters = array())
	{
		if ($this->urlPrefix === null) {
			throw new Exception('No URL prefix set');
		}

		$path = $this->getPath($actionId, $actionParameters);
		if ($path === null) {
			return null;
		}

		$url = $this->urlPrefix . $path;

		return $url;
	}

	/**
	 * Routes a request URL to an action 
	 *
	 * Requires a URL prefix to have been set.
	 * 
	 * @access public
	 * @param  string $requestUrl 
	 * @return array
	 */
	public function routeRequest($requestUrl)
	{
		if ($this->urlPrefix === null) {
			throw new Exception('No URL prefix set');
		}
		
		if (strpos($requestUrl, $this->urlPrefix) !== 0) {
			return null;
		}

		$prefixLength = strlen($this->urlPrefix);
		$requestPath  = substr($requestUrl, $prefixLength);

		return $this->getAction($requestPath);
	}

	/**
	 * Sets a prefix to be prepended to request paths for generating
	 * request URLs in getUrl()
	 * 
	 * @access public
	 * @param  string $urlPrefix 
	 */
	public function setUrlPrefix($urlPrefix)
	{
		$this->urlPrefix = $urlPrefix;
	}

	/**
	 * Converts a request pattern into a regular expression
	 *
	 * Takes a human-readable request pattern such as '/article/{id}' and
	 * converts it into a regular expression with named capture.
	 * 
	 * @access private
	 * @param  string $requestPattern 
	 * @return string
	 */
	private function generateRegEx($requestPattern)
	{
		$requestPattern = trim($requestPattern, '/');
		$requestPattern = '/' . $requestPattern;

		$regEx = preg_replace(
			'|{([^}]+)}|',
			'(?P<$1>[^/]+)',
			$requestPattern
		);

		$regEx = "|^{$regEx}$|";

		return $regEx;
	}

	/**
	 * Inserts the given set of parameters into the given request pattern
	 * to generate a request path
	 *
	 * For example, given a string '/article/{id}' and an array('id'=>123),
	 * returns the string '/article/123'.
	 * 
	 * @access private
	 * @param  string $requestPattern 
	 * @param  array  $actionParameters 
	 * @return string
	 */
	private function injectParameters($requestPattern, $actionParameters)
	{
		foreach ($actionParameters as $name => $value) {
			$requestPattern = str_replace(
				'{' . $name . '}',
				$value,
				$requestPattern
			);
		}
		return $requestPattern;
	}

	/**
	 * Returns an array listing the value names in the given request
	 * pattern
	 *
	 * For example, given the pattern '/article/{id}', returns the array
	 * [{ id : null}]
	 * 
	 * @access private
	 * @param  string $requestPattern 
	 * @return array
	 */
	private function parsePattern($requestPattern)
	{
		if (!preg_match_all('/\{([^{}]+)\}/', $requestPattern, $matches)) {
			return array();
		}

		return array_fill_keys($matches[1], null);
	}

}

