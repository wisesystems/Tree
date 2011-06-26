<?php

namespace Tree\Component;

use \Exception;
use \PDO;

use \Tree\Interfaces\HtmlResponseGenerator;

/**
 * Action 
 *
 * Actions are the controllers of an application
 *
 * They are analagous to methods of Controller subclasses in many frameworks,
 * or alternatively to files such as 'view-profile.php' in old-fashioned
 * applications that use the filesystem to route requests.
 * 
 * @abstract
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2010 - 2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
abstract class Action {

	protected $configValues = array();

	protected $databases = array();

	/**
	 * An associative array mapping names of acceptable input values to
	 * details of how to filter or sanitise those values
	 * 
	 * @access protected
	 * @var    array
	 */
	protected $inputFilters = array();

	/**
	 * An associative array mapping names of input values to those values
	 * 
	 * @access private
	 * @var    array
	 */
	private $inputValues = array();

	abstract public function main(array $input);

	/**
	 * Returns the input value that has been stored against the given name 
	 * 
	 * @access public
	 * @param  string $name 
	 * @return mixed
	 */
	public function getInputValue($name)
	{
		if (!isset($this->inputValues[$name])) {
			return null;
		}

		return $this->inputValues[$name];
	}

	/**
	 * Runs the main() method and returns its return value 
	 *
	 * @access public
	 * @return mixed
	 */
	public function performAction()
	{
		$this->applyUnusedInputFilters();

		$output = call_user_func(
			array($this, 'main'),
			$this->inputValues
		);

		return $output;
	}

	/**
	 * Stores the configuration values from the .ini file for later use
	 * 
	 * @access public
	 * @param  array $configValues 
	 */
	public function setConfigValues(array $configValues)
	{
		$this->configValues = $configValues;
	}

	/**
	 * Sets up an input filter under the given name
	 *
	 * @access public
	 * @param  string  $name      e.g. 'id'
	 * @param  integer $filterId  e.g. FILTER_VALIDATE_INT
	 * @param  integer $inputType e.g. INPUT_GET
	 * @param  array   $options   any applicable filter_var options
	 */
	public function setInputFilter($name, $filterId, $inputType = null,
		array $options = array())
	{
		$this->inputFilters[$name] = array(
			'filter-id'  => $filterId,
			'input-type' => $inputType,
			'options'    => $options,
		);
	}

	/**
	 * Filters and stores the given input value under the given name 
	 * 
	 * @access public
	 * @param  string $name 
	 * @param  mixed  $value 
	 * @return void
	 */
	public function setInputValue($name, $value)
	{
		$value = $this->filterInputValue($name, $value);

		$this->inputValues[$name] = $value;
	}

	public function supportsResponseType($responseType)
	{
		if ($responseType == 'text/html' && $this instanceof HtmlResponseGenerator) {
			return true;
		}
		return false;
	}

	/**
	 * Lazy-loads and returns the database of the given name if it's 
	 * available in the configuration
	 * 
	 * @access protected
	 * @param  string $database 
	 * @return PDO
	 */
	protected function getDatabase($database)
	{
		if (!isset($this->configValues['database'])) {
			return null;
		}

		if (!isset($this->configValues['database'][$database])) {
			return null;
		}

		$dsn  = $this->configValues['database'][$database]['dsn'];
		$user = $this->configValues['database'][$database]['username'];

		if (isset($this->configValues['database'][$database]['password'])) {
			$pass = $this->configValues['database'][$database]['password'];
		} else {
			$pass = null;
		}

		try {
			$pdo = new PDO($dsn, $user, $pass);
		} catch (Exception $e) {
			return null;
		}

		return $pdo;
	}

	/**
	 * Applies any unused input filters that have input types set to those
	 * inputs
	 *
	 * For example, if there is a filter called 'id' with an input-type of
	 * INPUT_GET, and it has not yet been used by a call to 
	 * setInputValue(), then it will be applied to the GET parameter 'id'.
	 * 
	 * @access private
	 * @return void
	 */
	private function applyUnusedInputFilters()
	{
		foreach ($this->inputFilters as $name => $filter) {
			
			if (isset($this->inputValues[$name])) {
				continue;
			}

			if (!isset($filter['input-type']) || $filter['input-type'] === null) {
				continue;
			}

			$value = filter_input(
				$filter['input-type'],
				$name,
				$filter['filter-id'],
				$filter['options']
			);

			$this->inputValues[$name] = $value;
		}
	}

	/**
	 * Runs the given value through the filter whose name matches the given
	 * name, returning the resulting value
	 * 
	 * @access private
	 * @param  string $name 
	 * @param  mixed $value 
	 * @return mixed
	 */
	private function filterInputValue($name, $value)
	{
		if (!isset($this->inputFilters[$name])) {
			return null;
		}

		if (!isset($this->inputFilters[$name]['filter-id'])) {
			throw new Exception('Missing filter ID');
		}

		$filter = $this->inputFilters[$name];

		if (!isset($filter['options'])) {
			$filter['options'] = array();
		}

		$value = filter_var(
			$value,
			$filter['filter-id'],
			$filter['options']
		);

		return $value;
	}

}

