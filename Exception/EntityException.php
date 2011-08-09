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

	/**
	 * An attempt was made to store entity properties in the database, but the
	 * entity did not have a database table name to save to
	 */
	const NO_TABLE_NAME_SET = 1;

	/**
	 * An attempt was made to revert changes to an entity, but the entity was not
	 * hydrated and therefore had no initial database state to revert to
	 */
	const REVERTING_UNHYDRATED_ENTITY = 2;

}

