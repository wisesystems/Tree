<?php

namespace Tree\Behaviour;

/**
 * Http302Response 
 *
 * Interface for Action subclasses to declare that they can generate a response
 * when the HTTP response code is 302
 *
 * Actions should implement this interface if they ever intend to use 302
 * redirects
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface Http302Response {

	/**
	 * Return a response to send to the client when the action returns 302
	 * 
	 * @access public
	 * @param  \Tree\Http\Request $request 
	 * @return \Tree\Http\Response
	 */
	public function get302Response($request);

}



