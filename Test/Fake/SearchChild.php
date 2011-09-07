<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Orm/Search.php';

use \Tree\Orm\Search;

/**
 * Fake_SearchChild
 *
 * A fake search for Fake_EntityChild
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Search
 * @version    0.00
 */
class Fake_SearchChild extends Search {

	protected $entityClass = '\Tree\Test\Fake_EntityChild';

}

