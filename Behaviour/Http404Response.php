<?php

namespace Tree\Behaviour;

/**
 * Http404Response 
 *
 * Interface for Action subclasses to declare that they can generate a response
 * when the HTTP response code is 404
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface Http404Response {


	/**
	 * Return a response to send to the client when the action returns 404
	 * 
	 * @access public
	 * @param  \Tree\Request\Request $request 
	 * @return \Tree\Response\Response
	 */
	public function get404Response($request);

}


