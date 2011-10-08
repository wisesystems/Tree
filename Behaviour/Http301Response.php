<?php

namespace Tree\Behaviour;

/**
 * Http301Response 
 *
 * Interface for Action subclasses to declare that they can generate a response
 * when the HTTP response code is 301
 *
 * Actions should implement this interface if they ever intend to use 301
 * redirects
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface Http301Response {

	/**
	 * Return a response to send to the client when the action returns 200
	 * 
	 * @access public
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	public function get301Response($request);

}


