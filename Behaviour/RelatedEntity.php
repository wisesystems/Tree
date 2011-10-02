<?php

namespace Tree\Behaviour;

/**
 * RelatedEntity 
 *
 * An interface for subclasses of \Tree\Orm\Entity to implement to indicate
 * that they want to define relationships with other entities
 * 
 * @author    Henry Smith <henry@henrysmith.org> 
 * @copyright 2011 Henry Smith
 * @license   GPLv2.0
 * @package   Tree
 * @package   Behaviour
 * @version   0.00
 */
interface RelatedEntity {

	/**
	 * Should be implemented with a method returning an array in which each
	 * element is an entity relationship definition
	 * 
	 * @access public
	 * @return array
	 */
	public function getEntityRelationships();

}

