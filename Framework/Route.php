<?php

namespace Tree\Framework;

/**
 * Route 
 *
 * Defines a mapping between an action and a pattern
 *
 * Patterns are a simple form of pseudo-regex, against which URL paths are
 * checked. If the path matches the pattern, then the request is routed to that
 * action.
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Route {

	/**
	 * The name of the route
	 *
	 * This isn't important data, but since the configuration file syntax allows
	 * for each route to have a name, there's no point wasting this information.
	 * If a route is found to be invalid, it may be helpful to be able to refer to
	 * it by name to aid in debugging.
	 * 
	 * @access private
	 * @var    string
	 */
	private $name;

	/**
	 * The pattern against which paths will be checked
	 *
	 * Patterns are strings of the form '/example/{id}', where the part '{id}' is
	 * essentially a named wildcard.
	 * 
	 * @access private
	 * @var    string
	 */
	private $pattern;

	/**
	 * The name of the subclass of \Tree\Component\Action which will be returned 
	 * if a path matches the pattern
	 * 
	 * @access private
	 * @var    string
	 */
	private $actionId;

	/**
	 * A list of the paramter names found in the pattern given to the constructor
	 * 
	 * @access private
	 * @var    array
	 */
	private $parameterNames = array();

	/**
	 * An associative array mapping parameter names to regular expressions
	 *
	 * These regular expressions are used to fine-tune the set of strings that can
	 * match each parameter in the pattern string.
	 * 
	 * @access private
	 * @var    array
	 */
	private $parameterPatterns = array();

	/**
	 * An associative array mapping tested paths to the sets of parameter values
	 * that were found in them, if any
	 *
	 * The purpose of storing this data is to avoid having to re-perform the
	 * expensive pattern matching process once a path has been tested once.
	 * 
	 * @access private
	 * @var    array
	 */
	private $matchHistory = array();

	/**
	 * @access public
	 * @param  string $pattern  The request pattern to test paths against
	 * @param  string $actionId The name of the action class to return
	 * @param  string $name     [optional] 
	 */
	public function __construct($pattern, $actionId, $name = null)
	{
		$this->name     = $name;
		$this->pattern  = $pattern;
		$this->actionId = $actionId;

		$this->parameterNames = $this->extractParameters($pattern);
	}

	/**
	 * Returns an Action subclass configured with parameters extracted from the
	 * given $path according to the pattern given to the constructor
	 * 
	 * @param  string $path 
	 * @return mixed
	 */
	public function getAction($path)
	{
		$action = null;

		if ($this->matchesPath($path)) {

			$action = new $this->actionId;

			foreach ($this->matchHistory[$path] as $name => $value) {
				$action->setParameter($name, $value);
			}

		}

		return $action;
	}

	/**
	 * Returns a path string that routes to the route's action, if the given
	 * action name and parameters match those of the route
	 *
	 * For example, if the route's pattern is '/users/{username}' and its action
	 * is 'Action_UserView', then given the parameters 'Action_UserView' and 
	 * ["username":"root"], the returned value would be '/users/root'.
	 * 
	 * @access public
	 * @param  string $actionId 
	 * @param  array  $parameters 
	 * @return string
	 */
	public function getPath($actionId, array $parameters)
	{
		if ($actionId !== $this->actionId) {
			return null;
		}

		$parameterNames = array_keys($parameters);

		if ($parameterNames !== $this->parameterNames) {
			return null;
		}

		foreach ($parameters as $name => $value) {
			if (!isset($this->parameterPatterns[$name])) {
				continue;
			}

			$pattern = $this->parameterPatterns[$name];

			if (!preg_match($pattern, $value)) {
				return null;
			}

		}

		$path = $this->injectParameters($this->pattern, $parameters);

		return $path;
	}

	/**
	 * Indicates whether the given path matches the route's pattern
	 * 
	 * @access public
	 * @param  string $path 
	 * @return boolean
	 */
	public function matchesPath($path)
	{
		if (!isset($this->matchHistory[$path])) {

			$regEx = $this->getRegularExpression();

			if (preg_match("|^{$regEx}$|", $path, $matches)) {

				$this->parameterValues[$path] = array();

				$this->matchHistory[$path] = array();

				foreach ($matches as $name => $value) {
					if (!is_integer($name)) {
						$this->matchHistory[$path][$name] = $value;
					}
				}

			} else {

				$this->matchHistory[$path] = null;
			}

		}

		return is_array($this->matchHistory[$path]);
	}

	/**
	 * Stores a regular expression for the given parameter, such that paths will
	 * not be considered to match unless the values for each parameter match the 
	 * patterns given here
	 * 
	 * @access public
	 * @param  string $parameterName 
	 * @param  string $pattern 
	 */
	public function setParameterPattern($parameterName, $pattern)
	{
		$this->matchHistory = array();
		$this->parameterPatterns[$parameterName] = $pattern;
	}

	/**
	 * Returns a list of parameter names found in the given pattern string
	 *
	 * For example, given the string '/example/{id}', returns the array("id")
	 * 
	 * @param  string $pattern 
	 * @return array
	 */
	private function extractParameters($pattern)
	{
		if (!preg_match_all('/\{([^{}]+)\}/', $pattern, $matches)) {
			return array();
		}

		return $matches[1];
	}

	private function injectParameters($pattern, array $parameters)
	{
		foreach ($parameters as $name => $value) {
			$pattern = str_replace(
				'{' . $name . '}',
				$value,
				$pattern
			);
		}
		return $pattern;
	}

	/**
	 * Returnst the regular expression to be used by the route to determine
	 * whether or not a given path matches its pattern
	 *
	 * @access private
	 * @return string
	 */
	private function getRegularExpression()
	{
		$pattern = $this->pattern;
		$pattern = trim($pattern, '/');
		$pattern = '/' . $pattern;

		$expression = preg_replace_callback(
			'|{([^}]+?)}|',
			array($this, 'tokenCallback'),
			$pattern
		);

		return $expression;
	}

	/**
	 * Used by getRegularExpression to generate the part of the regular
	 * expression that will determine if the part of the path corresponding to a
	 * parameter is a valid match for that parameter
	 *
	 * @access private
	 * @param  array $matches 
	 * @return string
	 */
	private function tokenCallback($matches)
	{
		$token = $matches[0];
		$name  = $matches[1];

		if (isset($this->parameterPatterns[$name])) {
			$pattern = $this->parameterPatterns[$name];
		} else {
			$pattern = '[^/]+';
		}

		$format = '(?P<%s>%s)';
		$string = sprintf($format, $name, $pattern);

		return $string;
	}

}
