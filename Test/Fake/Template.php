<?php

namespace Tree\Test;

use \Tree\Component\Template;

/**
 * Fake_Template 
 *
 * A simple fake template for use in testing the Template component
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\Template
 * @version    0.00
 */
class Fake_Template extends Template {

	protected $optionalInputValues = array(
		'footnote' => 'example footnote',
	);

	protected $requiredInputValues = array(
		'content',
	);

}

