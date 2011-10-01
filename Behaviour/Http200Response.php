<?php

namespace Tree\Behaviour;

/**
 * Http200Response 
 *
 * Interface for Action subclasses to declare that they can generate a response
 * when the HTTP response code is 404
 *
 * Almost all actions should implement this interface as 200 is the HTTP code
 * for a successful request, so clients will be expecting an appropriate
 * response
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface Http200Response {

	/**
	 * Return a response to send to the client when the action returns 200
	 * 
	 * @access public
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	public function get200Response($request);

}

