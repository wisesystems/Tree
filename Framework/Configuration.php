<?php

namespace Tree\Framework;

use \ArrayAccess;
use \Iterator;
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
class Configuration implements ArrayAccess, Iterator {

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
	 * An associative array of the values found in the ini file 
	 * 
	 * @access private
	 * @var    array
	 */
	private $iniValues;

	/**
	 * The current internal pointer value for the Iterator implementation 
	 * 
	 * @access private
	 * @var    integer
	 */
	private $iteratorIndex = 0;

	/**
	 * @access public
	 * @param  string $absolutePath 
	 */
	public function __construct($absolutePath)
	{
		$this->absolutePath = $absolutePath;
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
	 * ArrayAccess: Indicates whether the given configuration value exists 
	 * 
	 * @access public
	 * @param  string $offset 
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		$iniValues = $this->getIniValues();
		return isset($iniValues[$offset]);
	}

	/**
	 * ArrayAccess: Returns the given configuration value 
	 * 
	 * @access public
	 * @param  string $offset 
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		$iniValues = $this->getIniValues();
		return $iniValues[$offset];
	}

	/**
	 * ArrayAccess: Does nothing because configuration values must be set in the
	 * ini file, not at runtime
	 * 
	 * @access public
	 * @param  string $offset 
	 * @param  mixed  $value 
	 */
	public function offsetSet($offset, $value)
	{
		// do nothing
	}

	/**
	 * ArrayAccess: Does nothing because configuration values should be removed
	 * from the ini file, not at runtime
	 * 
	 * @access public
	 * @param  string $offset 
	 */
	public function offsetUnset($offset)
	{
		// do nothing
	}

	/**
	 * Returns an array representation of all the configuration values
	 * 
	 * @access public
	 * @return array
	 */
	public function toArray()
	{
		return $this->getIniValues();
	}

	/**
	 * Iterator: Returns the config value at the current iterator index
	 * 
	 * @access public
	 * @return mixed
	 */
	public function current()
	{
		$values = $this->toArray();

		$current = array_slice($values, $this->iteratorIndex, 1);
		$current = current($current);

		return $current;
	}

	/**
	 * Iterator: Returns the key name of the value at the current iterator index
	 * 
	 * @access public
	 * @return mixed
	 */
	public function key()
	{
		$values = $this->toArray();
		$keys   = array_keys($values);

		$key = array_slice($keys, $this->iteratorIndex, 1);
		$key = current($key);

		return $key;
	}

	/**
	 * Iterator: Increments the iterator index
	 * 
	 * @access public
	 */
	public function next()
	{
		$this->iteratorIndex++;
	}

	/**
	 * Iterator: Resets the iterator index
	 * 
	 * @access public
	 */
	public function rewind()
	{
		$this->iteratorIndex = 0;
	}
	
	/**
	 * Iterator: Indicates whether the iterator index is valid
	 * 
	 * @access public
	 * @return boolean
	 */
	public function valid()
	{
		if ($this->iteratorIndex < 0) {
			return false;
		} elseif ($this->iteratorIndex >= count($this->toArray())) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Parses the ini file and returns its contents as an associative array 
	 * 
	 * @access private
	 * @return array
	 */
	private function parseIniFile()
	{
		if (!file_exists($this->absolutePath)) {
			$message = "Unable to find {$this->absolutePath}";
			$code    = ConfigurationException::FILE_NOT_FOUND;

			throw new ConfigurationException($message, $code, $this->absolutePath);
		}

		if (!is_readable($this->absolutePath)) {
			$message = "Unable to read {$this->absolutePath}";
			$code    = ConfigurationException::FILE_NOT_READABLE;

			throw new ConfigurationException($message, $code, $this->absolutePath);
		}

		// Excuse for using error suppression: parse_ini_file triggers an E_WARNING
		// if the ini file is malformed, which gets in the way of handling these
		// cases in a more elegant way.
		$values = @parse_ini_file($this->absolutePath, true);

		if ($values === false) {
			$message = "Unable to parse {$this->absolutePath}";
			$code    = ConfigurationException::FILE_NOT_PARSEABLE;

			throw new ConfigurationException($message, $code, $this->absolutePath);
		}
		
		return $values;
	}

}

