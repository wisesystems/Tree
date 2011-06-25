<?php

namespace Tree\Framework;

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
	 */
	public static function main()
	{
		$tree = new self;
		$tree->runFramework();
	}

	private $autoloader;

	private $configuration;

	private $requestHandler;

	private $router;

	public function runFramework()
	{
		if (!$this->includePathContainsTree()) {
			return false;
		}

		$this->loadDependencies();
		$this->configureDependencies();

		echo '<pre>';
		print_r($this);

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

	private function loadDependencies()
	{
		require_once 'Tree/Framework/Autoloader.php';
		$this->autoloader = new Autoloader;
		$this->autoloader->registerAutoloader();

		$this->configuration  = new Configuration('Tree.ini');
		$this->requestHandler = new RequestHandler;
		$this->router         = new Router;

	}

	private function configureDependencies()
	{
		if (isset($this->configuration['router'])) {
			$this->configureRouter($this->configuration['router']);
		}

		if (isset($this->configuration['routes'])) {
			$this->configureRoutes($this->configuration['routes']);
		}

		$this->configureRequestHandler();
	}

	private function configureRouter(array $config)
	{
		if (isset($config['urlprefix'])) {
			$this->router->setUrlPrefix($config['urlprefix']);
		}
	}

	private function configureRoutes(array $routes)
	{
		foreach ($routes as $name => $route) {

			$action  = $route['action'];
			$pattern = $route['pattern'];
			
			if (isset($route['parameters'])) {
				$parameters = $route['parameters'];
			} else {
				$parameters = array();
			}

			$this->router->addRoute($pattern, $action, $parameters);
		}
	}

	private function configureRequestHandler()
	{
		$this->requestHandler->setConfiguration($this->configuration);
		$this->requestHandler->setRouter($this->router);
	}

}

