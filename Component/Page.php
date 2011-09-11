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

	/**
	 * Array of Javascript files on which the HTML document depends and which
	 * should be loaded with it
	 * 
	 * @access private
	 * @var    array
	 */
	private $javascriptDependencies = array();

	/**
	 * Array of CSS stylesheets on which the HTML document depends and which
	 * should be loaded with it
	 * 
	 * @access private
	 * @var    array
	 */
	private $stylesheetDependencies = array();

	/**
	 * Adds a Javascript file to the list of those to be loaded by the HTML
	 * document
	 * 
	 * @access public
	 * @param  string $filename 
	 */
	public function addJavascriptDependency($filename)
	{
		$this->javascriptDependencies[] = $filename;
	}

	/**
	 * Adds a CSS stylesheet to the list of those to be loaded by the HTML
	 * document
	 * 
	 * @access public
	 * @param  string $filename 
	 */
	public function addStylesheetDependency($filename)
	{
		$this->stylesheetDependencies[] = $filename;
	}

	/**
	 * Returns the list of Javascript files on which the HTML document depends
	 * 
	 * @access public
	 * @return array
	 */
	public function getJavascriptDependencies()
	{
		return $this->javascriptDependencies;
	}

	/**
	 * Returns the list of CSS stylesheets on which the HTML document depends
	 * 
	 * @access public
	 * @return array
	 */
	public function getStylesheetDependencies()
	{
		return $this->stylesheetDependencies;
	}

}

