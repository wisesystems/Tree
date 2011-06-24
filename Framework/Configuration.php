<?php

namespace Tree\Framework;

use \ArrayAccess;
use \Tree\Exception\ConfigurationException;

/**
 * Configuration 
 * 
 * Loads and parses an ini file to configure the framework
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
	 * The full path of the ini file 
	 *
	 * e.g. '/some/directory/config.ini'
	 * 
	 * @access private
	 * @var    string
	 */
	private $absolutePath;

	/**
	 * The base filename of the ini file
	 *
	 * e.g. 'config.ini'
	 * 
	 * @access private
	 * @var    string
	 */
	private $relativePath;

	/**
	 * An associative array of the values found in the ini file 
	 * 
	 * @access private
	 * @var    array
	 */
	private $iniValues;

	/**
	 * @access public
	 * @param  string $relativePath 
	 */
	public function __construct($relativePath)
	{
		$this->relativePath = $relativePath;
	}

	/**
	 * Returns the absolute path to the ini file, or null if it cannot be found 
	 * 
	 * @access public
	 * @return string
	 */
	public function getAbsolutePath()
	{
		if (is_null($this->absolutePath)) {
			$this->absolutePath = $this->findAbsolutePath();
		}

		return $this->absolutePath;
	}

	/**
	 * Returns an associative array of the values in the ini file 
	 * 
	 * @access public
	 * @return void
	 */
	public function getIniValues()
	{
		if (is_null($this->iniValues)) {
			$this->iniValues = $this->parseIniFile();
		}

		return $this->iniValues;
	}

	/**
	 * Scans the include path attempting to find an absolute path matching the
	 * given relative path
	 * 
	 * @access private
	 * @param  string $relativePath 
	 * @return string
	 */
	private function findAbsolutePath()
	{
		$includePath = get_include_path();
		$includePath = explode(PATH_SEPARATOR, $includePath);

		foreach ($includePath as $path) {

			$path  = rtrim($path, DIRECTORY_SEPARATOR);
			$path .= DIRECTORY_SEPARATOR;

			$absolutePath = $path . $this->relativePath;

			if (file_exists($absolutePath)) {
				return $absolutePath;
			}

		}

		return null;
	}

	/**
	 * Parses the ini file and returns its contents as an associative array 
	 * 
	 * @access private
	 * @return array
	 */
	private function parseIniFile()
	{
		$absolutePath = $this->getAbsolutePath();

		if (is_null($absolutePath)) {
			$message = "Unable to find {$this->relativePath}";
			$code    = ConfigurationException::FILE_NOT_FOUND;

			throw new ConfigurationException($message, $code);
		}

		if (!is_readable($absolutePath)) {
			$message = "Unable to read {$this->absolutePath}";
			$code    = ConfigurationException::FILE_NOT_READABLE;

			throw new ConfigurationException($message, $code);
		}

		// Excuse for using error suppression: parse_ini_file triggers an E_WARNING
		// if the ini file is malformed, which gets in the way of handling these
		// cases in a more elegant way.
		$values = @parse_ini_file($this->absolutePath, true);

		if ($values === false) {
			$message = "Unable to parse {$this->absolutePath}";
			$code    = ConfigurationException::FILE_NOT_PARSEABLE;

			throw new ConfigurationException($message, $code);
		}
		
		return $values;
	}

}

