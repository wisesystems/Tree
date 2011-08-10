<?php

namespace Tree\Orm;

use \Tree\Database\Query_Select;

/**
 * Search 
 *
 * Base class for building searches for entities
 *
 * Heavily influenced by the Jelly ORM library for the Kohana framework
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Orm
 * @uses       \Tree\Database\Query_Select
 * @version    0.00
 */
abstract class Search {

	protected $entityClass;

}

