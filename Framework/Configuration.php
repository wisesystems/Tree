<?php

namespace Tree\Framework;

/**
 * Configuration 
 * 
 * Loads and parses an INI file to configure the framework
 *
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Configuration {

	/**
	 * The full path of the INI file 
	 *
	 * e.g. '/some/directory/config.ini'
	 * 
	 * @access private
	 * @var    string
	 */
	private $absolutePath;

	/**
	 * The base filename of the INI file
	 *
	 * e.g. 'config.ini'
	 * 
	 * @access private
	 * @var    string
	 */
	private $relativePath;

	/**
	 * @access public
	 * @param  string $relativePath 
	 */
	public function __construct($relativePath)
	{
		$this->relativePath = $relativePath;
	}

	/**
	 * Returns the absolute path to the INI file, or null if it cannot be found 
	 * 
	 * @access public
	 * @return string
	 */
	public function getAbsolutePath()
	{
		if (is_null($this->absolutePath)) {
			$this->absolutePath = $this->findAbsolutePath($this->relativePath);
		}

		return $this->absolutePath;
	}

	/**
	 * Scans the include path attempting to find an absolute path matching the
	 * given relative path
	 * 
	 * @access private
	 * @param  string $relativePath 
	 * @return string
	 */
	private function findAbsolutePath($relativePath)
	{
		$includePath = get_include_path();
		$includePath = explode(PATH_SEPARATOR, $includePath);

		foreach ($includePath as $path) {

			$path  = rtrim($path, DIRECTORY_SEPARATOR);
			$path .= DIRECTORY_SEPARATOR;

			$absolutePath = $path . $relativePath;

			if (file_exists($absolutePath)) {
				return $absolutePath;
			}

		}

		return null;
	}



}

