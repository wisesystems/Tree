<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';

use \Tree\Orm\Search;

/**
 * Fake_SearchParent
 *
 * A fake search for Fake_EntityParent
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Search
 * @version    0.00
 */
class Fake_SearchParent extends Search {

	protected $entityClass = '\Tree\Test\Fake_EntityParent';

}

