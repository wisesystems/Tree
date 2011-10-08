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


		require_once 'Tree/Framework/Autoloader.php';
		$this->autoloader = new Autoloader;
		$this->autoloader->registerAutoloader();
		

		$this->configuration  = new Configuration($iniFile);
		$this->configurator   = new Configurator($this->configuration->toArray());
		$this->requestHandler = new RequestHandler;
		$this->router         = new Router;


		$this->configurator->configureRouter($this->router);
		$this->configurator->configureTemplate('\Tree\Component\Template');


		$this->requestHandler->setConfiguration($this->configuration);
		$this->requestHandler->setRouter($this->router);


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

