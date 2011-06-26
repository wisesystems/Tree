<?php

namespace Tree\Framework;

use \Tree\Interfaces\HtmlResponseGenerator;

use \Tree\Response\Response;
use \Tree\Response\Response_Text;

class RequestHandler {

	private $configuration;

	private $router;

	public function handleRequest($request)
	{
		$requestUrl = $request->getUrl();

		$spec = $this->router->routeRequest($requestUrl);

		$action = $this->loadAction($spec);

		if (!$action->supportsResponseType($spec[2])) {

		}

		$return = $action->performAction();

		if ($return === true) {
			
			$response = $this->getResponseFromAction($action, $spec);

			if ($response instanceof Response) {
				$response->sendResponse();
				return true;
			}

		}
		

		return false;
	}

	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}

	public function setRouter($router)
	{
		$this->router = $router;
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
	}

}

