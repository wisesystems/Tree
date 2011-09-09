<?php

namespace Tree\Component;

class Page {

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

