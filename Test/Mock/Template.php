<?php

namespace Tree\Test;

use \Tree\Component\Template;

/**
 * Mock_Template 
 *
 * A simple mock template for use in testing the Template component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\Template
 * @version    0.00
 */
class Mock_Template extends Template {

	protected $optionalInputValues = array(
		'footnote' => 'example footnote',
	);

	protected $requiredInputValues = array(
		'content',
	);

}

