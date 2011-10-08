<?php

namespace Tree\Framework;

use \Tree\Component\Template;
use \Tree\Request\Request;
use \Tree\Request\Request_Http;
use \Tree\Response\Response;

/**
 * Application
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
class Application {

	/**
	 * The absolute path to the INI file containing the application's
	 * configuration
	 * 
	 * @access private
	 * @var    string
	 */
	private $iniFile;

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
	 * @var    \Tree\Framework\Configuration
	 */
	private $configuration;

	/**
	 * The configurator for applying config values to the framework
	 * 
	 * @access private
	 * @var    \Tree\Framework\Configurator
	 */
	private $configurator;

	/**
	 * The request handler for generating responses to requests
	 * 
	 * @access private
	 * @var    \Tree\Framework\RequestHandler
	 */
	private $requestHandler;

	/**
	 * The router for mapping request URLs to actions
	 * 
	 * @access private
	 * @var    \Tree\Framework\Router
	 */
	private $router;

	/**
	 * @access public
	 * @param  string $iniFile 
	 */
	public function __construct($iniFile)
	{
		$this->iniFile = $iniFile;
	}

	/**
	 * The overall flow of control of the framework
	 * 
	 * @access public
	 */
	public function run()
	{
		require_once 'Tree/Framework/Autoloader.php';
		$this->autoloader = $this->getAutoloader();
		$this->autoloader->registerAutoloader();
		

		$configurator = $this->getConfigurator();
		$this->configurator->configureTemplate('\Tree\Component\Template');


		$request        = $this->detectRequest();
		$requestHandler = $this->getRequestHandler();
		$response       = $requestHandler->handleRequest($request);


		if ($response instanceof Response) {
			$response->sendResponse();
		}

	}

	/**
	 * Returns the class autoloader
	 * 
	 * @access private
	 * @return \Tree\Framework\Autoloader
	 */
	private function getAutoloader()
	{
		if ($this->autoloader === null) {
			$this->autoloader = new Autoloader;
		}

		return $this->autoloader;
	}

	/**
	 * Returns an object providing access to the values in the config file
	 * 
	 * @access private
	 * @return \Tree\Framework\Configuration
	 */
	private function getConfiguration()
	{
		if ($this->configuration === null) {
			$this->configuration = new Configuration($this->iniFile);
		}

		return $this->configuration;
	}

	/**
	 * Returns an object that applies the values in the config file to the
	 * framework
	 * 
	 * @access private
	 * @return \Tree\Framework\Configurator
	 */
	private function getConfigurator()
	{
		if ($this->configurator === null) {
			$configuration = $this->getConfiguration();
			$configuration = $configuration->toArray();

			$this->configurator = new Configurator($configuration);
		}

		return $this->configurator;
	}

	/**
	 * Returns an object that takes a request and returns a response to it
	 * 
	 * @access private
	 * @return \Tree\Framework\RequestHandler
	 */
	private function getRequestHandler()
	{
		if ($this->requestHandler === null) {

			$configuration = $this->getConfiguration();
			$router        = $this->getRouter();

			$this->requestHandler = new RequestHandler;

			$this->requestHandler->setConfiguration($configuration);
			$this->requestHandler->setRouter($router);
		}
		
		return $this->requestHandler;
	}

	/**
	 * Returns an object that maps request URLs to actions
	 * 
	 * @access private
	 * @return \Tree\Framework\Router
	 */
	private function getRouter()
	{
		if ($this->router === null) {

			$configurator = $this->getConfigurator();

			$this->router = new Router;

			$configurator->configureRouter($this->router, '\Tree\Framework\Route');
		}

		return $this->router;
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

