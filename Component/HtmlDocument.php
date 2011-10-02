<?php

namespace Tree\Component;

use \ArrayAccess;
use \Tree\Response\Response_Html;

/**
 * HtmlDocument 
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
abstract class HtmlDocument extends Response_Html implements ArrayAccess {

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
	 * The top-level layout template for the document into which the actual 
	 * content is to be inserted
	 * 
	 * @access private
	 * @var    \Tree\Component\Template
	 */
	private $layoutTemplate;

	/**
	 * To be overridden with a method that returns the name of the layout
	 * template class. The layout template class must be able to accept certain
	 * input values and generate a valid (X)HTML document when generated
	 * 
	 * @abstract
	 * @access public
	 * @return string
	 */
	abstract public function getLayoutTemplateClassName();

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

	/**
	 * \ArrayAccess: Indicates whether the document's layout template has the
	 * given value
	 * 
	 * @access public
	 * @param  string $offset 
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		$layoutTemplate = $this->getLayoutTemplate();
		return isset($layoutTemplate[$offset]);
	}

	/**
	 * \ArrayAccess: Returns the given template variable from the document's
	 * layout template
	 * 
	 * @access public
	 * @param  string $offset 
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		$layoutTemplate = $this->getLayoutTemplate();
		return $layoutTemplate[$offset];
	}

	/**
	 * \ArrayAccess: Sets the given template variable in the document's layout
	 * template
	 * 
	 * @access public
	 * @param  string $offset 
	 * @param  mixed  $value 
	 */
	public function offsetSet($offset, $value)
	{
		$layoutTemplate          = $this->getLayoutTemplate();
		$layoutTemplate[$offset] = $value;
	}

	/**
	 * \ArrayAccess: Unsets the given template variable in the document's layout
	 * template
	 * 
	 * @access public
	 * @param  string $offset 
	 */
	public function offsetUnset($offset)
	{
		$layoutTemplate = $this->getLayoutTemplate();
		unset($layoutTemplate[$offset]);
	}

	/**
	 * \Tree\Response\Response: Overrides the basic response-body-sending with a
	 * little extra code to do the final compiling of the page data into a
	 * coherent HTML document
	 * 
	 * @access public
	 */
	public function sendResponseBody()
	{
		$layoutTemplate = $this->getLayoutTemplate();
		$layoutTemplate['pageContent'] = $this->pageContent;
		$layoutTemplate['stylesheets'] = $this->getStylesheetDependencies();

		$pageHtml = $layoutTemplate->getOutput();

		echo $pageHtml;
	}

	/**
	 * Returns the document's layout template object
	 * 
	 * @access private
	 * @return \Tree\Component\Template
	 */
	private function getLayoutTemplate()
	{
		if ($this->layoutTemplate === null) {

			$class    = $this->getLayoutTemplateClassName();
			$template = new $class;

			$this->layoutTemplate = $template;
		}

		return $this->layoutTemplate;
	}

}

