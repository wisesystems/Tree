<?php

namespace Tree\Behaviour;

/**
 * HttpPostRequest 
 * 
 * Interface for Action subclasses to support processing HTTP requests whose
 * request method is POST
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @package    Tree
 * @subpackage Behaviour
 * @license    GPLv2.0
 * @version    0.00
 */
interface HttpPostRequest {

	/**
	 * Process a POST request and return a HTTP response code such as 200 or 404
	 * 
	 * @access public
	 * @param  array $input 
	 * @return integer
	 */
	public function post(array $input);

}

