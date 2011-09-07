<?php

namespace Tree\Exception;

use \Exception;

/**
 * SearchException 
 *
 * Provides debug information about problems to do with entity searches
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class SearchException extends Exception {

	/**
	 * A relationship name that was passed to Search did not correspond to any
	 * actual defined relationship
	 */
	const NO_SUCH_RELATIONSHIP = 1;

	/**
	 * A relationship name that was passed to Search whose corresponding 
	 * relationship spec was mal-formed
	 */
	const MALFORMED_RELATIONSHIP_SPEC = 2;

	/**
	 * An attempt was made to include a related entity in the result of a query
	 * that would have resulted in a broken query
	 */
	const CANNOT_INCLUDE_RELATIONSHIP = 3;

}

