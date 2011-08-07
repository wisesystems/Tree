<?php

namespace Tree\Behaviour;

/**
 * PreTemplateDisplayLogic 
 *
 * Interface for Template subclasses that want to define a method to be called
 * just before the template file is loaded and executed
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Behaviour
 * @version    0.00
 */
interface PreTemplateDisplayLogic {

	/**
	 * Executed just before the template file is loaded and executed, this method
	 * is the ideal place for lots of different types of template display logic
	 * 
	 * @access public
	 */
	public function preProcess();

}

