<?php

namespace Tree\Framework;

use \Tree\Behaviour\Http200Response;
use \Tree\Behaviour\Http404Response;
use \Tree\Behaviour\Http403Response;
use \Tree\Behaviour\Http500Response;
use \Tree\Component\Action;
use \Tree\Response\Response;
use \Tree\Response\Response_Html;
use \Tree\Response\Response_Text;

/**
 * RequestHandler 
 *
 * Receives a request, returns a response
 *
 * This class is in charge of the process of loading an action, running it,
 * and returning a response, with all the associated error handling along the
 * way.
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @package    Tree
 * @subpackage Framework
 * @license    GPLv2.0
 * @uses       \Tree\Framework\Configuration
 * @uses       \Tree\Framework\Router
 * @uses       \Tree\Request\Request
 * @uses       \Tree\Response\Response
 * @uses       \Tree\Component\Action_HtmlResponseGenerator
 * @uses       \Tree\Component\Action_JsonResponseGenerator
 * @uses       \Tree\Component\Action_TextResponseGenerator
 * @version    0.00
 */
class RequestHandler {

	/**
	 * The Configuration instance for configuring Actions
	 * 
	 * @access private
	 * @var    \Tree\Framework\Configuration
	 */
	private $configuration;

	/**
	 * The Router instance for mapping URLs to Actions
	 * 
	 * @access private
	 * @var    \Tree\Framework\Router
	 */
	private $router;

	/**
	 * Returns a Response corresponding to the given Request
	 * 
	 * @access public
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	public function handleRequest($request)
	{
		$requestUrl = $request->getUrl();

		$spec = $this->router->routeRequest($requestUrl);

		if (is_null($spec)) {
			// this is a request whose URL doesn't match any of the patterns in these
			// router, the simplest kind of 404
			return $this->handle404($request, null);
		}

		$action = $this->loadAction($spec);

		$return = $action->performAction();

		if (is_null($return)) {
			// actions return null to indicate that there is nothing to return, i.e.
			// the given parameters don't correspond to any entity, which is a 404
			return $this->get404Response($request, $action);
		}

		if ($return === false) {
			// actions return false to indicate that some unexpected error has
			// prevented them from completing, which is an internal server error
			return $this->get500Response($request, $action);
		}

		if ($return === 200) {
			return $this->handle200($request, $action);
		} elseif ($return === 404) {
			return $this->handle404($request, $action);
		} elseif ($return === 500) {
			return $this->handle500($request, $action);
		}


		// at this point the only possible state is that the action returned
		// something other than true, false or null, which is wrong
		return $this->handle500($request, $action);
	}

	/**
	 * Sets the Configuration instance to be used to configure Actions
	 * 
	 * @access public
	 * @param  \Tree\Framework\Configuration $configuration 
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}

	/**
	 * Sets the Router instance to be used to map URLs to Actions
	 * 
	 * @access public
	 * @param  \Tree\Framework\Router $router 
	 */
	public function setRouter($router)
	{
		$this->router = $router;
	}

	/**
	 * Returns a response suitable for sending when the action has been completed
	 * successfully
	 * 
	 * @access private
	 * @param  \Tree\Request\Request $request 
	 * @param  \Tree\Component\Action
	 * @return \Tree\Response\Response
	 */
	private function handle200($request, $action)
	{
		if ($action instanceof Action && $action instanceof Http200Response) {
			$response = $action->get200Response($request);
		} else {
			$response = new Response_Html;
			$response->setStatus(200);
			$response->setBody('<h1>200 OK</h1>');
		}

		return $response;
	}

	/**
	 * Returns a response suitable for sending when the request resource cannot
	 * be found
	 * 
	 * @access private
	 * @param  \Tree\Request\Request $request 
	 * @param  \Tree\Component\Action
	 * @return \Tree\Response\Response
	 */
	private function handle404($request, $action)
	{
		if ($action instanceof Action && $action instanceof Http404Response) {
			$response = $action->get404Response($request);
		} else {
			$response = new Response_Html;
			$response->setStatus(404);
			$response->setBody('<h1>404 File Not Found</h1>');
		}

		return $response;
	}

	/**
	 * Returns a response suitable for sending when there has been an internal
	 * server error
	 * 
	 * @access private
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	private function handle500($request, $action)
	{
		if ($action instanceof Action && $action instanceof Http500Response) {
			$response = $action->get500Response($request);
		} else {
			$response = new Response_Html;
			$response->setStatus(500);
			$response->setBody('<h1>500 Internal Server Error</h1>');
		}

		return $response;
	}

	/**
	 * Loads and returns the Action subclass defined by the given route
	 * specification
	 * 
	 * @access private
	 * @param  array $spec 
	 * @return Action
	 */
	private function loadAction(array $spec)
	{
		$actionClass  = $spec[0];
		$parameters   = $spec[1];

		$action = new $actionClass;

		foreach ($parameters as $name => $value) {
			$action->setInputValue($name, $value);
		}

		$action->setConfiguration($this->configuration);

		return $action;
	}

}

