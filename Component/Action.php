<?php

namespace Tree\Component;

use \Exception;
use \PDO;

use \Tree\Database\Connection_MySql;

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
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Component
 * @version    0.00
 */
abstract class Action {

	/**
	 * The Configuration instance containing the config values from the ini file
	 * 
	 * @access protected
	 * @var    \Tree\Framework\Configuration
	 */
	protected $configuration;

	/**
	 * An associative array mapping database connection IDs to their corresponding
	 * connections
	 * 
	 * @access protected
	 * @var    array
	 */
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

	/**
	 * Processes the request
	 *
	 * This method should do any requisite fetching, updating, creation, or
	 * deletion of data required by the request. It should then return a HTTP
	 * status code to indicate the outcome and the type of response to be sent.
	 * 
	 * @abstract
	 * @access public
	 * @param  array $input 
	 * @return integer       e.g. 200, 404, 500
	 */
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
	 * @param  array \Tree\Framework\Configuration
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
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

	/**
	 * Lazy-loads and returns the database of the given name if it's 
	 * available in the configuration
	 * 
	 * @access protected
	 * @param  string $database 
	 * @return \Tree\Database\Connection
	 */
	protected function getDatabase($database)
	{
		if (isset($this->databases[$database])) {
			return $this->databases[$database];
		}

		if (!isset($this->configuration['database'])) {
			return null;
		}

		if (!isset($this->configuration['database'][$database])) {
			return null;
		}

		$vendor = $this->configuration['database'][$database]['vendor'];

		switch ($vendor) {

			case 'MySQL':
				$connection = new Connection_MySql;
				break;

			default:
				return null;

		}

		$connection->setIniValues($this->configuration['database'][$database]);

		$this->databases[$database] = $connection;

		return $connection;
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
		} else {
			$filter['options'] = array('options' => $filter['options']);
		}

		$value = filter_var(
			$value,
			$filter['filter-id'],
			$filter['options']
		);

		return $value;
	}

}

