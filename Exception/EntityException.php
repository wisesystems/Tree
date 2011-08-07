<?php

namespace Tree\Exception;

use \Exception;

/**
 * EntityException 
 *
 * Provides debug information about problems to do with Entity subclasses
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Exception
 * @uses       \Exception
 * @version    0.00
 */
class EntityException extends Exception {

	/**
	 * An attempt was made to get or set an attribute that was not actually a
	 * an attribute of the entity in question.
	 */
	const NO_SUCH_ATTRIBUTE = 0;

}

