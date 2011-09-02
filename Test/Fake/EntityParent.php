<?php

namespace Tree\Test;

require_once '../Orm/Entity.php';
require_once '../Behaviour/RelatedEntity.php';

use \Tree\Orm\Entity;
use \Tree\Behaviour\RelatedEntity;

/**
 * Fake_EntityParent
 *
 * A fake entity subclass that implements a relationship in which its role is
 * as the parent of another entity
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
class Fake_EntityParent extends Entity implements RelatedEntity {

	public function getEntityColumnList()
	{
		return array(
			'article_id',
			'article_title',
			'article_body',
		);
	}

	public function getEntityTableName()
	{
		return 'article';
	}

	public function getEntityPrimaryKey()
	{
		return array(
			'article_id',
		);
	}

	public function getEntityRelationships()
	{
		return array(
			array(
				'name'        => 'attributes',
				'cardinality' => Entity::RELATIONSHIP_ONE_TO_MANY,
				'class'       => '\Tree\Test\Fake_EntityChild',
			),
		);
	}

}

