<?php

namespace Tree\Framework;

use \Tree\Exception\AutoloaderException;

/**
 * Autoloader 
 *
 * Loads PHP classes from their source files
 *
 * Infers paths to files from the structure of the namespace and class name
 * according to the PSR-0 standard. Assumes a correctly configured
 * include_path.
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Framework
 * @version    0.00
 */
class Autoloader {

	/**
	 * The callback function to be registered on the autoload stack
	 * 
	 * @access public
	 * @param  string $class 
	 * @return boolean
	 */
	public function autoloadClass($class)
	{
		$filename = $this->translateClassName($class);

		return $this->loadClassFromFile($class, $filename);
	}

	/**
	 * Indicates whether there are any more autoloaders queued up after this one
	 *
	 * This is important to know in order to not throw exceptions when there's a
	 * good chance that a subsequent autoload function might find the class.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isLastAutoloader()
	{
		$autoloaders = spl_autoload_functions();

		$lastAutoloader = end($autoloaders);

		if (is_array($lastAutoloader) && $lastAutoloader[0] instanceof self) {
			return true;
		}

		return false;
	}

	/**
	 * Registers the autoload function 
	 * 
	 * @access public
	 */
	public function registerAutoloader()
	{
		spl_autoload_register(array($this, 'autoloadClass'));
	}

	/**
	 * Loads the given class from the given filename, if that filename exists in
	 * the include_path
	 * 
	 * @access private
	 * @param  string $class 
	 * @param  string $filename 
	 * @return boolean 
	 */
	private function loadClassFromFile($class, $filename)
	{
		
		$includePath = get_include_path();
		$includePath = explode(PATH_SEPARATOR, $includePath);

		foreach ($includePath as $path) {

			$path  = rtrim($path, DIRECTORY_SEPARATOR);
			$path .= DIRECTORY_SEPARATOR;

			$absolutePath = $path . $filename;

			if (!file_exists($absolutePath)) {
				continue;
			}

			if (!is_readable($absolutePath)) {

				if ($this->isLastAutoloader()) {

					$message = "Unable to read {$absolutePath}";
					$code    = AutoloaderException::FILE_NOT_READABLE;
					throw new AutoloaderException($message, $code, $class, $absolutePath);

				} else {
					return false;
				}

			}

			require $absolutePath;

			if (class_exists($class, false) || interface_exists($class, false) || trait_exists($class, false)) {

				return true;

			} elseif ($this->isLastAutoloader()) {

				$message = "Class {$class} not found in file {$absolutePath}";
				$code    = AutoloaderException::CLASS_NOT_FOUND;
				throw new AutoloaderException($message, $code, $class, $absolutePath);

			} else {
				return false;
			}

		}

		if ($this->isLastAutoloader()) {
			$message = "File {$filename} not found in include_path";
			$code    = AutoloaderException::FILE_NOT_FOUND;
			throw new AutoloaderException($message, $code, $class);
		}

		return false;
	}

	/**
	 * Translates class names to paths to the files containing those
	 * classes according to the PSR-0 standard
	 *
	 * - A fully-qualified namespace and class must have the following
	 *   structure: \<Vendor Name>\(<Namespace>\)*<Class Name>
	 *
	 * - Each namespace must have a top-level namespace ("Vendor Name").
	 *
	 * - Each namespace can have as many sub-namespaces as it wishes.
	 *
	 * - Each namespace separator is converted to a DIRECTORY_SEPARATOR
	 *   when loading from the file system.
	 *
	 * - Each "_" character in the CLASS NAME is converted to a
	 *   DIRECTORY_SEPARATOR. The "_" character has no special meaning in
	 *   the namespace.
	 *
	 * - The fully-qualified namespace and class is suffixed with ".php"
	 *   when loading from the file system.
	 *
	 * - Alphabetic characters in vendor names, namespaces, and class
	 *   names may be of any combination of lower case and upper case.
	 * 
	 * Separated from the registered autoloader method to improve
	 * testability.
	 * 
	 * @access private
	 * @param  string $class 
	 * @return string
	 */
	private function translateClassName($class)
	{
		$class    = ltrim($class, '\\');
		$filename = '';

		$lastSeparator = strripos($class, '\\');
		if ($lastSeparator) {
			$namespace = substr($class, 0, $lastSeparator);
			$class     = substr($class, $lastSeparator + 1);
			$filename  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
			$filename .= DIRECTORY_SEPARATOR;
		}

		$filename .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

		return $filename;

	}

}

