<?php

namespace Tree\Framework;

/**
 * Router 
 *
 * Creates a two-way mapping between request URLs and Actions
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
	 * An array of Route objects against which to test URLs and Action
	 * specifications
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
	 * Adds a route to the list of those to be tested against when routing
	 * requests
	 * 
	 * @access public
	 * @param  \Tree\Framework\Route $route 
	 */
	public function addRoute($route)
	{
		$this->routes[] = $route;
	}

	/**
	 * Attempts to find an Action that corresponds to the given URL according to
	 * the list of routes
	 * 
	 * @access public
	 * @param  string $url 
	 * @return \Tree\Component\Action
	 */
	public function getAction($url)
	{
		$action = null;

		if (strpos($url, $this->urlPrefix) === 0) {

			$prefixLength = strlen($this->urlPrefix);
			$requestPath  = substr($url, $prefixLength);

			foreach ($this->routes as $route) {

				if ($route->matchesPath($requestPath)) {
					$action = $route->getAction($requestPath);
					break;
				}
			}
		}
		
		return $action;
	}

	/**
	 * Attempts to generate a URL corresponding the given action specification
	 * according to the list of routes
	 * 
	 * @access public
	 * @param  string $actionId 
	 * @param  array  $parameters 
	 * @return string
	 */
	public function getUrl($actionId, array $parameters)
	{
		$url = null;

		foreach ($this->routes as $route) {
			$path = $route->getPath($actionId, $parameters);

			if ($path !== null) {
				$url = $this->urlPrefix . $path;
				break;
			}
		}

		return $url;
	}
	
	/**
	 * Sets a prefix to be prepended to request paths when testing URLs and action
	 * specifications against the list of routes
	 * 
	 * @access public
	 * @param  string $urlPrefix 
	 */
	public function setUrlPrefix($urlPrefix)
	{
		$this->urlPrefix = $urlPrefix;
	}

}

