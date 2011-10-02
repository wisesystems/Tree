<?php

namespace Tree\Test;

require_once '../Component/HtmlDocument.php';

use \Tree\Component\HtmlDocument;

/**
 * Fake_HtmlDocument 
 *
 * A basic fake HtmlDocument implementation to aid in testing
 * \Tree\Component\HtmlDocument
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       \Tree\Component\HtmlDocument
 * @version    0.00
 */
class Fake_HtmlDocument extends HtmlDocument {

	public function getLayoutTemplateClassName()
	{
		return '';
	}

}

