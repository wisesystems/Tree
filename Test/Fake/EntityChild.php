<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Behaviour/RelatedEntity.php';

use \Tree\Orm\Entity;
use \Tree\Behaviour\RelatedEntity;

/**
 * Fake_EntityChild
 *
 * A fake entity subclass that implements a relationship in which its role is
 * as the child of another entity
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Orm\Entity
 * @uses       \Tree\Behaviour\RelatedEntity
 * @version    0.00
 */
class Fake_EntityChild extends Entity implements RelatedEntity {

	public function getEntityColumnList()
	{
		return array(
			'article_id',
			'attribute_name',
			'attribute_value',
		);
	}

	public function getEntityTableName()
	{
		return 'article_attribute';
	}

	public function getEntityPrimaryKey()
	{
		return array(
			'article_id',
			'attribute_name',
		);
	}

	public function getEntityRelationships()
	{
		return array(
			array(
				'name'        => 'article',
				'cardinality' => Entity::RELATIONSHIP_MANY_TO_ONE,
				'class'       => '\Tree\Test\Fake_EntityParent',
			),
		);
	}

}

