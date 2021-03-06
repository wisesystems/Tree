<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';

use \Tree\Orm\Entity;

/**
 * Fake_Entity 
 *
 * A simple fake entity with the bare minimum implmentation to be functional
 * for verifying the basic functionality of a simple Entity subclass
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Entity
 * @version    0.00
 */
class Fake_Entity extends Entity {

	public function getEntityColumnList()
	{
		return array(
			'id',
			'title',
			'body',
		);
	}

	public function getEntityPrimaryKey()
	{
		return array(
			'id',
		);
	}

	public function getEntityTableName()
	{
		return 'article';
	}

}

