<?php

namespace Tree\Framework;

use \Tree\Interfaces\HtmlResponseGenerator;

use \Tree\Response\Response;
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
 * @uses       \Tree\Request\Request;
 * @uses       \Tree\Response\Response;
 * @uses       \Tree\Interfaces\HtmlResponseGenerator;
 * @uses       \Tree\Interfaces\JsonResponseGenerator;
 * @uses       \Tree\Interfaces\TextResponseGenerator;
 * @version    0.00
 */
class RequestHandler {

	private $configuration;

	private $router;

	public function handleRequest($request)
	{
		$requestUrl = $request->getUrl();

		$spec = $this->router->routeRequest($requestUrl);

		if (is_null($spec)) {
			// this is a request whose URL doesn't match any of the patterns in these
			// router, the simplest kind of 404
			return $this->handle404($request);
		}

		$action = $this->loadAction($spec);

		if (!$action->supportsResponseType($spec[2])) {
			// requests shouldn't be being routed to actions that can't provide a
			// response in the right format, this is a configuration problem
			return $this->handle500($request);
		}

		$return = $action->performAction();

		if (is_null($return)) {
			// actions return null to indicate that there is nothing to return, i.e.
			// the given parameters don't correspond to any entity, which is a 404
			return $this->handle404($request);
		}

		if ($return === false) {
			// actions return false to indicate that some unexpected error has
			// prevented them from completing, which is an internal server error
			return $this->handle500($request);
		}

		if ($return === true) {

			$response = $this->getResponseFromAction($action, $spec);

			if ($response instanceof Response) {
				return $response;
			} else {
				return $this->handle500($request);
			}

		}

		// at this point the only possible state is that the action returned
		// something other than true, false or null, which is wrong
		return $this->handle500($request);
	}

	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}

	public function setRouter($router)
	{
		$this->router = $router;
	}

	private function handle404($request)
	{
	}

	private function handle500($request)
	{
	}

	private function loadAction(array $spec)
	{
		$actionClass  = $spec[0];
		$parameters   = $spec[1];
		$responseType = $spec[2];

		$action = new $actionClass;

		foreach ($parameters as $name => $value) {
			$action->setInputValue($name, $value);
		}

		return $action;
	}

	private function getResponseFromAction($action, array $spec)
	{
		if ($spec[2] === 'text/html') {
			return $action->getHtmlResponse();
		}

		if ($spec[2] === 'text/plain') {
			return $action->getTextResponse();
		}

		if ($spec[2] === 'application/json') {
			return $action->getJsonResponse();
		}
		
		return null;
	}

}

