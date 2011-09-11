<?php

namespace Tree\Test;

require_once '../Component/Page.php';

use \Tree\Component\Page;

/**
 * Fake_Page 
 *
 * A basic fake page implementation to aid in testing \Tree\Component\Page
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\Page
 * @version    0.00
 */
class Fake_Page extends Page {

	public function getLayoutTemplateClassName()
	{
		return '';
	}

}

