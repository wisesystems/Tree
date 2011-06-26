<?php

namespace Tree\Request;

/**
 * Request 
 * 
 * Base class for modelling the requests that the application receives
 *
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Request
 * @version    0.00
 */
abstract class Request {

	/**
	 * Returns the URL that initiated the request
	 *
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function getUrl();

}

