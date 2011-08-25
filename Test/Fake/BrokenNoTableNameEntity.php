<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';

use \Tree\Orm\Entity;

/**
 * Fake_BrokenNoTableNameEntity 
 *
 * A simple fake entity with no table name set to make sure that Entity
 * recognises this invalid state and throws an exception
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Entity
 * @version    0.00
 */
class Fake_BrokenNoTableNameEntity extends Entity {

	protected $primaryKey = array(
		'id',
	);

	public function getEntityColumnList()
	{
		return array(
			'id',
			'title',
			'body',
		);
	}

	public function getEntityTableName()
	{
		return null;
	}

}

