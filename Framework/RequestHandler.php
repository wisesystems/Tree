<?php

namespace Tree\Framework;

use \Tree\Behaviour\HttpGetRequest;
use \Tree\Behaviour\Http200Response;
use \Tree\Behaviour\Http301Response;
use \Tree\Behaviour\Http302Response;
use \Tree\Behaviour\Http404Response;
use \Tree\Behaviour\Http403Response;
use \Tree\Behaviour\Http500Response;
use \Tree\Component\Action;
use \Tree\Http\Response;
use \Tree\Http\Response_Html;
use \Tree\Http\Response_Text;

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
 * @uses       \Tree\Http\Request
 * @uses       \Tree\Http\Response
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
	 * @param  \Tree\Http\Request $request 
	 * @return \Tree\Http\Response
	 */
	public function handleRequest($request)
	{
		$requestMethod = $request->getMethod();
		$requestUrl    = $request->getUrl();

		$action = $this->router->getAction($requestUrl);

		if (is_null($action)) {
			// this is a request whose URL doesn't match any of the patterns in these
			// router, the simplest kind of 404
			$status = 404;

		} elseif (!$action->supportsMethod($requestMethod)) {
			$status = 403;
		} else {
			$action->setConfiguration($this->configuration);
			$action->setRouter($this->router);

			$status = $action->performAction($requestMethod);
		}


		switch ($status) {

			// actions return null to indicate that there is nothing to return, i.e.
			// the given parameters don't correspond to any entity, which is a 404
			case null: return $this->handle404($request, $action);

			// actions return false to indicate that some unexpected error has
			// prevented them from completing, which is an internal server error
			case false: return $this->handle500($request, $action);

			case 200: return $this->handle200($request, $action);
			case 301: return $this->handle301($request, $action);
			case 302: return $this->handle302($request, $action);
			case 404: return $this->handle404($request, $action);
			case 403: return $this->handle403($request, $action);
			case 500: return $this->handle500($request, $action);

			// if none of the above is true, something's gone very wrong
			default: return $this->handle500($request, $action);

		}
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
	 * @param  \Tree\Http\Request  $request 
	 * @param  \Tree\Component\Action $action
	 * @return \Tree\Http\Response
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
	 * Returns a response suitable for sending when the action has returned 301
	 * indicating that the resource requested has moved
	 * 
	 * @access private
	 * @param  \Tree\Http\Request  $request 
	 * @param  \Tree\Component\Action $action 
	 * @return \Tree\Http\Response
	 */
	private function handle301($request, $action)
	{
		if ($action instanceof Action && $action instanceof Http301Response) {
			$response = $action->get301Response($request);
		} else {
			$response = $this->handle500($request, $action);
		}

		return $response;
	}

	/**
	 * Returns a response suitable for sending when the action has returned 302
	 * indicating that the resource requested has been found at a different URL
	 * 
	 * @access private
	 * @param  \Tree\Request\Request  $request 
	 * @param  \Tree\Component\Action $action 
	 * @return \Tree\Response\Response
	 */
	private function handle302($request, $action)
	{
		if ($action instanceof Action && $action instanceof Http302Response) {
			$response = $action->get302Response($request);
		} else {
			$response = $this->handle500($request, $action);
		}

		return $response;
	}

	/**
	 * Returns a response suitable for sending when the request method is not
	 * allowed
	 * 
	 * @access private
	 * @param  \Tree\Http\Request  $request 
	 * @param  \Tree\Component\Action $action
	 * @return \Tree\Http\Response
	 */
	private function handle403($request, $action)
	{
		$response = new Response_Html;
		$response->setStatus(403);
		$response->setBody('<h1>403 Method Not Allowed</h1>');

		return $response;
	}


	/**
	 * Returns a response suitable for sending when the request resource cannot
	 * be found
	 * 
	 * @access private
	 * @param  \Tree\Http\Request  $request 
	 * @param  \Tree\Component\Action $action
	 * @return \Tree\Http\Response
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
	 * @param  \Tree\Http\Request  $request 
	 * @param  \Tree\Component\Action $action
	 * @return \Tree\Http\Response
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
	 * @param  \Tree\Component\Action
	 */
	private function configureAction($action)
	{
	}

}

