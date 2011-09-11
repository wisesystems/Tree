<?php

namespace Tree\Component;

use \Tree\Response\Response_Html;

/**
 * Page 
 *
 * Represents a HTML document response to a HTTP request
 * 
 * @copyright  2011 Henry Smith
 * @author     Henry Smith <henry@henrysmith.org> 
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @uses       \Tree\Response\Response_Html
 * @version    0.00
 */
class Page extends Response_Html {

	private $javascriptDependencies = array();

	private $stylesheetDependencies = array();

	public function addJavascriptDependency($filename)
	{
		$this->javascriptDependencies[] = $filename;
	}

	public function addStylesheetDependency($filename)
	{
		$this->stylesheetDependencies[] = $filename;
	}

	public function getJavascriptDependencies()
	{
		return $this->javascriptDependencies;
	}

	public function getStylesheetDependencies()
	{
		return $this->stylesheetDependencies;
	}

}

