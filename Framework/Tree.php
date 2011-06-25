<?php

namespace Tree\Framework;

use \Tree\Framework\Autoloader;
use \Tree\Framework\Configuration;

/**
 * Tree 
 *
 * Manages the top-level flow of control
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Tree {

	/**
	 * Runs the application
	 *
	 * This is essentially a wrapper to keep as much actual code as possible away
	 * from publicly accessible directories.
	 * 
	 * @static
	 * @access public
	 */
	public static function main()
	{
		$tree = new self;
		$tree->runFramework();
	}

	public function runFramework()
	{
		if (!$this->includePathContainsTree()) {
			return false;
		}

		require_once 'Tree/Framework/Autoloader.php';

		$autoloader = new Autoloader;
		$autoloader->registerAutoloader();

		$configuration = new Configuration('Tree.ini');

		print_r($configuration);

	}

	/**
	 * Indicates whether the PHP include_path has been correctly set up so that
	 * Tree is actually available to load
	 * 
	 * @access private
	 * @return boolean
	 */
	private function includePathContainsTree()
	{
		$includePath = get_include_path();
		$includePath = explode(PATH_SEPARATOR, $includePath);

		$treeDirectory = __DIR__;
		$treeDirectory = dirname($treeDirectory);
		$treeDirectory = dirname($treeDirectory);

		return in_array($treeDirectory, $includePath);
	}

}

