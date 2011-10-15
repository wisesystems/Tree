<?php

namespace Tree\Component;

use \Exception;
use \PDO;

use \Tree\Behaviour\HttpDeleteRequest;
use \Tree\Behaviour\HttpGetRequest;
use \Tree\Behaviour\HttpPostRequest;
use \Tree\Behaviour\HttpPutRequest;
use \Tree\Database\Connection_MySql;

/**
 * Action 
 *
 * Actions are the controllers of an application
 *
 * They are analagous to methods of Controller subclasses in many frameworks,
 * or alternatively to files such as 'view-profile.php' in old-fashioned
 * applications that use the filesystem to route requests.
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
abstract class Action {

	/**
	 * The Configuration instance containing the config values from the ini file
	 * 
	 * @access protected
	 * @var    \Tree\Framework\Configuration
	 */
	protected $configuration;

	/**
	 * An associative array mapping database connection IDs to their corresponding
	 * connections
	 * 
	 * @access protected
	 * @var    array
	 */
	protected $databases = array();

	/**
	 * An associative array mapping names of input values to those values
	 * 
	 * @access private
	 * @var    array
	 */
	private $parameters = array();

	/**
	 * The request router for the action subclass to use to generate URLS etc
	 * 
	 * @access private
	 * @var    \Tree\Framework\Router
	 */
	private $router;

	/**
	 * Returns the input value that has been stored against the given name 
	 * 
	 * @access public
	 * @param  string $name 
	 * @return mixed
	 */
	public function getParameter($name)
	{
		if (!isset($this->parameters[$name])) {
			return null;
		}

		return $this->parameters[$name];
	}

	/**
	 * Runs the main() method and returns its return value 
	 *
	 * @access public
	 * @param  string $requestMethod  GET, POST, etc
	 * @return mixed
	 */
	public function performAction($requestMethod)
	{
		switch ($requestMethod) {

			case 'DELETE':
				$methodName = 'delete';
				break;

			case 'GET':
				$methodName = 'get';
				break;

			case 'POST':
				$methodName = 'post';
				break;

			case 'PUT':
				$methodName = 'put';
				break;

		}

		// todo: throw unsupport method exception

		$output = call_user_func(
			array($this, $methodName),
			$this->parameters
		);

		return $output;
	}

	/**
	 * Stores the configuration values from the .ini file for later use
	 * 
	 * @access public
	 * @param  array \Tree\Framework\Configuration
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}

	/**
	 * Filters and stores the given input value under the given name 
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  mixed  $value 
	 * @return void
	 */
	public function setParameter($name, $value)
	{
		$this->parameters[$name] = $value;
	}

	/**
	 * Stores the router instance for the Action to use later
	 * 
	 * @access public
	 * @param  \Tree\Framework\Router $router 
	 */
	public function setRouter($router)
	{
		$this->router = $router;
	}

	/**
	 * Indicates whether the given HTTP request method is supported
	 * 
	 * @access public
	 * @param  string $method  'GET', 'POST', 'PUT', 'DELETE'
	 * @return boolean
	 */
	public function supportsMethod($method)
	{
		$supportsMethod = false;

		switch ($method) {

			case 'GET':
				$supportsMethod = $this instanceof HttpGetRequest;
				break;

			case 'POST': 
				$supportsMethod = $this instanceof HttpPostRequest;
				break;

			case 'PUT':
				$supportsMethod = $this instanceof HttpPutRequest;
				break;

			case 'DELETE':
				$supportsMethod = $this instanceof HttpDeleteRequest;
				break;

		}

		return $supportsMethod;
	}

	/**
	 * Lazy-loads and returns the database of the given name if it's 
	 * available in the configuration
	 * 
	 * @access protected
	 * @param  string $database 
	 * @return \Tree\Database\Connection
	 */
	protected function getDatabase($database)
	{
		if (isset($this->databases[$database])) {
			return $this->databases[$database];
		}

		if (!isset($this->configuration['database'])) {
			return null;
		}

		if (!isset($this->configuration['database'][$database])) {
			return null;
		}

		$vendor = $this->configuration['database'][$database]['vendor'];

		switch ($vendor) {

			case 'MySQL':
				$connection = new Connection_MySql;
				break;

			default:
				return null;

		}

		$connection->setIniValues($this->configuration['database'][$database]);

		$this->databases[$database] = $connection;

		return $connection;
	}

	/**
	 * Returns the request router
	 * 
	 * @access protected
	 * @return \Tree\Framework\Router
	 */
	protected function getRouter()
	{
		return $this->router;
	}

}

