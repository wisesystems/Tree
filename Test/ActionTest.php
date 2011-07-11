<?php

namespace Tree\Test;

require 'PHPUnit/Autoload.php';
require '../Component/Action.php';
require '../Component/Action/HtmlResponseGenerator.php';
require 'Mock/Action.php';

use \Tree\Component\Action;
use \PHPUnit_Framework_TestCase;

/**
 * ActionTest 
 *
 * Tests for correct filtering of input values by Action
 * 
 * @author     Henry Smith <henry@henrysmith.org> 
 * @copyright  2011 Henry Smith
 * @license    GPLv2.0
 * @package    Tree
 * @subpackage Test
 * @uses       PHPUnit_Framework_TestCase
 * @version    0.00
 */
class ActionTest extends PHPUnit_Framework_TestCase {

	private $action;

	public function setUp()
	{
		$this->action = new Mock_Action;
	}

	/**
	 * Tests that setInputValue() stores values according the filtering
	 * rules set using setInputFilter()
	 */
	public function testSetInputValueFiltersValue()
	{
		$this->action->setInputFilter('id', FILTER_VALIDATE_INT);

		$this->action->setInputValue('id', 'blarg');
		$this->assertEquals(false, $this->action->getInputValue('id'));

		$this->action->setInputValue('id', 12345);
		$this->assertEquals(12345, $this->action->getInputValue('id'));
	}

	/**
	 * Tests that performAction() correctly passes any input values that
	 * have been received through to the main() method
	 */
	public function testPerformActionPassesInputToMain()
	{
		$this->action->setInputFilter('id', FILTER_VALIDATE_INT);
		$this->action->setInputValue('id', 12345);

		$response = $this->action->performAction();

		$this->assertEquals(200, $response);
	}

	/**
	 * Tests that supportsResponseType() can tell when the Action can return a 
	 * response of the given type
	 */
	public function testRecognisesSupportedResponseFormat()
	{
		$responseType = 'text/html';
		$expected     = true;
		$output       = $this->action->supportsResponseType($responseType);

		$this->assertEquals($expected, $output);
	}

	/**
	 * Tests that supportsResponseType() can tell when the Action cannot return a 
	 * response of the given type
	 */
	public function testRecognisesUnsupportedResponseFormat()
	{
		$responseType = 'application/json';
		$expected     = false;
		$output       = $this->action->supportsResponseType($responseType);

		$this->assertEquals($expected, $output);
	}

}

