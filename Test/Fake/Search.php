<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';

use \Tree\Orm\Search;

/**
 * Fake_Search 
 *
 * A simple fake search with the bare minimum of implementation details to test
 * the functionality of Search
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Search
 * @version    0.00
 */
class Fake_Search extends Search {

	protected function getEntityClass()
	{
		return '\Tree\Test\Fake_Entity';
	}

}

