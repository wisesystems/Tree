<?php

namespace Tree\Behaviour;

/**
 * Http500Response 
 *
 * Interface for Action subclasses to declare that they can generate a response
 * when the HTTP response code is 500
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface Http500Response {

	/**
	 * Return a response to send to the client when the action returns 500
	 * 
	 * @access public
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	public function get500Response($request);

}


