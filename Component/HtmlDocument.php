<?php

namespace Tree\Component;

use \stdClass;
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
	 * The document's content body
	 *
	 * Not necessarily the whole contents of the <body></body> tag, as the layout
	 * template will probably add more.
	 * 
	 * @access private
	 * @var    string
	 */
	private $content;

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
	 * An associative array of name-value pairs representing the document's header
	 * <meta /> values
	 * 
	 * @access private
	 * @var    array
	 */
	private $metaValues = array();

	/**
	 * An associative array of name-value pairs representing the document's header
	 * <meta /> http-equiv values
	 * 
	 * @access private
	 * @var    array
	 */
	private $metaHttpEquiv = array();

	/**
	 * The document's header title for the <title></title> tags
	 * 
	 * @access private
	 * @var    string
	 */
	private $title;

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
	 * @param  string $filename             The name of the CSS file
	 * @param  string $media    [optional]  The CSS media type
	 */
	public function addStylesheetDependency($filename, $media = 'all')
	{
		$stylesheet        = new stdClass;
		$stylesheet->href  = $filename;
		$stylesheet->media = $media;

		$this->stylesheetDependencies[] = $stylesheet;
	}

	/**
	 * Returns the document's content
	 * 
	 * @access public
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
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
	 * Returns the header title
	 * 
	 * @access public
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
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

		$layoutTemplate['linkTags'] = $this->getHeaderLinkTags();
		$layoutTemplate['metaTags'] = $this->getHeaderMetaTags();
		$layoutTemplate['title']    = $this->getTitle();
		$layoutTemplate['content']  = $this->getContent();

		$pageHtml = $layoutTemplate->getOutput();

		echo $pageHtml;
	}

	/**
	 * Stores the document's content
	 * 
	 * @access public
	 * @param  string $content 
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

	/**
	 * Stores the given name-value pair to be displayed as a meta value in the
	 * document's header as <meta name="$name" value="$value" />
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  string $value 
	 */
	public function setMeta($name, $value)
	{
		$this->metaValues[$name] = $value;
	}

	/**
	 * Stores the given name-value pair to be displayed as a meta http-equiv value
	 * in the document's header as <meta http-equiv="$name" value="$value" />
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  string $value 
	 */
	public function setMetaHttpEquiv($name, $value)
	{
		$this->metaHttpEquiv[$name] = $value;
	}

	/**
	 * Stores the document's header title
	 * 
	 * @access public
	 * @param  string $title 
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Returns an array of HTML header link tags
	 * 
	 * @access private
	 * @return array
	 */
	private function getHeaderLinkTags()
	{
		$tags = array();

		$stylesheets = $this->getStylesheetDependencies();

		foreach ($stylesheets as $stylesheet) {

			$tags[] = array(
				'href'  => $stylesheet->href,
				'media' => $stylesheet->media,
				'rel'   => 'stylesheet',
				'type'  => 'text/css',
			);

		}

		return $tags;
	}

	/**
	 * Returns an array of HTML header meta tags
	 * 
	 * @access private
	 * @return array
	 */
	private function getHeaderMetaTags()
	{
		$tags = array();

		foreach ($this->metaValues as $name => $value) {

			$tags[] = array(
				'name'    => $name,
				'content' => $value,
			);

		}

		foreach ($this->metaHttpEquiv as $name => $value) {

			$tags[] = array(
				'http-equiv' => $name,
				'content'    => $value,
			);

		}

		return $tags;
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

