<?php

namespace Tree\Framework;

use \Tree\Component\Template;
use \Tree\Request\Request;
use \Tree\Request\Request_Http;
use \Tree\Response\Response;

/**
 * Tree 
 *
 * Manages the top-level flow of control
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Tree {

	/**
	 * Runs the application
	 *
	 * This is essentially a wrapper to keep as much actual code as possible away
	 * from publicly accessible directories.
	 * 
	 * @static
	 * @access public
	 * @param  string $iniFile  The name of the INI file to configure the app
	 */
	public static function main($iniFile)
	{
		$tree = new self;
		$tree->runFramework($iniFile);
	}

	/**
	 * The class autoloader
	 * 
	 * @access private
	 * @var    \Tree\Framework\Autoloader
	 */
	private $autoloader;

	/**
	 * The Configuration class containing the config values from the ini file
	 * 
	 * @access private
	 * @var    \Tree\Configuration\Configuration
	 */
	private $configuration;

	/**
	 * The request handler for generating responses to requests
	 * 
	 * @access private
	 * @var    \Tree\Framework\RequestHandler
	 */
	private $requestHandler;

	private $router;

	/**
	 * The overall flow of control of the framework
	 * 
	 * @access public
	 * @param  string $iniFile
	 */
	public function runFramework($iniFile)
	{
		if (!$this->includePathContainsTree()) {
			return false;
		}

		$this->loadDependencies($iniFile);
		$this->configureDependencies();

		$request  = $this->detectRequest();
		$response = $this->requestHandler->handleRequest($request);

		if ($response instanceof Response) {
			$response->sendResponse();
		}

	}

	/**
	 * Indicates whether the PHP include_path has been correctly set up so that
	 * Tree is actually available to load
	 * 
	 * @access private
	 * @return boolean
	 */
	private function includePathContainsTree()
	{
		$includePath = get_include_path();
		$includePath = explode(PATH_SEPARATOR, $includePath);

		$treeDirectory = __DIR__;
		$treeDirectory = dirname($treeDirectory);
		$treeDirectory = dirname($treeDirectory);

		return in_array($treeDirectory, $includePath);
	}

	/**
	 * Instantiates all framework classes needed to bootstrap the framework
	 * 
	 * @access private
	 */
	private function loadDependencies($iniFile)
	{
		require_once 'Tree/Framework/Autoloader.php';
		$this->autoloader = new Autoloader;
		$this->autoloader->registerAutoloader();

		$this->configuration  = new Configuration($iniFile);
		$this->requestHandler = new RequestHandler;
		$this->router         = new Router;
	}

	/**
	 * Applies configuration values from the ini file to the components of the
	 * framework
	 * 
	 * @access private
	 */
	private function configureDependencies()
	{
		if (isset($this->configuration['router'])) {
			$this->configureRouter($this->configuration['router']);
		}

		if (isset($this->configuration['routes'])) {
			$this->configureRoutes($this->configuration['routes']);
		}

		if (isset($this->configuration['template'])) {
			$this->configureTemplate($this->configuration['template']);
		}

		$this->configureRequestHandler();
	}

	/**
	 * Applies the ini config values to the request router
	 * 
	 * @access private
	 * @param  array $config 
	 */
	private function configureRouter(array $config)
	{
		if (isset($config['urlprefix'])) {
			$this->router->setUrlPrefix($config['urlprefix']);
		}
	}

	/**
	 * Sets up the request routes from the config file
	 * 
	 * @access private
	 * @param  array $routes 
	 */
	private function configureRoutes(array $routes)
	{
		foreach ($routes as $name => $route) {

			$action   = $route['action'];
			$pattern  = $route['pattern'];

			$route = new Route($pattern, $action);

			foreach ($route as $name => $value) {
				switch ($name) {
					case 'action':
						continue;
					case 'pattern':
						continue;
					default:
						// todo : reenable this extra-parameters feature
						//$parameters[$name] = $value;
				}
			}
		
			$this->router->addRoute($route);
		}
	}

	/**
	 * Applies the ini config values to the request router and injects
	 * dependencies
	 * 
	 * @access private
	 */
	private function configureRequestHandler()
	{
		$this->requestHandler->setConfiguration($this->configuration);
		$this->requestHandler->setRouter($this->router);
	}

	/**
	 * Applies the ini config values to the Template class
	 * 
	 * @access private
	 * @param  array $config 
	 */
	private function configureTemplate(array $config)
	{
		if (isset($config['directory'])) {
			Template::setTemplateDirectory($config['directory']);
		}

		if (isset($config['globals']) && is_array($config['globals'])) {
			foreach ($config['globals'] as $name => $value) {
				
				Template::setGlobalValue($name, $value);

			}
		}
	}

	/**
	 * Detects the type of request that has been received and instantiates the
	 * corresponding subclass of Request
	 * 
	 * @access private
	 * @return \Tree\Request\Request
	 */
	private function detectRequest()
	{
		return new Request_Http;
	}

}

