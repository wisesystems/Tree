<?php

namespace Tree\Framework;

/**
 * Configurator 
 *
 * Applies the configuration values from the INI values to the classes and
 * objects that they configure
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Configurator {

	/**
	 * An array containing the raw config valus
	 * 
	 * @access private
	 * @var    array
	 */
	private $values;

	/**
	 * @access public
	 * @param  array $configuration 
	 */
	public function __construct(array $configuration)
	{
		$this->values = $configuration;
	}

	/**
	 * Configures the given request router object
	 * 
	 * @access public
	 * @param  \Tree\Framework\Router $router 
	 */
	public function configureRouter($router)
	{
		if (isset($this->values['router']['urlprefix'])) {
			$router->setUrlPrefix($this->values['router']['urlprefix']);
		}

		if (isset($this->values['routes'])) {

			foreach ($this->values['routes'] as $name => $parameters) {

				switch (false) {
					case isset($parameters['action']):
					case isset($parameters['pattern']):
						continue;
				}

				$action  = $parameters['action'];
				$pattern = $parameters['pattern'];
				$route   = new Route($pattern, $action);

				$router->addRoute($route);
			}

		}
	}

	/**
	 * Configures the given template class
	 * 
	 * @access public
	 * @param  \Tree\Component\Template
	 */
	public function configureTemplate($templateClass)
	{
		if (isset($this->values['template']['directory'])) {
			$templateClass::setTemplateDirectory($this->values['template']['directory']);
		}

		if (isset($this->values['template']['globals']) && is_array($this->values['template']['globals'])) {
			foreach ($this->values['template']['globals'] as $name => $value) {
				
				$templateClass::setGlobalValue($name, $value);

			}
		}

	}

}

