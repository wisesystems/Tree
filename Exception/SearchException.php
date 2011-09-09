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

	/**
	 * The instance of \Tree\Orm\Search that caused the exception
	 * 
	 * @access private
	 * @var    \Tree\Orm\Search
	 */
	private $search;

	/**
	 * @access public
	 * @param  string           $message 
	 * @param  integer          $code 
	 * @param  \Tree\Orm\Search $search  [optional]
	 */
	public function __construct($message, $code, $search = null)
	{
		parent::__construct($message, $code);

		$this->search = $search;
	}

	/**
	 * Returns the Search instance that caused the exception
	 * 
	 * @access public
	 * @return \Tree\Orm\Search
	 */
	public function getSearch()
	{
		return $this->search;
	}

}

